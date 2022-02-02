<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


#################### Authentication #####################

Route::group(['middleware' => 'api','prefix' => 'auth' , 'namespace'=> 'Api'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');

    Route::get('userProfile', 'AuthController@userProfile');
    Route::post('update/profile','ProfileController@update');

});
########################################################################

#################### Manage Users #######################################

Route::group(['middleware' => 'api','prefix' => 'users' , 'namespace'=> 'Api'], function ($router) {

    Route::get('/all','UserController@index');
    Route::post('/create','UserController@store');
    Route::put('/update/{id}','UserController@update');
    Route::delete('/delete/{id}','UserController@delete');

});
########################################################################

############################ Manage Service####################################
Route::group(['namespace'=>'Api','middleware' => 'api','prefix'=>'services'],function($router){

    Route::get('index','ServiceController@index');
    Route::post('store','ServiceController@store');
    Route::get('show/{id}','ServiceController@show');
    Route::post('/update/{service}','ServiceController@update');
    Route::delete('/{service}','ServiceController@destroy');
});
########################################################################

############################ Manage Testimonial####################################

Route::group(['prefix'=>'testimonial','namespace'=>'Api','middleware'=>'api'],function(){

    Route::apiResource('testimonials','TestimonialController');
    // Route::post('store','TestimonialController@store');
 });

########################################################################

################################ Manage Gallary #########################

Route::group(['namespace'=>'Api','middleware'=>'api','prefix'=>'gallary'],function(){

    Route::post('store','GallaryController@store');
    Route::get('index','GallaryController@index');
    Route::get('show/photo/{gallary}','GallaryController@show');
    Route::post('update/{gallary}','GallaryController@update');
    Route::put('status/{gallary}','GallaryController@change_status');
    Route::delete('/{gallary}','GallaryController@destroy');
});

########################################################################

