<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status',  'shipping_address', 'payment_method','payment_status'];

protected $hidden = ['created_at', 'updated_at'];

/*    public function scopeWithStoreProducts($query, $storeId)
    {
        return $query->whereHas('products', function ($query) use ($storeId) {
            $query->where('order_product.store_id', $storeId);
        })->with(['products' => function ($query) use ($storeId) {
            $query->where('order_product.store_id', $storeId);
        }]);
    }*/

    public function scopeWithStoreProducts($query, $storeId)
    {
        return $query->whereHas('products.store', function ($query) use ($storeId) {
            $query->where('id', $storeId);
        })->with(['products.store' => function ($query) use ($storeId) {
            $query->where('id', $storeId);
        }]);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            "id"=>  null,
            'user_id' => null,
            'status' => null,
            'payment_status' => null,
            'payment_method' => null,
            'limit' => null,
            'created_at' => null,
            'page' => null,
        ], $filters);
        $builder->when($options['id'], function ($query, $id) {
            $query->where('id', $id);
        });
        $builder->when($options['user_id'], function ($query, $user_id) {
            $query->where('user_id', $user_id);
        });
$builder->when($options['status'], function ($query, $status) {
            $query->where('status', $status);
        });


$builder->when($options['created_at'], function ($query, $created_at) {
            $query->where('created_at', $created_at);
        });


$builder->when($options['limit'], function ($query, $limit) {
            $query->limit($limit);
        });

$builder->when($options['page'], function ($query, $page) {
            $query->paginate($page);
        });
$builder->when($options['payment_status'], function ($query, $payment_status) {
            $query->where('payment_status', $payment_status);
        });
$builder->when($options['payment_method'], function ($query, $payment_method) {
            $query->where('payment_method', $payment_method);
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
        return $this->created_at?->format('Y-m-d H:i:s');
    }
/*    public function getOrderTotalAttribute()
    {
        return $this->orderProducts->sum(function ($orderProduct) {
            return $orderProduct->quantity * $orderProduct->price;
        });
    }*/

    public function getOrderTotalAttribute()
    {
        return Cache::remember("order_total_{$this->id}", now()->addMinutes(10), function () {
            return $this->orderProducts->sum(function ($orderProduct) {
                return $orderProduct->quantity * $orderProduct->price;
            });
        });
    }

    // Specify the attributes that should be appended to the model's array form
    protected $appends = ['created_att','order_total'];


}
