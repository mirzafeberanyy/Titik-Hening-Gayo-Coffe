<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'weight_grams', 'grind_type', 
        'roast_level', 'sku', 'stock', 'price', 'is_active'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}