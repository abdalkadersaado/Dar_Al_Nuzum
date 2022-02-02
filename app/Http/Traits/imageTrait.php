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

    # to save  image
    public function store_image_file2($image,$pathImage)
    {
        $file = $image;
        // dd($file);
        $extension = $file->getClientOriginalExtension();
        $temp_name  = uniqid(10) . time();
        $image = interImage::make($file);
        // $path = $file->storeAs('attachments/gallary/', $file->getClientOriginalName(), 'upload_attachments');
        $path = $file->storeAs($pathImage, $file->getClientOriginalName(), 'upload_attachments');

        // $path = 'image/gallary/image_gallary_' . $temp_name . '.' . $extension;
        $image->save($path);

        return $path;
    }

}
