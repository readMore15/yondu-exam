<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// view all
Route::get('users', 'Api\UsersController@index');

// get individual entry
Route::get('user/{id}', 'Api\UsersController@show');

// store/add user
Route::post('user', 'Api\UsersController@store');

// update user
Route::put('user', 'Api\UsersController@store');

// delete user
Route::delete('user/{id}', 'Api\UsersController@destroy');

// users multiple delete
Route::post('multipledelete', 'Api\UsersController@multiple');