<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start' => $this->faker->dateTimeBetween('now', '+30 days'),
            'end' => $this->faker->dateTimeBetween('now', '+60 days'),
            'venue_id' => function () {
                return \App\Models\Venue::inRandomOrder()->first()->id;
            },
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Event $event) {
            $categories = Category::inRandomOrder()->limit(rand(1, 3))->get();
            $event->categories()->attach($categories);
        });
    }
}
