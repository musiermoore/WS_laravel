<?php

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

use App\Http\Resources\AirportResource;
use App\Models\Airport;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
//    Route::get('airport', function () {
//        return AirportResource::collection(Airport::all());
//        return Airport::find(1);
//    });
    Route::get('airport', 'AirportController@search')->name('airport');
});
