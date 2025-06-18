<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategories;
use App\Models\Products;

class ProductListController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function showAllCategories()
    {
        try {
            $categories = ProductCategories::with(
                'childList',
                // 'brand:id,name,slug,image,status,description,created_at'
            )
                ->withTrashed()
                ->whereNull('parent_category_id')
                ->get();
            return response()->json([
                'message' => 'Category fetch successfully.',
                'data' => $categories,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function showAllProducts()
    {
        try {
            $products = Products::with('subCategory.parentCategory', 'productItem:id,product_id,count,discount_percent,qty_on_stock,sku,selling_price,created_at,updated_at')
                ->get();
            return response()->json([
                'message' => 'All products fetch successfully.',
                'data' => $products,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
