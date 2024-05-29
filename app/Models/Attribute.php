<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function values()
    {
        return $this->hasMany(Value::class);
    }

    public function valuesNames()
    {
        return $this->hasMany(Value::class)->select('name')->where('attribute_id', $this->id)->get();
    }
}
