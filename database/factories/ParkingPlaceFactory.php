<?php

namespace Database\Factories;

use App\Models\ParkingPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParkingPlaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ParkingPlace::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => strtoupper(\Str::random(4)),
            'price' => $this->faker->randomFloat(2, 100, 600)
        ];
    }
}
