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

    public function __construct()
    {

        $this->middleware('JWT');
    }

    public static function index()
    {
        // $this->authorize('viewAny', Service::class);

        $user = auth()->user();

        if ($user->role_id == 1) {

            $services = Service::all();
            if (count($services) > 0) {
                return $services;
            } else {
                return response()->json([
                    'message' => 'no found any saved service .'
                ]);
            }
        }
        return response()->json([
            'message' => 'This action is unauthorized.',
            'status' => 403
        ]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'unique:services,name'],
            'description' => ['required'],

        ]);

        $user = auth()->user();

        if ($user->role_id == 1) {

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
                'status' => 200
            ]);
        }
        return response()->json([
            'message' => 'This action is unauthorized.',
            'status' => 403
        ]);
    }

    public function update(Request $request, Service $service)
    {

        $request->validate([
            'name' => 'required', 'unique:services,name' . $service->id,
            'description' => 'required',

        ]);

        $user = auth()->user();

        if ($user->role_id == 1) {

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
        return response()->json([
            'message' => 'This action is unauthorized.',
            'status' => 403
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        if ($user->role_id == 1) {

            $service = Service::findOrFail($id);

            return response()->json($service, 200);
        }
        return response()->json([
            'message' => 'This action is unauthorized.',
            'status' => 403
        ]);
    }

    public function destroy(Service $service)
    {
        $user = auth()->user();

        if ($user->role_id == 1) {

            $image = $service->image;

            if ($image) {
                unlink($image);
            }

            $service->delete();

            return response()->json([
                'status' => '200',
                'message' => 'service was Deleted successfully.'
            ]);
        }
        return response()->json([
            'message' => 'This action is unauthorized.',
            'status' => 403
        ]);
    }
}
