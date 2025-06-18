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
        ];

        protected $casts = [
                // 'product_id' => 'integer',
                'sku' => 'string',
                'product_image' => 'string',
                'image_series' => 'integer',
                'count' => 'integer',
        ];

        protected $hidden = [
                'cost_price'
        ];

        public function product()
        {
                return $this->belongsTo(Products::class, 'product_id', 'id');
        }
}
