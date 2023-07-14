<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\COE\PatientFundRetrievalApprovalMail;
use App\Mail\COE\PatientFundRetrievalRequestMail;
use App\Models\COE;
use App\Models\FundRetrieval;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FundRetrievalController extends Controller
{
    //
    public function index()
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_user_id' => 'required|numeric',
            'comment' => 'required|string',
            'reason_for_retrieval' => 'required|string'
        ]);

        $patient = User::find($request->patient_user_id);

        if ($patient->fundRetrievals()->count()) {
            throw new HttpException(400, "A request has already been made for this patient");
        }


        $fund_retrieval = FundRetrieval::create([
            'user_id' => $request->patient_user_id,
            'requested_by' => auth()->id(),
            'wallet_balance' => $patient->wallet->balance,
            'comment' => $request->comment,
            'coe_id' => auth()->user()->coe->id,
        ]);


        $patient->update([
            'status' => $request->reason_for_retrieval,
        ]);

        \Mail::to($this->createMailingList($patient))->send(new PatientFundRetrievalRequestMail($fund_retrieval, $patient, $request->reason_for_retrieval, $request->comment));
        return ResponseHelper::ajaxResponseBuilder(true, null, $fund_retrieval);
    }

    public function getCOEFundRetrievals($coe_id)
    {
        $retrievals = FundRetrieval::where(['coe_id' => $coe_id])->with(['user', 'coe', 'requester', 'approver', 'user.patient'])->orderBy('created_at', 'desc')->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $retrievals);
    }

    public function approve(Request $request, $retrieval_id)
    {
        $this->validate($request, [
            'retrieval' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $retrieval = FundRetrieval::find($retrieval_id);


            if ($retrieval->status !== CHFConstants::$PENDING) {
                throw new HttpException(400, "This request is not pending");
            }

            $coe = COE::find($retrieval->coe_id);
            $patient_wallet = Wallet::where(['user_id' => $retrieval->user_id])->first();
            $retrieval->update([
                'status' => CHFConstants::$APPROVED,
                'approved_by' => auth()->id(),
                'approved_on' => now(),
                'amount_retrieved' => $patient_wallet->balance,
            ]);

            $coe->update([
                'fund_allocation' => $coe->fund_allocation + $patient_wallet->balance,
            ]);

            $patient_wallet->update([
                'balance' => 0,
            ]);

            \Mail::to($this->createMailingList($retrieval->user))->send(new PatientFundRetrievalApprovalMail($retrieval));

            DB::commit();


            $updated_retrieval = FundRetrieval::where(['id' => $retrieval_id])->with(['user', 'coe', 'requester', 'approver', 'user.patient'])->first();

            return ResponseHelper::ajaxResponseBuilder(true, null, $updated_retrieval);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /* CLASS UTILITIES */
    private function createMailingList($patient)
    {
        $mailing_list = User::whereHas('roles', function ($query) {
            $query->whereIn('role', [CHFConstants::$CMD, CHFConstants::$COE_ADMIN]);
        })->where(['coe_id' => auth()->user()->coe_id, 'status' => CHFConstants::$ACTIVE])->orWhereHas('roles', function ($query) {
            $query->where('role', CHFConstants::$CHF_ADMIN);
        })->pluck('email')->toArray();


        $mailing_list = array_merge($mailing_list, [
            $patient->patient->primaryPhysician->email,
            'eokorie@emgeresources.com',
            'yokubadejo@emgeresources.com'
        ]);

        return $mailing_list;
    }
}
