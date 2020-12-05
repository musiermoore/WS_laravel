<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function info()
    {
        return response()->json(new UserResource(Auth::user()));
    }

    public function logout()
    {
        Auth::user()->api_token = null;
        Auth::user()->save();
    }

    public function getBookingsForUser()
    {
        $document_number = Auth::user()->document_number;

        $bookings = Booking::whereHas('passengers', function ($query) use ($document_number) {
            $query->where('document_number', $document_number);
        })->get();

        $bookings = $bookings->map(function (Booking $booking) {
            $booking->flightFrom->setDate($booking->date_from);

            if($booking->flightBack) {
                $booking->flightBack->setDate($booking->date_back);
            }

            return $booking;
        });

        return response()->json([
            'data' => [
                'items' => BookingResource::collection($bookings),
            ],
        ]);
    }
}
