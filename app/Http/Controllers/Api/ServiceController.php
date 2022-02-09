<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Traits\imageTrait;
use App\Http\Requests\storeService;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;

class ServiceController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware(['Admin','ChangeLocal'])->only(['update', 'destroy', 'store']);
        $this->middleware('ChangeLocal')->only(['store','index','show']);
    }

    public function index()
    {
        // $this->authorize('viewAny', Service::class);

        $services = Service::Selection()->get();
        if (count($services) > 0) {
            return  $this->responseData('service', $services);
        } else {
            return $this->responseError('no found any saved service yet .', 404);
        }
    }
    public function store(storeService $request)
    {
        if ($request->image) {
            $path = $this->store_image_file2($request->image,'attachments/services');

            $service = new Service();
            $service->name_en = $request->name_en;
            $service->name_ar = $request->name_ar;
            $service->description_en = $request->description_en;
            $service->description_ar = $request->description_ar;
            $service->image = $path;
            $service->save();
        } else {
            $service = new Service();
            $service->name_ar = $request->name_ar;
            $service->name_en = $request->name_en;
            $service->description = $request->description;

            $service->save();
        }
        return $this->responseSuccess(__('Added successfully'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->responseError(__('Not Found page'), 404);
        }

        $request->validate([
            'name_en' => 'required', 'unique:services,name_en' . $service->id,
            'name_ar'=>'required','unique:services,name_ar,except,id',
            'description_en' => 'required',

        ]);

        if ($request->image) {
            $path = $this->store_image_file($request->image);

            $service->update([
                'name_en' => $request->name_en,
                'name_ar'=>$request->name_ar,
                'description_en' => $request->description_en,
                'description_ar'=>$request->description_ar,
                'image' => $path
            ]);
        } else {
            $service->update([
                'name_en' => $request->name_en,
                'name_ar'=>$request->name_ar,
                'description_en' => $request->description_en,
                'description_ar'=>$request->description_ar,
            ]);
        }
        return $this->responseSuccess(__('Updated Successfully'));
    }

    public function show($id)
    {
        $service = Service::Selection()->find($id);
        if (!$service) {
            return $this->responseError(__('Not Found Page'), 404);
        }

        return $this->responseData('service', $service);
    }

    public function destroy($service)
    {
        $service = Service::find($service);

        if (!$service) {
            return $this->responseError(__('Not Found Page'), 404);
        }

        $image = $service->image;

        if ($image) {
            unlink($image);
        }

        $service->delete();
        return $this->responseSuccess(__('Deleted Successfully'));
    }
}
