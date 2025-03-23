<?php

namespace App\Http\Controllers\Api;

use App\Models\Products;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Products::orderBy('id', 'desc')->get();
            return response()->json([
                'message' => 'Product fetch successfully.',
                'data' => $products,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'category_id' => 'required',
                'name' => 'required',
            ]);

            $products = Products::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'created_at' => Carbon::now(),
            ]);
            return response()->json([
                'message' => 'Product created successfully.',
                'data' => $products,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($product_id)
    {
        try {
            $products = Products::with('category')->findOrFail($product_id);
            return response()->json([
                'message' => 'Product fetch successfully.',
                'data' => $products,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = $request->validate([
                'category_id' => 'required',
                'name' => 'required',
            ]);
            $products = Products::findOrFail($id);

            $products->category_id = $request->category_id;
            $products->name = $request->name;
            $products->description = $request->description;
            $products->updated_at = Carbon::now();

            return response()->json([
                'message' => 'Product updated successfully.',
                'data' => $products,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $products = Products::findOrFail($id);
            $products->delete();
            return response()->json([
                'message' => 'Product delete successfully',
                'data' => $products,
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch products.', 'message' => $e->getMessage()], 500);
        }
    }
}
