<?php

use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreRiderModelController;
use App\Http\Controllers\RiderController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('show.rider.details','RiderController@showRider')->name('show.rider.details');
Route::post('verify.rider','RiderController@_verifyRider')->name('verify.rider');
Route::post('block.rider','RiderController@_blockRider')->name('block.rider');
Route::post('update.rider.store','StoreRiderModelController@store')->name('update.rider.store');

// Route::get('store.destroy/{id}','StoreController@destroy')->name('store.destroy');

Route::resource('store','StoreController')->middleware('auth');
Route::resource('rider','RiderController')->middleware('auth');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
