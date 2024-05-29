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
        'slug',
        'thumb_image',
        'store_id',
        'quantity', // 'quantity' is added to the fillable array
        'category_id',
        'price',
        'status',
    ];
    public function attributeValues()
    {
        return $this->belongsToMany(Value::class, 'product_values', 'product_id', 'value_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function userStore()
    {
        return Store::find($this->store_id)->user_id;
    }

}
