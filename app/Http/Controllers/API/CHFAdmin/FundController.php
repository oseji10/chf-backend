<?php

namespace App\Http\Controllers\API\CHFAdmin;

use App\Events\FundApproved;
use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ApplicationReview;
use App\Models\SiteSetting;
use App\Models\StageApprovalAmount;
use App\Models\CommitteeApproval;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\COE\CMDRecommendationRejectedMail;
use App\Mail\TestMail;

class FundController extends Controller
{

    public function recommendPatient(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|string',
            'application_id' => 'required|numeric',
            'comment' => "required|string|min:3"
        ]);

        $application = ApplicationReview::findOrFail($request->application_id);

        if ($application->hasReviewBy(auth()->id())) {
            return ResponseHelper::noDataErrorResponse("You have already reviewed this application", 400);
        }

        CommitteeApproval::create([
            'committee_member_id' => auth()->id(),
            'application_review_id' => $request->application_id,
            'status' => strtolower($request->status),
            'reason' => $request->comment,
        ]);

        return ResponseHelper::ajaxResponseBuilder(true, "Application reviewed successfully", $application->committeeApprovals);
    }

    /* 
    *   APPROVE A PATIENT FUND
     */
    public function approveFund(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|numeric',
            // 'reason' => 'string',
            'status' => 'required|string',
            'application_id' => 'required|numeric'
        ]);

        /* 
        *   TODO
        *   CHECK IF PATIENT HAS PREVIOUSLY BEEN APPROVED A FUND 
         */

        try {

            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $application = ApplicationReview::findOrFail($request->application_id);

            /* BEFORE A COMMITTEE MEMBER CAN APPROVE A REQUEST, THERE SHOULD
            ENOUGH BALANCE IN THE POOL FOR THAT PATIENT */
            // $pool_account_balance = SiteSetting::where('key','pool_account_balance')->first();
            // if ((float)$pool_account_balance->value < $application->patient->approvableFund()) {
            //     return ResponseHelper::noDataErrorResponse("Insufficient fund in pool for this approval",400);
            // }

            /* ENSURE THERE IS ENOUGH ALLOCATION FOR THE HOSPITAL PATIENT IS ENROLLED */
            if ($application->coe->fund_allocation < $request->amount_approved) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse('Fund allocation balance for ' . $application->coe->coe_name . ' is less than approved for this patient', 400);
            }

            /* PREVENT A SINGLE COMMITTEE MEMEBER FROM APPROVING TWICE
                THIS IS A POSSIBLE SCENARIO WITH POOR NETWORK ISSUES */
            // if($application->user->hasReviewBy(auth()->id())){
            //     return ResponseHelper::noDataErrorResponse("You have already review this application.",400);
            // // }

            // $committee_approval = CommitteeApproval::create([
            //     'committee_member_id' => auth()->id(),
            //     'status' => $request->status,
            //     'reason' => $request->reason,
            //     'application_review_id' => $application->id,
            // ]);

            // /* MAKE FINAL DECISION IF MAJORITY APPROVE */
            // $approval_committee = User::whereHas('roles',function($query){
            //     return $query->where('role','Patient Approval Committee');
            // });

            if (strtolower($request->status) === strtolower(CHFConstants::$APPROVED)) {
                // $approval_status = $application->approvedDecisions()->count() > $application->declinedDecisions()->count() ? "approved" : 'declined';

                $amount_approved = $request->amount_approved;
                $application->update([
                    'status' => CHFConstants::$APPROVED,
                    'amount_approved' => $amount_approved,
                    'reviewed_by' => auth()->id(),
                    'reviewed_on' => now(),
                ]);

                event(new FundApproved($application));
                /* SUBTRACT APPROVED AMOUNT FROM POOL ACCOUNT */
                // $pool_account_balance->update([
                //     'value' => (string) (((float) $pool_account_balance->value) - $amount_approved),
                // ]);

                $application->user->wallet->balance += $amount_approved;
                $application->user->wallet->save();

                /* SUBTRACT AMOUNT APPROVED FROM THE COE ALLOCATION */
                $application->coe()->update([
                    'fund_allocation' => $application->coe->fund_allocation - $amount_approved,
                ]);
            } else if (strtolower($request->status) === strtolower(CHFConstants::$DECLINED)) {
                $application->patient->cmd_reviewed_on = NULL;
                $application->patient->cmd_reviewer_id = NULL;
                $application->patient->cmd_review_status = CHFConstants::$PENDING;
                \Mail::to($application->user->patient->cmdReviewer->email)->send(new CMDRecommendationRejectedMail($application, $request->reason));
                $application->patient->save();
            }

            DB::commit();
            $updated_application = ApplicationReview::where('id', $application->id)->with('committeeApprovals', 'user', 'supportAssessment', 'patient', 'patient.coe', 'patient.ailment')->first();

            return ResponseHelper::ajaxResponseBuilder(
                true,
                __('fund.review-success'),
                [$updated_application]
            );
        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
