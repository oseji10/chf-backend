<?php

namespace App\Http\Controllers\API\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\tUserVerification;
use Illuminate\Database\Eloquent\Builder;


class COEHelpDeskController extends Controller
{
    use tUserVerification;
     /*
    * Here, the super admin creates CHF help desk staff
    *
    */

    /*
    * Get all
    *
    */
    public function index(){
        try{
            $coe_id=request()->coe_id;
           if($coe_id){
            $users=User::whereHas("roles", function(Builder $query){
                $query->where("role.id",6);
            })->where('coe_id',$coe_id)->get();
           }else{
            $users=User::whereHas("roles", function(Builder $query){
                $query->where("role.id",6);
            })->get();
           }
            return ResponseHelper::ajaxResponseBuilder(true, __('chfstaff.success'), $users ,200);

        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }
    }

    public function store(Request $request){
        $validated_data = $this->validate($request,[
            'email' => 'required|string|email|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'other_names' => 'string',
            'date_of_birth' => 'date',
            'phone_number' => 'required|string',
            'gender' => 'required|string|max:6|min:4',
            'coe_id'=>'required|string',
        ]);

        try {

            DB::beginTransaction();
            
            /* 
            *   HASH THE USER'S PASSWORD
            */
            $password=$request->phone_number;
            $validated_data['password'] = Hash::make($password);
            $validated_data['email_verified_at']=date("Y-m-d H:i:s");

            $user = User::create($validated_data);
            $user->roles()->attach([6]);

            $this->sendCreatedEmailToStaff($request->email,$password);
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('chfstaff.created'), User::where('email',$request->email)->first(),201);
        } catch (\Exception $ex) {
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }

    }


    /*
    * Get a particular CHF help desk staff
    *
    */
    public function view($user_id){
        try{
            $user=User::findorfail($user_id);
            return ResponseHelper::ajaxResponseBuilder(true, __('chfstaff.success'), $user ,200);

        }catch(\Exception $ex){
            
            return ResponseHelper::noDataErrorResponse(__('chfstaff.failed'));
        }
    }

}
