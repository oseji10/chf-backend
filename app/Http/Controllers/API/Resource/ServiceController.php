<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\COEService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //
    public function index(){
        if(isset(request()->coe)){
            $services = Service::wherehas('coes',function($query){
                $query->where('coe_id',request()->coe);
            })->with('parent')->with('category')
            ->with('subServices')->with(['coes'=>function($coes){
                $coes->where('coe.id',request()->coe);
            }])
        ->get()->sortBy('service_name');
        return ResponseHelper::ajaxResponseBuilder(true,'Services',$services);
        }
        $services = Service::with('parent')->with('category')->with('subServices')->with('coes')
        ->get()->sortBy('service_name');
        return ResponseHelper::ajaxResponseBuilder(true,'Services',$services);
    }

    public function store(Request $request){
        $this->validate($request,[
            'service_name' => 'required|string',
            'service_code' => 'required|string|min:3|max:5',
            'price' => 'required|numeric',
            'service_category_id' => 'required|numeric',
        ]);
        try {
            $new_service = Service::create($request->all());

            $service = Service::where('id', $new_service->id)->with('parent')->with('category')->with('subServices')->with('coes')->first();

            return ResponseHelper::ajaxResponseBuilder(true,__('service.created'),$service, 201);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($service_id){
        try {
            $service = Service::where('id',$service_id)->with('parent')->with('category')->with('subServices')->with('coes');
            return ResponseHelper::ajaxResponseBuilder(true, __('service.found'),$service);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('service.find-failed'));
        }
    }

    public function update(Request $request,$service_id){
        $this->validate($request,[
            'service_name' => 'required|string',
            'service_category_id' => 'required|numeric',
            'service_code' => 'required|string|min:3|max:5',
            'price' => 'required|numeric',

        ]);

        try{
            Service::findOrFail($service_id)->update($request->all());
            $service = Service::where('id',$service_id)->with('parent')->with('category')->with('subServices')->with('coes')->first();
            return ResponseHelper::ajaxResponseBuilder(true, 'Service Updated', $service);
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse('Could not update Service');
        }
    }

    public function destroy($service_id){
        try {
            $service = Service::findOrFail($service_id);
            $service->delete();
            return ResponseHelper::noDataSuccessResponse(__('service.deleted'));
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('service.delete-failed'));
        }
    }

    public function attachCoe(Request $request, $service_id, $coe_id){
        $this->validate($request,[
            'price' => 'required|numeric',
        ]);
        try{
            $service = Service::findOrFail($service_id);
            $service->coes()->attach($coe_id, ['price' => $request->price]);
            return ResponseHelper::ajaxResponseBuilder(true,"Service attached successfully",$service::where('id',$service_id)->with('parent')->with('category')->with('subServices')->with('coes')->first());
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    public function detachCoe($service_id, $coe_id){
        try{
            $service = Service::findOrFail($service_id);
            $service->coes()->detach($coe_id);
            return ResponseHelper::ajaxResponseBuilder(true,"Service detached successfully",Service::where('id',$service_id)->with('parent')->with('category')->with('subServices')->with('coes')->first());
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    public function updateCoePrice(Request $request, $service_id, $coe_id){
        $this->validate($request,[
            'price' => 'required|numeric',
        ]);
        try{
            $service = Service::findOrFail($service_id);
            $service->coes()->updateExistingPivot($coe_id, ['price' => $request->price]);
            return ResponseHelper::ajaxResponseBuilder(true,"Service COE price successfully",$service::where('id',$service_id)->with('parent')->with('category')->with('subServices')->with('coes')->first());
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

}
