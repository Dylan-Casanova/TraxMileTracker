<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TripsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetTrips()
    {
        // Create some test data
        $car1 = DB::table('cars')->insertGetId([
            'make' => 'Ford',
            'model' => 'Mustang',
            'year' => 2022,
        ]);
        $car2 = DB::table('cars')->insertGetId([
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2021,
        ]);
        DB::table('trips')->insert([
            'date' => '2023-01-15',
            'miles' => 100,
            'car_id' => $car1,
        ]);
        DB::table('trips')->insert([
            'date' => '2023-02-01',
            'miles' => 150,
            'car_id' => $car2,
        ]);
        DB::table('trips')->insert([
            'date' => '2023-02-10',
            'miles' => 120,
            'car_id' => $car1,
        ]);

        // Mock the authentication
        $user = $this->mock(Authenticatable::class);
        $this->actingAs($user, 'api'); // Use the 'api' guard for authentication

        // Call the index function in TripsController
        $response = $this->get('/api/get-trips');

        // Assert the response status is successful
        $response->assertStatus(200);

        // Assert the response structure and data
        $expectedStructure = [
            'trips' => [
                '*' => [
                    'id',
                    'date',
                    'miles',
                    'car' => [
                        'id',
                        'make',
                        'model',
                        'year',
                    ],
                    'total',
                ],
            ],
        ];
        $response->assertJsonStructure($expectedStructure);
    }
}
