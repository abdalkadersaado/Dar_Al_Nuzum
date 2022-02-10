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
        $this->middleware(['JWT', 'Admin','ChangeLocal'])->except('index', 'show','blogBySearch');
        $this->middleware('ChangeLocal');
    }

    public function index()
    {

        $blogs = Blog::Selection()->latest()->paginate(10);

        return $this->responseData('blogs', $blogs, 'Blogs Selected Successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => ['required'],
            'title_ar' => ['required'],
            'description_en' => ['required'],
            'description_ar' => ['required'],
            'image' => ['required']
        ]);

        $path =  $this->store_image_file2($request->image, 'attachments/blog');
        $request->user()->blogs()->create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
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
            'title_en' => ['required'],
            'title_ar' => ['required'],
            'description_en' => ['required'],
            'description_ar' => ['required'],
            'image' => ['required']
        ]);
        //update image from directory
        $img = $blog->image;
        if ($img) {
            unlink($img);
        }

        $path =  $this->store_image_file2($request->image, 'attachments/blog');

        $blog->update([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'image' => $path,
        ]);
        return $this->responseSuccess('Updated successfully');
    }


    public function show($id)
    {
        $blog = Blog::Selection()->find($id);

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

    public function blogBySearch(Request $request){

        $key = $request->key;
        $bloglist = Blog::where('title_ar','LIKE',"%{$key}%")
                        ->orWhere('title_en','LIKE',"%{$key}%")
                        ->Selection()->get();

        if(count($bloglist) == 0){
            return $this->responseError('Not Found Any search');
        }

        if($bloglist){
            return $this->responseData('users',$bloglist);
        }

    }
}
