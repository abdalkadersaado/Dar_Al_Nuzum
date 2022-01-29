<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return  User::all();
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        // return $user;

        return response()->json([
            'user' => $user,
            'status' => 201,
            'message' => 'user created Successfully.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email','unique:users'],
            'password' => ['sometimes', 'min:6'],
        ]);

        $id = User::findOrFail($id);

        $id->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'user Updated successfully.'
        ]);
    }

    public function delete($id)
    {

        $user = User::findOrFail($id);

        if ($user) {

            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User Deleted Successfully.'
            ]);
        }

    }
}
