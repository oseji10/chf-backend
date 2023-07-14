<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\Role;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    //
    public function index(Request $request){
        /** If query param role is provided then return only service category attached to that role */
        
        if(isset(request()->roles)){
            $roles=explode(',',request()->roles);
            $coeId=request()->coe;
            $service_categories=[];
            foreach($roles as $categoryRole){
                $service_category=ServiceCategory::whereHas('roles', function($query) use($categoryRole){
                    return $query->where('role_id',$categoryRole);
                })
                ->with('roles')->with(['services'=>function($services) use($coeId){
                     $services->whereHas('coes',function($query) use($coeId){
                        return $query->where('coe_id',$coeId);
                    })->with(['coes'=>function($coes) use($coeId){
                        $coes->where('coe.id',$coeId);
                    }]);
                }])->get()->toArray();
                 $service_categories=array_merge($service_categories,$service_category);
            }
            
            return ResponseHelper::ajaxResponseBuilder(true, "All categories by role",collect($service_categories));
        }
       
        $service_categories = ServiceCategory::with('roles')->with('services')->with('services.coes')->get();
        return ResponseHelper::ajaxResponseBuilder(true, "All categories",$service_categories);
    }
    
    public function store(Request $request){
        $this->validate($request,[
            'category_name' => 'required|string',
            'category_code' => 'required|string|min:3|max:5',
        ]);

        try {
            $new_category = ServiceCategory::create(array_merge($request->all(),["created_by",auth()->user()->id]));

           $serviceCategory = ServiceCategory::where('id',$new_category->id)->with('roles')->with('services')->first();

            return ResponseHelper::ajaxResponseBuilder(true,__('category.created'),$serviceCategory, 201);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($service_category_id){
        try {
            $serviceCategory = ServiceCategory::findOrFail($service_category_id)->with('roles')->with('services')->first();;
            return ResponseHelper::ajaxResponseBuilder(true, __('category.found'),$serviceCategory);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('category.find-failed'));
        }
    }

    public function update(Request $request,$service_category_id){
        $this->validate($request,[
            'category_name' => 'required|string',
            'category_code' => 'required|string|min:3|max:5',
        ]);

        try{
            $serviceCategory=ServiceCategory::findOrFail($service_category_id);
            $serviceCategory->update($request->all());
            return ResponseHelper::ajaxResponseBuilder(true, __('category.updated'), $serviceCategory->where('id',$service_category_id)->with('roles')->with('services')->first());
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('category.update-failed'));
        }
    }

    public function destroy($service_category_id){
        try {
            $serviceCategory = ServiceCategory::findOrFail($service_category_id);
            if($serviceCategory->services->count()>0){
                return ResponseHelper::noDataErrorResponse('Category already has services and cannot be deleted');
            }
            $serviceCategory->delete();
            return ResponseHelper::noDataSuccessResponse(__('category.deleted'));
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('category.delete-failed'));
        }
    }

    public function detachRole( $service_category_id,$role_id){
        try{
            $serviceCategory = ServiceCategory::findOrFail($service_category_id);
            $serviceCategory->roles()->detach($role_id);
            return ResponseHelper::ajaxResponseBuilder(true,__('category.detach-role-success'),$serviceCategory::where('id',$service_category_id)->with('roles')->with('services')->first());
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    public function attachRole($service_category_id, $role_id){
        try{
            $serviceCategory = ServiceCategory::findOrFail($service_category_id);
            $serviceCategory->roles()->attach($role_id);
            return ResponseHelper::ajaxResponseBuilder(true,__('category.attach-role-success'),ServiceCategory::where('id',$service_category_id)->with('roles')->with('services')->first());
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

}    