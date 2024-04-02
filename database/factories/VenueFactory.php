<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{

    public function definition()
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
        ];
    }
}
