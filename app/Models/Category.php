<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function scopeParent($query)
    {
        return $query->whereNull('category_id');

    }

    public function scopeChild($query)
    {
        return $query->whereNotNull('category_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::Class,'category_id')->with('children');
    }

//    public function parent()
//    {
//        return $this->belongsTo(Category::class, 'category_id');
//    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

public function stores()
{
    return$this->hasMany(Store::class);

}


}

