<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeValue extends Pivot
{
    protected $table = 'attribute_values';
    protected $fillable = ['name'
    ,
        'attribute_id',
        'value_id',
    ];

//
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
    public function values()
    {
        return $this->hasMany(Value::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }




}
