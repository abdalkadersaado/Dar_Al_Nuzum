<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Traits\imageTrait;
use App\Http\Controllers\Controller;
use App\Http\Traits\responseApiTrait;

class BlogController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware(['JWT', 'Admin'])->except('index', 'show');
    }

    public function index()
    {

        $blogs = Blog::latest()->paginate(10);

        return $this->responseData('blogs', $blogs, 'Blogs Selected Successfully');
    }

    public function store(Request $request)
    {
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
        return $this->responseSuccess('Added successfully');
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->responseError('Not Found Page', 404);
        }

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
        return $this->responseSuccess('Updated successfully');
    }


    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->responseError('Not Found Page', 404);
        }

        return $this->responseData('blog', $blog, 'blog selected successfully.');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->responseError('Not Found Page', 404);
        }


        //delete image from directory
        $img = $blog->image;
        if ($img) {
            unlink($img);
        }

        $blog->delete();
        return $this->responseSuccess('blog was Deleted successfully.');
    }
}
