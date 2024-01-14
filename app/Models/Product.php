<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    protected $with = ['attributes'];


    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    public function attributes(): BelongsToMany
    {

        return $this->belongsToMany(Attribute::class, 'values')
            ->withPivot('value', 'display_type');

    }
}
