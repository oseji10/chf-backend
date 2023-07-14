<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Ailment;
use Illuminate\Http\Request;

class AilmentController extends Controller
{
    //
    public function index(){
        try {
            return ResponseHelper::ajaxResponseBuilder(true, "All ailments",Ailment::get());
        } catch (\Exception $e) {
            return ResponseHelper::noDataErrorResponse($e->getMessage());
        }
    }
}
