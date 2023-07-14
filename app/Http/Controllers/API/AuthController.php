<?php

namespace App\Http\Controllers\API;

use App\Helpers\AWSHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Hash;
use App\Helpers\ResponseHelper;
use App\Helpers\TokenHelper;
use App\Jobs\SendAccountVerificationMail;
use App\Mail\EmailVerification;
use App\Models\IdentificationDocument;
use App\Traits\tUserVerification;

use App\Models\User;
use App\Models\UserLog;
use App\Models\UserVerification;

class AuthController extends Controller
{
    //
    use tUserVerification;


    public function register(Request $request)
    {
        $validated_data = $this->validate($request, [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|min:10:|unique:users',
            'identification_number' => 'required|string|unique:patient',
            'identification_id' => 'required',
            'password' => 'required',
            'date_of_birth' => 'required',
            'coe_id' => 'required|numeric' //Replace with top validattion when hospitals have been created.
        ]);



        //Hash the password
        $validated_data['password'] = Hash::make($validated_data['password']);

        try {
            /* VALIDATE THE IDENTIFICATION NUMBER PROVIDED */
            $this->validateIdentificationNumber($request->identification_number, $request->identification_id);

            /* 
            *   USE TRANSACTION TO PREVENT CREATING ONE RECORD WITHOUT THE OTHER
             */
            DB::beginTransaction();

            $coe_id = $validated_data['coe_id'];

            unset($validated_data['coe_id']);

            $user = User::create($validated_data);

            //GENERATE A UNIQUE CHF ID FOR NEW USER. CAN BE REPLACE WITH BETTER EFFICIENT ALGORITHM
            $validated_data['chf_id'] = $request->cap_id ?? "CHF-" . rand(3904, 9999) . substr(time(), 5) . range('A', 'Z')[rand(0, 25)];
            $validated_data['coe_id'] = $coe_id;
            /* 
            *   CREATES A NEW PATIENT RECORD FOR THE USER USING MODEL RELATIONSHIP
             */
            $user->patient()->create($validated_data);

            /* 
            *   CREATE A NEW WALLET FOR THE USER WITH 0.0 BALANCE
             */
            $user->wallet()->create();

            /* 
            *   USE THE ATTACH METHOD TO CREATE ROLES USING MODEL RELATIONSHIP. 'attach' METHOD 
            *   USED FOR MANY TO MANY RELATIONSHIP
             */
            $user->roles()->attach([1]);

            /* 
            *   UNCOMMENT THE LINE BELOW IF YOU DECIDE TO USE LARAVEL JOBS
             */
            // SendAccountVerificationMail::dispatch($validated_data);
            $token = TokenHelper::generateRandomToken();
            $phone = $validated_data['phone_number'];
            $email = $validated_data['email'];
            UserVerification::create([
                'channel' => $email,
                'hash' => $token,
            ]);
            \Mail::to($email)->send(new EmailVerification([
                'email' => $email,
                'hash' => $token,
            ]));
            AWSHelper::sendSMS($phone, "Your verification code is " . $token);
            // $this->sendEmailToken($validated_data['email'], $validated_data['phone_number']);

            DB::commit();

            return ResponseHelper::ajaxResponseBuilder(true, __('account.created'), $user->where('email', $request->email)->with('patient')->first(), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // return ResponseHelper::noDataErrorResponse(__('account.create-failed'));
            return ResponseHelper::exceptionHandler($e);
        }
    }

    /**
     *   API LOGIN METHOD
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        /* 
        *   USE THE API BELOW TO GET USER'S IP GEO RECORD. ALSO GET USER AGENT FOR LOG
         */
        // $user_agent = Http::get('https://ipapi.co/'. $request->ip() .'/json/')->json();
        // return [$user_agent];

        try {

            $user = User::where('email', $request->email)->first();

            if (!$user) {

                return ResponseHelper::noDataErrorResponse(__('auth.failed'), 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return ResponseHelper::noDataErrorResponse(__('auth.failed'), 401);
            }

            if ($user->email_verified_at === null) {
                return ResponseHelper::noDataErrorResponse(__('auth.must-verify-email'), 401);
            }

            if (!auth()->attempt($request->only('email', 'password'))) {
                return ResponseHelper::noDataErrorResponse(__('auth.failed'), 401);
            }

            UserLog::create([
                'user_id' => auth()->id(),
                'ip_state' => 'unidentified', //Find API to get IP state
                'ip_country' => 'unidentified', //Find API to get IP country
                'ip' => $request->ip(),
                'device' =>  $request->header('User-Agent'),
            ]);

            $access_token = auth()->user()->createToken('Access_token')->accessToken;

            //If user has wallet, get his wallet info else return null for wallet
            $wallet = auth()->user()->Wallet;

            return ResponseHelper::ajaxResponseBuilder(true, __('auth.success'), [
                'user' => User::where('id', auth()->user()->id)->with("roles")->with('patient')->with('patient.applicationReview')->first(),
                'access_token' => $access_token,
                'permissions' => auth()->user()->permissions(),
                'wallet' => $wallet,

            ]);
        } catch (\Exception $e) {
            \Log::info($e);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    /* 
    *   LOGOUT OF CURRENT DEVICE
     */
    public function logout()
    {
        try {
            auth()->user()->token()->revoke();
            return ResponseHelper::noDataSuccessResponse(__('auth.logout'));
        } catch (\Exception $e) {
            return ResponseHelper::noDataErrorResponse(__('auth.logout-failed'));
        }
    }

    /* 
    *   LOGOUT OF ALL DEVICES
     */
    public function logoutAllDevices()
    {
        // FIND ALL THE USER TOKENS IN THE DATABASE AND CHANGE REVOKED TO TRUE. NO LONGER VALID
        DB::table('oauth_access_tokens')
            ->where('user_id', auth()->id())
            ->update([
                'revoked' => true
            ]);
        return ResponseHelper::noDataSuccessResponse(__('auth.logout-all'));
    }

    protected function validateIdentificationNumber($id, $type = 1)
    {
        $id_type = IdentificationDocument::find($type);
        if (!(strlen(str_replace(' ', '', (string)$id)) === $id_type->identification_length)) {
            throw new \Exception('Invalid Identification Number', 400);
        }
        return true;
    }

    public function refreshLogin()
    {
        $access_token = auth()->user()->createToken('Access_token')->accessToken;

        //If user has wallet, get his wallet info else return null for wallet
        $wallet = auth()->user()->Wallet;

        return ResponseHelper::ajaxResponseBuilder(true, __('auth.success'), [
            'user' => User::where('id', auth()->user()->id)->with("roles")->with('patient')->with('patient.applicationReview')->first(),
            'access_token' => $access_token,
            'permissions' => auth()->user()->permissions(),
            'wallet' => $wallet,

        ]);
    }

    /* ONLY FOR SUPERADMIN TO LOGIN AS ANY USER WITH THEIR EMAIL TO PROVIDE SUPPORT */
    public function superLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ResponseHelper::noDataErrorResponse(__('auth.failed'), 401);
        }

        $user = auth()->login($user);
        $access_token = auth()->user()->createToken('Access_token')->accessToken;

        //If user has wallet, get his wallet info else return null for wallet
        $wallet = auth()->user()->Wallet;

        return ResponseHelper::ajaxResponseBuilder(true, __('auth.success'), [
            'user' => User::where('id', auth()->user()->id)->with("roles")->with('patient')->with('patient.applicationReview')->first(),
            'access_token' => $access_token,
            'permissions' => auth()->user()->permissions(),
            'wallet' => $wallet,

        ]);
    }
}
