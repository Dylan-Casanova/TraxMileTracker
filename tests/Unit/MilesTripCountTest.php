<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CarsController;
use Carbon\Carbon;
use App\Cars;


class CarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testCarShowWithTrips()
    {
        // Create a car
        $carId = DB::table('cars')->insertGetId([
            // Set the car attributes
            'make' => 'Ford',
            'model' => 'Mustang',
            'year' => 2022
        ]);

        // Retrieve the car model from the database
        $car = Cars::find($carId);

        // Create three trips for the car
        $trips = [
            ['car_id' => $carId, 'miles' => 100, 'date' => Carbon::now()->subDays(3)],
            ['car_id' => $carId, 'miles' => 150, 'date' => Carbon::now()->subDays(2)],
            ['car_id' => $carId, 'miles' => 120, 'date' => Carbon::now()->subDays(1)],
        ];
        DB::table('trips')->insert($trips);

        // Instantiate the CarsController
        $controller = new CarsController();

        // Get the car information
        $response = $controller->show($car);

        // Assert the response is a JSON response
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);

        // Assert the response status is successful
        $this->assertEquals(200, $response->getStatusCode());

        // Assert the car has the correct total miles
        $this->assertEquals(370, $response->getData()->data->total_miles);

        // Assert the car has the correct trip count
        $this->assertEquals(3, $response->getData()->data->trip_count);
    }
}
