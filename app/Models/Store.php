<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function getOrderCountAttribute()
    {
        return OrderProduct::whereHas('product', function ($query) {
            $query->where('store_id', $this->id);
        })->distinct('order_id')->count('order_id');
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'status' => null,
            "id"=>null,
            "name"=>null,
            "user_id"=>null,

            'category_id' => null,
            'search' => null,
            'sort' => null,
            'limit' => null,
            'page' => null,
        ], $filters);

        $builder->when($options['status'], function ($query, $status) {
            $query->where('status', $status);
        });
        $builder->when($options['id'], function ($query, $id) {
            $query->where('id', $id);
        });
        $builder->when($options['name'], function ($query, $name) {
            $query->where('name', 'like',"%$name%");
        });
        $builder->when($options['user_id'], function ($query, $user_id) {
            $query->where('user_id', $user_id);
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

 /*   public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderProduct::class, 'store_id', 'id', 'id', 'order_id');
    }*/
    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderProduct::class, 'product_id', 'id', 'id', 'order_id')
            ->whereHas('products', function ($query) {
                $query->where('store_id', $this->id);
            });
    }



    public static function getImageUrl( $imagePath): string
    {
        return url('storage/' . $imagePath);
    }





}
