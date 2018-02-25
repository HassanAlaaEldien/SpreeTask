<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     *  Handle the request for listing all users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('listUsers', User::class);

        $users = User::all();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }


    /**
     * Handle the request for creating new user.
     *
     * @param CreateUserRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => bcrypt($request->password)]);

        $user->roles()->attach($request->role);

        return redirect()->back()->with(['success' => 'User Created Successfully!']);
    }

    /**
     * Handle the request for updating specific user information.
     *
     * @param User $user
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $user->update(['name' => $request->name, 'email' => $request->email]);

        $user->roles()->sync($request->role, true);

        return view('admin.users.update-view', compact('user'));
    }

    /**
     * Handle the request for updating specific user password.
     *
     * @param User $user
     * @param UpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(User $user, UpdatePasswordRequest $request)
    {
        if (Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
            $user->update(['password' => bcrypt($request->password)]);

            return response()->json(['success' => 'User password updated successfully!']);
        }

        return response()->json(['fail' => 'Please, enter valid old password!']);
    }

    /**
     * Handle the request for deleting specific user.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('deleteUser', User::class);

        $user->delete();

        return response()->json(['success' => 'User deleted successfully!']);
    }
}
