<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::Class,'category_id')->with('children');
    }

    public static function parents()
    {
        return Category::select('id', 'name')
            ->whereNull('category_id')
            ->get();
    }
}
