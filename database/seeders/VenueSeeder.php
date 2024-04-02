<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $venueNames = [
            'The Grand Ballroom',
            'The Civic Center',
            'The Garden Pavilion',
            'The Riverside Hotel',
            'The Plaza',
            'The Summit Conference Center',
            'The Lakeside Manor',
            'The Crystal Palace',
            'The Metropolitan Hall',
            'The Harborview Ballroom',
            'The Sunset Terrace',
            'The Parkside Lounge',
            'The Evergreen Club',
            'The Maplewood Hall',
            'The Oakwood Mansion',
            'The Lakeshore Pavilion',
            'The Pinecrest Manor',
            'The Sunset View',
            'The Rosewood Retreat',
            'The Orchid Garden'
        ];

        foreach ($venueNames as $venueName) {
            Venue::factory()->create([
                'name' => $venueName,
            ]);
        }
    }
}
