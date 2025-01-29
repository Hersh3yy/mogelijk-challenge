<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    /**
     * List and filter real estate properties.
     *
     * Returns a paginated list of properties with optional price range filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|gt:price_min',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Property::query();

        if ($request->has('price_min')) {
            $query->where('price', '>=', $validated['price_min']);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $validated['price_max']);
        }

        $perPage = $request->input('per_page', 15);
        $properties = $query->paginate($perPage);

        return response()->json($properties);
    }

    /**
     * Create a new property listing.
     *
     * Store a new real estate property with the required name, address, and price.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'address' => 'required|string',
            'price' => 'required|numeric|gt:0',
        ]);

        $property = Property::create($validated);

        return response()->json($property, 201);
    }

    /**
     * Get a specific property by ID.
     */
    public function show(string $id): JsonResponse
    {
        $property = Property::findOrFail($id);
        return response()->json($property);
    }

    /**
     * Update a property listing.
     *
     * Partially update a property's information. Only provided fields will be updated.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|min:3',
            'address' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|gt:0',
        ]);

        $property->update($validated);

        return response()->json($property);
    }

    /**
     * Delete a property listing.
     */
    public function destroy(string $id): JsonResponse
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(null, 204);
    }
}
