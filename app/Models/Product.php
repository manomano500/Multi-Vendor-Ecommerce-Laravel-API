<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'thumb_image',
        'store_id',
        'quantity', // 'quantity' is added to the fillable array
        'category_id',
        'price',
        'status',
    ];
    public function attributeValues()
    {
        return $this->belongsToMany(Variation::class, 'product_variations', 'product_id', 'value_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function variations()
    {
        return $this->belongsToMany(Variation::class, 'product_variations');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Store::class);
    }
}
