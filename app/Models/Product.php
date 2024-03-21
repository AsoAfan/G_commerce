<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

//    protected $with = ['attributes'];

    protected $hidden = ['discount_id', 'category_id', 'brand_id'];


    public function scopeFilter(Builder $query, array $filters)
    {
        /**
         * Filters:
         * ==========
         * 1. Search
         * 2. attribute values
         * 3. brand
         * 4. category
         * 5. subcategory
         * 6. group
         */

        // 1. search by name and description
        $query->when($filters['s'] ?? false, fn($query, $searchTerm) => $query
            ->where('name', "LIKE", "%$searchTerm%")
            ->orWhere('description', 'LIKE', "%$searchTerm%")

        );

        // 2. filter by attributes => attributes=attr_name:attr_value,attr_name2:attr_value2
        $query->when($filters['attributes'] ?? false, function ($query) use ($filters) {


            $attributes = collect(explode(",", $filters['attributes']))->map(
                function ($attribute) {
                    [$name, $value] = explode(":", $attribute);
                    return compact('name', 'value');
                }
            );


            // TODO: Better way (without loop)?
            foreach ($attributes as $attribute) {

                $query->WhereHas('attributes', function ($query) use ($attribute) {
                    $query->where('name', $attribute['name'])->where('value', $attribute['value']);
                });
            }
        });

        // 3. filter by brand
        $query->when($filters['brand'] ?? false, fn(Builder $query, $brand) => $query->whereHas('brand', fn(Builder $query) => $query
            ->where("id", $brand)
        )
        );

        // 4. category
        $query->when($filters['category'] ?? false, fn($query) => $query->whereHas('category', fn($query, $category) => $query->where('id', $category)
        )
        );

        // 5. subcategory
        $query->when($filters['subcategory'] ?? false, function (Builder $query, $subcategory) {
            $subcategories = explode(',', $subcategory);

            $query->withCount('subcategories')
                ->whereHas('subCategories', function (Builder $query) use ($subcategories) {
                    $query->distinct()->whereIn('sub_categories.id', $subcategories);
                }, count($subcategories));

        });

        /* $query->when($filters['subcategory'] ?? false, fn(Builder $query) => $query
             ->whereHas('subCategories', fn(Builder $query, $id) => $query
                 ->where('id', $id)
             )
         );*/


        // 6. group
        $query->when($filters['groups'] ?? false, function (Builder $query, $group) use ($filters) {
            $groups = explode(',', $group);
            $query
                ->withCount('groups')
                ->whereHas('groups', function (Builder $query) use ($groups, $group) {
                    $query->distinct()->whereIn('groups.id', $groups);
                }, '=', count($groups));
        });

    }

    public function users()
    {
        return $this->belongsToMany(Product::class, 'favourites');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
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
            ->withPivot([
                'value',
                'display_type',
                'price',
                'image_path',
                'image_name',
                'price',
                'currency',
                'quantity'
            ]);

    }
}
