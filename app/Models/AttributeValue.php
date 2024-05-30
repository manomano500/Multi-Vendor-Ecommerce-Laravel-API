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


    public function attributess()
    {
        return $this->hasMany(Attribute::class);
    }
    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }




}
