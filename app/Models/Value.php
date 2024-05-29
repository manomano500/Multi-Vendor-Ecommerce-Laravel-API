<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = [
        'name',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_values')
            ->using(AttributeValue::class)
            ->withPivot('attribute_id', 'value_id');


    }

}
