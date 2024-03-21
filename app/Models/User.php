<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
        'otp_code',
        'otp_secret',
        'otp_slug',
        'otp_expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'favourites');
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    /*
     *         return $this->belongsToMany(User::class, 'ratings')
            ->withPivot(['rating', 'review'])->select(['users.id', 'users.username', 'users.image_path', 'rating', 'review']);

     * */

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'ratings')->select('users.username');
    }

    /*
            public function otp()
        {
            return $this->hasOne(Otp::class);

        }
    */


    function setPasswordAttribute($value): void
    {

        $this->attributes['password'] = bcrypt($value);

    }

    function setRoleAttribute($value): void
    {

        $this->attributes['role'] = strtolower($value);

    }
}
