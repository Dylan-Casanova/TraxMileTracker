<?php

namespace Tests\Feature;

use App\Cars;
use App\User;
use Database\Factories\CarsFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddDeleteCarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function addNewCar()
    {
        Passport::actingAs(UserFactory::new()->create());
        $carData = [
            "year" => 2017,
            "make" => "Dodge",
            "model" => "Challenger R/T 392"
        ];
        $response = $this->post('/api/add-car', $carData);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Car created successfully']);

        $this->assertDatabaseHas('cars', $carData);
    }

    /** @test */
    public function deleteRegisteredCar()
    {
        Passport::actingAs(UserFactory::new()->create());
        $userId = auth()->user()->id;
        $carData1 = ['user_id' => $userId, "year" => 2015, "make" => "Dodge", "model" => "Viper"];
        $car1 = Cars::create($carData1)->toArray();
        $this->delete('/api/delete-car/' . $car1['id']);
        self::assertDatabaseMissing('cars', ['id' => $car1['id']]);
    }
}
