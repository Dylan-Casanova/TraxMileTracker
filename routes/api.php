<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\TripsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


//////////////////////////////////////////////////////////////////////////
/// Mock Endpoints To Be Replaced With RESTful API.
/// - API implementation needs to return data in the format seen below.
/// - Post data will be in the format seen below.
/// - /resource/assets/traxAPI.js will have to be updated to align with
///   the API implementation
//////////////////////////////////////////////////////////////////////////

// endpoint to get all cars for the logged in user

Route::get('/get-cars', [CarsController::class, 'index'])->middleware('auth:api');

// endpoint to add a new car.

Route::post('/add-car', [CarsController::class, 'store'])->middleware('auth:api');

Route::get('/get-car/{car}', [CarsController::class, 'show'])->middleware('auth:api');
Route::delete('/delete-car/{car}', [CarsController::class, 'destroy'])->middleware('auth:api');

// endpoint to get a car with the given id

// Route::get('/get-car/{id}', [CarsController::class, 'show'])->middleware('auth:api');

// endpoint to delete a car with a given id

// Route::delete('/delete-car/{id}', [CarsController::class, 'destroy'])->middleware('auth:api');

// endpoint to get the trips for the logged in user

Route::get('/get-trips', [TripsController::class, 'index'])->middleware('auth:api');

// endpoint to add a new trip.

Route::post('/add-trip', [TripsController::class, 'store'])->middleware('auth:api');
