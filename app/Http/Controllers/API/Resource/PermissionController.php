<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //
    public function index(){
        return ResponseHelper::ajaxResponseBuilder(true, 'All Permissions', Permission::with('roles')->orderBy('permission')->get());
    }

    public function store(Request $request){
        $this->validate($request,[
            'permission' => 'required|string',
        ]);

        try {
            $permission = Permission::create([
                "permission" => strtoupper($request->permission),
            ]);
            
            return ResponseHelper::ajaxResponseBuilder(true,__('permission.created'), Permission::with('roles')->where('id',$permission->id)->first());
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
