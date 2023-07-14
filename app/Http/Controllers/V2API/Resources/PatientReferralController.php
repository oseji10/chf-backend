<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\AWSHelper;
use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Helpers\TokenHelper;
use App\Http\Controllers\Controller;
use App\Mail\BillingInvoice;
use App\Mail\COE\InwardReferralFulfilledMail;
use App\Mail\COE\NewPatientReferralEmail;
use App\Mail\COE\OutwardReferralFulfilledMail;
use App\Mail\COE\PhysicianNewAppointmentMail;
use App\Mail\COE\ReferralApprovalMail;
use App\Mail\Patient\NewReferralAppointmentMail;
use App\Models\COE;
use App\Models\Patient;
use App\Models\Referral;
use App\Models\ReferralService;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PatientReferralController extends Controller
{
    //
    public function store(Request $request)
    {
        $this->validate($request, [
            'referrenceCOEId' => 'required|numeric',
            'patientCHFId' => 'required|string',
            'services' => 'required|array|min:1'
        ]);
        try {

            /* 
            *   A HOSPITAL STAFF SHOULD NOT MAKE REFERRAL TO THEIR OWN HOSPITAL 
            */
            if ($request->referrenceCOEId === auth()->user()->coe_id) {
                throw new HttpException(400, "You cannot make referral to your own hospital.");
            }

            $patient = Patient::where('chf_id', $request->patientCHFId)->first();

            /* 
            *   A HOSPITAL STAFF SHOULD NOT MAKE REFERRAL FOR A PATIENT NOT
            *   REGISTERED TO THEIR HOSPITAL
            */
            if ($patient->coe_id !== auth()->user()->coe_id) {
                throw new HttpException(400, "You can only make referrals for patients registered to your hospital.");
            }


            DB::beginTransaction();

            $total = 0;
            $patientReferralId = Referral::count() + 1;

            $referral = new Referral();
            $referral->id = $patientReferralId;
            $referral->reference_coe_id = $request->referrenceCOEId;
            $referral->referring_coe_id = auth()->user()->coe_id;
            $referral->referred_by = auth()->id();
            $referral->referral_note = $request->referralNote;
            $referral->patient_chf_id = $request->patientCHFId;

            foreach ($request->services as $service) {
                $referralService = new ReferralService();
                $referralService->service_id = $service['serviceId'];
                $referralService->referral_id = $patientReferralId;
                $referralService->service_name = $service['serviceName'];
                $referralService->quantity = $service['quantity'];
                $referralService->cost = $service['price'];
                $referralService->total = $service['quantity'] * $service['price'];
                $referralService->save();

                $total += $service['quantity'] * $service['price'];
            }

            if ($patient->user->wallet->balance < $total) {
                throw new HttpException(400, "Patient wallet balance is not enough for this referral.");
            }
            $referral->save();

            $coe_staff_emails = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['cmd', 'coe admin', 'mdt']);
            })->where(['coe_id' => auth()->user()->coe_id, 'status' => CHFConstants::$ACTIVE])->pluck('email')->toArray();

            $mail_list = array_merge($coe_staff_emails, [auth()->user()->email]);

            \Mail::to($mail_list)->send(new NewPatientReferralEmail);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function getCOEStaffReferrals()
    {
        $referrals = Referral::where('assigned_to', auth()->id())->orWhere('referred_by', auth()->id())
            ->with(['referrer', 'fulfiller', 'attendantStaff', 'referringCOE', 'referenceCOE', 'patient', 'services'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ResponseHelper::ajaxResponseBuilder(true, null, $referrals);
    }

    public function getCOEReferrals()
    {
        $user = auth()->user();
        $referrals = Referral::where('referring_coe_id', $user->coe_id)
            ->orWhere('reference_coe_id', $user->coe_id)
            ->with(['referrer', 'fulfiller', 'attendantStaff', 'patient', 'referenceCOE', 'referringCOE', 'services'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ResponseHelper::ajaxResponseBuilder(true, null, $referrals);
    }

    public function approveReferral(Request $request)
    {
        $this->validate($request, [
            'referralId' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $referral = Referral::find($request->referralId);

            if ($referral->status !== CHFConstants::$PENDING) {
                throw new HttpException(400, "This referral is not approveable.");
            }

            $referral->status = CHFConstants::$APPROVED;
            $referral->cmd_id = auth()->id();
            $referral->cmd_approved_on = now(CHFConstants::$DEFAULT_TIMEZONE);
            $referral->save();

            $updatedReferral = Referral::with(['referrer', 'fulfiller', 'attendantStaff', 'patient', 'referenceCOE', 'referringCOE', 'services'])->find($request->referralId);

            $coe_staff = User::whereHas('roles', function ($query) {
                return $query->whereIn('role', ['coe admin', 'cmd']);
            })->where(['coe_id' => auth()->user()->coe_id, 'status' => CHFConstants::$ACTIVE])->pluck('email')->toArray();


            $reference_coe_staff = User::whereHas('roles', function ($query) {
                return $query->whereIn('role', ['coe admin', 'cmd']);
            })->where(['coe_id' => $referral->reference_coe_id, 'status' => CHFConstants::$ACTIVE])->pluck('email')->toArray();

            $mail_list = array_merge(
                array_merge(
                    $coe_staff,
                    ['eokorie@emgeresources.com'/* , 'yokubadejo@emgeresources.com', "docokpako@gmail.com" */, "uchenwokwu@gmail.com"]
                ),
                $reference_coe_staff,
            );

            \Mail::to($mail_list)->send(new ReferralApprovalMail);
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, null, $updatedReferral);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function assignToStaff(Request $request)
    {
        $this->validate($request, [
            'referralId' => 'required|numeric',
            'appointmentDate' => 'required',
            'staffId' => 'required|numeric',
        ]);

        $referral = Referral::find($request->referralId);

        $referral->assigned_to = $request->staffId;
        $referral->assigned_by = auth()->id();
        $referral->assigned_on = now('Africa/Lagos');
        $referral->status = CHFConstants::$ASSIGNED;
        $referral->appointment_note = $request->appointmentNote;
        $referral->appointment_date = $request->appointmentDate;
        $referral->save();

        $updatedReferral = Referral::with(['referrer', 'fulfiller', 'attendantStaff', 'patient', 'referenceCOE', 'referringCOE', 'services'])->find($request->referralId);

        $attendant_staff_email = User::where('id', $request->staffId)->pluck('email')->toArray();

        $coe_staff_emails = User::whereHas('roles', function ($query) {
            $query->whereIn('role', ['mdt', 'coe admin']);
        })->where(['coe_id' => auth()->user()->coe_id, 'status' => CHFConstants::$ACTIVE])->pluck('email')->toArray();

        $mail_list = array_merge(
            $coe_staff_emails,
            $attendant_staff_email
        );

        \Mail::to($referral->patient->user->email)->send(new NewReferralAppointmentMail($updatedReferral));

        \Mail::to($mail_list)->send(new PhysicianNewAppointmentMail($updatedReferral));

        return ResponseHelper::ajaxResponseBuilder(true, null, $updatedReferral);
    }

    public function attendToReferral(Request $request)
    {
        $this->validate($request, [
            'referralId' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $referral = Referral::find($request->referralId);
            $referral->fulfilled_by = auth()->id();
            $referral->fulfilled_on = now(CHFConstants::$DEFAULT_TIMEZONE);
            $referral->status = CHFConstants::$FULFILLED;
            $referral->fulfill_note = $request->appointmentNote;
            $referral->save();

            $patient = Patient::where('chf_id', $referral->patient_chf_id)->first();

            $coe = COE::find($referral->reference_coe_id);
            $total = 0;

            $transactionId = TokenHelper::generateTransactionId();

            foreach ($referral->services as $service) {

                $total += $service->cost * $service->quantity;

                $transaction = new Transaction();
                $transaction->biller_id = auth()->id();
                $transaction->transaction_id = $transactionId;
                $transaction->service_id = $service->service_id;
                $transaction->quantity = $service->quantity;
                $transaction->cost = $service->cost;
                $transaction->total = $service->cost * $service->quantity;
                $transaction->coe_id = auth()->user()->coe_id;
                $transaction->user_id = $patient->user->id;
                $transaction->is_drug = 0;
                $transaction->save();
            }

            $patient_wallet = $patient->user->wallet;
            if ($patient_wallet->balance < $total) {
                DB::rollBack();
                throw new HttpException(400, "Patient's wallet balance is not sufficient for this referral");
            }

            $patient->user->wallet()->update(['balance' => $patient_wallet->balance - $total]);


            $coe->wallet()->update(['balance' => $coe->wallet->balance + $total]);

            $updatedReferral = Referral::with(['referrer', 'attendantStaff', 'patient', 'referenceCOE', 'referringCOE', 'services', 'fulfiller'])->find($request->referralId);

            $transactions = Transaction::where('transaction_id', $transactionId)->get();


            $coe_staff_emails = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['cmd', 'mdt', 'coe admin']);
            })->where(['coe_id' => $referral->reference_coe_id, 'status' => CHFConstants::$ACTIVE])->pluck('email')->toArray();

            $referring_coe_staff = User::whereHas('roles', function ($query) {
                $query->whereIn('role', ['cmd', 'mdt', 'coe admin']);
            })->where(['coe_id' => $referral->referring_coe_id, 'status' => CHFConstants::$ACTIVE])->pluck('email')->toArray();

            $mail_list =  array_merge(
                array_merge(
                    array_merge(
                        $coe_staff_emails,
                        ['eokorie@emgeresources.com', 'yokubadejo@emgeresources.com', "docokpako@gmail.com", "uchenwokwu@gmail.com"]
                    ),
                    $referring_coe_staff
                ),
                [$referral->referrer->email, $referral->attendantStaff->email],
            );

            \Mail::to($mail_list)->send(new InwardReferralFulfilledMail($updatedReferral, $transactionId));
            \Mail::to($referral->patient->user->email)->send(new BillingInvoice($transactions));

            AWSHelper::sendSMS($referral->patient->user->phone_number, "NGN" . (string) $total . " has been charged from your CHF wallet");

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, null, $updatedReferral);
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
