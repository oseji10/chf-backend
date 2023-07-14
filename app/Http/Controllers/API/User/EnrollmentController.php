<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHelper;
use App\Models\User;

class EnrollmentController extends Controller
{
    
    /* 
    *   COMPLETE USER ENROLLMENT
    */
    public function completeEnrollment(Request $request){
        /* 
        *   USING THE 'sometimes' VALIDATION RULE ENABLES USING ONE ENDPOINT FOR 
        *   MULTISTAGE FORM. IT ONLY VALIDATE WHEN THE FIELD IS SENT.
         */
        $this->validate($request,[
            'first_name' => 'sometimes|required|string|min:3',
            'last_name' => 'sometimes|required|string|min:3',
            'identification_id' => 'sometimes|required|numberic',
            'identification_number' => 'sometimes|required|string|min:7',
            'yearly_income' => 'sometimes|required|numeric',
            'ailment_id' => 'sometimes|required|numeric',
            'gender' => 'sometimes|required|string|min:4',
            'date_of_birth' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
        ]);

        $user = auth()->user();

        try {
            DB::beginTransaction();
            $user->update($request->except('email'));
            $user->patient()->update(
                $request->only('identification_id','phone_no_alt','ailment_id','identification_number',
                'yearly_income','state_id','lga_id','address','city','state_of_residence')
            );
            DB::commit();

            $user = User::where('id',$user->id)->with("roles")->with('patient')->first();
            return ResponseHelper::ajaxResponseBuilder(true, __('account.updated'),$user);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('account.update-failed'));
        }
    }

     
    /* 
    *   VIEW USER ENROLLMENT
    */
    public function viewEnrollment(Request $request){
       
        $user = auth()->user();

        try {
            $user = User::where('id',$user->id)->with('patient')->first();
            return ResponseHelper::ajaxResponseBuilder(true, __('success'),$user);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('server.error'));
        }
    }
}
