<?php

namespace App\Http\Controllers\V2API\Secretariat;

use App\Helpers\CHFConstants;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CHFStaffController extends Controller
{
    //
    public function index()
    {
        try {
            $user = User::whereHas('roles', function ($query) {
                $query->where('role', CHFConstants::$CHF_ADMIN);
            })->first();
            $user_roles = $user->roles->pluck('id');

            $staff = User::whereHas('roles', function ($query) use ($user_roles) {
                $query->whereIn('parent_id', $user_roles);
            })->get();

            dd($staff);
            return ResponseHelper::ajaxResponseBuilder(true, null, $staff);
        } catch (\Exception $e) {
            \Log::info($e);
        }
    }
}
