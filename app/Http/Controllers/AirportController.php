<?php

namespace App\Http\Controllers;

use App\Http\Resources\AirportResource;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        if (!$request->filled('query')) {
            return response()->json([
                'error' => [
                    "code"      => 400,
                    "message"   => "Bad Request",
                ]
            ], 400);
        }

        $query = $request->input('query');

        $items = Airport::search($query);

        return response()->json([
           'data' => [
               'items' => AirportResource::collection($items),
           ],
        ], 200);
    }
}
