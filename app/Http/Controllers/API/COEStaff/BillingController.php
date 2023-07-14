<?php

namespace App\Http\Controllers\API\COEStaff;

use App\Helpers\AWSHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\BillingInvoice;
use App\Mail\DrugBillingInvoice;
use App\Models\Comment;
use App\Models\Service;
use App\Models\Stakeholder;
use App\Models\StakeholderTransaction;
use App\Models\Transaction;
use App\Models\TransactionDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BillingController extends Controller
{
    //
    public function store(Request $request)
    {
        $this->validate($request, [
            'services' => 'required|array|min:1',
            'patient_id' => 'required|numeric',

        ]);

        try {
            DB::beginTransaction();
            $transaction_id = "CHFTRX-" . (100 + rand(149, 888)) . time() . strtoupper(substr(md5(time()), 3, 3));

            $total = 0;
            $patient = User::findOrFail($request->patient_id);

            /* 
            *   SINCE THERE IS NO DIRECT RELATIONSHIP BETWEEN COE AND WALLET,
            *   ONLY ONE COE ADMIN EXIST FOR A COE, HENCE, JOIN SELECT ALL USERS ASSOCIATED WITH
            *   THE COE AND GET THE WALLET THAT HAS is_coe
             */
            $coe = User::where('coe_id', auth()->user()->coe_id)->whereHas('wallet', function ($query) {
                $query->where('is_coe', 1);
            })->first();

            /* PATIENTS SHOULD ONLY BE BILLED DIRECTLY FROM THEIR REGISTERED COE */
            if (auth()->user()->coe_id !== $patient->patient->coe_id) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse("You cannot bill for a patient that is not registered at your facility. Please use the referral feature", 400);
            }

            /* SALES NEED TO BE TRACKED FOR COE. */
            if (!$coe) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse("No wallet assigned to COE", 400);
            }

            foreach ($request->services as $new_service) {
                $service = Service::with(['coes' => function ($coes) {
                    $coes->where('coe.id', auth()->user()->coe_id);
                }])
                    ->find($new_service['id']);
                // $bill_id = rand(1000789203,9836475847);

                /* 
                *   PREVENT DUPLICATE KEY
                *   REGENERATE BILLING ID IF IT EXISTS
                 */
                // while (Transaction::where('bill_id', $bill_id)->get()->count()) {
                //     $bill_id = rand(1000789203,9836475847);
                // }
                $subtotal = $service->coes[0]->pivot['price'] * $new_service['quantity'];
                // $discount = $subtotal * 0.25; //Change 0.25 to discount settings from DB
                $total += $subtotal;


                //cost represent the cost of a single quantity. subtaotal = cost * quantity
                $transaction = Transaction::create([
                    'transaction_id' => $transaction_id,
                    'biller_id' => auth()->id(),
                    'service_id' => $service->id,
                    'quantity' => $new_service['quantity'],
                    'cost' => $service->coes[0]->pivot['price'], //This is the unit cost of the service
                    'discount' => 0,
                    'total' => $subtotal, //We subtract discount from subtotal 
                    'coe_id' => auth()->user()->coe->id,
                    'user_id' => $request->patient_id,
                ]);

                // $this->initiateSplit($transaction);
            }

            /* ATTACH UPLOADED FILES TO TO  */
            if ($request->documents) {
                foreach ($request->documents as $file) {
                    TransactionDocument::create([
                        'id' => auth()->id() + time() + rand(1030, 9099),
                        'transaction_id' => $transaction_id,
                        'document_url' => $file['file_url'],
                        'document_name' => $file['file_name'],
                    ]);
                }
            }

            /*
            *   PREVENT PERSISTENCE WHEN PATIENT'S WALLET BALANCE IS LESS THAN TOTAL 
             */
            if ($total > $patient->wallet->balance) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse(__('wallet.insufficient-fund'), 400);
            }

            /* CREATE A COMMENT FOR THE TRANSACTIOIN IF PRESENT */
            if ($request->comment) {
                Comment::create([
                    'comment' => $request->comment,
                    'transaction_id' => $transaction_id,
                    'commented_by' => auth()->id(),
                ]);
            }

            /* 
            *   DEBIT PATIENT WALLET
             */
            // $patient->wallet->balance = ;
            $patient->wallet()->update(['balance' => $patient->wallet->balance - $total]);

            /* 
            *   CREDIT COE
            *   FIND RELATIONSHIP BETWEEN COE AND WALLET AND CREDIT
             */
            // $coe->wallet->balance = $coe->wallet->balance + $total;
            $coe->wallet()->update(['balance' => $coe->wallet->balance + $total]);

            $transactions = Transaction::where('transaction_id', $transaction_id)->get();

            /* SEND INVOICE TO COE AND PATIENT */
            //ADD COE NAME TO SMS NOTIFICATION
            Mail::to($patient->email)->send(new BillingInvoice($transactions));
            AWSHelper::sendSMS($patient->phone_number, "NGN" . (string)$total . " has been charged from your CHF wallet");

            //ONLY COMMIT AFTER ALL PROCESSES INCLUDING MAILING SUCCEED
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, "Billing successful. N$total was charged from " . $patient->first_name . ' ' . $patient->last_name . " wallet", [
                'user' => null,
                'coe' => null,
                'transactions' => []
            ]);
        } catch (\Exception $ex) {
            \Log::info($ex);
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function drugStore(Request $request)
    {
        $this->validate($request, [
            'drugs' => 'required|array|min:1',
            'patient_id' => 'required|numeric',

        ]);
        // return [$request->drugs][0];

        try {
            DB::beginTransaction();

            // Use order id from CAP
            $transaction_id = $request->order_id;

            $total = 0;
            $coetotal = 0;

            $patient = User::findOrFail($request->patient_id);

            /* 
            *   SINCE THERE IS NO DIRECT RELATIONSHIP BETWEEN COE AND WALLET,
            *   ONLY ONE COE ADMIN EXIST FOR A COE, HENCE, JOIN SELECT ALL USERS ASSOCIATED WITH
            *   THE COE AND GET THE WALLET THAT HAS is_coe
             */
            $coe = User::where('coe_id', auth()->user()->coe_id)->whereHas('wallet', function ($query) {
                $query->where('is_coe', 1);
            })->first();

            foreach ($request->drugs as $drug_service) {
                // We can't find drugs from CHF
                // $service = Service::find($new_service['id']);
                // $bill_id = rand(1000789203,9836475847);
                $drug = $drug_service['drug'];

                $subtotal = $drug['price'] * $drug_service['quantity'];

                // $discount = $subtotal * 0.25; //There is no discount here as all CAP drugs are already discounted
                // COE markup for that drug * quantity of the drug bought
                $coetotal += ($drug['price'] * $drug_service['quantity']);

                $total += $subtotal; //subtotal == total because there is no discount
                $transaction = Transaction::create([
                    'transaction_id' => $transaction_id,
                    'biller_id' => auth()->id(),
                    'service_id' => 0,
                    'drug_id' => $drug['productId'],
                    'quantity' => $drug_service['quantity'],
                    'cost' => $drug['price'], //This is the unit cost of the drug
                    'discount' => 0,
                    'total' => $subtotal,
                    'coe_id' => auth()->user()->coe->id,
                    'user_id' => $request->patient_id,
                    'is_drug' => 1
                ]);
            }

            /*
            *   PREVENT PERSISTENCE WHEN PATIENT'S WALLET BALANCE IS LESS THAN TOTAL 
             */
            if ($total > $patient->wallet->balance) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse(__('wallet.insufficient-fund'), 400);
            }

            /* ATTACH UPLOADED FILES TO TO  */
            if ($request->documents) {
                foreach ($request->documents as $file) {
                    TransactionDocument::create([
                        'id' => auth()->id() + time() + rand(102030, 9394099),
                        'transaction_id' => $transaction_id,
                        'document_url' => $file['file_url'],
                        'document_name' => $file['file_name'],
                    ]);
                }
            }

            /* CREATE A COMMENT FOR THE TRANSACTIOIN IF PRESENT */
            if ($request->comment) {
                Comment::create([
                    'comment' => $request->comment,
                    'transaction_id' => $transaction_id,
                    'commented_by' => auth()->id(),
                ]);
            }

            /* 
            *   DEBIT PATIENT WALLET
             */
            // $patient->wallet->balance = ;
            $patient->wallet()->update(['balance' => $patient->wallet->balance - $total]);

            /* 
            *   CREDIT COE
            *   FOR DRUG BILLING, IT IS THE COE MARKUP FOR EACH DRUG ON CAP THAT WILL BE ADDED TO THE COE'S
            *   WALLET. THE REST WILL BE PAID TO MANUFACTURER, DISTRIBUTOR, BANK CHARGES AND EMGE AS APPROPRIATE
            *   THROUGH INTERNAL OP OF FINANCE DEPARTMENT.
             */
            $coe->wallet()->update(['balance' => $coe->wallet->balance + $coetotal]);



            $transactions = Transaction::where('transaction_id', $transaction_id)->get();

            /* SEND INVOICE TO COE AND PATIENT */
            //ADD COE NAME TO SMS NOTIFICATION
            // Mail::to($patient->email)->send(new DrugBillingInvoice($transactions, $request->drugs));
            AWSHelper::sendSMS($patient->phone_number, "NGN" . (string)$total . " has been charged from your CHF wallet");

            DB::commit();

            return ResponseHelper::ajaxResponseBuilder(true, "Billing successful. N$total was charged from " . $patient->first_name . ' ' . $patient->last_name . " account", [
                'user' => null,
                'coe' => null,
                'transactions' => []
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
