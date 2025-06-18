<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    /** @use HasFactory<\Database\Factories\ProductsFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'product_image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'category_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'product_image' => 'string',

    ];

    public function subCategory()
    {
        return $this->belongsTo(ProductCategories::class, 'category_id');
    }

    public function productItem(){
        return $this->hasOne(ProductItems::class, 'product_id', 'id');
    }

    
}
