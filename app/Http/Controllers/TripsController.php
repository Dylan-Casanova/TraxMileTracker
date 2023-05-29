<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripsRequest;
use App\Trips;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TripsController extends Controller
{

    public function index()
    {
        $trips = DB::table('trips')
            ->join('cars', 'trips.car_id', '=', 'cars.id')
            ->select(
                'trips.id',
                'trips.date',
                'trips.miles',
                'cars.id as car_id',
                'cars.make',
                'cars.model',
                'cars.year'
            )
            ->orderBy('trips.date', 'asc')
            ->get();

        $formattedTrips = [];
        foreach ($trips as $trip) {
            $formattedTrip = [
                'id' => $trip->id,
                'date' => Carbon::parse($trip->date)->format('m/d/Y'),
                'miles' => $trip->miles,
                'car' => [
                    'id' => $trip->car_id,
                    'make' => $trip->make,
                    'model' => $trip->model,
                    'year' => $trip->year
                ]
            ];

            $totalMiles = DB::table('trips')
                ->join('cars', 'trips.car_id', '=', 'cars.id')
                ->where('cars.id', $trip->car_id)
                ->where('trips.date', '<=', $trip->date)
                ->sum('trips.miles');

            $formattedTrip['total'] = $totalMiles;
            $formattedTrips[] = $formattedTrip;
        }

        return response()->json([
            'data' => $formattedTrips
        ]);
    }

    public function store(TripsRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['date'] = date('Y-m-d H:i:s', strtotime($validatedData['date'])); // Convert the date format

        $trip = Trips::create($validatedData);

        return response()->json([
            'trip' => $trip,
            'message' => 'Trip created successfully'
        ]);
    }
}
