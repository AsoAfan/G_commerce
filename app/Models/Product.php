<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    protected $with = ['attributes'];

    protected $hidden = ['discount_id', 'category_id', 'brand_id'];


    public function scopeFilter(Builder $query, array $filters)
    {

        $start = microtime(true);

        /**
         * Filters:
         * ==========
         * 1. Search
         * 2. attribute values
         *
         *
         *


         */

// Your code here

// Loop on request query instead of all available attributes
//        $at = Attribute::all();

        $query->when($filters['s'] ?? false, function ($query, $searchTerm) {
            $query
                ->where('name', "LIKE", "%$searchTerm%")
                ->orWhere('description', 'LIKE', "%$searchTerm%");
        }
        );

        $query->when($filters['attribute'] ?? false, function ($query) use ($filters) {

            // Split the attributes and values
            $attributes = explode(',', $filters['attribute']);

            // Nested whereHas for each attribute
//            $query->where(function ($query) use ($attributes) {
            foreach ($attributes as $attribute) {
//                    dd($attribute);
//                    if ($attribute != 'test:B')
//                        dd(explode(':', $attribute));
                [$attributeName, $attributeValue] = explode(':', $attribute);

                $query->WhereHas('attributes', function (Builder $q) use ($attributeName, $attributeValue) {
                    $q->where('name', $attributeName)->where('value', $attributeValue);
                    Log::info($q->toRawSql() . PHP_EOL);
                });
            }
//            });

        });
    }


    public function ratings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ratings')
            ->withPivot(['rating', 'review'])->select(['users.id', 'users.username', 'users.image_path', 'rating', 'review']);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    public function attributes(): BelongsToMany
    {

        return $this->belongsToMany(Attribute::class, 'values')
            ->withPivot('value', 'display_type');

    }
}
