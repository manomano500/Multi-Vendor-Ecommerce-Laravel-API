<?php

namespace App\Models;

use App\Events\OrderProductUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class   OrderProduct extends Pivot
{
    use HasFactory;
    protected $table = 'order_product';
public $timestamps = false;
public $incrementing = true;

    protected $dispatchesEvents = [
        'updated' => OrderProductUpdated::class,
    ];



    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'store_id', 'status','variations' ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

//    public function store()
//    {
//        return $this->belongsTo(Store::class, 'store_id', 'id');
//    }

    public function store()
    {
        // Ensure the product relationship is loaded and then return the store relationship
        return $this->product->store();
    }

    public function getProductStoreId()
    {
        return $this->product ? $this->product->store_id : null;
    }


public function variations()
{
    $variations = json_decode($this->variations, true);
    return $variations;



}



}
