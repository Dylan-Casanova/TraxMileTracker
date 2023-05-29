<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Cars;
use App\User;

class CarManagementTest extends TestCase
{
    use RefreshDatabase;

    public function testAddAndDeleteCar()
    {
        // Create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Create a new car
        $carData = [
            'make' => 'Ford',
            'model' => 'Mustang',
            'year' => 2022,
        ];
        $car = Cars::create($carData);

        // Assert the car was added to the database
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'make' => 'Ford',
            'model' => 'Mustang',
            'year' => 2022,
        ]);

        // Get the ID of the created car
        $carId = $car->id;

        // Delete the car
        $deleteResponse = $this->delete("/api/delete-car/$carId");

        // Assert the response status is successful and the car was deleted
        $deleteResponse->assertStatus(302);
        $this->assertDatabaseMissing('cars', ['id' => $carId]);
    }
}
