<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = [
        'value',
        'attribute_id',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variations');
    }

    public function orderProducts()
    {
        return $this->belongsToMany(OrderProduct::class, 'order_product_variation');
    }
}
