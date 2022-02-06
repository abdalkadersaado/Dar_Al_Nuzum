<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use responseApiTrait;

    public function __construct()
    {
        $this->middleware('Admin');
    }

    public function index()
    {
        $user = User::get();
        return $this->responseData('users', $user, 'Users Selected Successfully.');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $this->responseSuccess('User Created Successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->responseError('Not Found User', 404);
        }

        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['sometimes', 'min:6'],
        ]);

        $id = User::findOrFail($id);

        $id->update($request->all());
        return $this->responseSuccess('User Updated Successfully.');
    }

    public function delete($id)
    {

        $user = User::find($id);

        if (!$user) {
            return $this->responseError('Not Found User', 404);
        }

        $image = $user->profile->image;

        if ($image) {
            unlink($image);
        }
        if ($user) {
            $user->delete();
            return $this->responseSuccess('User Deleted Successfully.');
        }
    }
}
