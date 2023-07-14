<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\ApplicationReview;
use App\Models\PersonalInformation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PersonalInformationController extends Controller
{
    //
    public function store(Request $request){
        $this->validate($request, [
        'gender'=>'required|string',
        'ethnicity'=>'required|string',
        'marital_status'=>'required|string',
        'no_of_children'=>'required|int',
        'level_of_education'=>'required|string',
        'religion'=>'required|string',
        'occupation'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
            $patient = Patient::where('user_id',auth()->user()->id)->first();

            if(empty($patient)){
                return ResponseHelper::noDataErrorResponse('Not authorized: ',403);
            }

            // Insert into application review
            $applicationReview = ApplicationReview::create(['patient_id'=>$patient->id,'coe_id'=>$patient->coe_id, 'user_id'=>$patient->user_id, 'status'=>'In Progress']);

            // Update all records with status = "active"
            PersonalInformation::where('patient_id',$patient->id)->where('status','active')->update(['status' => 'inactive']);
                
                $request['patient_id'] = $patient->id;
                $request['user_id']=auth()->user()->id;
                $request['status']="active";
                $request['application_review_id']=$applicationReview->id;
                $request['age']=$patient->user->age();

                $personalInfo = PersonalInformation::create($request->all());

                // Update Patient information
                Patient::where('id',$patient->id)->update($request->except(['patient_id','user_id','application_review_id','status','family_set_up','gender','age']));

                //Update User table
                User::where('id',$patient->user_id)->update($request->only(['gender']));


            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('personalInformation.created'), PersonalInformation::findOrFail($personalInfo->id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'gender'=>'required|string',
            'ethnicity'=>'required|string',
            'marital_status'=>'required|string',
            'no_of_children'=>'required|int',
            'level_of_education'=>'required|string',
            'religion'=>'required|string',
            'occupation'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
            $patient = Patient::where('user_id',auth()->user()->id)->first();

            if(empty($patient)){
                return ResponseHelper::noDataErrorResponse('Not authorised',403);
            }

            $request['age']=$patient->user->age();

            // Update all records with status = "active"
            PersonalInformation::where('id',$id)->where('status','active')->update($request->only(
                ['nhis_no','gender','ethnicity','marital_status','no_of_children','level_of_education','religion','occupation','age']));
                
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('familyHistory.updated'), PersonalInformation::findOrFail($id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($id){
        return ResponseHelper::ajaxResponseBuilder(true,'personal info', PersonalInformation::findOrFail($id));
    }

    public function viewActiveRecord(){
        return ResponseHelper::ajaxResponseBuilder(true,'personal active info',  PersonalInformation::where('user_id',auth()->user()->id)->where('status','active')->first());
    }
}
