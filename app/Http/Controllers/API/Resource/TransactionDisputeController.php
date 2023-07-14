<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\TransactionDispute;
use App\Models\TransactionDisputeComment;
use Exception;
use Illuminate\Http\Request;

class TransactionDisputeController extends Controller
{
    //
    protected function index()
    {
        $transaction_disputes = TransactionDispute::with('coe')->with(['coeStaff', 'patient.patient', 'raiser', 'transactions', 'transactions.service'])->orderBy('created_at', 'DESC')->get();
        return ResponseHelper::ajaxResponseBuilder(true, 'Disputes', $transaction_disputes);
    }

    protected function getCOEDispute($coe_id)
    {
        $transaction_disputes = TransactionDispute::with('coe')->with(['coeStaff', 'patient.patient', 'raiser', 'transactions', 'transactions.service'])->where('coe_id', $coe_id)->orderBy('created_at', 'DESC')->get();
        return ResponseHelper::ajaxResponseBuilder(true, 'Disputes', $transaction_disputes);
    }

    public function comments($transaction_id)
    {
        try {
            $dispute = TransactionDispute::where('transaction_id', $transaction_id)->first();

            if (!$dispute) {
                throw new Exception("Dispute not found", 404);
            }

            $dispute_comments = TransactionDisputeComment::where('transaction_dispute_id', $dispute->id)->with('user')->orderBy('created_at', 'desc')->get();
            return ResponseHelper::ajaxResponseBuilder(true, "Transaction comments", $dispute_comments);
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    public function storeComment(Request $request, $transaction_id)
    {
        $this->validate($request, [
            'comment' => 'required|string',
        ]);

        try {
            $transaction_dispute = TransactionDispute::where('transaction_id', $transaction_id)->first();
            $dispute_comment = TransactionDisputeComment::create([
                'transaction_dispute_id' => $transaction_dispute->id,
                'user_id' => auth()->id(),
                'comment' => $request->comment,
            ]);

            $dispute_comment_with_user = TransactionDisputeComment::where('id', $dispute_comment->id)->with('user')->first();

            return ResponseHelper::ajaxResponseBuilder(true, "Comment Created", $dispute_comment_with_user, 201);
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }
    }
}
