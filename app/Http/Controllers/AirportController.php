<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirportResource;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function search()
    {

        $query = $_GET['query'] ?? '';

        return response()->json([
           'data' => [
               'items' => AirportResource::collection(
                  Airport::where('name', 'like', '%' . $query . '%')
                      ->orWhere('iata', 'like', '%' . $query . '%')
                      ->orWhere('city', 'like', '%' . $query . '%')->get()
               ),
           ],
        ]);
    }
}
