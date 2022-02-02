<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\imageTrait;
use App\Models\Gallary;
use Intervention\Image\Facades\Image as interImage;

class GallaryController extends Controller
{
    use imageTrait;

    public function __construct()
    {

        $this->middleware('JWT')->except(['index', 'show']);
    }

    public function index()
    {

        return Gallary::all();
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
        return response()->json([
            'message' => 'Photo added successfully.',
            'status' => 200
        ]);
    }

    public function update(Request $request, Gallary $gallary)
    {
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
        return response()->json([
            'message' => 'updatde has done successfully'
        ]);
    }

    public function change_status(Request $request, $gallary)
    {

        $status_photo = Gallary::findOrFail($gallary);
        if ($status_photo) {
            $status_photo->update([
                'status' => $request->status,

            ]);
            return response()->json([
                'message' => ' status updated has done successfully.'
            ]);
        }
    }

    public function show(Gallary $gallary)
    {

        return response()->json($gallary);
    }

    public function destroy(Gallary $gallary)
    {

        $image = $gallary->image;
        if ($image) {
            unlink($image);
        }

        $gallary->delete();

        return response()->json([
            'message' => 'photo deleted successfully.',
            'status' => 200
        ]);
    }
}
