<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\COE;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\tUserVerification;
use Illuminate\Support\Facades\Date;

class COEController extends Controller
{
    use tUserVerification;
    
    //Get all COEs
    public function index(){    
        $coesWithWallet=COE::with("state")->with("wallet")->orderBy('coe_name','desc')->get();
        return ResponseHelper::ajaxResponseBuilder(true, __('success'), $coesWithWallet,200);
    }

    public function store(Request $request){
        $validated_data = $this->validate($request,[
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|min:10',
            'coe_name' => 'required|string',
            'coe_type' => 'required|string',
            'coe_address' => 'string',
            'state_id' => 'required|string',
        ]);

        try {
            // The default password will be the staff firstname and lastname
            $password=$request->phone_number;
            $validated_data['password'] = Hash::make($password);
            $validated_data['email_verified_at']=date("Y-m-d H:i:s");
            $request['id']=time();
            DB::beginTransaction();

            // create a coe admin
            $user= User::create($validated_data);
    
            //Create COE
            $coe = COE::create($request->only(['coe_name','coe_type','coe_address','state_id','coe_id_cap','id']));

            $validated_data['coe_id']= $coe->id;

            //Attach coe admin role to use
            $user->roles()->attach([3]);

            //create a coe wallet
            $validated_data["is_coe"]=1;
            $user->wallet()->create($validated_data);

            $this->sendCreatedEmailToStaff($request->email,$password);

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('coe.created'), COE::find($coe->id)->with("state")->with("wallet")->with("staffs")->whereHas("staffs",function($query) use ($coe){
                $query->where("id",$coe->Wallet->user_id); })->first() ,201);
        } catch (\Exception $ex) {
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('coe.failed'));
        }

    }

    //Get a single coe
    public function view($coe_id){
        try{
            $coe = COE::with("state")->with("wallet")->with("wallet.user")->find($coe_id);
           
            return ResponseHelper::ajaxResponseBuilder(true, __('coe.success'), $coe,200);

        }catch(\Exception $ex){
           
            return ResponseHelper::noDataErrorResponse(__('coe.failed'));
        }
       
    }

    public function patients(){
        try{
            $coe = COE::where('id',auth()->user()->coe_id)->whereHas('patients.applicationReview',function($query){
                $query->where('status','approved');
            })->with('patients')->with('patients.user')->with('patients.user.applicationReview')->first();
            return ResponseHelper::ajaxResponseBuilder(true, "Patients for " . $coe->coe_name, $coe);
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }

    public function updateCOE(Request $request, $coe_id){
        $validated_data = $this->validate($request,[
            'coe_name' => 'required|string',
            'coe_type' => 'required|string',
            'coe_address' => 'string',
            'state_id' => 'required|int',
        ]);
        try{
             //Check that the user has roles of Super admin before proceeding
             $hasRoles=auth()->user()->roles()->wherePivot("role_id",'5')->first();
             if(empty($hasRoles)){
                 return ResponseHelper::noDataErrorResponse(__('Not authorized'));
             }

            $coe=COE::find($coe_id);

            DB::beginTransaction();

            $coe->update($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('coe.update'), 
            COE::with("state")->with("wallet")->find($coe->id),200);
        }catch(\Exception $ex){
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('coe.failed'));
        }
    }

    public function search($search_value){
        try {
            $coe = COE::with("state")->with('wallet')->where('coe_type',$search_value)->orWhere('coe_name','LIKE',"%{$search_value}%")
            ->paginate(10);

            return ResponseHelper::ajaxResponseBuilder(true, "COE search",$coe);
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('coe.failed'));
        }
    }

    public function destroy($coe_id){
        try{
             //Check that the user has roles for CHF admin and Super admin before proceeding
             $hasRoles=auth()->user()->roles()->wherePivot("role_id",'5')->first();
             if(empty($hasRoles)){
                 return ResponseHelper::noDataErrorResponse(__('Not authorized'));
             }

            DB::beginTransaction();

            $coe=COE::find($coe_id)->with("user")->roles()->wherePivot("role_id",'3')->with("wallet")->first();

            $user= $coe->user;
            $wallet = $coe->wallet;

            $coe->delete();

            $user->delete();

            $wallet->delete();

            DB::commit();
    
            return ResponseHelper::noDataSuccessResponse(__('coe.delete'));
        }catch(\Exception $ex){
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('coe.failed'));
        }
    }

    public function getStaff($coe_id){
        try{
            $coe=COE::with('staffs','staffs.roles')->find($coe_id);
            return ResponseHelper::ajaxResponseBuilder(true, __('coe staff'),$coe);
        }catch(\Exception $ex){
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('coe.failed'));
        }
    }

}
