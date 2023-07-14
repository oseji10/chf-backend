<?php

namespace App\Http\Controllers\API\Patient;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class PatientBillingController extends Controller
{
    //
    public function range($patient_id){
        /*  */
        
        $patient = Patient::where('chf_id', $patient_id)->first();
        
        // $start_date = request()->start_date ?? new Date();
        $start_date = date('Y-m-d 00:00:00', strtotime(request()->start_date ?? new Date()));
        $end_date = date('Y-m-d 23:59:59', strtotime(request()->end_date ?? new Date()));
        
        try {
            $transactions = Transaction::where('user_id', $patient->user->id)->whereBetween('created_at', [$start_date, $end_date])->with('coe')->with('biller')->with('coe')->with('service')->with('user.patient')->with('service.category')->with('documents')->get()->groupBy('transaction_id');
            return ResponseHelper::ajaxResponseBuilder(true, 'Transaction Range',$transactions);
        } catch (\Exception $ex) {
            return ResponseHelper::exceptionHandler($ex);
        }
    }
}
