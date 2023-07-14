<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Request;

class CustomException extends Exception
{
    //
    public function report(){
        
    }

    public function render(Request $reques){
        
    }
}
