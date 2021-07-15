<?php

use App\Http\Controllers\StoreController;
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
Route::get('/test', function () {
    return view('admin.index');
});
Route::post('show.rider.details','RiderController@showRider')->name('show.rider.details');
Route::get('store.destroy/{id}','StoreController@destroy')->name('store.destroy');

Route::resource('store','StoreController');
Route::resource('rider','RiderController');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
