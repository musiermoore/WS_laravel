<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passengers extends Model
{
    protected $fillable = [ 'first_name', 'last_name', 'birth_date', 'document_number'];
}
