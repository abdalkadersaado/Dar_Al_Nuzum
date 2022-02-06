<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\imageTrait;
use App\Http\Traits\responseApiTrait;

class ServiceController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware(['JWT', 'Admin'])->only(['update', 'destroy', 'store']);
    }

    public function index()
    {
        // $this->authorize('viewAny', Service::class);

        $services = Service::all();
        if (count($services) > 0) {
            return  $this->responseData('service', $services, 'Services Selected Successfully.');
        } else {
            return $this->responseError('no found any saved service yet .', 404);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:services,name'],
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
        return $this->responseSuccess('Service was Added Successfully.');
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->responseError('Not Found page', 404);
        }

        $request->validate([
            'name' => 'required', 'unique:services,name' . $service->id,
            'description' => 'required',

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
        return $this->responseSuccess('Service was Updated Successfully.');
    }

    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return $this->responseError('Not Found page', 404);
        }
        $service = Service::findOrFail($id);
        return $this->responseData('service', $service, 'Service Selected Successfully');
    }

    public function destroy($service)
    {
        $service = Service::find($service);

        if (!$service) {
            return $this->responseError('Not Found page', 404);
        }

        $image = $service->image;

        if ($image) {
            unlink($image);
        }

        $service->delete();
        return $this->responseSuccess('Service was Deleted Successfully');
    }
}
