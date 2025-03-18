<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variation extends Model
{
    /** @use HasFactory<\Database\Factories\VariationFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $casts = [
        'category_id' => 'integer',
        'name' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategories::class, 'category_id');
    }
}
