<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\AdminNotification;
use App\Notifications\NewUserRegisteredNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'google_id',
        'phone',
        'address',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'google_id'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter(Builder $builder, $filters)

    {
        $options = array_merge([
            'search' => null,
            'role' => null,
            'phone' => null,
            'email' => null,
            'name' => null,
            "role_id" => null,
            'limit' => null,
            "id"    => null,
            'page' => null,
        ], $filters);
        $builder->when($options['role_id'], function ($query, $role) {
            $query->where('role_id', $role);
        });
        $builder->when($options['id'], function ($query, $id) {
            $query->where('id', $id);
        });

        $builder->when($options['phone'], function ($query, $phone) {
            $query->where('phone','like', "%$phone%");
        });
        $builder->when($options['email'], function ($query, $email) {
            $query->where('email', $email);
        });
        $builder->when($options['name'], function ($query, $name) {
            $query->where('name', 'like',"%$name%");
        });
        $builder->when($options['limit'], function ($query, $limit) {
            $query->limit($limit);
        });
        $builder->when($options['page'], function ($query, $page) {
            $query->paginate($page);
        });


    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = 'http://localhost:8080/reset-password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }


    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Store::class);
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }






    public static function sendNotificationToUsers($message, $condition = null)
    {
        $query = self::query();

        if ($condition) {
            $query->where($condition);
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(new AdminNotification($message));
        }
    }


    protected static function booted()
    {
        static::created(function ($user) {
            // Logic to notify administrators
            $admins = User::where('role_id', 1)->get(); // Example: Fetch admins
            foreach ($admins as $admin) {
                $admin->notify(new NewUserRegisteredNotification($user));
            }
        });
    }
}
