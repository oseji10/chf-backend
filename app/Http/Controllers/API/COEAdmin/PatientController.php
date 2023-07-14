<?php

namespace App\Http\Controllers\API\COEAdmin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    //
    public function index(){
        /* THIS PREVENTS A USER THAT IS NOT A COE ADMIN FROM 
        *   FETCHING PATIENTS DATA FOR COE EVEN BY URL INJECTION
         */

        try{
            $user = User::findOrFail(auth()->id());
            $patients = Patient::where('coe_id', $user->id);

        }catch(\Exception $ex){
            ResponseHelper::exceptionHandler($ex);
        }
    }
}
