<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\COE;
use App\Models\Patient;
use App\Models\PatientTransferRequest;
use App\Models\Referral;
use App\Models\ReferralService;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\tQuery;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class COEController extends Controller
{
    //
    use tQuery;

    public function index()
    {
        $coes = $this->getManyThroughPipe(COE::class)->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $coes);
    }

    public function view($id)
    {
        $coe = $this->getOneThroughPipe(COE::class, $id)->first();

        return ResponseHelper::ajaxResponseBuilder(true, null, $coe);
    }

    public function findAllCOEPatients($coe_id)
    {
        $patients = $this->getManyThroughPipe(Patient::class)
            ->where('coe_id', $coe_id)->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $patients);
    }

    public function findCOEMDTPatients($coe_id)
    {
        $patients = $this->getManyThroughPipe(Patient::class)
            ->where(['coe_id' => $coe_id, 'social_worker_status' => CHFConstants::$APPROVED, 'primary_physician_status' => CHFConstants::$APPROVED])->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $patients);
    }

    public function findOneCOEPatient($coe_id, $chf_id)
    {
        $patient = $this->getOneThroughPipe(Patient::class, $chf_id)
            ->whereHas('coe', function ($query) use ($coe_id) {
                $query->where('id', $coe_id);
            })
            ->orWhere('chf_id', $chf_id)/* ->where('coe_id', $coe_id) */->first();

        if (!$patient) {
            throw new HttpException(404, "Patient not found");
        }

        return ResponseHelper::ajaxResponseBuilder(true, null, $patient);
    }

    public function patientTransfers($coe_id)
    {
        $patient_transfers = $this->getManyThroughPipe(PatientTransferRequest::class)
            ->whereHas('requestingPhysician', function ($query) use ($coe_id) {
                return $query->where('coe_id', $coe_id);
            })->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $patient_transfers);
    }

    public function approveTransfer(Request $request)
    {
        $this->validate($request, [
            'transferIds' => 'required|array'
        ]);

        $patient_transfers = [];

        foreach ($request->transferIds as $id) {
            $patient_transfer = PatientTransferRequest::with(['requestingPhysician', 'currentPhysician'])->find($id);
            $patient_transfer->approved_by = auth()->id();
            $patient_transfer->approved_on = now();
            $patient_transfer->status = strtoupper(CHFConstants::$APPROVED);
            $patient_transfer->save();
            array_push($patient_transfers, $patient_transfer);
            $patient = Patient::where('chf_id', $patient_transfer->patient_chf_id)->first();
            $patient->primary_physician = $patient_transfer->requesting_physician_id;
            $patient->save();
        }

        return ResponseHelper::ajaxResponseBuilder(true, null, $patient_transfers);
    }

    public function staff($coe_id)
    {
        $coe = COE::with(['staffs'])->find($coe_id);

        return ResponseHelper::ajaxResponseBuilder(true, null, $coe->staffs);
    }


    /* 
    *   CONSIDER GETTING THE COE ID FROM REQUEST PARAMETER TO IMPORVE ROUTE REUSABILITY
     */
    public function getCOEAnalytics()
    {
        $coe_id = auth()->user()->coe_id;

        $current_month_started = Carbon::today(CHFConstants::$DEFAULT_TIMEZONE)->startOf('month')->startOf('day');
        $current_month_ended = Carbon::today(CHFConstants::$DEFAULT_TIMEZONE)->endOf('month')->endOf('day');
        $last_month_started = Carbon::today(CHFConstants::$DEFAULT_TIMEZONE)->subMonth()->startOf('month')->startOf('day');
        $last_month_ended = Carbon::today(CHFConstants::$DEFAULT_TIMEZONE)->subMonth()->endOf('month')->endOf('day');

        $coe = COE::find($coe_id);

        $hospital_transactions = Transaction::where('coe_id', $coe_id);

        $current_month_volume = Transaction::where('coe_id', $coe_id)->whereBetween('created_at', [$current_month_started, $current_month_ended])->sum('total');
        $last_month_volume = Transaction::where('coe_id', $coe_id)->whereBetween('created_at', [$last_month_started, $last_month_ended])->sum('total');

        $outward_referral_volume = ReferralService::whereHas('referral', function ($query) use ($coe_id) {
            $query->where('referring_coe_id', $coe_id)->where('status', CHFConstants::$FULFILLED);
        })->sum('total');

        $inward_referral_volume = ReferralService::whereHas('referral', function ($query) use ($coe_id) {
            $query->where('reference_coe_id', $coe_id)->where('status', CHFConstants::$FULFILLED);
        })->sum('total');

        $current_month_inward_referral_volume = ReferralService::whereHas('referral', function ($query) use ($coe_id, $current_month_ended, $current_month_started) {
            $query->where('reference_coe_id', $coe_id)->where('status', CHFConstants::$FULFILLED)->whereBetween('created_at', [$current_month_started, $current_month_ended]);
        })->sum('total');

        $current_month_outward_referral_volume = ReferralService::whereHas('referral', function ($query) use ($coe_id, $current_month_started, $current_month_ended) {
            $query->where('referring_coe_id', $coe_id)->where('status', CHFConstants::$FULFILLED)->whereBetween('created_at', [$current_month_started, $current_month_ended]);
        })->sum('total');

        $last_month_inward_referral_volume = ReferralService::whereHas(
            'referral',
            function ($query) use ($coe_id, $last_month_started, $last_month_ended) {
                $query->where('reference_coe_id', $coe_id)->where('status', CHFConstants::$FULFILLED)->whereBetween('created_at', [$last_month_started, $last_month_ended]);
            }
        )->sum('total');

        $last_month_outward_referral_volume = ReferralService::whereHas(
            'referral',
            function ($query) use ($coe_id, $last_month_started, $last_month_ended) {
                $query->where('referring_coe_id', $coe_id)->where('status', CHFConstants::$FULFILLED)->whereBetween('created_at', [$last_month_started, $last_month_ended]);
            }
        )->sum('total');




        $coe_patients_count = User::whereHas('patient', function ($query) use ($coe_id) {
            return $query->where('coe_id', $coe_id);
        })->count();

        $coe_patients_wallet_balance = Wallet::whereHas('user', function ($query) use ($coe_id) {
            $query->whereHas('patient', function ($query) use ($coe_id) {
                $query->where('coe_id', $coe_id);
            });
        })->sum('balance');

        $coe_users = User::where('coe_id', $coe_id);

        return ResponseHelper::ajaxResponseBuilder(null, null, [
            'hospital_billing_volume' => Transaction::where('coe_id', $coe_id)->sum('total'),
            'outward_referral_volume' => $outward_referral_volume,
            'inward_referral_volume' => $inward_referral_volume,
            'patients_count' => $coe_patients_count,
            'transactions_count' => Transaction::where('coe_id', $coe_id)->count(),
            'staff_count' => $coe_users->count(),
            'allocation_balance' => $coe->fund_allocation,
            'last_month_volume' => $last_month_volume,
            'current_month_volume' => $current_month_volume,
            'last_month_outward_referral_volume' => $last_month_outward_referral_volume,
            'current_month_outward_referral_volume' => $current_month_outward_referral_volume,
            'last_month_inward_referral_volume' => $last_month_inward_referral_volume,
            'current_month_inward_referral_volume' => $current_month_inward_referral_volume,
            'patients_wallet_balance' => $coe_patients_wallet_balance,
        ]);
    }
}
