<?php

namespace App\Traits;

use App\Helpers\AWSHelper;
use App\Mail\EmailVerification;
use App\Mail\StaffCreatedEmail;
use App\Models\UserVerification;
use App\Helpers\ResponseHelper;
use Mail;
use \Exception;

use App\Models\User;

trait tUserVerification
{

    /* 
    *   CREATE NEW TOKEN FOR VERIFICATION
    *   
    */

    private function generateToken(String $type = 'numeric')
    {
        return rand(100938, 999189);
    }

    public function sendEmailToken($email, $phone = null)
    {
        $token = $this->generateToken();

        $data = [
            'type' => 'email',
            'channel' => $email,
            'hash' => $token,
        ];

        try {
            if ($phone)
                AWSHelper::sendSMS($phone, "Your verification code is " . $token);
            Mail::to($email)->send(new EmailVerification([
                'email' => $data['channel'],
                'hash' => $data['hash']
            ]));
            return UserVerification::create($data);
        } catch (\Throwable $th) {
            throw new \Exception('Unable to send verification email');
        }
    }


    /*
    * Send staff created email to staff with default password
    *
    */
    public function sendCreatedEmailToStaff($email, $password)
    {

        $data = [
            'type' => 'email',
            'channel' => $email,
            'password' => $password,
        ];

        try {
            Mail::to($email)->send(new StaffCreatedEmail($data));
            return true;
        } catch (\Throwable $th) {
            throw new \Exception($th);
        }
    }

    /* 
    *   VERIFY EMAIL TOKEN SENT TO THE USER'S EMAIL
     */
    public function verifyEmail()
    {
        $email = request()->get('email');
        $hash = request()->get('hash');

        if (empty($email) || empty($hash)) {
            throw new \Exception("Unable to verify email", 400);
        }

        $verification = UserVerification::where('channel', $email)->where('hash', $hash)->where('status', 0)->first();

        if (!$verification) {
            throw new Exception("Unable to verify email. You are advised to contact CHF support.", 1);
        }

        /* 
        *   NULLIFY ALL OTHER VERIFICATION CODE SENT TO THE USER'S EMAIL
         */
        $verification->update([
            'status' => 1,
        ]);

        return ResponseHelper::noDataSuccessResponse("Email has been verified");
    }
}
