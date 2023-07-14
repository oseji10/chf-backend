<?php

namespace App\Http\Controllers\API\FandA;

use App\Events\FandA\PermSecPaymentApprovedEvent;
use App\Events\PaymentApprovedEvent;
use App\Events\PaymentInitiatedEvent;
use App\Events\PaymentRecommendedEvent;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\COE;
use App\Models\Payment;
use App\Models\Transaction;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    //
    public function index()
    {
        $payment = Transaction::whereNotNull('payment_initiated_by')->groupBy('payment_initiated_on', 'coe_id')->with('coe')->with('dispute')->with('paymentTransactions')->with('payment')->with('initiatedBy')->with('approvedBy')->with('recommendedBy')->get();
        return ResponseHelper::ajaxResponseBuilder(true, "Payments", $payment);
    }


    public function initiate(Request $request)
    {
        $this->validate($request, [
            'coe_id' => 'required|numeric',
            'transactions' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $total = 0;
        $event_transactions = [];
        $coe = COE::find($request->coe_id);

        try {
            // $transactions = Transaction::whereBetween('created_at',[$request->start_date, $request->end_date])->where('coe_id', $request->coe_id);

            // $transactions->update([
            //     'payment_initiated_by' => auth()->id(),
            //     'payment_initiated_on' => new DateTime(),
            // ]);
            DB::beginTransaction();
            $transaction_timestamp = date('Y-m-d h:i:s', time());
            $transactions = [];
            $total = 0;

            foreach ($request->transactions as $transaction) {
                $trx = Transaction::where('transaction_id', $transaction['transaction_id']);

                $total += $trx->sum('total');
                $trx->update([
                    'payment_initiated_by' => auth()->id(),
                    'payment_initiated_on' => $transaction_timestamp,
                    'status' => 'initiated'
                ]);
                array_push($transactions, $trx->get());
            }
            Payment::create([
                'payment_initiated_by' => auth()->id(),
                'payment_initiated_on' => $transaction_timestamp,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'payment_amount' => $total,
            ]);

            /* SEND EMAIL TO STAKEHOLDERS */
            event(new PaymentInitiatedEvent($transactions, 'emails.fanda.paymentInitiated'));
            DB::commit();
            return ResponseHelper::noDataSuccessResponse("Payment initiated successfully");
        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::info($ex);
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    /* RECOMMEND FUND FOR APPROVAL */
    public function recommend(Request $request)
    {
        $this->validate($request, [
            'initiated_on' => 'required',
            'coe_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id);

            $recommendation_timestamp = new DateTime();

            $transactions->update([
                'payment_recommended_by' => auth()->id(),
                'payment_recommended_on' => $recommendation_timestamp,
                'status' => 'recommended',
            ]);

            $transactions->first()->payment()->update([
                'payment_recommended_by' => auth()->id(),
                'payment_recommended_on' => $recommendation_timestamp,
                'status' => 'recommended',
            ]);

            /* FETCH TRANSACTIONS AGAIN AS CALLING first() MUTATES THE */
            /* $transactions OBJECT */
            $event_transactions = Transaction::where(
                'payment_initiated_on',
                $request->initiated_on
            )->where('coe_id', $request->coe_id)->get();

            event(new PaymentRecommendedEvent($event_transactions, 'emails.fanda.paymentRecommended'));
            DB::commit();
            return ResponseHelper::noDataSuccessResponse("Payment has been recommended for approval.");
        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::info($ex);
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    /* RECOMMEND FUND FOR APPROVAL */
    public function dhsApproval(Request $request)
    {
        $this->validate($request, [
            'initiated_on' => 'required',
            'coe_id' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();
            $approval_timestamp = new DateTime();
            $transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id);
            $transactions->update([
                'payment_approved_by' => auth()->id(),
                'payment_approved_on' => $approval_timestamp,
                'status' => 'DHS Approved'
            ]);

            $transactions->first()->payment()->update([
                'payment_approved_by' => auth()->id(),
                'payment_approved_on' => $approval_timestamp,
                'status' => 'DHS Approved'
            ]);

            $event_transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id)->get();

            event(new PaymentApprovedEvent($event_transactions, 'emails.fanda.paymentApproved'));
            DB::commit();
            return ResponseHelper::noDataSuccessResponse("Payment has been approved.");
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::exceptionHandler($ex);
        }
    }


    /* DFA APPROVAL */
    public function dfaApproval(Request $request)
    {
        $this->validate($request, [
            'initiated_on' => 'required',
            'coe_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $approval_timestamp = new DateTime();
            $transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id);
            $transactions->update([
                'dfa_id' => auth()->id(),
                'dfa_approved_on' => $approval_timestamp,
                'status' => 'Disbursed'
            ]);

            $transactions->first()->payment()->update([
                'dfa_id' => auth()->id(),
                'dfa_id' => $approval_timestamp,
                'status' => 'Disbursed'
            ]);

            $event_transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id)->get();

            event(new PaymentApprovedEvent($event_transactions, 'emails.fanda.dfaApproved'));
            DB::commit();
            return ResponseHelper::noDataSuccessResponse("Payment has been approved.");
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    /* PERMSEC APPROVAL */
    public function permsecApproval(Request $request)
    {
        $this->validate($request, [
            'initiated_on' => 'required',
            'coe_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $approval_timestamp = new DateTime();
            $transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id);
            $transactions->update([
                'permsec_id' => auth()->id(),
                'permsec_approved_on' => $approval_timestamp,
                'status' => 'Permsec Approved'
            ]);

            $transactions->first()->payment()->update([
                'permsec_id' => auth()->id(),
                'permsec_approved_on' => $approval_timestamp,
                'status' => 'Permsec Approved'
            ]);

            $event_transactions = Transaction::where('payment_initiated_on', $request->initiated_on)->where('coe_id', $request->coe_id)->get();

            event(new PermSecPaymentApprovedEvent($event_transactions));
            DB::commit();
            return ResponseHelper::noDataSuccessResponse("Payment has been approved.");
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::exceptionHandler($ex);
        }
    }
}
