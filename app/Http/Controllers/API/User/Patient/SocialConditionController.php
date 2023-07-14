<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\SocialCondition;
use App\Models\ApplicationReview;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class SocialConditionController extends Controller
{
    //
    public function store(Request $request){
        $this->validate($request, [
            'have_running_water'=>'required|string',
            'type_of_toilet_facility'=>'required|string',
            'have_generator_solar'=>'required|string',
            'means_of_transportation'=>'required|string',
            'have_mobile_phone'=>'required|string',
            'how_maintain_phone_use'=>'required|string'
        ]);

        try {
            DB::beginTransaction();
            $applicationReview = ApplicationReview::where('user_id',auth()->user()->id)->where('status','In Progress')->first();

            if(empty($applicationReview)){
                return ResponseHelper::noDataErrorResponse('Not authorized: ',403);
            }


            // Update all records with status = "active"
            SocialCondition::where('patient_id',$applicationReview->patient_id)->where('status','active')->update(['status' => 'inactive']);
                
            $request['patient_id'] = $applicationReview->patient_id;
            $request['user_id']=$applicationReview->user_id;
            $request['status']='active';
            $request['application_review_id'] = $applicationReview->id;

            $socialCondition = SocialCondition::create($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('socialCondition.created'), SocialCondition::findOrFail($socialCondition->id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function update(Request $request,$id){
        $this->validate($request, [
            'have_running_water'=>'required|string',
            'type_of_toilet_facility'=>'required|string',
            'have_generator_solar'=>'required|string',
            'means_of_transportation'=>'required|string',
            'have_mobile_phone'=>'required|string',
            'how_maintain_phone_use'=>'required|string'
        ]);

        try {
            DB::beginTransaction();
           
            // Update all records with status = "active"
            SocialCondition::where('id',$id)->where('status','active')->update($request->only([
                'have_running_water',
                'type_of_toilet_facility',
                'have_generator_solar',
                'means_of_transportation',
                'have_mobile_phone',
                'how_maintain_phone_use'
            ]));
                
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('socialCondition.updated'), SocialCondition::findOrFail($id));
        } catch (\Exception $ex) {
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($id){
        return SocialCondition::findOrFail($id);
    }

    public function viewActiveRecord(){
        return SocialCondition::where('user_id',auth()->user()->id)->where('status','active')->first();
    }
}
