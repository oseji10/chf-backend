<?php

namespace App\Http\Controllers\V2API\COE;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\COE\InvoiceGeneratedMail;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class COEInvoiceController extends Controller
{
    //
    public function getHospitalInvoices($coe_id)
    {
        $payments = Payment::where('coe_id', $coe_id)->with(['transactions', 'transactions.coe', 'transactions.service', 'cmd', 'coe'])->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $payments);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'startDate' => 'required',
            'endDate' => 'required',
        ]);

        $user = User::find(auth()->id());

        $startDate = $request->startDate . " 00:00:00";
        $endDate = $request->endDate . " 23:59:59";

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->where('coe_id', $user->coe_id)->where('is_disputed', 0)->whereNull('status');

        // return $transactions->get();

        if (!$transactions->count()) {
            throw new \Exception("No transaction that can be invoice in this range", 400);
        }

        $current_date = now();

        $transactions->update([
            'cmd_approved_on' => $current_date,
            'cmd_approver_id' => auth()->id(),
            'status' => CHFConstants::$CMD_APPROVED
        ]);

        $payment = new Payment();

        $payment_reference = time() . rand(1000, 9999);
        $payment->payment_reference = $payment_reference;
        $payment->cmd_approver_id = auth()->id();
        $payment->cmd_approved_on = $current_date;
        $payment->status = CHFConstants::$CMD_APPROVED;
        $payment->start_date = $startDate;
        $payment->coe_id = auth()->user()->coe_id;
        $payment->end_date = $endDate;


        $payment->save();

        $emails = ['eokorie@emgeresources.com', 'yokubadejo@emgeresources.com'];

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('role', ["chf admin", 'dfa', 'dhs', 'nccp-dir']);
        })->where('status', CHFConstants::$ACTIVE)->get(['email']);

        foreach ($users as $$user) {
            array_push($emails, $user->email);
        }

        \Mail::to($emails)->send(new InvoiceGeneratedMail($payment));

        return ResponseHelper::noDataSuccessResponse("Invoice generated successfully");
    }
}
