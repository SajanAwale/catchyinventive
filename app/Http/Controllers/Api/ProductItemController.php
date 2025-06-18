<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductItemController extends Controller
{
    public function store($product_id)
    {
        try {
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to store price of a Product.', 'message' => $e->getmessage()], 500);
        }
    }
}
    