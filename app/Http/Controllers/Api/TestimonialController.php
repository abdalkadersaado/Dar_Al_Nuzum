<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;
use App\Policies\TestimonialPolicy;

class TestimonialController extends Controller
{
    use responseApiTrait;

    public function __construct()
    {
        $this->middleware(['auth','Admin','ChangeLocal']);
    }

    public function index()
    {

        $data = Testimonial::with('user:id,name,email')->latest()->paginate(5);

        return $this->responseData('testimonial', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'description' => ['required']
        ]);


        $testimonial = $request->user()->testimonial()->create(

            ['description' => $request->description]
        );

        return $this->responseSuccess(__('Added successfully'));
    }

    public function update(Request $request, $testimonial)
    {

        $testimonial = Testimonial::find($testimonial);

        if (!$testimonial) {
            return $this->responseError(__('Not Found Page'), 404);
        }

        $this->authorize('update', $testimonial);

        $request->validate([
            'description' => ['required']
        ]);

        $testimonial->update([
            'description' => $request->description
        ]);
        return $this->responseSuccess(__('Updated Successfully'));
    }

    public function show($id)
    {
    }

    public function destroy($testimonial)
    {
        $testimonial = Testimonial::find($testimonial);

        if (!$testimonial) {
            return $this->responseError(__('Not Found Page'), 404);
        }

        $this->authorize('delete', $testimonial);

        $testimonial->delete();
        return $this->responseSuccess(__('Deleted successfully'));
    }
}
