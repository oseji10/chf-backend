<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Traits\tUserVerification;

use App\Models\UserVerification;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class UserVerificationController extends Controller
{
    /* 
    *   Inject Verification methods from tUserVerification trait
     */
    use tUserVerification;

    public function sendPasswordRecoveryEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return ResponseHelper::noDataErrorResponse("User with that email not found");
            }

            return $this->resendEmail($request);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse("Could not send verification email");
        }
    }

    public function verifyAccountEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'string|required',
            'hash' => 'string|required',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if (empty($user)) {
                return ResponseHelper::noDataErrorResponse("Unable to verify email. You are advised to contact CHF support", 400);
            }

            $user_verification = UserVerification::where('channel', $request->email)->where('hash', $request->hash)->where('status', 0)->first();

            if (!$user_verification) {
                return ResponseHelper::noDataErrorResponse("Unable to verify code", 400);
            }

            $user_verification->update(['status' => 1]);

            $user->email_verified_at = now();
            $user->emailVerifications()->update(['status' => 1]);
            $user->save();
            return ResponseHelper::noDataSuccessResponse("Email verification successful");
        } catch (\Exception $ex) {
            \Log::info($ex);
            return ResponseHelper::noDataErrorResponse("Unable to verify email", 400);
        }
    }
    /* 
    *   SEND NEW VERIFICATION CODE TO THE USER'S EMAIL
     */
    public function sendOTP($email)
    {
        try {
            return ResponseHelper::noDataSuccessResponse('Please verify your email');
        } catch (\Throwable $th) {
            return ResponseBuilder::noDataErrorResponse('Could not send verification mail. Try again');
        }
    }

    /* 
    *   RESEND VERIFICATION EMAIL  
     */
    public function resendEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email'
        ]);

        try {
            $this->sendEmailToken($request->email);
            return ResponseHelper::noDataSuccessResponse("Email verification sent");
        } catch (\Exception $ex) {
            \Log::info($ex);
            return ResponseHelper::noDataErrorResponse('Could not send verification mail.');
        }
    }

    public function verifyRecoveryCode()
    {
        $email = request()->email;
        $hash = request()->hash;
        if (!$email || !$hash) {
            return ResponseHelper::noDataErrorResponse("Verification code or ID seem to be missing", 400);
        }
        $user = User::where('email', $email)->first();

        if (!$user) {
            return ResponseHelper::noDataErrorResponse("Seem like the account was not found. Please try again.", 404);
        }

        $verification = UserVerification::where('channel', $email)->where('hash', $hash)->first();

        if (!$verification) {
            return ResponseHelper::noDataErrorResponse("Seems like you entered an invalid code. Please check and try again", 400);
        }

        $verification->delete();

        return ResponseHelper::noDataSuccessResponse("Recovery code verified!");
    }
}
