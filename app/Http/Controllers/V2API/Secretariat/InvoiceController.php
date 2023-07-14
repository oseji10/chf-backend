<?php

namespace App\Http\Controllers\V2API\Secretariat;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\Secretariat\PaymentApprovedMail;
use App\Mail\Secretariat\PaymentDFAApprovedMail;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Mail\Secretariat\PaymentInitiatedMail;
use App\Mail\Secretariat\PaymentPermsecApprovedMail;
use App\Mail\Secretariat\PaymentRecommendedMail;
use App\Models\User;

class InvoiceController extends Controller
{
    //
    public function getAllInvoices()
    {
        $payments = Payment::orderBy('created_at', 'desc')->with(['cmd', 'transactions', 'transactions.service', 'transactions.coe', 'initiator', 'approver', 'dfa', 'permsec', 'recommender',])->get();

        return ResponseHelper::ajaxResponseBuilder(true, null, $payments);
    }

    public function initiatePayment(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $payment = Payment::find($request->invoice_id);

            if (!$payment) {
                throw new \Exception("Invoice not found", 404);
            }

            $current_date = now();

            $payment->status = CHFConstants::$PAYMENT_INITIATED;
            $payment->payment_initiated_on = $current_date;
            $payment->payment_initiated_by = auth()->id();
            $payment->save();
            $payment->transactions()->update([
                'status' => CHFConstants::$PAYMENT_INITIATED,
                'payment_initiated_by' => auth()->id(),
                'payment_initiated_on' => $current_date,
            ]);

            $mail_recipients = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['dfa', 'nccp-dir', 'dhs', 'perm sec']);
            })->pluck('email')->toArray();

            $mail_list = array_merge(
                $mail_recipients,
                ['eokorie@emgeresources.com', auth()->user()->email] // Add any static recipient to this array
            );

            \Mail::to($mail_list)->send(new PaymentInitiatedMail);
            DB::commit();

            $updated_invoice = Payment::where('id', $request->invoice_id)->with(['cmd', 'transactions', 'transactions.service', 'transactions.coe', 'initiator', 'approver', 'dfa', 'permsec', 'recommender',])->first();

            return ResponseHelper::ajaxResponseBuilder(true, null, $updated_invoice);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function recommendPayment(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $payment = Payment::find($request->invoice_id);

            if (!$payment) {
                throw new \Exception("Invoice not found", 404);
            }

            $current_date = now();

            $payment->status = CHFConstants::$PAYMENT_RECOMMENDED;
            $payment->payment_recommended_on = $current_date;
            $payment->payment_recommended_by = auth()->id();
            $payment->save();
            $payment->transactions()->update([
                'status' => CHFConstants::$PAYMENT_RECOMMENDED,
                'payment_recommended_by' => auth()->id(),
                'payment_recommended_on' => $current_date,
            ]);

            $mail_recipients = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['dfa', 'dhs']);
            })->pluck('email')->toArray();

            $mail_list = array_merge(
                $mail_recipients,
                ['eokorie@emgeresources.com', auth()->user()->email] // Add any static recipient to this array
            );

            \Mail::to($mail_list)->send(new PaymentRecommendedMail);
            DB::commit();

            $updated_invoice = Payment::where('id', $request->invoice_id)->with(['cmd', 'transactions', 'transactions.service', 'transactions.coe', 'initiator', 'approver', 'dfa', 'permsec', 'recommender',])->first();

            return ResponseHelper::ajaxResponseBuilder(true, null, $updated_invoice);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function approvePayment(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $payment = Payment::find($request->invoice_id);

            if (!$payment) {
                throw new \Exception("Invoice not found", 404);
            }

            $current_date = now();

            $payment->status = CHFConstants::$PAYMENT_APPROVED;
            $payment->payment_approved_on = $current_date;
            $payment->payment_approved_by = auth()->id();
            $payment->save();
            $payment->transactions()->update([
                'status' => CHFConstants::$PAYMENT_APPROVED,
                'payment_approved_by' => auth()->id(),
                'payment_approved_on' => $current_date,
            ]);


            $mail_recipients = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['dfa', 'perm sec']);
            })->pluck('email')->toArray();

            $mail_list = array_merge(
                $mail_recipients,
                ['eokorie@emgeresources.com', auth()->user()->email] // Add any static recipient to this array
            );

            \Mail::to($mail_list)->send(new PaymentApprovedMail);
            DB::commit();

            $updated_invoice = Payment::where('id', $request->invoice_id)->with(['cmd', 'transactions', 'transactions.service', 'transactions.coe', 'initiator', 'approver', 'dfa', 'permsec', 'recommender',])->first();

            return ResponseHelper::ajaxResponseBuilder(true, null, $updated_invoice);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function dfaRecommendPayment(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $payment = Payment::find($request->invoice_id);

            if (!$payment) {
                throw new \Exception("Invoice not found", 404);
            }

            $current_date = now();

            $payment->status = CHFConstants::$DFA_APPROVED;
            $payment->dfa_approved_on = $current_date;
            $payment->dfa_id = auth()->id();
            $payment->save();
            $payment->transactions()->update([
                'status' => CHFConstants::$DFA_APPROVED,
                'dfa_id' => auth()->id(),
                'dfa_approved_on' => $current_date,
            ]);


            $mail_recipients = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['perm sec']);
            })->pluck('email')->toArray();

            $mail_list = array_merge(
                $mail_recipients,
                ['eokorie@emgeresources.com', auth()->user()->email] // Add any static recipient to this array
            );

            \Mail::to($mail_list)->send(new PaymentDFAApprovedMail);
            DB::commit();

            $updated_invoice = Payment::where('id', $request->invoice_id)->with(['cmd', 'transactions', 'transactions.service', 'transactions.coe', 'initiator', 'approver', 'dfa', 'permsec', 'recommender',])->first();

            return ResponseHelper::ajaxResponseBuilder(true, null, $updated_invoice);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function permsecApprovePayment(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $payment = Payment::find($request->invoice_id);

            if (!$payment) {
                throw new \Exception("Invoice not found", 404);
            }

            $current_date = now();

            $payment->status = CHFConstants::$PERMSEC_APPROVED;
            $payment->permsec_approved_on = $current_date;
            $payment->permsec_id = auth()->id();
            $payment->save();
            $payment->transactions()->update([
                'status' => CHFConstants::$PERMSEC_APPROVED,
                'permsec_id' => auth()->id(),
                'permsec_approved_on' => $current_date,
            ]);

            $mail_recipients = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['chf admin', 'nccp-dir', 'dhs', 'dfa']);
            })->pluck('email')->toArray();



            $mail_list = [
                'eokorie@emgeresources.com',
                auth()->user()->email,
                $payment->cmd->email,
                $payment->approver->email,
                $payment->recommender->email,
                $payment->initiator->email,
                $payment->dfa->email,
            ];

            \Mail::to($mail_list)->send(new PaymentPermsecApprovedMail);
            DB::commit();

            $updated_invoice = Payment::where('id', $request->invoice_id)->with(['cmd', 'transactions', 'transactions.service', 'transactions.coe', 'initiator', 'approver', 'dfa', 'permsec', 'recommender',])->first();

            return ResponseHelper::ajaxResponseBuilder(true, null, $updated_invoice);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
