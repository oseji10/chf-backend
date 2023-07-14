<?php

namespace App\Http\Controllers\API\COEAdmin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManageStaffController extends Controller
{
    //
    use \App\Traits\tUserVerification;

    public function updateStaffDetail(Request $request, $staff_id){
        $this->validate($request,[
            'email' => 'required|string|email',
            'phone_number' => 'required|string|min:11|max:11',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string'
        ]);

        $staff = User::findOrFail($staff_id);

        $staff->update($request->all());
        return ResponseHelper::ajaxResponseBuilder(true, "Staff detail updated.", $staff);
        try{

        }catch(\Exception $ex){
            return ResponseHelper::exceptionHandler($ex);
        }
    }
}
