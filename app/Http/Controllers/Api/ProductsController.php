<?php

namespace App\Http\Controllers\Api;

use App\Models\Products;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Products::with('category.parent')->get();
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

            if ($request->hasFile('image')) {
                // $this->uploadImage($request->product_image);
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg'
                ]);

                $image_path = $request->file('image');
                $fileName = time() . '_' . $image_path->getClientOriginalName();
                $filePath = 'product/' . $fileName;
                // Store the file in storage file Path
                Storage::disk('public')->putFileAs('product', $image_path, $fileName);
                // Set image path in the database
                $products->product_image  = $filePath;
            }
            $products->save();

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
     * Upload Image function
     */
    private function uploadImage(Request $request)
    {
        try {
        } catch (\Exception $e) {
            return response()->json(['error' => 'Image Upload Failed.', 'message' => $e->getMessage()], 500);
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

            if ($request->image != null) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg'
                ]);

                $image_path = $request->file('image');
                $fileName = time() . '_' . $image_path->getClientOriginalName();
                $filePath = 'product/' . $fileName;
                // Store the file in storage file Path
                Storage::disk('public')->putFileAs('product', $image_path, $fileName);
                // Set image path in the database
                $productsInfo  = $filePath;
            } else {
                $productsInfo = $products->product_image;
            }

            // Update the information from the request
            if ($products) {
                $products = Products::where('id', $id)
                    ->update([
                        'category_id' => $request->category_id,
                        'name' => $request->name,
                        'description' => $request->description,
                        'product_image' => $productsInfo,
                        'updated_at' => Carbon::now()
                    ]);
                return response()->json([
                    'message' => 'Product updated successfully.',
                    'data' => $products,
                    'status' => 200,
                ]);
            } else {
                return response()->json(['error' => 'Product not found.'], 404);
            }
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
            $products->forceDelete();
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
