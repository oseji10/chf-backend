<?php

namespace App\Http\Controllers\V2API\Auth;

use App\Helpers\ResponseHelper;
use App\Helpers\TokenHelper;
use App\Http\Controllers\Controller;
use App\Mail\Superadmin\ManualPasswordResetNotificationMail;
use App\Models\User;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    //
    public function manualPasswordReset()
    {
        $email = request()->get('email');

        if (!$email) {
            return ResponseHelper::noDataErrorResponse("You must provide an email", 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return ResponseHelper::noDataErrorResponse("User with email not found", 404);
        }

        $password = TokenHelper::generateRandomToken(10);

        $user->password = \Hash::make($password);

        $user->save();

        \Mail::to($email)->send(new ManualPasswordResetNotificationMail($password));
        return "Password reset sent";
    }
}
