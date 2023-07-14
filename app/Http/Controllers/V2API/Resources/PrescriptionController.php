<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\AWSHelper;
use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Helpers\TokenHelper;
use App\Http\Controllers\Controller;
use App\Mail\BillingInvoice;
use App\Models\Comment;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionProduct;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PrescriptionController extends Controller
{
    //

    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_id' => 'numeric|required',
            'prescriptions' => 'array|required|min:1',
        ]);

        try {
            DB::beginTransaction();
            $newPrescription = Prescription::create([
                'created_by' => auth()->id(),
                'coe_id' => auth()->user()->coe_id,
                'patient_user_id' => $request->patient_id,
                'creator_comment' => $request->comment,
            ]);

            foreach ($request->prescriptions as $prescription) {
                // \Log::info($prescription)
                PrescriptionProduct::create([
                    'prescription_id' => $newPrescription->id,
                    'drug_id' => $prescription['productId'],
                    'dosage' => $prescription['dosage'],
                ]);
            }

            DB::commit();

            return ResponseHelper::noDataSuccessResponse("Prescription Created");
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function getPatientPrescriptions($patient_id)
    {
        $patient = Patient::where('chf_id', $patient_id)->with(['user', 'user.wallet'])->first();

        if (!$patient) {
            throw new \Exception("Patient not found", 404);
        }

        if ($patient->coe_id !== auth()->user()->coe_id) {
            throw new HttpException(400, "This patient's prescription is not accessible at your center");
        }

        $prescriptions = Prescription::with(['prescriptionProducts', 'doctor', 'pharmacist', 'user', 'user.patient', 'hospital'])->where('patient_user_id', $patient->user_id)->orderBy('created_at', 'desc')->get();

        return ResponseHelper::ajaxResponseBuilder(true, 'Prescriptions', [
            'prescriptions' => $prescriptions,
            'patient' => $patient
        ]);
    }

    public function fulfillPrescription(Request $request)
    {
        // \Log::info($request->all());

        $transaction_id = TokenHelper::generateTransactionId();
        $total = 0;

        try {

            DB::beginTransaction();


            $prescription = Prescription::find($request['id']);
            $prescription->fulfiller_comment = $request['comment'];
            $prescription->fulfilled_by = auth()->id();
            $prescription->fulfilled_on = now();

            $prescription->update(['status' => CHFConstants::$FULFILLED]);

            foreach ($request['prescription_products'] as $prescriptionProduct) {
                $total += $prescriptionProduct['quantity_dispensed'] * $prescriptionProduct['cost'];
                Transaction::create([
                    'transaction_id' => $transaction_id,
                    'biller_id' => auth()->id(),
                    'service_id' => $prescriptionProduct['drug_id'],
                    'drug_id' => $prescriptionProduct['drug_id'],
                    'is_drug' => 1,
                    'drug_id' => $prescriptionProduct['drug_id'],
                    'quantity' => $prescriptionProduct['quantity_dispensed'],
                    'cost' => $prescriptionProduct['cost'],
                    'total' => $prescriptionProduct['quantity_dispensed'] * $prescriptionProduct['cost'],
                    'coe_id' => $request['coe_id'],
                    'user_id' => $request['patient_user_id'],
                    'prescription_id' => $prescription->id,
                ]);
            }

            $patient = User::findOrFail($request['patient_user_id']);

            /* 
                *   SINCE THERE IS NO DIRECT RELATIONSHIP BETWEEN COE AND WALLET,
                *   ONLY ONE COE ADMIN EXIST FOR A COE, HENCE, JOIN SELECT ALL USERS ASSOCIATED WITH
                *   THE COE AND GET THE WALLET THAT HAS is_coe
                 */
            $coe = User::where('coe_id', auth()->user()->coe_id)->whereHas('wallet', function ($query) {
                $query->where('is_coe', 1);
            })->first();


            /*
            *   PREVENT PERSISTENCE WHEN PATIENT'S WALLET BALANCE IS LESS THAN TOTAL 
             */
            if ($total > $patient->wallet->balance) {
                DB::rollBack();
                return ResponseHelper::noDataErrorResponse(__('wallet.insufficient-fund'), 400);
            }

            /* CREATE A COMMENT FOR THE TRANSACTIOIN IF PRESENT */
            if ($request['comment']) {
                Comment::create([
                    'comment' => $request['comment'],
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
            // Mail::to($patient->email)->send(new BillingInvoice($transactions));
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
            throw $ex;
        }
    }

    public function getDoctorPrescriptions()
    {
        $prescriptions = Prescription::with(['doctor', 'pharmacist', 'user', 'user.patient', 'hospital', 'prescriptionProducts'])->where('created_by', auth()->id())->orderBy('created_at', 'desc')->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $prescriptions);
    }
}
