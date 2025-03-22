<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Storeproduct_categoriesRequest;
use App\Http\Requests\Updateproduct_categoriesRequest;
use App\Models\ProductCategories;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        try {
            $categories = ProductCategories::with('parent')->get();
            return response()->json([
                'message' => 'Category fetch successfully.',
                'data'    => $categories,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        try {
            // dd(1);
            $request->validate([
                'category_name' => 'required|string|max:255|unique:product_categories,category_name',
                'parent_category_id' => 'nullable|exists:product_categories,id',
                'brand_id' => 'required|exists:brands,id',
            ]);

            $category = ProductCategories::create([
                'parent_category_id' => $request->parent_category_id ?? null,
                'category_name' => $request->category_name,
                'brand_id' => $request->brand_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Category store successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        try {
            $category = ProductCategories::with('children')->findOrFail($id);

            return response()->json([
                'message' => 'Category fetch successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'parent_category_id' => 'nullable|exists:product_categories,id',
                'brand_id' => 'required|exists:brands,id',
            ]);

            $category = ProductCategories::findOrFail($id);
            $category->update([
                'parent_category_id' => $request->parent_category_id ?? null,
                'category_name' => $request->category_name,
                'brand_id' => $request->brand_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Category updated successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        try {
            $category = ProductCategories::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully.',
                'data'    => $category,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
