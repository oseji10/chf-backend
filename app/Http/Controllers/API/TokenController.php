<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Traits\tUserVerification;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    //
    use tUserVerification;

    public function sendToken(){
        try{
            $this->sendEmailToken(auth()->user()->email);
            return ResponseHelper::noDataSuccessResponse("Verification token has been sent to your email.");
        }catch(\Exception $ex){
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    public function verifyToken(){
        try {
            $this->verifyEmail();
            return ResponseHelper::noDataSuccessResponse("Email verified");
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }
    }
}
