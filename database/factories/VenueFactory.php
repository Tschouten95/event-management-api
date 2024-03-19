<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            // Define other attributes and their fake data
        ];
    }
}
