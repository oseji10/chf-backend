<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    //
    public function index(){
        return ResponseHelper::ajaxResponseBuilder(true, "States", State::all());

    }
}
