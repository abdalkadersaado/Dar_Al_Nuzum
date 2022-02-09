<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\imageTrait;
use App\Http\Traits\responseApiTrait;
use App\Models\Team;

class TeamController extends Controller
{
    use imageTrait, responseApiTrait;

    public function __construct()
    {
        $this->middleware('Admin')->except('index','show');
    }

    public function index()
    {
        $teams = Team::get();
        return $this->responseData('teams', $teams, 'Teams selected Successfully.');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'image' => 'required|image',
            'profession' => 'required',
        ]);


        $path = $this->store_image_file2($request->image, 'attachments/team');

        $team = Team::create([
            'name' => $request->name,
            'image' => $path,
            'profession' => $request->profession
        ]);
        return $this->responseSuccess('Added Successfully.');
    }

    public function update(Request $request, $team)
    {

        $team = Team::find($team);

        if (!$team) {
            return $this->responseError('Not Found Page.', 404);
        }

        $request->validate([
            'name' => ['required'],
            'image' => 'required',
            'profession' => 'required',
        ]);

        $img = $team->image;
        if ($img) {
            unlink($img);
        }

        $path =  $this->store_image_file2($request->image, 'attachments/team');

        $team->update([
            'name' => $request->name,
            'image' => $path,
            'profession' => $request->profession
        ]);

        return $this->responseSuccess(__('Updated Successfully'));
    }
    public function show($team)
    {
        $team = Team::find($team);
        if ($team) {
            return response()->json($team);
        }
        if (!$team) {
            return $this->responseError(__('Not Found Page'), 404);
        }
    }


    public function destroy($Team)
    {
        $team = Team::find($Team);
        if (!$team) {
            return $this->responseError(__('Not Found Page'), 404);
        }
        $image = $team->image;

        if ($image) {
            unlink($image);
        }

        $team->delete();
        return $this->responseSuccess(__('Deleted Successfully'));
    }
}
