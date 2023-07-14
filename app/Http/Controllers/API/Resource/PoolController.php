<?php

namespace App\Http\Controllers\API\Resource;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\PoolCredit;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoolController extends Controller
{
    //

    public function store(Request $request){
        try{

            $this->validate($request,[
                'benefactor' => 'required|string',
                'credit' => 'required|numeric'
            ]);
    
            $pool_account_balance = SiteSetting::where('key','pool_account_balance')->first();
    
            DB::beginTransaction();

            $pool_account_balance->update([
                'value' => (string)( (float)$pool_account_balance->value + $request->credit),
            ]);
    
            PoolCredit::create([
                'benefactor' => $request->benefactor,
                'credit' => $request->credit,
                'user_id' => auth()->id(),
            ]);

            DB::commit();
    
            return ResponseHelper::noDataSuccessResponse('Pool credited successfully.');
        }catch(\Exception $ex){
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse("Could not credit pool");
        }
    }
}
