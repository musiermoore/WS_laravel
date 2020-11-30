<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    public function airportFrom()
    {
        return $this->hasOne(Airport::class, 'id', 'from_id');
    }

    public function airportTo()
    {
        return $this->hasOne(Airport::class, 'id', 'to_id');
    }

    /**
     * @param $from
     * @param $to
     * @param $date
     *
     * @return mixed
     */
    public static function getFlightInformation($from, $to, $date) {
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

    /**
     * @param $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Accessor for Time (from)
     *
     * @param $time
     *
     * @return string
     */
    public function getTimeFromAttribute($time)
    {
        $newTime = Carbon::parse($time)->format('H:i');

        return $newTime;
    }

    /**
     * Accessor for Time (to)
     *
     * @param $time
     *
     * @return string
     */
    public function getTimeToAttribute($time)
    {
        $newTime = Carbon::parse($time)->format('H:i');

        return $newTime;
    }
}
