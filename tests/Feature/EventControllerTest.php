<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_list_of_events()
    {
        Venue::factory()->count(5)->create();
        Category::factory()->count(5)->create();
        Event::factory()->count(10)->create();
        
        $response = $this->get('/api/event');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => ['id', 'title', 'description', 'start', 'end', 'venue', 'categories'],
        ]);
    }

    /** @test */
    public function it_returns_all_events()
    {
        Venue::factory()->count(5)->create();
        Category::factory()->count(5)->create();
        Event::factory()->count(10)->create();
    
        $response = $this->get('/api/event');
    
        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    /** @test */
    public function it_returns_a_specific_event()
    {
        $venue = Venue::factory()->create([
            'name' => 'The Rosewood Retreat',
            'address' => "677 Goldner Pass Suite 588\nPort Otiliaport, TX 47219"
        ]);


        $category = Category::factory()->create([
            "name" => "Music"
        ]);

        $event = Event::factory()->create([
            'title' => 'Music Festival',
            'description' => 'Consequuntur dolor placeat alias consequatur. Iure quia sed animi non ab et. Est dolores est animi.',
            'start' => '2024-04-28',
            'end' => '2024-04-22',
            'venue_id' => $venue->id,
        ]);
        
        $response = $this->get('/api/event/' . $event->id);

        $event->categories()->attach($category->pluck('id'));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'start' => $event->start,
            'end' => $event->end,
            'venue' => [
                'name' => $venue->name,
                'address' => $venue->address,
            ],
            'categories' => $category->pluck('name')->all()
        ]);
    }


    /** @test */
    public function it_stores_a_new_event()
    {
        $venue = Venue::factory()->create([
            'name' => 'The Rosewood Retreat',
            'address' => "677 Goldner Pass Suite 588\nPort Otiliaport, TX 47219"
        ]);

        $category = Category::factory()->create();

        $data = [
            'title' => 'Music Festival',
            'description' => 'Consequuntur dolor placeat alias consequatur. Iure quia sed animi non ab et. Est dolores est animi.',
            'start' => '2024-04-22',
            'end' => '2024-04-28',
            'venue_id' => $venue->id,
            'category_ids' => [$category->id]
        ];

        $response = $this->post('/api/event', $data);

        $response->assertStatus(201);

        unset($data['category_ids']);

        $this->assertDatabaseHas('events', $data);

        $event = Event::where('title', $data['title'])->first();
        $this->assertDatabaseHas('category_event', [
            'event_id' => $event->id,
            'category_id' => $category->id
        ]);
    }
}
