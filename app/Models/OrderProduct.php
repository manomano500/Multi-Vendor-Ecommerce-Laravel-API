<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class   OrderProduct extends Model
{
    use HasFactory;
    protected $table = 'order_products';


    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'store_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function storeOrder()
    {
        return $this->belongsTo(StoreOrder::class, 'order_id', 'order_id');
    }





}
