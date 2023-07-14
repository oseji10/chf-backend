<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class Notification extends Controller
{
    //
    public function transaction(Request $request){
        //Get three most recent transactions
        try{
            $user_id=auth()->user()->id;
            $recent_transactions = Transaction::groupBy('transaction_id')->selectRaw('id,sum(total) as total,created_at')->where('user_id',$user_id)->orderBy('created_at','desc')->take(3)->get();
            return ResponseHelper::ajaxResponseBuilder(true, "Transaction notification",$recent_transactions);
        } catch (\Exception $e) {
            // \Log::info($e);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
