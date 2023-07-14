<?php

namespace App\Http\Controllers\API\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeopoliticalZone;
use App\Helpers\ResponseHelper;;

class GeopoliticalZoneController extends Controller
{
    //Get all geo-political zones
    public function index(){
        try{
            $zones= GeopoliticalZone::with("states.lgas")->orderBy('geopolitical_zone','asc')->get();
            return ResponseHelper::ajaxResponseBuilder(true, __('success'), $zones,200);

        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse(__('Unable to get records'));
        }
    }
}
