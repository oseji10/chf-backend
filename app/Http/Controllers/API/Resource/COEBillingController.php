<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\COE;
use App\Models\Transaction;

class COEBillingController extends Controller
{
    //
    public function index($coe_id)
    {
        try {
            // Include start date and end date in the search so we subtract and add a day 
            $start_date = date('Y-m-d H:i:s', strtotime(request()->start_date . ' - 1 days'));
            $end_date = date('Y-m-d H:i:s', strtotime(request()->end_date . ' + 1 days'));
            $per_page = request()->per_page ?? 10;

            // This will help us get transactions where pagination is not required
            $is_paginate = request()->is_paginate;

            if ($is_paginate == "0") {
                // Don't paginate
                $billing_history = $this->fetchTransactionsWithoutPagination($start_date, $end_date, $coe_id);
            } else {
                // Paginate
                $billing_history = $this->fetchTransactionsWithPagination($start_date, $end_date, $coe_id, $per_page);
            }


            return ResponseHelper::ajaxResponseBuilder(true, 'COE Billings', $billing_history);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function fetchTransactionsWithPagination($start_date, $end_date, $coe_id, $per_page)
    {
        if (isset(request()->is_drug)) {

            $billing_history = Transaction::select('*')->where("is_drug", request()->is_drug)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('coe_id', $coe_id)->groupBy('transaction_id')->with('transactions')->with('coe')
                ->with('user')->with('user.patient')->with('transactions.service')
                ->with(['transactions.service.coes' => function ($coes) use ($coe_id) {
                    $coes->where('coe.id', $coe_id);
                }])->with('biller')->with('comment')->with('transactions.service.category')
                ->orderBy('created_at', 'desc')->paginate($per_page);
        } else {
            $billing_history = Transaction::select('*')->whereBetween('created_at', [$start_date, $end_date])
                ->where('coe_id', $coe_id)->groupBy('transaction_id')->with('transactions')->with('coe')
                ->with('user')->with('user.patient')->with('transactions.service')
                ->with(['transactions.service.coes' => function ($coes) use ($coe_id) {
                    $coes->where('coe.id', $coe_id);
                }])->with('biller')->with('comment')->with('transactions.service.category')
                ->orderBy('created_at', 'desc')->paginate($per_page);
        }

        return $billing_history;
    }

    public function fetchTransactionsWithoutPagination($start_date, $end_date, $coe_id)
    {
        if (isset(request()->is_drug)) {

            $billing_history = Transaction::select('*')->where("is_drug", request()->is_drug)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('coe_id', $coe_id)->groupBy('transaction_id')->with('transactions')->with('coe')
                ->with('user')->with('user.patient')->with('transactions.service')
                ->with(['transactions.service.coes' => function ($coes) use ($coe_id) {
                    $coes->where('coe.id', $coe_id);
                }])->with('biller')->with('comment')->with('transactions.service.category')
                ->orderBy('created_at', 'desc')->get();
        } else {
            $billing_history = Transaction::select('*')->whereBetween('created_at', [$start_date, $end_date])
                ->where('coe_id', $coe_id)->groupBy('transaction_id')->with('transactions')->with('coe')
                ->with('user')->with('user.patient')->with('transactions.service')
                ->with(['transactions.service.coes' => function ($coes) use ($coe_id) {
                    $coes->where('coe.id', $coe_id);
                }])->with('biller')->with('comment')->with('transactions.service.category')
                ->orderBy('created_at', 'desc')->get();
        }

        return $billing_history;
    }


    public function transactions($coe_id)
    {
        return ResponseHelper::ajaxResponseBuilder(true, "COE Transactions", COE::findOrFail($coe_id)
            ->transactions()->get()->toArray());
    }

    public function consolidated(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {
            $coes = COE::all();
            $response_data = [];
            $start_date = date('Y-m-d 0:0:0', strtotime($request->start_date));
            $end_date = date('Y-m-d 23:59:59', strtotime($request->end_date));
            foreach ($coes as $coe) {
                array_push($response_data, [
                    'coe' => $coe,
                    'transactions' => $coe->transactionsInterval($start_date, $end_date)->with('transactions')->with('user')->with('user.patient')->with('coe')->with('service')->with('transactions.service.category')->with('biller')->with('transactions.service.coes')->with('documents')->with('dispute')->with('payment')->get(),
                ]);
            }

            return ResponseHelper::ajaxResponseBuilder(true, "Consolidated Data", $response_data);
        } catch (\Exception $ex) {
            \Log::info($ex);
            return ResponseHelper::noDataErrorResponse("Could not find data");
        }
    }
}
