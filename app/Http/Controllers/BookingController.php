<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Http\Resources\PassengersResource;
use App\Models\Booking;
use App\Models\Passengers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

    /**
     * Getting information about booking by code
     *
     * @param $code
     *
     * @return BookingResource
     */
    public function info($code)
    {
        $booking = Booking::with('flightFrom', 'flightBack', 'passengers')
            ->where('code', $code)->first();

        $booking->flightFrom->setDate($booking->date_from);

        if (!empty($booking->flightBack)) {
            $booking->flightBack->setDate($booking->date_back);
        }

        return new BookingResource($booking);
    }

    /**
     * Getting information about occupied places by $flight_id and $date
     *
     * @param $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function occupiedPlaces($code)
    {
        $booking = Booking::where('code', $code)->first();

        $flightFrom = [
            'flight_id' => $booking->flight_from,
            'date' => $booking->date_from,
        ];

        $occupiedFrom = Booking::getOccupiedPlacesFrom($flightFrom['flight_id'], $flightFrom['date']);

        $flightBack = [
            'flight_id' => $booking->flight_back,
            'date' => $booking->date_back,
        ];

        $occupiedBack = Booking::getOccupiedPlacesBack($flightBack['flight_id'], $flightBack['date']);

        $result = [
            'data' => [
                'occupied_from' => $occupiedFrom,
                'occupied_back' => $occupiedBack,
            ],
        ];

        return response()->json($result, 200);
    }

    /**
     * @param $code
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function choosePlace($code, Request $request)
    {
        $rules = [
            'passenger' => 'required|integer',
            'seat'      => [
                'required',
                'regex:/^(1|2|3|4|5|6|7|8|9|10|11|12)([A-D])$/'
            ],
            'type'      => 'required|in:from,back'
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

        $booking = Booking::where('code', $code)->first();

        $passenger = Passengers::find($request->passenger);

        if (empty($passenger) || $passenger->booking_id != $booking->id) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Passenger does not apply to booking',
                ],
            ], 403);
        }

        if ($request->type === 'from') {
            $occupiedPlaces = Booking::getOccupiedPlacesFrom($booking->flight_from, $booking->date_from);
        } elseif ($request->type === 'back') {
            $occupiedPlaces = Booking::getOccupiedPlacesBack($booking->flight_back, $booking->date_back);
        }
        $occupiedPlaces = Arr::pluck($occupiedPlaces, 'place');

        if (in_array($request->seat, $occupiedPlaces)) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Seat is occupied',
                ],
            ], 422);
        }

        if($request->type === 'from') {
            $passenger->place_from = $request->seat;
        } else {
            $passenger->place_back = $request->seat;
        }
        $passenger->save();

        return response()->json([
            'data' => new PassengersResource($passenger),
        ]);
    }
}
