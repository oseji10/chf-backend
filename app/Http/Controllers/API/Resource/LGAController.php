<?php

namespace App\Http\Controllers\API\Resource;

use App\Http\Controllers\Controller;
use App\Models\LGA;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class LGAController extends Controller
{
    //
    public function index(){
        return ResponseHelper::ajaxResponseBuilder(true, "LGAs", LGA::all());

    }
}
