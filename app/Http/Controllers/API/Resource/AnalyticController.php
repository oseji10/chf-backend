<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Ailment;
use App\Models\ApplicationReview;
use App\Models\COE;
use App\Models\GeopoliticalZone;
use App\Models\Patient;
use App\Models\SiteSetting;
use App\Models\State;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    //
    public function index(){
        $pool_account_balance = SiteSetting::where('key','pool_account_balance')->first()->value;
        $geopolitical_zones = GeopoliticalZone::orderBy('geopolitical_zone', 'asc')->withCount('patients')->withCount('residencePatients')->get();
        $ailments = Ailment::orderBy('ailment_type','asc')->withCount('patients')->get();
        $states = State::withCount('patients')->get();
        $coes = COE::withCount('patients')->withCount('transactions')->withCount('billings')->with('wallet')->get();
        $total_approved_fund = (double) ApplicationReview::all()->sum('amount_approved');
        $utilized_funds = Transaction::all()->sum('total');
        $completed_profile = Patient::whereHas('supportAssessments')->count();
        // $utilized_funds = 0;
        // foreach(Transaction::all() as $transaction){
        //     $utilized_funds += ($transaction->total - $transaction->discount);
        // }

        $data = [
            'geopolitical_zones' => $geopolitical_zones,
            'patients_count' => Patient::all()->count(),
            'patients_approved'=>Patient::whereHas('applicationReview', function($query){
                return $query->where('status','approved');
            })->get()->count(),
            'patients_declined'=>Patient::whereHas('applicationReview', function($query){
                return $query->where('status','declined');
            })->get()->count(),
            'patients_pending'=>Patient::whereHas('applicationReview', function($query){
                return $query->where('status','pending');
            })->get()->count(),
            'ailments' => $ailments,
            'pool_account_balance' => (double) $pool_account_balance,
            'states' => $states,
            'coes' => $coes,
            'total_approved_fund' => $total_approved_fund,
            'utilized_funds' => $utilized_funds,
            'complete_profile' => $completed_profile,

        ];
        return ResponseHelper::ajaxResponseBuilder(true,"Analytics",$data);
    }


    // Patient analytics controller
    public function patient(){
        $geopolitical_zones = GeopoliticalZone::orderBy('geopolitical_zone', 'asc')->withCount('patients')->withCount('residencePatients')->get();
        $ailments = Ailment::orderBy('ailment_type','asc')->withCount('patients')->get();
        $states = State::withCount('patients')->get();
        $statesOfResidence = State::withCount('residencePatients')->get();

        $coes = COE::withCount('patients')->get();

        $data = [
            'geopolitical_zones' => $geopolitical_zones,
            'patients_count' => Patient::all()->count(),
            'patients_approved'=>Patient::whereHas('applicationReview', function($query){
                return $query->where('status','approved');
            })->get()->count(),
            'patients_declined'=>Patient::whereHas('applicationReview', function($query){
                return $query->where('status','declined');
            })->get()->count(),
            'patients_pending'=>Patient::whereHas('applicationReview', function($query){
                return $query->where('status','pending');
            })->get()->count(),
            'ailments' => $ailments,
            'states' => $states,
            'state_of_residence'=>$statesOfResidence,
            'coes' => $coes,
        ];
        return ResponseHelper::ajaxResponseBuilder(true,"Patient Analytics",$data);
    }

    // Service analytics controller
    public function service(){
        try{
        $start_date = date('Y-m-d H:i:s', strtotime(request()->start_date. ' - 1 days'));
        $end_date = date('Y-m-d H:i:s', strtotime(request()->end_date. ' + 1 days'));
        $coe=request()->coe;
        $category=request()->category;
    
        if(empty($category))  return ResponseHelper::ajaxResponseBuilder(true,"Patient Analytics",[]);
        
        if(isset($coe)){
            $service_category=ServiceCategory::where('id',$category)
            ->with('services')->with(['services.billings'=>function($billings) use($coe,$start_date,$end_date){
                 $billings->where('coe_id',$coe)->whereBetween('created_at',[$start_date, $end_date]);
            }])
            ->first();
            
        }else{
            $service_category=ServiceCategory::where('id',$category)
            ->with('services')->with(['services.billings'=>function($billings) use($start_date,$end_date){
                $billings->whereBetween('created_at',[$start_date, $end_date]);
            }])->first();
        }

            // Constructing the data
            //This function returns the analytics for a particular category at a time.
            $response=[
                "category"=>[
                    "id"=>$service_category->id,
                    "category_name"=>$service_category->category_name
                ]
                ];

            //Process the services
            $services=$service_category->services;
            $service_data=[];
            foreach($services as $service){
                // \Log::info($service);
                $service_count=0;
                if($service->billings->count()>0){
                    $service_count=$service->billings->sum('quantity');
                }
            
                array_push($service_data,[
                "id"=>$service->id,
                "service_name"=>$service->service_name,
                "service_code"=>$service->service_code,
                "billings_count"=>$service_count]);
            }
            $response["services"]=$service_data;
            return ResponseHelper::ajaxResponseBuilder(true,"Patient Analytics",$response);    
           
        }catch(\Exception $ex){
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('errors.server'));
        }
    }
}
