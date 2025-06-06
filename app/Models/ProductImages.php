<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_image',
        'image_series',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'product_image' => 'string',
        'image_series' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
