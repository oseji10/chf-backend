<?php

namespace App\Http\Controllers\API\COEAdmin;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\CMDPatientDeclinedMail;
use App\Mail\CMDPatientRecommendationMail;

class COEAdminPatientController extends Controller
{
    //
    public function index()
    {
        /* PREVENT A USER THAT IS NOT A COE ADMIN FROM 
        *   FETCHING PATIENTS DATA FOR COE EVEN BY URL INJECTION
         */

        try {

            $patients = Patient::where('coe_id', auth()->user()->coe_id)->with('user', 'applicationReview', 'primaryPhysician', 'ailment', 'familyHistories', 'personalInformation', 'socialConditions', "SupportAssessments", 'coe', 'user.wallet', 'stateOfResidence')->get();

            return ResponseHelper::ajaxResponseBuilder(true, "COE Admin patients", $patients);
        } catch (\Exception $ex) {
            ResponseHelper::exceptionHandler($ex);
        }
    }

    public function reviewPatient(Request $request)
    {
        $this->validate($request, [
            'patient_id' => 'required|numeric',
            'status' => 'required|string|max:20'
        ]);

        try {
            $patient = Patient::find($request->patient_id);

            $patient->update([
                'cmd_review_status' => $request->status,
                'cmd_reviewed_on' => date('Y-m-d h:i:s', time()),
                'cmd_reviewer_id' => auth()->id(),
            ]);

            if (strtolower($request->status) === CHFConstants::$CMD_APPROVED) {

                $chf_admins = User::whereHas('roles', function ($query) {
                    return $query->where('role', CHFConstants::$CHF_ADMIN);
                })->get();

                foreach ($chf_admins as $admin) {
                    \Mail::to($admin->email)->send(new CMDPatientRecommendationMail($patient));
                }
            } else if (strtolower($request->status) === CHFConstants::$CMD_DECLINED) {
                \Mail::to($patient->primaryPhysician->email)->send(new CMDPatientDeclinedMail($patient));
            }

            return ResponseHelper::ajaxResponseBuilder(true, __("messages.patient_review_success"), $patient);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return ResponseHelper::exceptionHandler($ex);
        }
    }
}
