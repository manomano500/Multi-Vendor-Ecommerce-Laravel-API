<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variation_id',
        'order_product_id'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

}
