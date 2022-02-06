<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use App\Http\Traits\imageTrait;
use App\Http\Traits\responseApiTrait;
use Intervention\Image\Facades\Image as interImage;

class ProfileController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware(['auth', 'Admin']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'description' => 'sometimes|required',
            'country' => 'sometimes|required',
            'phone' => ['sometimes', 'integer'],
            'image' => 'sometimes|required'
        ]);

        $user = auth()->user();

        if ($request->image) {

            $path = $this->store_image_file2($request->image, 'attachments/profile');

            $profile_info = $user->profile()->update([
                'image' => $path
            ]);
        } else {
            $profile_info = $user->profile()->update([

                'description' => $request->description,
                'country' => $request->country,
                'image' => $request->image,
                'phone' => $request->phone,
            ]);
        }
        return $this->responseSuccess('Profile Selected Successfully.');
    }
}
