<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Brand;


class ProductCategories extends Model
{
    /** @use HasFactory<\Database\Factories\ProductCategoriesFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_categories';

    protected $fillable = ['parent_category_id', 'brand_id', 'category_name'];

    protected $casts = [
        'parent_category_id' => 'integer',
        'brand_id' => 'integer',
        'category_name' => 'string',
    ];

    // protected $append = ['product_price'];

    // public function getProductPriceAttribute(){

    //     // Assuming you want the first product item's selling price
    //     $product = $this->products()->with('prodcutItem')->first();
    //     return $product?->productItem[0]->selling_price ?? null;
    // }

    // Parent category (Self Join)
    public function parentCategory()
    {
        return $this->belongsTo(ProductCategories::class, 'parent_category_id');
    }

    // Child categories
    public function children()
    {
        return $this->hasMany(ProductCategories::class, 'parent_category_id')->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function childList()
    {
        return $this->hasMany(ProductCategories::class, 'parent_category_id');
    }

    // public function products()
    // {
    //     return $this->hasMany(Products::class, 'category_id', 'id');
    // }
}
