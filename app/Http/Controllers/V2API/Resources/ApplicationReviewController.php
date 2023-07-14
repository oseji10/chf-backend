<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ApplicationReview;
use App\Traits\tQuery;
use Illuminate\Http\Request;

class ApplicationReviewController extends Controller
{
    use tQuery;
    //
    public function index()
    {
        $applications = $this->getManyThroughPipe(ApplicationReview::class)->get();

        return ResponseHelper::ajaxResponseBuilder(true, null, $applications);
    }

    public function view($id)
    {
        $application = $this->getOneThroughPipe(ApplicationReview::class)->find($id);
    }
}
