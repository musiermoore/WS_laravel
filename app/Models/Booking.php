<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['flight_from', 'flight_back', 'date_from', 'date_back', 'code'];

    public function flightFrom()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_from');
    }

    public function flightBack()
    {
        return $this->hasOne(Flight::class, 'id', 'flight_back');
    }

    public function passengers()
    {
        return $this->hasMany(Passengers::class, 'booking_id', 'id');
    }

    public function getCost()
    {
        $cost = $this->flightFrom->cost;

        if ($this->flightBack) {
            $cost += $this->flightBack->cost;
        }

        $countPassengers = $this->passengers()->count();

        return $countPassengers * $cost;
    }

    public static function getOccupiedPlacesFrom($flight_id, $date)
    {
        $occupied = [];

        $passengersFrom = Passengers::whereHas('booking', function ($query) use ($flight_id, $date) {
            $query->where([
                'flight_from' => $flight_id,
                'date_from'   => $date,
            ]);
        })->get();

        $passengersFrom->map(function ($passenger) use (&$occupied) {
            if ($passenger->place_from ) {
                $occupied[] = [
                    'passenger_id' => $passenger->id,
                    'place'        => $passenger->place_from,
                ];
            }
        });

        return $occupied;
    }

    public static function getOccupiedPlacesBack($flight_id, $date)
    {
        $occupied = [];

        $passengersBack = Passengers::whereHas('booking', function ($query) use ($flight_id, $date) {
            $query->where([
                'flight_back' => $flight_id,
                'date_back'   => $date,
            ]);
        })->get();

        $passengersBack->map(function ($passenger) use (&$occupied) {
            if ($passenger->place_back) {
                $occupied[] = [
                    'passenger_id' => $passenger->id,
                    'place'        => $passenger->place_back,
                ];
            }
        });

        return $occupied;
    }
}
