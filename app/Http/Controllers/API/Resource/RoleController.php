<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleParent;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index(){
        return ResponseHelper::ajaxResponseBuilder(true, 'Roles',Role::with('permissions')->with("roleParents")->get());
    }

    public function store(Request $request){
        $this->validate($request,[
            'role' => 'required|string',
            'description' => 'required|string',
            'parentRole'=>'required|int'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create($request->only('role','description'));

            /** A parent role must have the right to create a role. Except for super-admin */
            $roleParent = RoleParent::create([
                "role_id"=>$role->id,
                "parent_role_id"=>$request->parentRole
            ]);

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('role.created'), Role::with('permissions')->with("roleParents")->where('id',$role->id)->first());
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($role_id){
        return Role::findOrFail($role_id);
    }

    public function update(Request $request, $role_id){
        $this->validate($request, [
            'role' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            Role::findOrFail($role_id)->update($request->only('role','description'));
            return ResponseHelper::noDataSuccessResponse(__('role.updated'));
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('role.update-failed'));
        }
    }

    public function destroy($role_id){
        try {
            Role::findOrFail($role_id)->delete();
            return ResponseHelper::noDataSuccessResponse(__('role.deleted'));
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('role.delete-failed'));
        }
    }

    public function detachPermission($role_id, $permission_id){
        try{
            $role = Role::findOrFail($role_id);
            $role->permissions()->detach($permission_id);
            return ResponseHelper::ajaxResponseBuilder(true,__('role.detach-permission-success'),$role::where('id',$role_id)->with('permissions')->with("roleParents")->first());
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    /* ATTACH A SINGLE PERMISSION TO A ROLE */
    public function attachPermission($role_id, $permission_id){
        try{
            $role = Role::findOrFail($role_id);
            /* PREVENT A SINGLE PERMISSION ATTACHED TO A ROLE MORE THAN ONCE */
            if ($role->hasPermission($permission_id)) throw new \Exception("Permission already attached to the role", 400);

            $role->permissions()->attach($permission_id);

            return ResponseHelper::ajaxResponseBuilder(true,__('role.attach-permission-success'),$role::where('id',$role_id)->with('permissions')->with("roleParents")->first());
        }catch(\Exception $ex){
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    public function attachParent(Request $request){
        $this->validate($request, [
            'role_id' => 'required|int',
            'parent_role_id' => 'required|int',
        ]);

        try{
            $role = Role::findOrFail($request->role_id);
            $roleParent = RoleParent::create([
                "role_id"=>$request->role_id,
                "parent_role_id"=>$request->parent_role_id
            ]);
            return ResponseHelper::ajaxResponseBuilder(true,__('role.parent-attach-success'),$role::where('id',$request->role_id)->with('permissions')->with("roleParents")->first());
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    public function childRoles(){
        try{
            /*  Get all the roles of the auth user*/
            $userRoles=auth()->user()->roles;
            $roles=[];
            foreach($userRoles as $userRole){
                $parentRoleWithChildren=RoleParent::where("parent_role_id",$userRole->id)->with('roleChild')->get();
                foreach($parentRoleWithChildren as $childRole){
                   if(!empty($childRole->roleChild))  array_push($roles,$childRole->roleChild);
                }
            }
            return ResponseHelper::ajaxResponseBuilder(true, 'Child Roles', $roles);
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
       
    }

    public function detachParent($parent_id, $role_id){
        try{
            $parent = RoleParent::findOrFail($parent_id)->delete();
            return ResponseHelper::ajaxResponseBuilder(true,__('role.parent-detach-success'),Role::where('id',$role_id)->with('permissions')->with("roleParents")->first());
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

}
