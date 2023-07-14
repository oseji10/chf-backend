<?php

namespace App\Http\Controllers\API\MDT;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\MDTComment;
use App\Models\Patient;
use Illuminate\Http\Request;

class MDTCommentController extends Controller
{
    //
    public function index($patient_id){
        
        try{
            $patient = Patient::where('chf_id', $patient_id)->first();
            $comments = MDTComment::where('patient_user_id', $patient->user->id)->with('mdtUser')->orderBy('created_at','desc')->get();
            return ResponseHelper::ajaxResponseBuilder(true, "MDT Comments", $comments);

        }catch(\Exception $ex){
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'visitation_date' => 'required|date',
            'patient_id' => "required",
            'comment' => 'required|string|min:50',
        ]);

        $patient = Patient::where('chf_id',$request->patient_id)->with('user')->first();
        try {
            MDTComment::create([
                'comment' => $request->comment,
                'patient_user_id' => $patient->user->id,
                'mdt_user_id' => auth()->id(),
                'visitation_date' => $request->visitation_date,
            ]);
            return ResponseHelper::noDataSuccessResponse("Comment created", 201);
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }

    }
}
