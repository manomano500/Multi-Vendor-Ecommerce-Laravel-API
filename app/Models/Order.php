<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_total', 'status',  'shipping_address', 'payment_method','payment_status'];

protected $hidden = ['created_at', 'updated_at'];

    public function scopeWithStoreProducts($query, $storeId)
    {
        return $query->whereHas('products', function ($query) use ($storeId) {
            $query->where('order_product.store_id', $storeId);
        })->with(['products' => function ($query) use ($storeId) {
            $query->where('order_product.store_id', $storeId);
        }]);
    }


    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'status' => 'pending',
            'payment_status' => null,
            'payment_method' => null,
            'limit' => null,
            'page' => null,
        ], $filters);

$builder->when($options['status'], function ($query, $status) {
            $query->where('status', $status);
        });

$builder->when($options['payment_status'], function ($query, $payment_status) {
            $query->where('payment_status', $payment_status);
        });

$builder->when($options['payment_method'], function ($query, $payment_method) {
            $query->where('payment_method', $payment_method);
        });

$builder->when($options['limit'], function ($query, $limit) {
            $query->limit($limit);
        });

$builder->when($options['page'], function ($query, $page) {
            $query->paginate($page);
        });




    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->using(OrderProduct::class)
            ->withPivot('id','quantity', 'price', 'status','variations');
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
        return $this->created_at?->format('Y-m-d H:i');
    }

    // Specify the attributes that should be appended to the model's array form
    protected $appends = ['created_att'];


}
