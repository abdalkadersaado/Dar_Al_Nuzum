<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['login', 'signup']]);
    }

    //

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required',
            'password' => 'required',

        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or Password Invalid'], 401);
        }


        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {


        // $profile = $user->Profile::with('user:id,name,email')->get();

        $user= auth()->user();

        $user_info = $user->load(['profile'=>function ($q){
            $q->select('description','country','image','phone','user_id');
        } ]);

        return response()->json([
            'user_info'=>$user_info,
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */



    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }



    public function signup(Request $request)
    {

        $validateData = $request->validate([
            'email' => 'required|unique:users|max:255',
            'name' => 'required',
            'password' => 'required|min:6'

        ]);

        $data = array();
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['role_id'] = $request->role_id;
        DB::table('users')->insert($data);

        // return response()->json($data);
         return $this->login($request);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'user_id' => auth()->user()->id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 100,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'role_id' => auth()->user()->role_id,
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


}
