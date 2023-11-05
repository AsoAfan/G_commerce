<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['hashed', 'slug', 'expires_at', 'user_id'];

    function user()
    {
        return $this->belongsTo(User::class);
    }


}
