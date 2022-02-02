<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestimonialController extends Controller
{

    public function __construct(){
         $this->middleware('JWT');
    }

    public function index(){


       $data['testimonial'] = Testimonial::with('user:id,name,email')->latest()->paginate(5);


       return response()->json($data);


    }

    public function store(Request $request){

        $request->validate([
            'description'=>['required']
        ]);


        $testimonial = $request->user()->testimonial()->create(

           ['description'=> $request->description]
        );

        return response()->json([
            'message' => 'Testimonial was added successfully.',
            'status'=> 201
        ]);

        // return response()->json([
        //     'testimonial'=>$testimonial,
        //     'user' => $request->user()->profile
        // ]);
    }

    public function update(Request $request, Testimonial $testimonial){

        $this->authorize('update',$testimonial);

        $request->validate([
            'description'=>['required']
        ]);

        $testimonial->update([
            'description'=>$request->description
        ]);

        return response()->json([
            'message'=>'testimonial was updated successfully.',
            'status' => 200
        ]);

    }

    public function show($id){

    }

    public function destroy(Testimonial $testimonial)
    {

        $this->authorize('delete',$testimonial);

        $testimonial->delete();

        return response()->json([
            'message'=>'testimonial deleted successfully.'
        ]);
    }
}
