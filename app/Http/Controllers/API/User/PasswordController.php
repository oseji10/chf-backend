<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Helpers\ResponseHelper;
use App\Models\User;

class PasswordController extends Controller
{
    //
    public function changePassword(Request $request){
        $this->validate($request,[
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
            'new_password_confirmation' => 'required|string',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return ResponseHelper::noDataErrorResponse('Old password is incorrect.',400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        return ResponseHelper::noDataSuccessResponse('Password changed successfully');

    }

    public function resetPassword(Request $request){
        $this->validate($request,[
            'email' => 'required|email|exists:users',
            'password' => 'required|string'
        ]);

        try {
            $user = User::where('email',$request->email)->first();

            $user->password = Hash::make($request->password);

            $user->save();

            return ResponseHelper::ajaxResponseBuilder(true, 'Password reset successfully.',$user);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse("Could not reset password.");
        }
    }
}
