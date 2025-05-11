<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategories;

class ProductListController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function showAllProducts()
    {
        try {
            $categories = ProductCategories::with(
                'childList:id,parent_category_id,category_name,brand_id',
                'brand:id,name,slug,image,status,description,created_at'
            )
                ->withTrashed()
                ->whereNull('parent_category_id')
                ->get();
            return response()->json([
                'message' => 'Category fetch successfully.',
                'data'    => $categories,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
