<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use App\Cars;
use Illuminate\Http\Request;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|numeric',

        ]);

        $car = Cars::create($validatedData);

        return response()->json([
            'data' => $car,
            'message' => 'Car created successfully'
        ], 201);
    }

    /**
     * Display the specified car.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $car = DB::table('cars')
            ->where('id', $id)
            ->first();
        $totalMiles = DB::table('trips')
            ->where('car_id', $id)
            ->sum('miles');
        $tripCount = DB::table('trips')
            ->where('car_id', $id)
            ->count();
        $car->total_miles = $totalMiles ?? 0;
        $car->trip_count = $tripCount ?? 0;
        return response()->json([
            'data' => $car,
        ]);
    }

    public function destroy($id)
    {
        $car = Cars::findOrFail($id);
        $car->delete();

        return response()->json([
            'message' => 'Car deleted successfully',
        ], 200 );
    }
}
