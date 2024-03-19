<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VenueControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_list_of_venues()
    {
        $response = $this->get('/api/venue');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => ['id', 'name', 'address', 'created_at', 'updated_at'],
        ]);
    }

    /** @test */
    public function it_returns_all_venues()
    {
        Venue::factory()->count(3)->create();
    
        $response = $this->get('/api/venue');
    
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_returns_a_specific_venue()
    {
        $venue = Venue::factory()->create();
    
        $response = $this->get('/api/venue/' . $venue->id);
    
        $response->assertStatus(200);
        $response->assertJson(
            json_decode(json_encode([
                [
                    'id' => $venue->id,
                    'name' => $venue->name,
                    'address' => $venue->address,
                ]
            ]), true)
        );
    }

    /** @test */
    public function it_stores_a_new_venue()
    {
        $data = [
            'name' => 'Test Venue',
            'address' => '123 Test Street',
        ];

        $response = $this->post('/api/venue', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('venues', $data);
    }

    /** @test */
    public function it_updates_a_specific_venue()
    {
        $venue = Venue::factory()->create();

        $updatedData = [
            'name' => 'Updated Venue Name',
            'address' => 'Updated Venue Address',
        ];

        $response = $this->put('/api/venue/' . $venue->id, $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('venues', $updatedData);
    }

    /** @test */
    public function it_deletes_a_specific_venue()
    {
        $venue = Venue::factory()->create();

        $response = $this->delete('/api/venue/' . $venue->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('venues', ['id' => $venue->id]);
    }

    /** @test */
    public function it_handles_error_when_showing_non_existing_venue()
    {
        $nonExistentId = 9999;

        $response = $this->get('/api/venue/' . $nonExistentId);

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Venue not found',
        ]);
    }

    /** @test */
    public function it_handles_error_when_updating_non_existing_venue()
    {
        $nonExistentId = 9999;

        $updatedData = [
            'name' => 'Updated Venue Name',
            'address' => 'Updated Venue Address',
        ];

        $response = $this->put('/api/venue/' . $nonExistentId, $updatedData);

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Venue not found',
        ]);
    }

    /** @test */
    public function it_handles_error_when_deleting_non_existing_venue()
    {
        $nonExistentId = 9999;

        $response = $this->delete('/api/venue/' . $nonExistentId);

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Venue not found',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing_venue()
    {
        $response = $this->json('POST','/api/venue', []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'address'
        ]
        ]);
    }

    /** @test */
    public function it_validates_max_length_of_name_and_address_when_storing_venue()
    {
        $data = [
            'name' => str_repeat('a', 256),
            'address' => str_repeat('a', 256),
        ];

        $response = $this->json('POST','/api/venue', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'address'
        ]
        ]);
    }

    
    /** @test */
    public function it_validates_required_fields_when_updating_venue()
    {
        $venue = Venue::factory()->create();

        $response = $this->json('PUT','/api/venue/' . $venue->id, []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'address'
        ]
        ]);
    }

}
