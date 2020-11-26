<?php

namespace App\Models;

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

    public function setDate($date)
    {
        $this->date = $date;
    }
}
