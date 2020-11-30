<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlightResource;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $rules = [
            'from'       => 'required|exists:airports,iata',
            'to'         => 'required|exists:airports,iata',
            'date_from'  => 'required|date_format:Y-m-d',
            'date_back'  => 'date_format:Y-m-d',
//            'passengers' => 'int|between:1,8',
        ];


        $data = $request->input();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'error' => [
                    'code'      => 422,
                    'message'   => "Validation error",
                    'errors'    => $validator->errors(),
                ]
            ], 422);
        }

        $flightsTo = Flight::getFlightInformation($request->from, $request->to, $request->date_from);

        $flightsBack = collect();
        if($request->has('date_back')) {
            $flightsBack = Flight::getFlightInformation($request->to, $request->from, $request->date_back);
        }

        $result = [
          'data' => [
              "flights_to"   => FlightResource::collection($flightsTo),
              "flights_back" => FlightResource::collection($flightsBack),
          ],
        ];

        return response()->json($result);
    }
}
