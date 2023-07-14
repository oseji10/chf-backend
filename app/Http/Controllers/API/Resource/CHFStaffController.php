<?php

namespace App\Http\Controllers\API\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\tUserVerification;

class CHFStaffController extends Controller
{
    use tUserVerification;
    /*
    * Here, the CHF admin creates CHF auditors and approvals staff
    *
    */


    /*
    * Get all
    *
    */
    public function index(){
        try{
            $per_page = request()->per_page ?? 10;
            $users=User::with('roles')->whereHas("roles",function($query){
                $query->where('role_id','19')->orWhere('role_id','27');
            })->orderBy('first_name','asc')->orderBy('last_name','asc')->paginate($per_page);
            
            return ResponseHelper::ajaxResponseBuilder(true, __('chfstaff.success'), $users ,200);
        }catch(\Exception $ex){
            
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }
    }

    public function store(Request $request){
        $validated_data = $this->validate($request,[
            'email' => 'required|string|email|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'other_names' => 'string',
            'phone_number' => 'required|string',
            'gender' => 'required|string|max:6|min:4',
        ]);

        try {
            DB::beginTransaction();
            
            $password=$request->phone_number;
            $validated_data['password'] = Hash::make($password);
            $validated_data['email_verified_at']=date("Y-m-d H:i:s");

            $user = User::create($validated_data);
            $user->roles()->attach([7]);

            $this->sendCreatedEmailToStaff($request->email,$password);
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('chfstaff.created'), User::where('email',$request->email)->first(),201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }

    }


    /*
    * Get a particular chf staff
    *
    */
    public function view($user_id,User $user){
        try{
            return ResponseHelper::ajaxResponseBuilder(true, __('chfstaff.success'),  $user->where("id",$user_id)->with("roles")->first() ,200);

        }catch(\Exception $ex){
            
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }
    }

    public function search($search_value){
        try {
            $user = User::with('roles')->whereHas('roles',function($query){
                $query->where('role_id','7');
            })->where(function($query) use($search_value) {
                $query->where('email','like',"%{$search_value}")
                      ->orWhere('first_name','like',"%{$search_value}%")->orWhere('last_name','LIKE',"%{$search_value}%");
            })->get();
            return ResponseHelper::ajaxResponseBuilder(true, "CHF staff search",$user);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }
    }


   
}
