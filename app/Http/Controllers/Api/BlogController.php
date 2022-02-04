<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Traits\imageTrait;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    use imageTrait;

    public function __construct()
    {
        $this->middleware('JWT')->except('index','show');
    }

    public function index()
    {

        return Blog::latest()->paginate(10);
    }

    public function store(Request $request)
    {

        $user = auth()->user();
        if ($user->role_id == 1) {
            $request->validate([
                'title' => ['required'],
                'description' => ['required'],
                'image' => ['required']
            ]);

            $path =  $this->store_image_file2($request->image, 'attachments/blog');
            $request->user()->blogs()->create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $path,
            ]);

            return response()->json([
                'message' => 'Added successfully',
                'status' => 200
            ]);
        }else{
            return response()->json([
                'message' => 'Action not Authorization.',
                'status' => 403
            ]);
        }
    }

    public function update(Request $request, Blog $blog)
    {
        $user = auth()->user();
        if ($user->role_id == 1) {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'image' => ['required']
        ]);
        //update image from directory
        $img = $blog->image;
        if ($img) {
            unlink($img);
        }


        $path =  $this->store_image_file2($request->image, 'attachments/blog');

        $blog->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $path,
        ]);

        return response()->json([
            'message' => 'Updated successfully',
            'status' => 200
        ]);
    }else{
        return response()->json([
            'message' => 'Action not Authorization.',
            'status' => 403
        ]);
    }

    }

    public function show(Blog $blog)
    {

        return response()->json($blog);
    }

    public function destroy(Blog $blog)
    {
        $user = auth()->user();
        if ($user->role_id == 1) {
        //delete image from directory
        $img = $blog->image;
        if ($img) {
            unlink($img);
        }

        $blog->delete();

        return response()->json([
            'message' => 'blog was Deleted successfully.',
            'status' => 200
        ]);
    }else{
            return response()->json([
                'message' => 'Action not Authorization.',
                'status' => 403
            ]);
        }
    }
}
