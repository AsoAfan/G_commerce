<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $with = ['attributes'];


    public function attributes(): BelongsToMany
    {

        return $this->belongsToMany(Attribute::class, 'values')
            ->withPivot('value', 'display_type');

    }
}
