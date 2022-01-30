<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image as interImage;

use App\Http\Traits\imageTrait;
use GuzzleHttp\Psr7\Message;

class ServiceController extends Controller
{
    use imageTrait;

    public function index()
    {

        return Service::all();
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'description' => ['required'],

        ]);

        if ($request->image) {

            $path = $this->store_image_file($request->image);

            $service = new Service();
            $service->name = $request->name;
            $service->description = $request->description;
            $service->image = $path;
            $service->save();
        } else {
            $service = new Service();
            $service->name = $request->name;
            $service->description = $request->description;

            $service->save();
        }
        return response()->json([
            'message' => 'Service was added Successfully.',
            'status' => 201
        ]);
    }

    public function update(Request $request, Service $service)
    {

        $request->validate([
            'name' => ['required'],
            'description' => ['required'],

        ]);

        if ($request->image) {
            $path = $this->store_image_file($request->image);

            $service->update([
                'name' => $request->name,
                'description' => $request->name,
                'image' => $path
            ]);
        } else {
            $service->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }
        return response()->json([
            'message' => 'Service was Updated Successfully.',
            'status' => 200
        ]);
    }

    public function show($id)
    {

        $service = Service::findOrFail($id);

        return response()->json($service, 200);
    }

    public function destroy(Service $service)
    {

        $service->delete();

        return response()->json([
            'status' => '200',
            'message' => 'service was Deleted successfully.'
        ]);
    }
}
