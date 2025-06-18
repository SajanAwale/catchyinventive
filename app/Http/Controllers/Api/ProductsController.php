<?php

namespace App\Http\Controllers\Api;

use App\Models\Products;
use App\Http\Controllers\Controller;
use App\Models\ProductItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Products::with('subCategory.parentCategory', 'productItem')->get();
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
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Removed manual SKU from validation
            'qty_in_stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Create product
            $product = Products::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'created_at' => now(),
            ]);
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image');
                $fileName = time() . '_' . $imagePath->getClientOriginalName();
                $folderPath = 'product';
                $filePath = $folderPath . '/' . $fileName;

                // Check if the folder exists, and if not create it
                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }
                // Store the file
                Storage::disk('public')->putFileAs($folderPath, $imagePath, $fileName);
                // Save the file path to the model
                $product->product_image = $filePath;
                $product->save();
            }

            // Auto-generate unique SKU
            $sku = 'SKU-' . strtoupper(uniqid());

            // Create Product Item
            $productItem = ProductItems::create([
                'product_id' => $product->id,
                'sku' => $sku,
                'qty_on_stock' => $validated['qty_in_stock'],
                'cost_price' => $validated['cost_price'],
                'selling_price' => $validated['selling_price'],
                'discount_percent' => $validated['discount_percentage'] ?? 0,
                'count' => (int) 1,
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully.',
                'data' => [
                    'product' => $product,
                    'item' => $productItem,
                ],
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create product.',
                'message' => $e->getMessage()
            ], 500);
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
     * @param mixed $product_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show($product_id)
    {
        try {
            $products = Products::with('subCategory.parentCategory', 'productItem')
                ->where('id', $product_id)
                ->findOrFail($product_id);

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
                $productsInfo = $filePath;
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
                $productItem = ProductItems::where('product_id', $id)->update([
                    'qty_on_stock' => $request->qty_in_stock,
                    'cost_price' => $request->cost_price,
                    'selling_price' => $request->selling_price,
                    'discount_percent' => $request->discount_percentage ?? 0,
                    'count' => (int) $request->count + 1,
                    'updated_at' => Carbon::now(),
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
            $productItem = ProductItems::where('product_id', $id)->forceDelete();
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

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        if (!$keyword) {
            return response()->json(['error' => 'Keyword is required.'], 400);
        }

        $products = Products::where('name', 'LIKE', "%{$keyword}%")->get();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No products found matching the keyword.',
                'products' => [],
                'count' => 0,
            ], 404); // Optional: Use 200 if you prefer
        }

        return response()->json([
            'products' => $products,
            'count' => $products->count(),
        ],200);
    }
}
