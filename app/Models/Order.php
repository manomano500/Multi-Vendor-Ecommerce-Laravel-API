<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_total', 'status', 'city', 'shipping_address'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity', 'price');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variations()
    {
return $this->hasManyThrough(Variation::class, Product::class, 'id', 'product_id', 'id', 'id');}



    public function orderProductVariations()
    {
        return $this->hasMany(OrderProductVariation::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
}





}
