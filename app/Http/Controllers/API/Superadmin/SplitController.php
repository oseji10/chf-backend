<?php

namespace App\Http\Controllers\API\Superadmin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SplitController extends Controller
{
    //
    public function split(Request $request){
        $this->validate($request,[
            'transactions' => 'required|array|min:1'
        ]);

        try{
            foreach ($request->transactions as $transaction_id) {
                DB::beginTransaction();
                /* FIND ALL TRANSACTIONS WITH THAT PROVIDED TRANSACTION REGERENCE */
                $transaction_group = Transaction::where('transaction_id',$transaction_id)->get();
                foreach ($transaction_group as $transaction) {
                    /* SET SPLITTED TO TRUE */
                    $transaction->is_splitted = 1;
                    $transaction->save();

                    foreach($transaction->stakeholderTransactions as $strx){
                        /* CREDIT THE COE WALLET IF STAKEHOLDER IS A COE */
                        if ($strx->stakeholder->is_coe) {
                            $coe = $transaction->coe;
                            $coe->wallet()->update(['balance' => $coe->wallet->balance + $strx->amount]);
                        }
                        $strx->is_paid = 1;
                        $strx->save();
                    }
                }
                DB::commit();
                return ResponseHelper::noDataSuccessResponse("Split successful");
            }
        }catch(\Exception $ex){
            return ResponseHelper::noDataErrorResponse($ex->getMessage());
        }
    }
}
