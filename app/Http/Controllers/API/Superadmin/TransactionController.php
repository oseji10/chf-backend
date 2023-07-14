<?php

namespace App\Http\Controllers\API\Superadmin;

use App\Events\DisputeResolutionEvent;
use App\Events\TransactionFlaggedEvent;
use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\Dispute\TransactionDisputeRaisedMail;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\TransactionDispute;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DateTime;

class TransactionController extends Controller
{
    //
    public function getSingleTransaction($transaction_id)
    {
        try {
            $transaction = Transaction::where('transaction_id', $transaction_id)->with('transactions')->with('user')->with('biller')->with('coe')->first();
            if (!$transaction) {
                throw new Exception("Transaction with ID does not exist", 404);
            }
            return ResponseHelper::ajaxResponseBuilder(true, 'Transaction', $transaction);
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    public function dispute(Request $request)
    {
        $this->validate($request, [
            'transaction_id' => 'string|required|min:10',
            // 'status' => 'string|required',
            'comment' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $transactions = Transaction::where('transaction_id', $request->transaction_id)->get();


            if (!$transactions) {
                throw new Exception('No transaction found', 404);
            }

            if ($transactions[0]->dispute) {
                throw new Exception("This transaction has been disputed or has been resolved.", 400);
            }

            /* SET THE STATUS OF ALL TRANSACTIONS TO THE STATUS */
            foreach ($transactions as $transaction) {
                $transaction->update([
                    'status' => 'disputed',
                    'is_disputed' => true,
                ]);
            }

            $coe_id = $transactions[0]->coe_id;

            $transaction_dispute = TransactionDispute::create([
                'transaction_id' => $transactions[0]->transaction_id,
                'reason_for_dispute' => $request->comment,
                'coe_id' => $coe_id,
                'status' => CHFConstants::$OPEN,
                'coe_staff_id' => $transactions[0]->biller_id,
                'disputed_by' => auth()->id(),
                'patient_user_id' => $transactions[0]->user_id
            ]);

            $hospital_cmd = User::whereHas('roles', function ($query) {
                return $query->where('role', CHFConstants::$CMD);
            })->where(['status' => CHFConstants::$ACTIVE, 'coe_id' => $coe_id])->get('email')->toArray();

            $secretariat_staff = User::whereHas('roles', function ($query) {
                $query->where('role', CHFConstants::$CHF_ADMIN);
            })->where(['status' => CHFConstants::$ACTIVE])->get('email')->toArray();

            $mailing_list = array_merge(
                array_merge($hospital_cmd, $secretariat_staff),
                [
                    'eokorie@emgeresources.com',
                    'yokubadejo@emgeresources.com',
                    $transactions[0]->biller->email,
                    // $transactions[0]->patient->user->email,
                ]
            );

            //Get CAP Drugs
            $response = Http::get(config('services.cap.host') . '/product');
            // event(new TransactionFlaggedEvent($transaction_dispute));
            \Mail::to($mailing_list)->send(new TransactionDisputeRaisedMail($transaction_dispute, $response->json()['data']));

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, 'Transaction has been flagged.');
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function resolveDispute($transaction_id)
    {
        try {
            DB::beginTransaction();
            $transactions = Transaction::where('transaction_id', $transaction_id)->get();

            if ($transactions->isEmpty()) {
                throw new Exception('No transaction found', 404);
            }

            $resolved_on = new DateTime();
            $resolved_by = auth()->id();

            foreach ($transactions as $transaction) {
                $transaction->update([
                    'is_disputed' => 0,
                ]);
            }

            $dispute = $transactions[0]->dispute;
            $dispute->update([
                'status' => 'resolved',
                'resolved_by' => $resolved_by,
                'resolved_on' => $resolved_on,
            ]);

            event(new DisputeResolutionEvent($dispute));

            DB::commit();

            return ResponseHelper::noDataSuccessResponse('Query for the transaction has been resolved');
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::exceptionHandler($ex);
        }
    }


    protected function getTransactionDisputes()
    {
        $transaction_disputes = TransactionDispute::where('coe_staff_id', auth()->id())->orWhere('patient_user_id', auth()->id())->orWhereHas('coe', function ($query) {
            return $query->where('admin_id', auth()->id());
        })->orWhereHas('coeStaff', function ($query) {
            return $query->where('id', auth()->id());
        })->with('coe')->with(['coeStaff', 'patient.patient', 'raiser', 'transactions', 'transactions.service'])->orderBy('created_at', 'DESC')->get();
        return ResponseHelper::ajaxResponseBuilder(true, 'Disputes', $transaction_disputes);
    }
}
