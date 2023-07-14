<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\tQuery;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use tQuery;
    //
    public function index()
    {
        $services = Service::orderBy('service_name', 'asc')->with(['coes'])
            ->get();
        return ResponseHelper::ajaxResponseBuilder(true, null, $services);
    }
}
