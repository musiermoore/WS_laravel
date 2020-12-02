<?php

namespace App\Http\Resources;

use App\Models\Passengers;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->flightBack) {
            $flights = collect([$this->flightFrom, $this->flightBack]);
        } else {
            $flights = collect([$this->flightFrom]);
        }

        $resultFlights =  FlightResource::collection($flights);

        return [
            'code'       => $this->code,
            'cost'       => $this->getCost(),
            'flights'    => $resultFlights,
            'passengers' => PassengersResource::collection($this->passengers),
        ];
    }
}
