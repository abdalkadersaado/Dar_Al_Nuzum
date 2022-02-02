<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image as interImage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {

        $request->validate([
            'description' => 'sometimes|required',
            'country' => 'sometimes|required',
            'phone' =>['sometimes','integer'],
            'image' => 'sometimes|required'
        ]);

        $user = auth()->user();

        if ($request->image) {

            $path = $this->store_image_file($request->image);
            // $result = $request->file('image')->store('apiDocs');

            $profile_info = $user->profile()->updateOrCreate([

                'user_id' => $user->id,
            ], [

                'image' => $path
            ]);
        } elseif ($request->description) {

            $profile_info = $user->profile()->updateOrCreate([

                'user_id' => $user->id,
            ], [
                'description' => $request->description,

            ]);
        } elseif ($request->country) {

            $profile_info = $user->profile()->updateOrCreate([

                'user_id' => $user->id,
            ], [
                'country' => $request->country,

            ]);
        }
        elseif ($request->phone) {

            $profile_info = $user->profile()->updateOrCreate([

                'user_id' => $user->id,
            ], [
                'phone' => $request->phone,

            ]);
        }
        else {
            $profile_info = $user->profile()->updateOrCreate([

                'user_id' => $user->id,
            ], [
                'description' => $request->description,
                'country' => $request->country,
                'image' => $request->image,
                'phone' => $request->phone,
            ]);
        }

        return response()->json($profile_info);
    }


    # to save  image
    public function store_image_file($image)
    {
        $file = $image;
        // dd($file);
        $extension = $file->getClientOriginalExtension();
        $temp_name  = uniqid(10) . time();
        $image = interImage::make($file);
        $path = 'uploads/profile/image_profile_' . $temp_name . '.' . $extension;
        $image->save($path);

        return $path;
    }
}
