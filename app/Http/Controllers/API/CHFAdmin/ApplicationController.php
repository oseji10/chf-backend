<?php

namespace App\Http\Controllers\API\CHFAdmin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ApplicationReview;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    //
    public function index(){
        $per_page = request()->per_page ?? 20;
        $filter = request()->filter;

        $applications = ApplicationReview::with('patient')->with('user')->with('supportAssessment')->with('committeeApprovals')->with('patient.coe')->with('patient.ailment');
        // $applications = ApplicationReview::whereIn('status',['pending','approved', 'declined','recommended'])->with('patient')->with('user')->with('supportAssessment')->with('committeeApprovals')->with('patient.coe')->with('patient.ailment');

        // switch($filter){
        //     case 'new':{
        //         $applications = $applications->where('status','pending');
        //         break;
        //     }
        //     case 'approved': {
        //         $applications = $applications->where('status','approved');
        //         break;
        //     }
        //     case 'declined': {
        //         $applications = $applications->where('status','declined');
        //         break;
        //     }
        //     default: 
                
        // }

        // return ResponseHelper::ajaxResponseBuilder(true, _('success'), $applications->orderBy('created_at','desc')->paginate($per_page));
        return ResponseHelper::ajaxResponseBuilder(true, _('success'), $applications->orderBy('created_at','desc')->get());
    }

    // Search Application
    public function search($application_id){
        $per_page = request()->per_page ?? 20;
        // try {
            $applications = ApplicationReview::whereIn('status',['pending','approved','declined'])->whereHas('user',function($query) use($application_id){
                return $query->where('email',$application_id);
            })->orWhereHas('patient',function($query) use($application_id){
                $query->where('chf_id',$application_id);
            })->with('user')->with('supportAssessment')->with('patient')->with('patient.coe')->with('patient.ailment')->with('committeeApprovals')->orderBy('created_at', 'desc')->paginate($per_page);


            return ResponseHelper::ajaxResponseBuilder(true, "Applicaiton search result",$applications);
        // } catch (\Exception $ex) {
        //     // \Log::info($ex);
        //     return ResponseHelper::noDataErrorResponse(__('patient.find-failed'));
        // }
    }
}
