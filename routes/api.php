<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\RiderLogController;
use App\Http\Controllers\RiderDeliveryStatusController;

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
Route::post('/profile.update.status','AjaxController@_checkProfileUpdateStatus')->name('profile.update.status');
Route::post('/update.delivery.status','RiderDeliveryStatusController@_setStatus')
                            ->name('update.delivery.status');
Route::post('/update.movement.status','RiderDeliveryStatusController@_setMovementStatus')
                            ->name('update.movement.status');

Route::post('/get.status','RiderDeliveryStatusController@_getStatus')->name('get.status');
Route::post('/get.location','RiderDeliveryStatusController@_getMovementStatus')->name('get.location');


Route::post('/set.log','RiderLogController@_setLog')->name('set.log');
Route::post('/get.log','RiderLogController@_getLog')->name('get.log');




Route::post('/create.order','OrderController@_createOrder')->name('create.order');
Route::post('/order.details','OrderController@_getOrderDetails')->name('order.details');
Route::post('/order.status.array','OrderController@_getDeliveryStatusArray')->name('order.status.array');
Route::post('/cancel.order','OrderController@_cancelOrder');
Route::post('/cancel.order.customer','OrderController@_cancelOrderByCustomer');


