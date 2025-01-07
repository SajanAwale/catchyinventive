<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table    = 'brands';
    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'status'];

    // Automatically set the sort_order field
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            $brand->sort_order = Brand::max('sort_order') + 1;
        });
    }
}
