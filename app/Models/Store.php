<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;



    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'status' => 'active',
            'category_id' => null,
            'search' => null,
            'sort' => null,
            'limit' => null,
            'page' => null,
        ], $filters);

        $builder->when($options['status'], function ($query, $status) {
            $query->where('status', $status);
        });

        $builder->when($options['category_id'], function ($query, $category) {
            $query->where('category_id', $category);
        });

        $builder->when($options['search'],  function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        });

        $builder->when($options['sort'], function ($query, $sort) {
            $query->orderBy($sort);
        });

        $builder->when($options['limit'], function ($query, $limit) {
            $query->limit($limit);
        });

        $builder->when($options['page'], function ($query, $page) {
            $query->paginate($page);
        });
    }

    protected $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'status',
        'user_id',
        'address',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // New function to get category name

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderProduct::class, 'store_id', 'id', 'id', 'order_id');
    }




    public static function getImageUrl( $imagePath): string
    {
        return url('storage/' . $imagePath);
    }





}
