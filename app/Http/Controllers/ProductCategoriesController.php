<?php

namespace App\Http\Controllers;

use App\Http\Requests\Storeproduct_categoriesRequest;
use App\Http\Requests\Updateproduct_categoriesRequest;
use App\Models\product_categories;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storeproduct_categoriesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(product_categories $product_categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateproduct_categoriesRequest $request, product_categories $product_categories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product_categories $product_categories)
    {
        //
    }
}
