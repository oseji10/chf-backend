<?php

namespace App\Http\Controllers\API\User\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Patient;
use App\Models\NextOfKin;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class NextOfKinController extends Controller
{
    //
    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required|string',
            'relationship'=>'required|string',
            'phone_number'=>'required|string',
            'email'=>'required|string',
            'address'=>'required|string',
            'city'=>'required|string',
            'state_of_residence'=>'required|int',
            'lga_of_residence'=>'required|int',
        ]);

        try {
            DB::beginTransaction();
            $patient = Patient::where('user_id',auth()->user()->id)->first();

            if(empty($patient)){
                return ResponseHelper::noDataErrorResponse('Not authorized: ',403);
            }

                $request['patient_id'] = $patient->id;
                $request['user_id'] = $patient->user_id;

                $nextOfKin = NextOfKin::create($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('nextOfKin.created'), NextOfKin::where('id',$nextOfKin->id)->first());
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function update(Request $request, $user_id){
        $this->validate($request, [
            'name'=>'required|string',
            'relationship'=>'required|string',
            'phone_number'=>'required|string',
            'email'=>'required|string',
            'address'=>'required|string',
            'city'=>'required|string',
            'state_of_residence'=>'required|int',
            'lga_of_residence'=>'required|int',
        ]);

        try {
            DB::beginTransaction();

            // Update all records with status = "active"
            NextOfKin::where('user_id',$user_id)->update($request->only([
                'name',
                'relationship',
                'phone_number',
                'email',
                'address',
                'city',
                'state_of_residence',
                'lga_of_residence',
            ]));
                
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true,__('nextOfKin.updated'), NextOfKin::where('user_id',$user_id)->first());
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function view($id){
        // WE CAN FIND NEXT OF KIN BY ID OR BY USER ID
        return NextOfKin::where('user_id',$id)->first();
    }

}
