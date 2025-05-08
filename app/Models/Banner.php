<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    // Fields that can be mass-assigned
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'image_thumbnail',
        'price',
        'status',
        'sort_order',
    ];

    // Type casting for attributes
    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];
}
