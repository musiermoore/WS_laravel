<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passengers extends Model
{
    protected $fillable = [ 'first_name', 'last_name', 'birth_date', 'document_number'];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'id', 'booking_id');
    }
}
