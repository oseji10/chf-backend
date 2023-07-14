<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\ApplicationReview;
use App\Models\SupportAssessment;
use Illuminate\Support\Facades\DB;

class SupportAssessmentController extends Controller
{
    //
    public function store(Request $request){
        $this->validate($request, [
        'feeding_assistance'=>'required|string',
        'medical_assistance'=>'required|string',
        'rent_assistance'=>'required|string',
        'clothing_assistance'=>'required|string',
        'transport_assistance'=>'required|string',
        'mobile_bill_assistance'=>'required|string',
        ]);

        // try {
            DB::beginTransaction();
            $applicationReview = ApplicationReview::where('user_id',auth()->user()->id)->where('status','In Progress')->first();

            if(empty($applicationReview)){
                return ResponseHelper::noDataErrorResponse('Not authorized: ',403);
            }

            $points_user_input=$this->userInputPoints($request->only([
                'feeding_assistance',
                'medical_assistance',
                'rent_assistance',
                'clothing_assistance',
                'transport_assistance',
                'mobile_bill_assistance',
            ]));
           

            $points_sys_suggested=$this->systemSuggestedPoints($applicationReview->personalHistory->average_income_per_month, $applicationReview->personalInformation->no_of_children);

            // Update all records with status = "active"
            SupportAssessment::where('patient_id',$applicationReview->patient_id)->where('status','active')->update(['status' => 'inactive']);
                
            $request['patient_id'] = $applicationReview->patient_id;
            $request['user_id']=$applicationReview->user_id;
            $request['points_user_input'] = $points_user_input;
            $request['points_sys_suggested']=$points_sys_suggested;
            $request['status']='active';
            $request['application_review_id'] = $applicationReview->id;

            $supportAssessment = SupportAssessment::create($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('supportAssessment.created'), SupportAssessment::findOrFail($supportAssessment->id));
        // } catch (\Exception $ex) {
        //     DB::rollBack();
        //     // \Log::info($ex);
        //     return ResponseHelper::noDataErrorResponse(__('errors.server'));
        // }
    }

    function convertInputToPoint($value){
        switch($value){
            case "Never":
                return 0;
            case "Once in a while": 
                return 1;
            case "Sometimes":
                return 2;
            case "Usually": 
                return 3;
            case "All the time": 
                return 4;      
            default: return 0;
        }  
    }

    function userInputPoints($userInputs){
        $points=0;
        foreach($userInputs as $input => $input_value){
            $points+=$this->convertInputToPoint($input_value);
        }
        return $points;
    }

    function systemSuggestedPoints($average_income_per_month, $no_of_children){
        $feeding_assistance=0;
        $medical_assistance=0;
        $rent_assistance=0;
        $clothing_assistance=0;
        $transport_assistance=0;
        $mobile_bill_assistance=0;
        $average_cost_per_head_per_day = $average_income_per_month/(($no_of_children+1)*30);
        if($average_cost_per_head_per_day>=2000){
            $feeding_assistance=1;
            $medical_assistance=3;
            $rent_assistance=3;
            $clothing_assistance=1;
            $transport_assistance=1;
            $mobile_bill_assistance=1;
        }else  if($average_cost_per_head_per_day<2000 && $average_cost_per_head_per_day>1500){
            $feeding_assistance=2;
            $medical_assistance=3;
            $rent_assistance=3;
            $clothing_assistance=2;
            $transport_assistance=2;
            $mobile_bill_assistance=1;
        }else  if($average_cost_per_head_per_day<=1500 && $average_cost_per_head_per_day>1000){
                $feeding_assistance=3;
                $medical_assistance=4;
                $rent_assistance=4;
                $clothing_assistance=2;
                $transport_assistance=1;
                $mobile_bill_assistance=2;
        }else {
                $feeding_assistance=3;
                $medical_assistance=4;
                $rent_assistance=4;
                $clothing_assistance=3;
                $transport_assistance=3;
                $mobile_bill_assistance=2;
        }
        return $feeding_assistance+$medical_assistance+$rent_assistance+$clothing_assistance+$transport_assistance+$mobile_bill_assistance;
    }

    public function update(Request $request,$id){
        $this->validate($request, [
            'feeding_assistance'=>'required|string',
            'medical_assistance'=>'required|string',
            'rent_assistance'=>'required|string',
            'clothing_assistance'=>'required|string',
            'transport_assistance'=>'required|string',
            'mobile_bill_assistance'=>'required|string',
        ]);

        // try {
        DB::beginTransaction();

        $applicationReview = ApplicationReview::where('status','In Progress')->whereHas('supportAssessment', function($query) use($id){
            $query->where('id', $id);
        })->first();

        $points_user_input=$this->userInputPoints($request->only([
            'feeding_assistance',
            'medical_assistance',
            'rent_assistance',
            'clothing_assistance',
            'transport_assistance',
            'mobile_bill_assistance',
        ]));
           

        $points_sys_suggested=$this->systemSuggestedPoints($applicationReview->personalHistory->average_income_per_month, $applicationReview->personalInformation->no_of_children);
        $request['points_user_input'] = $points_user_input;
        $request['points_sys_suggested']=$points_sys_suggested;
        
        // Update all records with status = "active"
        SupportAssessment::where('id',$id)->where('status','active')->update($request->only([
            'feeding_assistance',
            'medical_assistance',
            'rent_assistance',
            'clothing_assistance',
            'transport_assistance',
            'mobile_bill_assistance',
            'points_user_input',
            'points_sys_suggested'
        ]));
            
        DB::commit();
        // return ResponseHelper::ajaxResponseBuilder(true,__('supportAssessment.updated'), SupportAssessment::findOrFail($id));
        // } catch (\Exception $ex) {
        //     DB::rollBack();
        //     return ResponseHelper::noDataErrorResponse(__('errors.server'));
        // }
    }

    public function view($id){
        return SupportAssessment::findOrFail($id);
    }

    public function viewActiveRecord(){
        return SupportAssessment::where('user_id',auth()->user()->id)->where('status','active')->first();
    }

}
