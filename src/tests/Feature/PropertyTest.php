<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a property with missing required fields.
     */
    public function test_cannot_create_property_without_required_fields(): void
    {
        $response = $this->postJson('/api/properties', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'address', 'price']);
    }

    /**
     * Test creating a new property via POST endpoint
     */
    public function test_can_create_property(): void
    {
        $response = $this->postJson('/api/properties', [
            'name' => 'Test Villa',
            'address' => 'Test Street 123, Amsterdam',
            'price' => 1000000
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test Villa',
                'address' => 'Test Street 123, Amsterdam',
                'price' => 1000000
            ]);

        $this->assertDatabaseHas('properties', [
            'name' => 'Test Villa'
        ]);
    }

    /**
     * Test validation rules for property creation.
     */
    public function test_cannot_create_property_with_invalid_data(): void
    {
        $response = $this->postJson('/api/properties', [
            'name' => 'ab',  // too short
            'address' => '',  // empty
            'price' => -100,  // negative price
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'The name field must be at least 3 characters.',
                'address' => 'The address field is required.',
                'price' => 'The price field must be greater than 0.'
            ]);
    }
}
