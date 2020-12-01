<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\FlightResource;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function booking(Request $request)
    {
        $rules = [
            'flight_from_id'               => 'required|exists:flights,id',
            'flight_from_date'             => 'required|date_format:Y-m-d',
            'flight_back_id'               => 'required_with:flight_back_date|exists:flights,id',
            'flight_back_date'             => 'required_with:flight_back_id|date_format:Y-m-d',
            'passengers'                   => 'required|array|between:1,8',
            'passengers.*.first_name'      => 'required',
            'passengers.*.last_name'       => 'required',
            'passengers.*.birth_date'      => 'required|date_format:Y-m-d',
            'passengers.*.document_number' => 'required|numeric|digits:10',
        ];

        $data = $request->all();

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

        $booking->passengers()->createMany($request->passengers);

        $result = [
            'data' => [
                'code' => $booking->code,
            ],
        ];

        return response()->json($result, 201);
    }

    public function info()
    {
        dd(__METHOD__); // Затуп

        $flights = Booking::whereHas('flightFrom', function ($query) use ($code) {
            $query->where('code', $code);
        })->get();
        dd(new BookingResource($flights));



        $result = [
            'data' => [
                'code' => $code,
//                'cost' => Booking::getCost(),
                'flights' => new FlightResource($flights),
            ],
        ];

//        dd($result);

        return response()->json($result, 200);
    }
}
