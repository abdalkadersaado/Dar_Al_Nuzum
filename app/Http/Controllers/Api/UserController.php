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
        $user = User::where('role_id','=',2)->latest()->paginate(10);
        return $this->responseData('users', $user);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required','unique:users,name'],
            'email' => ['required', 'email', 'unique:users,email'],
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

    public function userBySearch(Request $request){

        $key = $request->key;
        $userlist = User::where('name','LIKE',"%{$key}%")->orWhere('email','LIKE',"%{$key}%")->get();
        if(count($userlist) == 0){
            return $this->responseError('Not Found Any search');
        }

        if($userlist){
            return $this->responseData('users',$userlist);
        }

    }
}
