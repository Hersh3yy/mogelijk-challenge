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

    /**
     * Test property retrieval with invalid ID.
     */
    public function test_cannot_get_property_with_invalid_id(): void
    {
        $response = $this->getJson('/api/properties/999');

        $response->assertStatus(404);
    }

    /**
     * Test property listing with valid price filter.
     */
    public function test_can_filter_properties_by_valid_price_range(): void
    {
        // Create test properties
        Property::create([
            'name' => 'Cheap House',
            'address' => 'Test Street 1',
            'price' => 100000
        ]);
        Property::create([
            'name' => 'Expensive House',
            'address' => 'Test Street 2',
            'price' => 500000
        ]);

        $response = $this->getJson('/api/properties?price_min=200000&price_max=600000');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Expensive House');
    }

    /**
     * Test property listing with invalid price filter.
     */
    public function test_cannot_filter_properties_with_invalid_price_range(): void
    {
        $response = $this->getJson('/api/properties?price_min=1000&price_max=500');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price_max']);
    }
}
