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
        'category_id',
        'price',
        'status',
    ];
    public function attributes()
    {
        return $this->hasMany(ProductValue::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }



    public function values()
    {
return $this->belongsToMany(Value::class, 'product_values', 'product_id', 'value_id')->withPivot('quantity');
    }
}
