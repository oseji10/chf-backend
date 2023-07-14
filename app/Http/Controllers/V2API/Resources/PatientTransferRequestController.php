<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Mail\PatientTransferRequestEmail;
use App\Models\Patient;
use App\Models\PatientTransferRequest;
use App\Models\User;
use Illuminate\Http\Request;

class PatientTransferRequestController extends Controller
{
    //
    public function create(Request $request)
    {
        $this->validate($request, [
            'patient_chf_id' => 'required|string',
            // 'current_physician_id' => 'required|numeric',
        ]);

        $patient = Patient::where('chf_id', $request->patient_chf_id)->first();
        $current_physician = User::find($patient->primary_physician);
        $user = auth()->user();


        $previousRequests = PatientTransferRequest::where('patient_chf_id', $request->patient_chf_id)->where('requesting_physician_id', auth()->id())->count();

        if ($previousRequests) {
            throw new \Exception("You have already requested for this patient transfer", 400);
        }

        // \Mail::to('geefive3@gmail.com')->send(new PatientTransferRequestEmail($patient, $current_physician, $user));

        $newPatientTransferRequest = PatientTransferRequest::create([
            'patient_chf_id' => $request->patient_chf_id,
            'requesting_physician_id' => auth()->id(),
            'current_physician_id' => $current_physician->id,
            'status' => strtoupper(CHFConstants::$PENDING),
        ]);

        return ResponseHelper::ajaxResponseBuilder(true, null, $newPatientTransferRequest);
    }

    public function approvePatientsTransfer(Request $request)
    {
        $this->validate($request, [
            'requestIds' => 'required|array'
        ]);

        $approved_transfers = [];

        foreach ($request->requestIds as $id) {
            $transferRequest = PatientTransferRequest::find($id);
            $updatedTransfer = $transferRequest->update([
                'status' => CHFConstants::$APPROVED,
                'approved_by' => auth()->id(),
            ]);

            array_push($approved_transfers, $updatedTransfer);
        }

        return ResponseHelper::ajaxResponseBuilder(true, null, $approved_transfers);
    }
}
