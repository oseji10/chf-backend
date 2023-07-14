<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\COE\MDTApprovePatientMail;
use App\Mail\PrimaryPhysicianPatientApprovedMail;
use App\Mail\SocialWorkerApprovedMail;
use App\Models\User;
use App\Models\COE;
use App\Models\Patient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\tUserVerification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class COEStaffController extends Controller
{
    use tUserVerification;

    /*
    * Get all staff of a particular coe
    *
    */
    public function index($coe_id)
    {
        try {
            $per_page = request()->per_page ?? 10;
            $users = COE::find($coe_id)->staffs()->with('roles')->orderBy('created_at', 'desc')->paginate($per_page);
            return ResponseHelper::ajaxResponseBuilder(true, __('coestaff.get'), $users, 200);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('coestaff.get-failed'));
        }
    }

    public function store(Request $request)
    {
        $validated_data = $this->validate($request, [
            'email' => 'required|string|email|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'other_names' => 'string',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
            'address' => 'string',
            'gender' => 'required|string|max:6|min:4',
        ]);

        try {

            DB::beginTransaction();

            /*
            *   HASH THE USER'S PASSWORD
            */
            $password = $request->phone_number;
            $validated_data['password'] = Hash::make($password);
            $validated_data['email_verified_at'] = date("Y-m-d H:i:s");

            /*
            *  USE THE AUTHENTICATED USER'S COE ID
            *
            */
            $validated_data['coe_id'] = auth()->user()->coe->id;

            $user = User::create($validated_data);
            $user->roles()->attach([2]);

            $this->sendCreatedEmailToStaff($request->email, $password);
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('coestaff.created'), User::with('coe')->where('email', $request->email)->get(), 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('coestaff.create-failed'));
        }
    }


    /*
    * Get all staff of a particular coe
    *
    */
    public function view($coe_id, $user_id)
    {
        try {
            $user = User::with('coe')->findorfail($user_id);
            return ResponseHelper::ajaxResponseBuilder(true, __('coestaff.get'), $user, 200);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('coestaff.get-failed'));
        }
    }

    /*
    *   ATTACH ADDITIONAL ROLES TO A COE STAFF
     */
    public function attachRole(Request $request)
    { }

    /*
    *   DETACH A ROLE FROM COE STAFF
     */
    public function detachRole(Request $request)
    { }

    public function billingHistory()
    {
        // Include start date and end date in the search so we subtract and add a day
        $start_date = date('Y-m-d H:i:s', strtotime(request()->start_date . ' - 1 days'));
        $end_date = date('Y-m-d H:i:s', strtotime(request()->end_date . ' + 1 days'));
        $per_page = request()->per_page ?? 20;

        if ($start_date && $end_date) {
            $billing_history = Transaction::select('*')->whereBetween('created_at', [$start_date, $end_date])
                ->where('biller_id', auth()->id())->groupBy('transaction_id')->with('transactions')
                ->with('coe')->with('user')->with('user.patient')->with('transactions.service')->with(['transactions.service.coes' => function ($coes) {
                    $coes->where('coe.id', auth()->user()->coe_id);
                }])
                ->with('biller')->with('comment')->with('transactions.service.category')
                ->orderBy('created_at', 'desc')->with('documents')->paginate($per_page);
        } else {
            $billing_history = Transaction::select('*')->where('biller_id', auth()->id())->groupBy('transaction_id')
                ->with('transactions')->with('coe')->with('user')->with('user.patient')->with('transactions.service')
                ->with(['transactions.service.coes' => function ($coes) {
                    $coes->where('coe.id', auth()->user()->coe_id);
                }])
                ->with('biller')->with('comment')->with('transactions.service.category')->with('documents')
                ->orderBy('created_at', 'desc')->paginate(20);
        }

        return ResponseHelper::ajaxResponseBuilder(true, '', $billing_history);
    }


    public function patients()
    {
        $patients = User::find(auth()->id())->physicianPatients()->whereHas('applicationReview', function ($query) {
            $query->where('status', CHFConstants::$PENDING);
        })->with('user')->with('stateOfResidence')->with('coe')->with('familyHistories')->with('ailment')->with('socialConditions')->with('SupportAssessments')->get();

        return ResponseHelper::ajaxResponseBuilder(true, CHFConstants::$PATIENTS, $patients);
    }

    public function reviewPatient(Request $request)
    {
        $this->validate($request, [
            'patient_id' => 'required|numeric',
            'status' => 'required|string|max:10',
            'carePlan' => 'required|string|min:50',
            'recommendedFund' => 'required|numeric'
        ]);

        try {
            $patient = Patient::find($request->patient_id);

            $patient->update([
                'primary_physician_status' => $request->status,
                'primary_physician_reviewed_on' => date('Y-m-d h:i:s', time()),
                'mdt_recommended_fund' => $request->recommendedFund,
                'care_plan' => $request->carePlan,
                'primary_physician_reviewer_id' => auth()->id(),
            ]);

            \Mail::to($patient->user->email)->send(new PrimaryPhysicianPatientApprovedMail);

            return ResponseHelper::ajaxResponseBuilder(true, __("messages.patient_review_success"), $patient);
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }
    }

    public function coePatients()
    {
        $patients = Patient::where('coe_id', auth()->user()->coe_id)->with('user', 'primaryPhysician', 'ailment', 'state', 'stateOfResidence', 'familyHistories', 'mdtComments', 'coe')->get();

        return ResponseHelper::ajaxResponseBuilder(true, "COE Patient", $patients);
    }

    public function coeMDTPatients()
    {
        $coe_id = auth()->user()->coe_id;
        $patients = Patient::where(['coe_id' => $coe_id, 'social_worker_status' => CHFConstants::$APPROVED, 'primary_physician_status' => CHFConstants::$APPROVED])
            ->with('user', 'primaryPhysician', 'ailment', 'state', 'stateOfResidence', 'familyHistories', 'mdtComments', 'coe', 'wallet')
            ->get();

        return ResponseHelper::ajaxResponseBuilder(true, "COE Patient", $patients);
    }

    public function mdtRecommendFund(Request $request)
    {
        $this->validate($request, [
            'patient_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $patient = Patient::find($request->patient_id);

            $patient->mdt_recommended_amount = $request->amount;

            $cmds = User::where(['coe_id' => auth()->user()->coe_id, 'status' => CHFConstants::$ACTIVE])->whereHas('roles', function ($query) {
                $query->where('role', CHFConstants::$COE_ADMIN)->orWhere('role', CHFConstants::$CMD);
            })->get();

            foreach ($cmds as $cmd) {
                /* CONSIDER RENAMING SocialWorkerApprovedMail to MDTApprovedMail */
                \Mail::to($cmd->email)->send(new MDTApprovePatientMail($patient));
            }
            $patient->save();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
        }


        return ResponseHelper::ajaxResponseBuilder(true, "Recommendation successful", $patient);
    }
}
