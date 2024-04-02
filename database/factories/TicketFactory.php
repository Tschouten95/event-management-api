<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => 1,
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'quantity_available' => $this->faker->numberBetween(1, 100),
        ];
    }
}
