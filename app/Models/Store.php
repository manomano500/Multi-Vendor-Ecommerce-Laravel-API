<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', 'active');
    }

    protected $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'status',
        'user_id',
        'address',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // New function to get category name

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(StoreOrder::class, OrderProduct::class, 'store_id', 'id', 'id', 'order_id')

            ->with('products')
            ;
    }

    public function storeOrders()
    {
        return $this->hasMany(StoreOrder::class);
    }







}
