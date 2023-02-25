<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $fillable = [
        'product_variant_one', 'product_variant_two','product_variant_three', 
        'product_id', 'price', 'stock'
    ];
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
