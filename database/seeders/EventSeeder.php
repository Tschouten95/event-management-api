<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTitles = [
            'Tech Conference 2024',
            'Music Festival',
            'Art Exhibition',
            'Business Summit',
            'Sports Championship',
            'Fashion Show',
            'Food Expo',
            'Film Festival',
            'Science Symposium',
            'Literature Conference',
            'Charity Gala',
            'Photography Exhibition',
            'Startup Pitch Competition',
            'Health & Wellness Expo',
            'Automobile Expo',
            'Design Conference',
            'Education Summit',
            'Gaming Convention',
            'Environmental Conference',
            'Culinary Workshop'
        ];

        foreach($eventTitles as $eventTitle) {
            Event::factory()->create(['title' => $eventTitle]);
        }
    }
}
