<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


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

    Route::group(['middleware' => 'api', 'prefix' => 'auth', 'namespace' => 'Api'], function ($router) {

        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');

        Route::get('userProfile', 'AuthController@userProfile');
        Route::post('update/profile', 'ProfileController@update');
    });
    ########################################################################

$locale = request()->segment(2);
Route::group(['prefix' => LaravelLocalization::setlocale($locale),'middleware' => 'api','namespace' => 'Api'], function()
{
    #################### Manage Users #######################################

    Route::group(['prefix' => 'users'], function ($router) {

        Route::get('/all', 'UserController@index');
        Route::post('/create', 'UserController@store');
        Route::put('/update/{id}', 'UserController@update');
        Route::delete('/delete/{id}', 'UserController@delete');
        // Search Route
        Route::get('/search/{key}','UserController@userBySearch');
    });
    ########################################################################

    ############################ Manage Service####################################
    Route::group(['prefix' => 'services'], function ($router) {

        Route::get('index', 'ServiceController@index');
        Route::post('store', 'ServiceController@store');
        Route::get('show/{id}', 'ServiceController@show');
        Route::post('/update/{service}', 'ServiceController@update');
        Route::delete('/{service}', 'ServiceController@destroy');
        Route::get('search/{key}','ServiceController@serviceBySearch');

    });
    ########################################################################

    ############################ Manage Testimonial####################################
    Route::apiResource('testimonials', 'TestimonialController');
    // Route::post('store','TestimonialController@store');

    ########################################################################

    ################################ Manage Gallary #########################

    Route::group(['prefix' => 'gallary'], function () {

        Route::post('store', 'GallaryController@store');
        Route::get('index', 'GallaryController@index');
        Route::get('show/photo/{gallary}', 'GallaryController@show');
        Route::post('update/{gallary}', 'GallaryController@update');
        Route::put('status/{gallary}', 'GallaryController@change_status');
        Route::delete('/{gallary}', 'GallaryController@destroy');
    });

    ########################################################################

    ################################ Manage Blog ###########################
    Route::apiResource('blogs', 'BlogController')->except('update');

    Route::post('blogs/{blog}', 'BlogController@update');
    // Search Route
    Route::get('blogs/search/{key}','BlogController@blogBySearch');
    ########################################################################

    ################################ Manage Blog ###########################


    Route::apiResource('Teams', 'TeamController')->except('update');

    Route::post('Teams/{team}', 'TeamController@update');
    ########################################################################


    ############################ Contact ####################################

    Route::apiResource('contacts', 'ContactController');
    Route::put('contacts/seen/{id}', 'ContactController@seen_message');
    Route::get('contacts/search/{key}','ContactController@contactBySearch');


    Route::get('allLanguages',function(){
        $langs= LaravelLocalization::getLocalesOrder();
        $current_lang = LaravelLocalization::getCurrentLocale();
        $locale = request()->segment(2);
        $set_locale = LaravelLocalization::setlocale($locale);
        return response()->json([
            'languages' =>array_keys($langs),
            'Current' => $current_lang,
            'set_locale'=>$set_locale
        ]);
    ########################################################################
});
    ########### when occur  Failure -> Not found page #####################

    Route::fallback(function(){
        return response()->json([
            'message'=> __('Not Found Page'),
            'status'=>404
        ],404);
    })->name('api.fallback');
    });
    ######################################################################


