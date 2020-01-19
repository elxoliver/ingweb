<?php

use App\Http\Controllers\StaffController;
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

Route::post('register', 'StaffController@register');
Route::post('login', 'StaffController@login');



Route::group(['middleware' => 'jwt.verify'], function(){
    Route::post('logout', 'StaffController@logout');
});