<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\API\CHFAdmin\RecommendationController;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{

    /* 
    *   RETURN ALL THE PATIENTS IN THE ORDER THEY APPLIED. FIRST IN FIRST OUT.
     */
    public function index()
    {
        $per_page = request()->per_page ?? 10;
        $filter = request()->filter;
        $users = null;

        switch (strtolower($filter)) {
            case 'geozone': {
                    $users = Patient::whereHas('state', function ($query) {
                        return $query->where('geopolitical_zone_id', request()->patient_filter_value);
                    })->with('user')->with('coe')->with('ailment')->with('applicationReview')->with('applicationReview.reviewer')->get();
                    break;
                }
            case 'geozone-residence': {
                    $users = Patient::whereHas('stateOfResidence', function ($query) {
                        return $query->where('geopolitical_zone_id', request()->patient_filter_value);
                    })->with('user')->with('coe')->with('ailment')->with('applicationReview')->with('applicationReview.reviewer')->get();
                    break;
                }
            case 'ailment': {
                    $users = Patient::whereHas('ailment', function ($query) {
                        return $query->where('id', request()->patient_filter_value);
                    })->with('user')->with('coe')->with('ailment')->with('applicationReview')->with('applicationReview.reviewer')->get();
                    break;
                }
            case 'coe': {
                    $users = Patient::where('coe_id', request()->patient_filter_value)
                        ->with('user')->with('coe')->with('ailment')->with('applicationReview')->with('applicationReview.reviewer')->get();
                    break;
                }
            default: {
                    $users = Patient::whereNotNull('ailment_id')->with('state')->with('stateOfResidence')->with('user')->with('user.wallet')->with('coe')->with('ailment')->with('applicationReview')->with('applicationReview.reviewer')->get();
                }
        }

        return ResponseHelper::ajaxResponseBuilder(true, null, $users);
    }

    //
    public function view($patient_id)
    {
        try {
            $patient = User::Where('email', $patient_id)->orWhere('phone_number', $patient_id)->orwhereHas('patient', function ($query) use ($patient_id) {
                return $query->where('identification_number', $patient_id)->orWhere('chf_id', $patient_id);
            })->with('wallet')->with('patient')->with('patient.state')->with('patient.stateOfResidence')->with('patient.ailment')->first();

            if (!$patient) {
                throw new ModelNotFoundException("Patient record not found");
            }
            return ResponseHelper::ajaxResponseBuilder(true, __('patient.found'), $patient);
        } catch (\Exception $ex) {
            // \Log::error($ex);
            return ResponseHelper::noDataErrorResponse($ex->getMessage(), 404);
        }
    }

    public function search($patient_id)
    {
        try {
            $patients = Patient::whereNotNull('ailment_id')->where('identification_number', $patient_id)->orWhere('chf_id', $patient_id)->orWhereHas('user', function ($query) use ($patient_id) {
                return $query->where('id', $patient_id)->orWhere('email', $patient_id)->orWhere('phone_number', $patient_id);
            })->with('state')->with('stateOfResidence')->with('user')->with('user.wallet')->with('coe')->with('ailment')->with('applicationReview')->with('applicationReview.reviewer')->paginate(10);


            return ResponseHelper::ajaxResponseBuilder(true, "Patient search result", $patients);
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('patient.find-failed'));
        }
    }

    /* 
        *   RETURN THE BILLING HISTORY FOR A LOGGED IN PATIENT
         */
    public function billingHistory($id = null)
    {

        $patient_id = $id ?? auth()->user()->patient->chf_id;
        $patient = Patient::where('chf_id', $patient_id)->first();
        // Include start date and end date in the search so we subtract and add a day 
        $start_date = date('Y-m-d 0:0:0', strtotime(request()->start_date));
        $end_date = date('Y-m-d 23:59:59', strtotime(request()->end_date));
        if ($start_date && $end_date) {
            $billing_history = Transaction::select('*')->whereBetween('created_at', [$start_date, $end_date])
                ->whereHas('user', function ($query) use ($patient_id) {
                    $query->whereHas('patient', function ($query) use ($patient_id) {
                        $query->where('chf_id', $patient_id);
                    });
                })->groupBy('transaction_id')->with('transactions')->with('coe')->with('user')
                ->with('transactions.service')->with(['transactions.service.coes' => function ($coes) {
                    $coes->where('coe.id', auth()->user()->patient->coe_id);
                }])
                ->with('biller')->with('comment')->with('transactions.service.category')->orderBy('created_at', 'desc')->paginate(20);
        } else {
            $billing_history = Transaction::select('*')->whereHas('user', function ($query) use ($patient_id) {
                $query->whereHas('patient', function ($query) use ($patient_id) {
                    $query->where('chf_id', $patient_id);
                });
            })->groupBy('transaction_id')->with('transactions')->with('coe')->with('user')
                ->with('transactions.service')->with(['transactions.service.coes' => function ($coes) {
                    $coes->where('coe.id', auth()->user()->patient->coe_id);
                }])
                ->with('biller')->with('comment')->with('transactions.service.category')->orderBy('created_at', 'desc')->paginate(20);
        }

        return ResponseHelper::ajaxResponseBuilder(true, '', $billing_history);
    }

    /* GET SPECIFIC PATIENTS BY FILTERING  */
    public function filter(Request $request)
    {

        $per_page = $request->per_page ?? 10;
        $filter = $request->filter;
        if (!$request->filter || !$request->filter_type) {
            return ResponseHelper::noDataErrorResponse('No filter and filter type specified', 400);
        }

        $data = null;

        switch (strtolower($request->filter_type)) {
            case 'coe': {
                    $data = Patient::where('coe_id', $filter)->with('coe')->with('applicationReview')->with('ailment')->with('user')->with('user.wallet')->with('applicationReview.reviewer')->with('state')->with('stateOfResidence')->orderBy('created_at', 'desc')->paginate($per_page);
                    break;
                }
            case 'ailment': {
                    $data = Patient::whereHas('ailment', function ($query) use ($filter) {
                        // SPLIT THE SEARCH STRING AND PICK ONLY FIRST PART OF CANCER STRING
                        // THE SECOND PART IS USUALLY IRRELEVANT. E.G "COLON CANCER" OR "BREAST CANCER"
                        return $query->where('ailment_type', explode('-', $filter)[0])->where('ailment_stage', explode('-', $filter)[1]);
                    })->with('coe')->with('applicationReview')->with('ailment')->with('user')->with('user.wallet')->with('applicationReview.reviewer')->with('state')->with('stateOfResidence')->orderBy('created_at', 'desc')->paginate($per_page);
                    break;
                }
            case 'geozone': {
                    $data = Patient::whereHas('state', function ($query) use ($filter) {
                        return $query->whereHas('geopoliticalZone', function ($query) use ($filter) {
                            return $query->where('geopolitical_zone', $filter);
                        });
                    })->with('coe')->with('applicationReview')->with('ailment')->with('user')->with('user.wallet')->with('applicationReview.reviewer')->with('state')->with('stateOfResidence')->orderBy('created_at', 'desc')->paginate($per_page);
                    break;
                }
            case 'geozone-residence': {
                    $data = Patient::whereHas('stateOfResidence', function ($query) use ($filter) {
                        return $query->whereHas('geopoliticalZone', function ($query) use ($filter) {
                            return $query->where('geopolitical_zone', $filter);
                        });
                    })->with('coe')->with('applicationReview')->with('ailment')->with('user')->with('user.wallet')->with('applicationReview.reviewer')->with('state')->with('stateOfResidence')->orderBy('created_at', 'desc')->paginate($per_page);
                    break;
                }

            default:
                $data = [
                    'data' => []
                ];
                break;
        }

        return ResponseHelper::ajaxResponseBuilder(true, "Patient search by " . $request->filter_type, $data);
    }

    public function viewPatient($patient_id)
    {
        return ResponseHelper::ajaxResponseBuilder(true, "Only Patient infromation", Patient::findOrFail($patient_id));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'state_id' => 'required|int',
            'lga_id' => 'required|int',
            'ailment_id' => 'required|int',
            'ailment_stage' => 'required|int',
            'city' => 'required|string',
            'state_of_residence' => 'required|int',
            'address' => 'required|string',
            'primary_physician' => 'required|int',
        ]);

        try {
            DB::beginTransaction();

            // Update all records with status = "active"
            Patient::where('id', $id)->update($request->only([
                'state_id',
                'lga_id', 'phone_no_alt', 'ailment_id', 'ailment_stage',
                'city', 'state_of_residence', 'address', 'primary_physician'
            ]));

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, 'updated', Patient::findOrFail($id));
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
