<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'store_id', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            OrderProduct::class,
            'store_order_id', // Foreign key on OrderProduct table...
            'id', // Foreign key on Product table...
            'id', // Local key on StoreOrder table...
            'product_id' // Local key on OrderProduct table...
        )->withPivot('quantity', 'price');
    }
}
