<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItems extends Model
{
    use HasFactory;

    protected $table = 'product_item';
    protected $fillable = [
       'product_id',
        'sku',
        'product_image',
        'qty_on_stock',
        'cost_price',
        'selling_price',
        'discount_percent',
        'count',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
       // 'product_id' => 'integer',
        'sku' => 'string',
        'product_image' => 'string',
        'qty_on_stock' => 'integer',
        'cost_price' => 'float',
        'selling_price' => 'float',
        'discount_percent' => 'float',
        'count' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
