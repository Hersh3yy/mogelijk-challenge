<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the property.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Property::query();

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        $properties = $query->get();

        return response()->json($properties);
    }

    /**
     * Store a newly created property.
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
     * Display the specified property
     */
    public function show(string $id)
    {
        $property = Property::findOrFail($id);

        return response()->json($property);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(null, 204);
    }
}
