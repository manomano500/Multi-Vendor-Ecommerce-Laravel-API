<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    protected $fillable = ['order_id', 'store_id', 'status'];

    public function scopeStoreOrderProducts($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }



    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'order_id');
    }


}
