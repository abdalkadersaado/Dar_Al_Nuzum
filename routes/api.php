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

