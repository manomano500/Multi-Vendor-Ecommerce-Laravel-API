<?php

namespace App\Models;

use App\Notifications\LowStockNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\hasOneThrough;
use Illuminate\Support\Facades\Notification;

class Product extends Model
{
    use SoftDeletes, HasFactory;

protected $hidden=['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'name',
        'description',
        'store_id',
        'quantity', // 'quantity' is added to the fillable array
        'category_id',
        'price',
        'status',
    ];
    public function scopeStatus(Builder $builder, $status)
    {
        $builder->where('status', $status);
    }
    public function scopeFilter(Builder $builder, $filters)
    {

     $options =array_merge([

         'status' => 'active', // 'status' is added to the array with a default value of 'active
        'category_id' => null,
        'store_id' => null,
        'search' => null,
        'price' => null,
        'sort' => null,//url?
        'limit' => null,
        'page' => null,
     ], $filters);

        $builder->when($options['status'], function ($query, $status) {
            $query->where('status', $status);

        });
        $builder->when($options['category_id'], function ($query, $category) {
            $query->where('category_id', $category);

        });
        $builder->when($options['store_id'], function ($query, $store) {
            $query->where('store_id', $store);

        });
        $builder->when($options['search'], function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        });

        $builder->when($options['price'], function ($query, $price) {
            $query->where('price', '<=', $price);

        });
        $builder->when($options['sort'], function ($query, $sort) {
            $query->orderBy( $sort);

        });
        $builder->when($options['limit'], function ($query, $limit) {
            $query->limit($limit);

        });


    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }



    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function variations()
    {
        return $this->belongsToMany(Variation::class, 'product_variations','product_id',);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function deleteImages(array $imageIds)
    {
        // Detach images that match the provided IDs
        $this->images()->whereIn('id', $imageIds)->delete();
    }



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function attributes()
    {
        return $this->hasManyThrough(Attribute::class, Variation::class, 'product_id', 'id', 'id', 'attribute_id');
    }


    public function user()
    {
        return $this->hasOneThrough(User::class, Store::class, 'id', 'id', 'store_id', 'user_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_product')
            ->withPivot('quantity', 'price', 'store_id')
            ->withTimestamps();
    }


    public function getImageUrlsAttribute()
    {
        return $this->images?->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => $image->image_url, // Adjust this according to your actual column name
            ];
        });
    }


    protected static function booted()
    {
        static::updated(function ($product) {
            if ($product->quantity < 10) {
                $product->notifyLowStock();
            }
        });
    }



    public function notifyLowStock()
    {
        $store = $this->store;
        $vendor = $store->user; // Assuming the store has a user relationship with the vendor
        $admins = User::where('role_id', '=', 1)->get(); // Assuming you have an 'is_admin' column in users table

// Merge vendor and admins into a single collection
        $notifiables = $admins->push($vendor);

// Send notifications to vendor and admins
        Notification::send($notifiables, new LowStockNotification($this));

    }}
