<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\NewUserRegisteredNotification;
use App\Notifications\ResetPasswordNotification;
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
        'google_id'

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
