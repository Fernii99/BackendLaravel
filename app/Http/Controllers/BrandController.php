<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Display a listing of the brands.
    public function index()
    {
        $brands = Brand::all();
        return response()->json($brands);
    }

    // Store a newly created brand in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand = Brand::create($validated);

        return response()->json([
            'message' => 'Brand created successfully!',
            'data' => $brand,
        ], 201);
    }

    // Display the specified brand.
    public function show($id)
    {
        $brands = Brand::whereHas('concessionaires', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();
        return response()->json($brands);
    }

    // Update the specified brand in storage.
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand->update($validated);

        return response()->json([
            'message' => 'Brand updated successfully!',
            'data' => $brand,
        ]);
    }

    // Remove the specified brand from storage.
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully!',
        ]);
    }
}
