<?php

use App\Http\Controllers\AirportController;
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

Route::middleware('auth:api')->get('/user',         [ UserController::class, 'info' ]);
Route::middleware('auth:api')->get('/user/booking', [ UserController::class, 'getBookingsForUser' ]);
Route::middleware('auth:api')->get('/user/logout',  [ UserController::class, 'logout' ]);

Route::post('register',             [ AuthController::class, 'register'           ])->name('register');
Route::post('login',                [ AuthController::class, 'login'              ])->name('login');
Route::get('airport',               [ AirportController::class, 'search'          ])->name('airport');
Route::get('flight',                [ FlightController::class,'search'            ])->name('flight');
Route::post('booking',              [ BookingController::class, 'booking'         ])->name('booking');
Route::get('booking/{code}',        [ BookingController::class, 'info'            ])->name('booking info');
Route::get('booking/{code}/seat',   [ BookingController::class, 'occupiedPlaces'  ])->name('booking-seat');
Route::patch('booking/{code}/seat', [ BookingController::class, 'choosePlace'     ])->name('booking-place');


