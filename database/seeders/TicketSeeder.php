<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = \App\Models\Event::all();

        $events->each(function ($event) {
            \App\Models\Ticket::factory(5)->create([
                'event_id' => $event->id,
            ]);
        });
    }
}
