<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = [
        'name',
        'attribute_id',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}
