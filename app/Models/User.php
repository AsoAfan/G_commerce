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
        'cart_id'
    ];

    protected $guarded = [];


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

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    private function createCard(): Cart
    {
        // TODO: Re-check
        $cart = Cart::create();
        $this->cart_id = $cart->id;
        $this->save();
        return $cart;
    }

    public function getCart()
    {
        // TODO: re-check later
        return Cart::find($this->cart_id) ?: $this->createCard();
    }

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


    public function ratings()
    {
        return $this->hasMany(Rating::class, 'ratings')->select('users.username');
    }


    function setPasswordAttribute($value): void
    {

        $this->attributes['password'] = bcrypt($value);

    }

    function setRoleAttribute($value): void
    {

        $this->attributes['role'] = strtolower($value);

    }
}
