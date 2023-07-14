<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Http\Controllers\Controller;
use App\Traits\tQuery;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    use tQuery;
    //
    public function view($patient_id)
    {
        // $user = $this->getOneThroughPipe(Patient::class,$patient_id)

    }
}
