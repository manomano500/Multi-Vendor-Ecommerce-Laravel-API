<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_total', 'status', 'city', 'shipping_address', 'payment_method','payment_status'];

protected $hidden = ['created_at', 'updated_at'];

    public function scopeWithStoreProducts($query, $storeId)
    {
        return $query->whereHas('products', function ($query) use ($storeId) {
            $query->where('order_product.store_id', $storeId);
        })->with(['products' => function ($query) use ($storeId) {
            $query->where('order_product.store_id', $storeId);
        }]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->using(OrderProduct::class)
            ->withPivot('quantity', 'price', 'variations');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variations()
    {
return $this->hasManyThrough(Variation::class, Product::class, 'id', 'product_id', 'id', 'id');}





    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class , 'order_id' , 'id');
    }




public static function booted()
{
 static::creating(function (Order $order) {
     $order->status = 'pending';


 });


}

    public function getCreatedAttAttribute()
    {
        return $this->created_at->format('Y-m-d H:i');
    }

    // Specify the attributes that should be appended to the model's array form
    protected $appends = ['created_att'];


}
