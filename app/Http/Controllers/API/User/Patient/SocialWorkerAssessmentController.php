<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Helpers\CHFConstants;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Mail\SocialWorkerApprovedMail;
use App\Models\Patient;
use App\Models\SocialWorkerAssessment;
use App\Models\ApplicationReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class SocialWorkerAssessmentController extends Controller
{
    //
    public function store(Request $request)
    {
        $this->validate($request, [
            'appearance' => 'required|string',
            'bmi' => 'required',
            'comment_on_home' => 'required|string',
            'comment_on_environment' => 'required|string',
            'comment_on_fammily' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $applicationReview = ApplicationReview::where('id', $request->application_review_id)->first();

            if (empty($applicationReview)) {
                return ResponseHelper::noDataErrorResponse('Not authorized: ', 403);
            }


            // Update all records with status = "active"
            SocialWorkerAssessment::where('patient_id', $applicationReview->patient_id)->where('status', 'active')->update(['status' => 'inactive']);

            $request['patient_id'] = $applicationReview->patient_id;
            $request['user_id'] = $applicationReview->user_id;
            $request['application_review_id'] = $applicationReview->id;

            $socialWorkerAssess = SocialWorkerAssessment::create($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('socialWorkerAssessment.created'), SocialWorkerAssessment::find($socialWorkerAssess->id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'appearance' => 'required|string',
            'bmi' => 'required',
            'comment_on_home' => 'required|string',
            'comment_on_environment' => 'required|string',
            'comment_on_fammily' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Update all records with status = "active"
            SocialWorkerAssessment::where('id', $id)->update($request->only([
                'have_running_water',
                'appearance',
                'bmi',
                'comment_on_home',
                'comment_on_environment',
                'comment_on_fammily',
                'general_comment',
                'status'
            ]));

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('socialWorkerAssessment.updated'), SocialWorkerAssessment::find($id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($id)
    {
        return SocialWorkerAssessment::findOrFail($id);
    }

    public function viewActiveRecord()
    {
        return SocialWorkerAssessment::where('user_id', auth()->user()->id)->where('status', 'active')->first();
    }

    public function reviewPatient(Request $request)
    {
        $this->validate($request, [
            'status' => 'string|required',
            'patient_id' => 'numeric|required'
        ]);

        $mdts = User::whereHas('roles', function ($query) {
            $query->where('role', CHFConstants::$MDT);
        })->where('coe_id', auth()->user()->coe_id)->get();


        $patient = Patient::find($request->patient_id);

        $patient->social_worker_status = $request->status;
        $patient->social_worker_reviewed_on = date('Y-m-d h:i:s', time());
        $patient->social_worker_reviewer_id = auth()->id();

        if (strtolower($request->status) === strtolower(CHFConstants::$APPROVED)) {
            foreach ($mdts as $mdt) {
                \Mail::to($mdt->email)->send(new SocialWorkerApprovedMail(($patient)));
            }
        }

        $patient->save();

        return ResponseHelper::ajaxResponseBuilder(true, "Patient Review Successful", $patient);
    }
}
