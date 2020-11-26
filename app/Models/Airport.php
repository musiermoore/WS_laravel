<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public static function search($query)
    {
        $items = Airport::where('name', 'like', '%' . $query . '%')
            ->orWhere('iata', 'like', '%' . $query . '%')
            ->orWhere('city', 'like', '%' . $query . '%')->get();

        return $items;
    }
}
