<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\ApplicationReview;
use App\Models\PersonalHistory;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PersonalHistoryController extends Controller
{
    //
    public function store(Request $request){
        $this->validate($request, [
        'average_income_per_month'=>'required|int',
        'average_eat_daily'=>'required|int',
        'who_provides_feeding'=>'required|string',
        'have_accomodation'=>'required|string',
        'type_of_accomodation'=>'required|string',
        'no_of_good_set_of_cloths'=>'required|int',
        'how_you_get_them'=>'required|string',
        'where_you_receive_care'=>'required|string',
        'why_choose_center_above'=>'required|string',
        'level_of_spousal_support'=>'required|string',
        'other_support'=>'required|string'
        ]);

        try {
            DB::beginTransaction();
            $applicationReview = ApplicationReview::where('user_id',auth()->user()->id)->where('status','In Progress')->first();

            if(empty($applicationReview)){
                return ResponseHelper::noDataErrorResponse('Not authorized: ',403);
            }

            // Update all records with status = "active"
            PersonalHistory::where('patient_id',$applicationReview->patient_id)->where('status','active')->update(['status' => 'inactive']);
            
            $request['patient_id'] = $applicationReview->patient_id;
            $request['user_id']=$applicationReview->user_id;
            $request['status']='active';
            $request['application_review_id'] = $applicationReview->id;

            $personalHistory = PersonalHistory::create($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('personalHistory.created'), PersonalHistory::findOrFail($personalHistory->id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function update(Request $request,$id){
        $this->validate($request, [
            'average_income_per_month'=>'required|int',
            'average_eat_daily'=>'required|int',
            'who_provides_feeding'=>'required|string',
            'have_accomodation'=>'required|string',
            'type_of_accomodation'=>'required|string',
            'no_of_good_set_of_cloths'=>'required|int',
            'how_you_get_them'=>'required|string',
            'where_you_receive_care'=>'required|string',
            'why_choose_center_above'=>'required|string',
            'level_of_spousal_support'=>'required|string',
            'other_support'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
           
            // Update all records with status = "active"
            PersonalHistory::where('id',$id)->where('status','active')->update($request->all());
                
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('personalHistory.updated'), PersonalHistory::findOrFail($id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($id){
        return PersonalHistory::findOrFail($id);
    }

    public function viewActiveRecord(){
        return PersonalHistory::where('user_id',auth()->user()->id)->where('status','active')->first();
    }
}
