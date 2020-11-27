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
