<?php

namespace App\Http\Controllers\Api;

use App\Models\Profile;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Traits\imageTrait;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;
use Intervention\Image\Facades\Image as interImage;

class ProfileController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware(['auth', 'Admin','ChangeLocal']);
    }

    public function update(Request $request , Profile $profile)
    {
        $request->validate([
            'description' => 'sometimes|required',
            'country' => 'sometimes|required',
            'phone' => ['sometimes', 'numeric'],
            'image' => 'sometimes|required'
        ]);

        $user = auth()->user();

        if ($request->image) {

            $image = $user->profile->image;

        if ($image) {
            unlink($image);
        }

            $path = $this->store_image_file2($request->image, 'attachments/profile');

            $profile_info = $user->profile()->updateOrCreate(['user_id'=>$user->id],[
                'image' => $path,
                'description' => $request->description,
                'country' => $request->country,
                'phone' => $request->phone,
            ]);
        } else {
            $profile_info = $user->profile()->update([

                'description' => $request->description,
                'country' => $request->country,
                'phone' => $request->phone,
            ]);
        }
        return $this->responseSuccess(__('Profile Updated Successfully'));
    }
}
