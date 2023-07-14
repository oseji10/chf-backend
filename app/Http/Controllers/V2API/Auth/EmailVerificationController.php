<?php

namespace App\Http\Controllers\V2API\Auth;

use App\Helpers\ResponseHelper;
use App\Helpers\TokenHelper;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\UserVerification;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailVerificationController extends Controller
{
    //
    public function sendEmailVerification(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        try {

            $token = TokenHelper::generateRandomToken();

            UserVerification::create([
                'channel' => $request->email,
                'hash' => $token,

            ]);

            \Mail::to($request->email)
                ->send(new EmailVerification([
                    'hash' => $token,
                    'email' => $request->email,
                ]));

            return ResponseHelper::noDataSuccessResponse("Code sent to email");
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse("Could not send verification email. Please try again");
        }
    }
}
