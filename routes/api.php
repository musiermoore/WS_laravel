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

Route::group([], function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('airport', 'AirportController@search')->name('airport');
    Route::get('flight', 'FlightController@search')->name('flight');
    Route::post('booking', 'BookingController@booking')->name('booking');
    Route::get('booking/{code}', 'BookingController@info')->name('booking info');
    Route::get('booking/{code}/seat', 'BookingController@occupiedPlaces')->name('booking seat');
    Route::patch('booking/{code}/seat', 'BookingController@choosePlace')->name('booking choose place');
});

