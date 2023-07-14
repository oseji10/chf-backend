<?php

namespace App\Http\Controllers\API\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\COE;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class BillingSummaryReportController extends Controller
{
    //
    public function index(){
        try {
            $start_date = date('Y-m-d H:i:s', strtotime(request()->start_date. ' - 1 days'));
            $end_date = date('Y-m-d H:i:s', strtotime(request()->end_date. ' + 1 days'));
            $is_drug=request()->is_drug;
            $coe=request()->coe;
            
           
            //There will always be a start date and end date because of the addition and subtraction of 1 day
            $billing_history = $this->fetchTransaction($start_date,$end_date,$coe,$is_drug);

            $summary=[];
            foreach($billing_history as $bill){
                //If there is a way to get the sum of billings from the DB using ORM it would reduce this time complexity
                $coe_sum=0;
                foreach($bill->billings as $billing){
                    $coe_sum=$coe_sum+($billing->cost * $billing->quantity);
                }
               
                array_push($summary,[
                    'coe'=>[
                        'id'=>$bill->id,
                        'coe_id_cap'=>$bill->coe_id_cap,
                        'coe_name'=>$bill->coe_name,
                        'coe_type'=>$bill->coe_type,
                        'coe_address'=>$bill->coe_address,
                        'created-at'=>$bill->created_at,
                        'updated-at'=>$bill->created_at,  
                    ],
                    'total'=>$coe_sum,
                    'coe_payment'=>$coe_sum*0.98,
                    'emge'=>$coe_sum*0.02,
                 ]);
            }
            return ResponseHelper::ajaxResponseBuilder(true, 'COE Billings',$summary);
            
        } catch (\Exception $ex) {
           // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function fetchTransaction($start_date,$end_date,$coe,$is_drug){
        if(isset($is_drug) && isset($coe)){
            $billing_history = COE::with(['billings'=>function ($billings) use($start_date,$end_date,$coe,$is_drug) {
                $billings->where("is_drug",$is_drug)->whereBetween('created_at',[$start_date, $end_date]);}])
                ->where('id',$coe)->get();
        }else if(!isset($is_drug) && isset($coe)){ 
            $billing_history = COE::with(['billings'=>function ($billings) use($start_date,$end_date,$coe) {
                $billings->whereBetween('created_at',[$start_date, $end_date]);}])->where('id',$coe)
                ->get();
        }else if(isset($is_drug) && !isset($coe)){ 
            $billing_history = COE::with(['billings'=>function ($billings) use($start_date,$end_date,$is_drug) {
                $billings->where("is_drug",$is_drug)->whereBetween('created_at',[$start_date, $end_date]);}])
                ->get();
        }else{
            $billing_history = COE::with(['billings'=>function ($billings) use($start_date,$end_date) {
                $billings->whereBetween('created_at',[$start_date, $end_date]);}])
                ->get();
        }

        return $billing_history;
    }

    //
    public function billingSummary(){
        try {
            $start_date = date('Y-m-d H:i:s', strtotime(request()->start_date. ' - 1 days'));
            $end_date = date('Y-m-d H:i:s', strtotime(request()->end_date. ' + 1 days'));
            $is_drug=request()->is_drug;
            $coe=request()->coe;
            
           
            //There will always be a start date and end date because of the addition and subtraction of 1 day
            $billing_history = $this->fetchTransaction($start_date,$end_date,$coe,$is_drug);

            $summary=[];
            foreach($billing_history as $bill){
                //If there is a way to get the sum of billings from the DB using ORM it would reduce this time complexity
                $coe_sum=0;
                foreach($bill->billings as $billing){
                    $coe_sum=$billing->cost * $billing->quantity;
                    array_push($summary,[
                        'coe'=>[
                            'id'=>$bill->id,
                            'coe_id_cap'=>$bill->coe_id_cap,
                            'coe_name'=>$bill->coe_name,
                            'coe_type'=>$bill->coe_type,
                            'coe_address'=>$bill->coe_address,
                            'created-at'=>$bill->created_at,
                            'updated-at'=>$bill->created_at,  
                        ],
                        'total'=>$coe_sum,
                        'coe_payment'=>$coe_sum*0.98,
                        'emge'=>$coe_sum*0.02,
                        'is_drug'=>$billing->is_drug,
                        'created_at'=>$billing->created_at
                     ]);
                }
            }
            return ResponseHelper::ajaxResponseBuilder(true, 'COE Billings',$summary);
            
        } catch (\Exception $ex) {
           // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }

    public function consolidated(Request $request, $coe_id){
        $this->validate($request,[
            'start_date' => 'required',
            'end_date' =>'required',
        ]);

        // return ResponseHelper::ajaxResponseBuilder(true, "Result", )
    }
}
