<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProfileController extends Controller
{
    public function update(Request $request)
    {

        $request->validate([
            'description' => 'sometimes|required',
            'country' => 'sometimes|required',
            'image' => ['sometimes','image']
        ]);

        $user = auth()->user();

        $profile_info = $user->profile()->updateOrCreate([

            'user_id' => $user->id,
        ], $request->all());

        return response()->json($profile_info);
    }

}
