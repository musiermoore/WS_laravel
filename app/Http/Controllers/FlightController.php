<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlightResource;
use App\Models\Airport;
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
            'date1'      => 'required|date_format:Y-m-d',
            'date2'      => 'date_format:Y-m-d',
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

        $flightsTo = $this->getFlightInformation($request->from, $request->to, $request->date1);

        $flightsBack = [];
        if($request->has('date2')) {
            $flightsBack = $this->getFlightInformation($request->to, $request->from, $request->date2);
        }
        
        $result = [
          'data' => [
              "flights_to"   => FlightResource::collection($flightsTo),
              "flights_back" => FlightResource::collection($flightsBack), // error
          ],
        ];

        return response()->json($result);
    }

    /**
     * @param $from
     * @param $to
     * @param $date
     *
     * @return mixed
     */
    protected function getFlightInformation($from, $to, $date) {
        $flights = Flight::whereHas('airportFrom', function ($query) use ($from) {
            $query->where('iata', $from);
        })->whereHas('airportTo', function ($query) use ($to) {
            $query->where('iata', $to);
        })->get();

        $flights = $flights->map(function ($flight) use ($date) {
            $flight->setDate($date);

            return $flight;
        });

        return $flights;
    }
}
