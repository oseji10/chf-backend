<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\AWSHelper;
use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\COE\COEPatientAdditionalFundNotification;
use App\Mail\Patient\PatientWalletTopUpEmail;
use App\Models\COE;
use App\Models\Patient;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTopup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WalletTopupController extends Controller
{
    //

    public function initiate(Request $request)
    {
        $this->validate($request, [
            'amount_requested' => 'numeric|required|min:1',
            'patient_chf_id' => 'string|required',
        ]);


        $patient = Patient::with(['user'])->where('chf_id', $request->patient_chf_id)->first();
        $patient_user_id = $patient->user->id;

        $patient_wallet = Wallet::where('user_id', $patient_user_id)->first();

        $coe_wallet = Wallet::where('coe_id', $patient->coe_id);

        /* ONLY STAFF PATIENT'S PRIMARY COE SHOULD REQUEST FOR ADDITIONAL FUND */
        if (auth()->user()->coe_id !== $patient->coe_id) {
            // throw new BadRequestException("Only staff at patient's primary COE may request for additional funding");
        }

        try {
            DB::beginTransaction();

            $topup_request = new WalletTopup();

            $topup_request->requester_id = auth()->id();
            $topup_request->requested_on = now(CHFConstants::$DEFAULT_TIMEZONE);
            $topup_request->patient_user_id = $patient_user_id;
            $topup_request->amount_requested = $request->amount_requested;
            $topup_request->coe_id = $patient->coe_id;
            $topup_request->previous_balance = $patient_wallet->balance;
            $topup_request->save();

            DB::commit();

            return ResponseHelper::ajaxResponseBuilder(true, "Top up initiated.", $topup_request, 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function creditWallet(Request $request)
    {
        $this->validate($request, [
            'topup_request_id' => 'numeric|required',
            'amount_credited' => 'numeric|required',
        ]);

        /* TODO
        *   ONLY ALLOW CREDITING OF REQUESTS APPROVED BY CMD
        *   PREVENT DUPLICATE CREDIT
         */

        $topup = WalletTopup::find($request->topup_request_id);

        $amount_credited = $request->amount_credited;

        $patient_wallet = Wallet::where('user_id', $topup->patient_user_id)->first();

        /* TAKE THE FUND FROM THE PATIENT'S CURRENT COE
        *   YOU CAN USE THE COE ID REGISTERED IN THE WALLET TOPUP TABLE
        *   BUT!
        *   PATIENT MIGHT BE TRANSFERRED TO A DIFFERENT COE
        *   THIS MIGHT RESULT IN DEDUCTING FROM THE OLD COE'S ALLOCATION.
         */
        $coe = COE::find($topup->user->patient->coe_id);

        try {
            if ($topup->status === CHFConstants::$CREDITED) {
                throw new HttpException(400, "This additional fund request has already been credited.");
            }

            DB::beginTransaction();

            if ($amount_credited > $coe->fund_allocation) {
                throw new HttpException(400, "COE allocation is less than credit amount. " . $coe->fund_allocation . " left");
            }

            $coe->fund_allocation = $coe->fund_allocation - $amount_credited;
            $coe->save();

            $patient_wallet->balance = $patient_wallet->balance + $amount_credited;
            $patient_wallet->save();

            $topup->amount_credited = $amount_credited;
            $topup->credited_by = auth()->id();
            $topup->credited_on = now(CHFConstants::$DEFAULT_TIMEZONE);
            $topup->status = CHFConstants::$CREDITED;
            $topup->save();


            $secretariatStaff = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ["chf admin",]);
            })->where('status', CHFConstants::$ACTIVE)->pluck('email')->toArray();

            $hospitalStakeholders = User::whereHas('roles', function ($query) {
                return $query->whereIn('role', ["MDT", "CMD"]);
            })->where([
                'coe_id' => $topup->user->patient->coe_id,
                'status' => CHFConstants::$ACTIVE,
            ])->pluck('email')->toArray();

            $mailing_list = array_merge(
                array_merge($secretariatStaff, $hospitalStakeholders),
                ['eokorie@emgeresources.com']
            );

            /* 1. NOTIFY PATIENT */
            \Mail::to($topup->user->email)->send(new PatientWalletTopUpEmail($topup));
            AWSHelper::sendSMS($topup->user->phone_number, "The amount of " . $amount_credited . " has been credited to your CHF wallet as additional funding.");

            /* 1. NOTIFY HOSPITAL STAKEHOLDERS */
            \Mail::to($mailing_list)->send(new COEPatientAdditionalFundNotification($topup));
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, "Wallet credited", $topup);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }



    public function topUpHistory($id)
    {

        // $patient_wallet_topup_history = "Hi";

        $patient_wallet_topup_history = WalletTopup::where('patient_user_id', $id)->get();


        return ResponseHelper::ajaxResponseBuilder(true, '', $patient_wallet_topup_history);
    }
}
