<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'product_image',
        'qty_on_stock',
        'cost_price',
        'selling_price',
        'discount_percentage'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'product_image' => 'string',
        'image_series' => 'integer',
    ];
}
