<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;
    public $translatable = ['name'];


    protected $fillable = ['name', 'type', 'category_id'];

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

    public function children(): HasMany
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
    public function scopeProduct(Builder $query)
    {
        return $query->where('type', 'product');
    }

    public function scopeStore(Builder $query)
    {
        return $query->where('type', 'store');
    }


}

