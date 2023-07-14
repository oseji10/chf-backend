<?php

namespace App\Http\Controllers\API\Superadmin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\COE;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function coePatientApprovalReport()
    {
        $coes = COE::orderBy('coe_name', 'asc')->with('patients', 'patients.applicationReview')->get();
        return ResponseHelper::ajaxResponseBuilder(true, "Report COES", $coes);
    }
}
