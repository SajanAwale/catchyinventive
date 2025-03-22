<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    /** @use HasFactory<\Database\Factories\ProductCategoriesFactory> */
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = ['parent_category_id', 'brand_id', 'category_name'];

    protected $casts = [
        'parent_category_id' => 'integer',
        'brand_id' => 'integer',
        'category_name' => 'string',
    ];

    // Parent category (Self Join)
    public function parent()
    {
        return $this->belongsTo(ProductCategories::class, 'parent_category_id');
    }

    // Child categories
    public function children()
    {
        return $this->hasMany(ProductCategories::class, 'parent_category_id');
    }
}
