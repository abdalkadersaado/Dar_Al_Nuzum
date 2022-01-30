<?php

namespace App\Http\Traits;

use Intervention\Image\Facades\Image as interImage;


trait imageTrait {

    public function store_image_file($image)
    {
        $file = $image;
        // dd($file);
        $extension = $file->getClientOriginalExtension();
        $temp_name  = uniqid(10) . time();
        $image = interImage::make($file);
        $path = 'image/services/image_services_' . $temp_name . '.' . $extension;
        $image->save($path);

        return $path;
    }

}
