<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\UserVerification;

class ProfileController extends Controller
{
    // Logged in User get profile
    public function index()
    {
        return ResponseHelper::ajaxResponseBuilder(true, "User profile", auth()->user());
    }

    // Logged in user update his profile
    public function updateProfile(Request $request)
    {
        $validated_data = $this->validate($request, [
            'first_name' => 'string',
            'last_name' => 'required|string',
            'other_names' => 'string',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
            'address' => 'string',
            'gender' => 'required|string|max:6|min:4',
        ]);
        try {
            $user = auth()->user();

            DB::beginTransaction();

            $user = User::update($request->all());

            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, "Profile updated successfully", User::where('id', auth()->user()->id)->with("roles")->with('patient')->first(), 200);
        } catch (\Exception $ex) {
            DB::rollBack();

            return ResponseHelper::noDataErrorResponse("Profile update failed");
        }
    }
}
