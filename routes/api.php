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

/** 
 * *http://127.0.0.1:8000/api/register
 * @param body: first_name, last_name, email, store_id, active, username, password, password_confirmation
 */ 
Route::post('register', 'StaffController@register');

/**
 * *http://127.0.0.1:8000/api/login
 * @param body: username, password
 */
Route::post('login', 'StaffController@login');



Route::group(['middleware' => 'jwt.verify'], function(){
    /**
     * *http://127.0.0.1:8000/api/logout
     * @param body: token
     */
    Route::post('logout', 'StaffController@logout');

    /**
     * *
     * @param url: store_id
     * @param body: token, first_name, last_name, email, store_id, active, username, password, password_confirmation
     */
    Route::post('register_staff/{store_id}', 'StaffController@register_staff');
});