<?php

namespace App\Http\Controllers\API\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\tUserVerification;

class UserController extends Controller
{
    use tUserVerification;

    //
    public function index()
    {
        try {
            $per_page = request()->per_page ?? 10;
            $email = request()->email;
            $role = request()->role;
            if (isset($email)) {
                return ResponseHelper::ajaxResponseBuilder(true, 'User', User::where("email", $email)->with("roles")->paginate(1));
            } else if (!empty($role)) {
                return ResponseHelper::ajaxResponseBuilder(true, 'User', User::with("roles")->wherehas("roles", function ($query) use ($role) {
                    $query->where('role.id', $role);
                })->paginate($per_page));
            }
            return ResponseHelper::ajaxResponseBuilder(true, 'Users', User::with("roles")->paginate($per_page));
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('server.error'));
        }
    }


    public function store(Request $request)
    {
        $validated_data = $this->validate($request, [
            'email' => 'required|string|email|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
            'gender' => 'required|string|max:6|min:4',
            'role_id' => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            /* 
            *   HASH THE USER'S PASSWORD
            *   Since user is created by other users such as superadmin, a default password of
            *   Phone number is used. The validated data is used becuase setting $request does not work
            */
            $password = $request->phone_number;
            $validated_data['password'] = Hash::make($password);
            $validated_data['email_verified_at'] = date("Y-m-d H:i:s");

            $user = User::create(array_merge($validated_data, $request->only(['other_names', 'profession', 'coe_id'])));
            $user->roles()->attach($request->only(['role_id']));

            $this->sendCreatedEmailToStaff($request->email, $password);
            DB::commit();
            return ResponseHelper::ajaxResponseBuilder(true, __('user.created'), User::with('roles')->where('email', $request->email)->first(), 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ResponseHelper::noDataErrorResponse(__('user.create-failed'));
        }
    }

    public function update(Request $request)
    {

        try {
            $user = User::findOrFail($request->user_id)->update($request->all());
            return ResponseHelper::ajaxResponseBuilder(true, __('user.updated'), User::where('id', $request->user_id)->with('roles')->first());
        } catch (\Exception $ex) {
            // \Log::info($ex);
            return ResponseHelper::noDataErrorResponse(__('user.update-failed'));
        }
    }

    public function profile()
    {
        $user = User::find(auth()->id());
        return ResponseHelper::ajaxResponseBuilder(true, null, [
            'user' => auth()->user(),
            'permissions' => $user->permissions(),
        ]);
    }

    public function updateRoles(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|numeric',
            'roles' => 'required|array'
        ]);

        $user = User::findOrFail($request->user_id);

        $user->roles()->sync($request->roles);

        $user = User::where('id', $request->user_id)->with(['roles'])->first();

        return ResponseHelper::ajaxResponseBuilder(true, 'Successful', $user);
    }
}
