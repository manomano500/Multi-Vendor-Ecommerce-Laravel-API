<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\hasOneThrough;

class Product extends Model
{
    use SoftDeletes, HasFactory;


public function scopeStatus(Builder $builder,$status)
{
    $builder->where('status', '', $status);
}
    protected $fillable = [
        'name',
        'description',
        'store_id',
        'quantity', // 'quantity' is added to the fillable array
        'category_id',
        'price',
        'status',
    ];


    public function store()
    {
        return $this->belongsTo(Store::class);
    }



    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function variations()
    {
        return $this->belongsToMany(Variation::class, 'product_variations','product_id',);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function attributes()
    {
        return $this->hasManyThrough(Attribute::class, Variation::class, 'product_id', 'id', 'id', 'attribute_id');
    }


    public function user()
    {
        return $this->hasOneThrough(User::class, Store::class, 'id', 'id', 'store_id', 'user_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_product')
            ->withPivot('quantity', 'price', 'store_id')
            ->withTimestamps();
    }


}
