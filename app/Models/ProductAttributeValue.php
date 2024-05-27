<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;
    protected $fillable = ['product_attribute_id', 'value_id','quantity'];

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function value()
    {
        return $this->belongsTo(Value::class);
    }
}
