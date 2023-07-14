<?php

namespace App\Http\Controllers\V2API\Resources;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\QueryFilters\PaginationFilter;
use App\QueryFilters\RelationshipFilter;
use App\QueryFilters\SortFilter;
use App\QueryFilters\StatusFilter;
use App\QueryFilters\User\UserFindFilter;
use App\Traits\tQuery;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class UserController extends Controller
{
    use tQuery;

    public function index()
    {
        $users = $this->getManyThroughPipe(User::class)->get();

        return $users;
    }

    public function view($id)
    {
        // return User::query()->where('id', $id)->orWhereHas('patient', function ($query) use ($id) {
        //     return $query->where('chf_id', $id);
        // })->first();
        $user = $this->getOneThroughPipe(User::class, $id, [UserFindFilter::class])
            ->first();
        return ResponseHelper::ajaxResponseBuilder(true, null, $user);
    }

    public function update(Request $request, $id)
    {
        User::find($id)->update($request->all());
        return ResponseHelper::noDataSuccessResponse("User Updated");
    }

    public function me()
    {
        return ResponseHelper::ajaxResponseBuilder(true, null, auth()->user());
    }
}
