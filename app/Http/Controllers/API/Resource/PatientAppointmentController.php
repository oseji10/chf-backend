<?php

namespace App\Http\Controllers\API\Resource;

use App\Http\Controllers\Controller;
use App\Events\PatientAppointmentEvent;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Patient;
use App\Models\PatientAppointment;
use Illuminate\Support\Facades\DB;

class PatientAppointmentController extends Controller
{
    //
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|int',
            'appointment_date' => 'required|date',
            'coe_to_visit' => 'required|int',
            'coe_staff_comment' => 'required|string',
            'staff_id' => 'required|int',
        ]);
        try {
            DB::beginTransaction();
            $patient = Patient::where('user_id', $request->user_id)->first();
            if (empty($patient)) {
                return ResponseHelper::noDataErrorResponse('Patient not found: ', 404);
            }
            $request['patient_id'] = $patient->id;

            //Insert into appointment schedule
            $patientAppointment = PatientAppointment::create($request->all());

            //send appointment email to user
            $patientAppointment = PatientAppointment::findOrFail($patientAppointment->id);
            // event(new PatientAppointmentEvent($patientAppointment));

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('patientAppointment.created'), $patientAppointment);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('patientAppointment.create-failed'));
        }
    }

    public function index()
    {
        //filter by date
        $filterdate = request()->appointment_date;
        if (isset($filterdate) && !empty($filterdate)) {
            return ResponseHelper::ajaxResponseBuilder(
                true,
                __('patientAppointment.created'),
                PatientAppointment::with('patient')->with('patient.user')
                    ->with('coe')->with('staff')->where('appointment_date', $filterdate)->get()
            );
        }

        return ResponseHelper::ajaxResponseBuilder(
            true,
            __('patientAppointment.created'),
            PatientAppointment::with('patient')->with('patient.user')->with('coe')->with('staff')->get()
        );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'appointment_date' => 'required|date|sometimes',
            'coe_to_visit' => 'required|int|sometimes',
            'coe_staff_comment' => 'required|string|sometimes',
        ]);
        try {
            //update appointment schedule other fields are is_confirmed, status
            $patientAppointment = PatientAppointment::where("id", $id)->update($request->all());

            //send appointment email to user
            $patientAppointment = PatientAppointment::where("id", $id)->with('patient')->with('patient.user')
                ->with('coe')->with('staff')->first();
            event(new PatientAppointmentEvent($patientAppointment));

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('patientAppointment.updated'), $patientAppointment);
        } catch (\Exception $ex) {
            DB::rollBack();
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('patientAppointment.update-failed'));
        }
    }

    public function view($id)
    {
        return ResponseHelper::ajaxResponseBuilder(
            true,
            __('patient appointment'),
            PatientAppointment::where('id', $id)->with('patient')->with('patient.user')
                ->with('coe')->with('staff')->first()
        );
    }

    public function viewByPatientId($id)
    {
        //$id can be user_id or patient_id
        try {
            $filterdate = request()->appointment_date;
            if (isset($filterdate) && !empty($filterdate)) {
                return ResponseHelper::ajaxResponseBuilder(
                    true,
                    __('patient appointment'),
                    PatientAppointment::where('appointment_date', $filterdate)
                        ->where('user_id', $id)->orWhere('patient_id', $id)
                        ->with('patient')->with('patient.user')->with('coe')->with('staff')->get()
                );
            }

            return ResponseHelper::ajaxResponseBuilder(
                true,
                __('patient appointment'),
                PatientAppointment::where('user_id', $id)->orWhere('patient_id', $id)
                    ->with('patient')->with('patient.user')->with('coe')->with('staff')->get()
            );
        } catch (\Exception $ex) {
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function viewByCoeId($coe_id)
    {
        //filter by date
        try {
            $filterdate = request()->appointment_date;
            if (isset($filterdate) && !empty($filterdate)) {
                return ResponseHelper::ajaxResponseBuilder(true, __('patient appointment'), PatientAppointment::where('appointment_date', $filterdate)
                    ->where('coe_to_visit', $coe_id)->with('patient')->with('patient.user')->with('coe')->with('staff')->get());
            }

            return ResponseHelper::ajaxResponseBuilder(true, __('patient appointment'),  PatientAppointment::where('coe_to_visit', $coe_id)->with('patient')
                ->with('patient.user')->with('coe')->with('staff')->get());
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
