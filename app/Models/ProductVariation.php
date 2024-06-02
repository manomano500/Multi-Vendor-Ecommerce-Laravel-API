<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariation extends Pivot
{

    protected $table = 'product_variations';

//    protected $fillable = ['product_id', 'variation_id'];



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

//    public static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($productVariation) {
//            $productVariation->quantity = 0;
//        });
//    }

}
