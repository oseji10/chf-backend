<?php

namespace App\Http\Controllers\API\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\tUserVerification;
use App\Models\Patient;
use App\Models\WalletTopup;
use App\Helpers\CHFConstants;

class CHFAdminController extends Controller
{
    use tUserVerification;

    /*
    * Here, the super admin creates CHF admins
    *
    */


    /*
    * Get all CHF Admins
    *
    */
    public function index(){
        try{
            $users=User::whereHas('roles',function($query){
                $query->where('role_id','4');
            })->orderBy('first_name','asc')->orderBy('last_name','asc')->get();
            return ResponseHelper::ajaxResponseBuilder(true, __('chfadmin.success'), $users ,200);

        }catch(\Exception $ex){
           
            return ResponseHelper::noDataErrorResponse(__('chfadmin.failed'));
        }
    }

    public function store(Request $request){
        $validated_data = $this->validate($request,[
            'email' => 'required|string|email|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'other_names' => 'string',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
            'gender' => 'required|string|max:6|min:4',
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
            $user->roles()->attach([4]);

            $this->sendCreatedEmailToStaff($request->email,$password);
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('chfadmin.created'), $user,201);
        } catch (\Exception $ex) {
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('chfadmin.failed'));
        }

    }


    /*
    * Get a particular chfadmin
    *
    */
    public function view($user_id){
        try{
            $user=User::findorfail($user_id);
            return ResponseHelper::ajaxResponseBuilder(true, __('chfadmin.success'), $user ,200);

        }catch(\Exception $ex){
            
            return ResponseHelper::noDataErrorResponse(__('chfadmin.failed'));
        }
    }
    
    public function search($search_value){
        try {
            $user = User::with('roles')->whereHas('roles',function($query){
                $query->where('role_id','4');
            })->where(function($query) use($search_value) {
                $query->where('email','like',"%{$search_value}")
                      ->orWhere('first_name','like',"%{$search_value}%")->orWhere('last_name','LIKE',"%{$search_value}%");
            })->get();
            return ResponseHelper::ajaxResponseBuilder(true, "CHF Admin search",$user);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('chfadmin.failed'));
        }
    }

    // public function chfPatients()
    // {
        
    //     // $coe_id = auth()->user()->coe_id;
    //     $patients = Patient::where(['social_worker_status' => CHFConstants::$APPROVED, 'primary_physician_status' => CHFConstants::$APPROVED])
    //         ->with('user',  'coe', 'wallet', 'walletTopup')
    //         ->get();

    //     return ResponseHelper::ajaxResponseBuilder(true, "COE Patient", $patients);
    // }

    public function chfPatients()
    {
        
        // $coe_id = auth()->user()->coe_id;
        $patients = WalletTopup::
            with('patient', 'coe', 'user', 'wallet')
            ->get();

        return ResponseHelper::ajaxResponseBuilder(true, "Patients Awaiting Topup", $patients);
    }
    
}
