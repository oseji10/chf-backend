<?php

namespace App\Http\Controllers\API\Resource;

use App\Events\ApplicationSubmittedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\ApplicationReview;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApplicationReviewController extends Controller
{
    //
    public function index(){
        try{
            $applicationReviews=ApplicationReview::orderBy('created_at','desc')->get();
            $responseData=[];
            foreach($applicationReviews as $applicationReview){
                $responseData[]=array(
                "applicationReview"=> $applicationReview,
                "user"=>$applicationReview->user,
                "ailment"=>$applicationReview->ailment,
                "patient"=> $applicationReview->patient,
                "personalInformation"=>$applicationReview->personalInformation,
                "familyHistory"=> $applicationReview->familyHistory,
                "personalHistory"=>$applicationReview->personalHistory,
                "socialCondition"=>$applicationReview->socialCondition,
                "socialWorkerAssessment"=>$applicationReview->SocialWorkerAssessment,
                "supportAssessment"=>$applicationReview->supportAssessment,
                );
            }
            
            return ResponseHelper::ajaxResponseBuilder(true, __('success'), $responseData,200);
        }catch(\Exception $ex){
            
            return ResponseHelper::noDataErrorResponse(__('Unable to get application reviews'));
        }
    }

public function view($user_id){
    try{
        $applicationReviews=ApplicationReview::where('user_id',$user_id)->orderBy('created_at','desc')->get();
        $responseData=[];
        foreach($applicationReviews as $applicationReview){
            array_push($responseData,[
                "applicationReview"=> $applicationReview,
                "user"=>$applicationReview->user,
                "ailment"=>$applicationReview->ailment,
                "patient"=> $applicationReview->patient,
                "personalInformation"=>$applicationReview->personalInformation,
                "familyHistory"=> $applicationReview->familyHistory,
                "personalHistory"=>$applicationReview->personalHistory,
                "socialCondition"=>$applicationReview->socialCondition,
                "socialWorkerAssessment"=>$applicationReview->SocialWorkerAssessment,
                "supportAssessment"=>$applicationReview->supportAssessment,
            ]);
    }
        return ResponseHelper::ajaxResponseBuilder(true, __('success'), $responseData,200);
    }catch(\Exception $ex){
        return ResponseHelper::noDataErrorResponse(__('Unable to get application review'));
    }
}
//viewById
public function viewById($id){
    try{
        $applicationReview=ApplicationReview::find($id);
        $responseData["applicationReview"]=$applicationReview;
        $responseData["user"]=$applicationReview->user;
        $responseData["ailment"]=$applicationReview->patient->ailment;
        $responseData["patient"]=$applicationReview->patient;
        $responseData["personalInformation"]=$applicationReview->personalInformation;
        $responseData["familyHistory"]=$applicationReview->familyHistory;
        $responseData["personalHistory"]=$applicationReview->personalHistory;
        $responseData["socialCondition"]=$applicationReview->socialCondition;
        $responseData["socialWorkerAssessment"]=$applicationReview->socialWorkerAssessment;
        $responseData["supportAssessment"]=$applicationReview->supportAssessment;
        $responseData["nextOfKin"]=$applicationReview->patient->nextOfKin;
        $responseData["coe"]=$applicationReview->patient->coe;
        $responseData["state"]=$applicationReview->patient->state;
        $responseData["approvals"]=$applicationReview->committeeApprovals;
        $responseData["state_of_residence"]=$applicationReview->patient->stateOfResidence;
    
        return ResponseHelper::ajaxResponseBuilder(true, __('success'), $responseData,200);
    }catch(\Exception $ex){
        return ResponseHelper::noDataErrorResponse(__('Unable to get application review'));
    }
}

public function update(Request $request, $application_review_id){

    try {
        DB::beginTransaction();
       
        ApplicationReview::where('id',$application_review_id)->update($request->all());

        // Send an email
        $applicationReview=ApplicationReview::where('id',$application_review_id)->first();
        event(new ApplicationSubmittedEvent($applicationReview));
            
        DB::commit();
        return ResponseHelper::ajaxResponseBuilder(true,'updated', $applicationReview);
    } catch (\Exception $ex) {
        DB::rollBack();
        // \Log::info($ex);
        return ResponseHelper::noDataErrorResponse(__('errors.server'));
    }
}

public function viewByCoe($coe_id, $user_id){
    try{
        $patient=Patient::where('coe_id',$coe_id)->where('id',$user_id)->orWhere('chf_id',$user_id)->orWhere('nhis_no',$user_id)->orWhereHas('user', function($query) use($user_id){
            $query->where('email', $user_id)->orWhere('phone_number',$user_id);
        })->first();
         
        $applicationReviews=ApplicationReview::where('patient_id',$patient->id)->orderBy('created_at','desc')->get();
        $responseData=[];
        foreach($applicationReviews as $applicationReview){
            array_push($responseData,[
                "applicationReview"=> $applicationReview,
                "user"=>$applicationReview->user,
                "ailment"=>$applicationReview->ailment,
                "patient"=> $applicationReview->patient,
                "personalInformation"=>$applicationReview->personalInformation,
                "familyHistory"=> $applicationReview->familyHistory,
                "personalHistory"=>$applicationReview->personalHistory,
                "socialCondition"=>$applicationReview->socialCondition,
                "socialWorkerAssessment"=>$applicationReview->SocialWorkerAssessment,
                "supportAssessment"=>$applicationReview->supportAssessment,
                "primaryPhysician"=>$applicationReview->patient->primaryPhysician,
            ]);
    }
        return ResponseHelper::ajaxResponseBuilder(true, __('success'), $responseData,200);
    }catch(\Exception $ex){
        // \Log::info($ex);
        return ResponseHelper::noDataErrorResponse(__('Patient not found in  this hospital'));
    }
}

}