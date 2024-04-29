<?php

namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_returns_a_list_of_categories()
    {
        $response = $this->get('/api/category');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => ['id', 'name', 'created_at', 'updated_at'],
        ]);
    }

    /** @test */
     public function it_returns_all_categories()
    {
        Category::factory()->count(3)->create();
        
        $response = $this->get('/api/category');
        
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_returns_a_specific_category()
    {
        $category = Category::factory()->create();
    
        $response = $this->get('/api/category/' . $category->id);
    
        $response->assertStatus(200);
        $response->assertJson(
            json_decode(json_encode([
                [
                    'id' => $category->id,
                    'name' => $category->name,
                ]
            ]), true)
        );
    }

    /** @test */
    public function it_stores_a_new_category()
    {
        $data = [
            'name' => 'Test Category',
        ];

        $response = $this->post('/api/category', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', $data);
    }

    /** @test */
    public function it_updates_a_specific_category()
    {
        $category = Category::factory()->create();

        $updatedData = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->put('/api/category/' . $category->id, $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', $updatedData);
    }

    /** @test */
    public function it_deletes_a_specific_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete('/api/category/' . $category->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_handles_error_when_showing_non_existing_category()
    {
        $nonExistentId = 9999;

        $response = $this->get('/api/category/' . $nonExistentId);

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Category not found',
        ]);
    }

    /** @test */
    public function it_handles_error_when_updating_non_existing_category()
    {
        $nonExistentId = 9999;

        $updatedData = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->put('/api/category/' . $nonExistentId, $updatedData);

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Category not found',
        ]);
    }

    /** @test */
    public function it_handles_error_when_deleting_non_existing_category()
    {
        $nonExistentId = 9999;

        $response = $this->delete('/api/category/' . $nonExistentId);

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Category not found',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing_category()
    {
        $response = $this->json('POST','/api/category', []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
        ]
        ]);
    }

    /** @test */
    public function it_validates_max_length_of_name_and_address_when_storing_category()
    {
        $data = [
            'name' => str_repeat('a', 256),
        ];

        $response = $this->json('POST','/api/category', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
        ]
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_updating_category()
    {
        $category = Category::factory()->create();

        $response = $this->json('PUT','/api/category/' . $category->id, []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
        ]
        ]);
    }

    
    /** @test */
    public function it_validates_max_length_of_name_and_address_when_updating_category()
    {

        $category = Category::factory()->create();

        $data = [
            'name' => str_repeat('a', 256),
        ];
    
        $response = $this->json('PUT','/api/category/' . $category->id, $data);
    
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
        ]
        ]);
    }
}
