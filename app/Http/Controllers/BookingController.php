<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function booking(Request $request)
    {
        $rules = [
            'flight_from' => 'required',
            'flight_from_id' => 'required|exists:flights,id',
            'flight_from_date' => 'required|date_format:Y-m-d',
            'flight_back_id' => 'required_with:flight_back|exists:flights,id',
            'flight_back_date' => 'required_with:flight_back|date_format:Y-m-d',
        ];

        $data = $request->input();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ],
            ], 422);
        }

        $bookingData = [
            'flight_from' => $request->flight_from_id,
            'date_from' => $request->flight_from_date,
            'code' => Str::upper(Str::random(5)),
        ];

        if ($request->has('flight_back')) {
            $bookingData['flight_back'] = $request->flight_back_id;
            $bookingData['date_back'] = $request->flight_back_date;
        }
    
        $booking = Booking::create($bookingData);


        $result = [
            'data' => [
                'code' => $booking->code,
            ],
        ];

        return response()->json($result, 201);
    }
}
