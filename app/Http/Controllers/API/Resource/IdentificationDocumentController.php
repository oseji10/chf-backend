<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\IdentificationDocument;
use Illuminate\Http\Request;

class IdentificationDocumentController extends Controller
{
    //
    public function index(){
        try {
            return ResponseHelper::ajaxResponseBuilder(true,"Identification Documents",IdentificationDocument::all());
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }
}
