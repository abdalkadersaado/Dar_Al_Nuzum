<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\imageTrait;
use App\Http\Traits\responseApiTrait;
use App\Models\Gallary;
use Intervention\Image\Facades\Image as interImage;

class GallaryController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware(['JWT', 'Admin'])->except(['index', 'show']);
    }

    public function index()
    {

        $galleries = Gallary::all();
        return $this->responseData('galleries', $galleries, 'Galleries Selected Successfully.');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'image' => ['required']
        ]);

        $path = $this->store_image_file2($request->image, 'attachments/gallary/');

        $Photo = Gallary::create([
            'name' => $request->name,
            'image' => $path,
        ]);
        return $this->responseSuccess('Photo Added Successfully.');
    }

    public function update(Request $request, $gallary)
    {
        $gallary = Gallary::find($gallary);

        if (!$gallary) {
            return $this->responseError(' Not Found page', 404);
        }

        $request->validate([
            'name' => ['required'],
            'image' => ['required']
        ]);

        $img = $gallary->image;
        if ($img) {
            unlink($img);
        }

        $path = $this->store_image_file2($request->image, 'attachments/gallary/');

        $gallary->update([
            'name' => $request->name,
            'image' => $path,
        ]);
        return $this->responseSuccess('Updatde has done Successfully');
    }

    public function change_status(Request $request, $gallary)
    {

        $status_photo = Gallary::findOrFail($gallary);
        if ($status_photo) {
            $status_photo->update([
                'status' => $request->status,

            ]);
            return $this->responseSuccess('Status Updated has done Successfully.');
        }
    }

    public function show($gallary)
    {

        $gallary = Gallary::find($gallary);

        if (!$gallary) {
            return $this->responseError(' Not Found page', 404);
        }
        if ($gallary) {
            return $this->responseData('gallery', $gallary, 'Photo Selected Successfully.');
        }
    }

    public function destroy($gallary)
    {
        $gallary = Gallary::find($gallary);

        if (!$gallary) {
            return $this->responseError(' Not Found page', 404);
        }

        $image = $gallary->image;
        if ($image) {
            unlink($image);
        }

        $gallary->delete();
        return $this->responseSuccess('Photo Deleted Successfully');
    }
}
