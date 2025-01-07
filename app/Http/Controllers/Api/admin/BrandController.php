<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use Exception;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::all();
            // return response()->json($brands, 200);
            return BrandResource::collection($brands);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'all fields are required', 
                'error' => $validator->messages()
            ], 422);
        }
        
        try {
            $brand = Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                
            ]);
            return response()->json([
                'message' => 'Brand created successfully.',
                'data'    => new BrandResource($brand),
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create product.', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Brand $brand)
    {
        try {
            $brand = Brand::findOrFail($brand->id);
            return response()->json($brand, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch product.', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'all fields are required', 
                'error' => $validator->messages()
            ], 422);
        }

        try {
            $brand = Brand::findOrFail($brand->id);
            $brand->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);
            return response()->json([
                'message' => 'Brand updated successfully.',
                'data'    => new BrandResource($brand),
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update product.', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            $brand = Brand::findOrFail($brand->id);
            $brand->delete();
            return response()->json([
                'message' => 'Brand deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete product.', 'message' => $e->getMessage()], 500);
        }
    }
}
