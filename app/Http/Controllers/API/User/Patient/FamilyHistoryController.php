<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\FamilyHistory;
use App\Models\ApplicationReview;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class FamilyHistoryController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
        'family_set_up'=>'required|string',
        'family_size'=>'required|int',
        'birth_order'=>'required|int',
        'father_education_status'=>'required|string',
        'mother_education_status'=>'required|string',
        'fathers_occupation'=>'required|string',
        'mothers_occupation'=>'required|string',
        'level_of_family_care'=>'required|string',
        'family_total_income_month'=>'required',
        ]);

        try {
            DB::beginTransaction();
            $applicationReview = ApplicationReview::where('user_id',auth()->user()->id)->where('status','In Progress')->first();

            if(empty($applicationReview)){
                return ResponseHelper::noDataErrorResponse('Not authorized: ',403);
            }

            // Update all records with status = "active"
            FamilyHistory::where('patient_id',$applicationReview->patient_id)->where('status','active')->update(['status' => 'inactive']);
            
            $request['patient_id'] = $applicationReview->patient_id;
            $request['user_id']=$applicationReview->user_id;
            $request['status']='active';
            $request['application_review_id'] = $applicationReview->id;
            $familyHistory = FamilyHistory::create($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('familyHistory.created'), FamilyHistory::findOrFail($familyHistory->id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function update(Request $request,$id){
        $this->validate($request, [
        'family_set_up'=>'required|string',
        'family_size'=>'required|int',
        'birth_order'=>'required|int',
        'father_education_status'=>'required|string',
        'mother_education_status'=>'required|string',
        'fathers_occupation'=>'required|string',
        'mothers_occupation'=>'required|string',
        'level_of_family_care'=>'required|string',
        'family_total_income_month'=>'required',
        ]);

        try {
            DB::beginTransaction();
           
            // Update all records with status = "active"
            FamilyHistory::where('id',$id)->where('status','active')->update($request->all());
                
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('familyHistory.updated'), FamilyHistory::findOrFail($id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($id){
        return FamilyHistory::findOrFail($id);
    }

    public function viewActiveRecord(){
        return FamilyHistory::where('user_id',auth()->user()->id)->where('status','active')->first();
    }

}
