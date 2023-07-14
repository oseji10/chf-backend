<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function index(Request $request)
    {
        $startDate = $request->startDate . " 00:00:00";
        $endDate = $request->endDate . " 23:59:59";
        $transactions =  Transaction::query()
            ->with([
                'biller' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'initiatedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'approvedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'recommendedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'dfaPaymentApprovedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'permSecPaymentApprovedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'service',
                'coe' => function ($query) {
                    $query->select(['id', 'coe_name']);
                },
                'patient'
            ])
            ->whereBetween('created_at', [$startDate, $endDate])->get();

        return ResponseHelper::ajaxResponseBuilder(true, null, $transactions);
    }

    public function search(Request $request)
    {
        \Log::info($request->q);
        $transactions =  Transaction::query()
            ->with([
                'biller' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'initiatedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'approvedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'recommendedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'dfaPaymentApprovedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'permSecPaymentApprovedBy' => function ($query) {
                    $query->select(['id', 'first_name', 'last_name', 'other_names']);
                },
                'service',
                'coe' => function ($query) {
                    $query->select(['id', 'coe_name']);
                },
                'patient'
            ])
            ->where('transaction_id', 'like', '%' . $request->q . '%')
            ->orWhereHas('patient', function ($query) use ($request) {
                $query->where('chf_id', 'like', '%' . $request->q . '%');
            })
            ->get();

        return ResponseHelper::ajaxResponseBuilder(true, null, $transactions);
    }
}
