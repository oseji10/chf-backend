<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    //
    public function index(){
        return ResponseHelper::ajaxResponseBuilder(true, "Site Settings", SiteSetting::all());
    }

    public function store(Request $request){
        $this->validate($request, [
            'key'=>'required|string',
            'value'=>'required|string',
            ]);
    
            try {
                DB::beginTransaction();
    
                $siteSetting = SiteSetting::create($request->all());
    
                DB::commit();
                return ResponseHelper::ajaxResponseBuilder(true,'Created', $siteSetting );
            } catch (\Exception $ex) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse(__('errors.server'));
            }
    }

    public function view($key){
        try{
            $setting = SiteSetting::where('key',$key)->first();

            if (!$setting) {
                throw new ModelNotFoundException("Could not find setting with that key");
            }

            return ResponseHelper::ajaxResponseBuilder(true, "Setting", $setting);
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    public function update(Request $request,$id){
        $this->validate($request, [
            'key'=>'required|string',
            'value'=>'required|string',
            ]);
    
            try {
    
                SiteSetting::where('id',$id)->update($request->all());
    
                return ResponseHelper::ajaxResponseBuilder(true,'Update', SiteSetting::findOrFail($id) );
            } catch (\Exception $ex) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse(__('errors.server'));
            }
    }
}
