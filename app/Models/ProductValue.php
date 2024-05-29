<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductValue extends Model
{
    protected $fillable = [
        'product_id',
        'value_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function value()
    {
        return $this->belongsTo(Value::class);
    }





}
