<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;

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
Route::get('/get.login','AjaxController@_getLoginStatus')->name('get.login');
Route::get('/create.user','AjaxController@_createUser')->name('create.user');
Route::get('/get.verified.status','AjaxController@_getVerifiedStatus')->name('get.verified.status');
Route::get('/update.profile','AjaxController@_updateProfile')->name('update.profile');

Route::post('/get.login','AjaxController@_getLoginStatus')->name('get.login');
Route::post('/create.user','AjaxController@_createUser')->name('create.user');
Route::post('/get.verified.status','AjaxController@_getVerifiedStatus')->name('get.verified.status');
Route::post('/update.profile','AjaxController@_updateProfile')->name('update.profile');

Route::post('/create.order','AjaxController@_createOrder')->name('create.order');

