<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarsRequest;
use App\Cars;
use Illuminate\Support\Facades\DB;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Cars::all();

        return response()->json([
            'data' => $cars
        ]);
    }

    /**
     * Store a newly created car in storage.
     *
     * @param  \App\Http\Requests\CarsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarsRequest $request)
    {
        $validatedData = $request->validated();

        $car = Cars::create($validatedData);

        return response()->json([
            'data' => $car,
            'message' => 'Car created successfully'
        ], 201);
    }

    /**
     * Display the specified car.
     *
     * @param  \App\Cars  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Cars $car)
    {
        $totalMiles = DB::table('trips')
            ->where('car_id', $car->id)
            ->sum('miles');
        $tripCount = DB::table('trips')
            ->where('car_id', $car->id)
            ->count();
        $car->total_miles = $totalMiles ?? 0;
        $car->trip_count = $tripCount ?? 0;
        return response()->json([
            'data' => $car,
        ]);
    }

    /**
     * Remove the specified car from storage.
     *
     * @param  \App\Cars  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cars $car)
    {
        $car->delete();

        return response()->json([
            'message' => 'Car deleted successfully',
        ], 200);
    }
}
