<?php

namespace App\Models;


use App\Exceptions\NotAvailableException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Cart extends Model
{
    use HasFactory;

    protected $casts = ['total_price' => 'float'];


    public function addProduct()
    {

    }

    public function calculateAttributes(Product $product, Collection $attributes)
    {
        if (!$product->relationLoaded('attributes'))
            $product->load(['attributes' => function ($query) use ($attributes) {
                $query->whereIn('attributes.id', $attributes->keys())->whereIn("value", $attributes->values());
            }]);

        return $attributes->reduce(function ($carry, $value, $key) use ($product) {
            if ($productAttribute = $product->Attributes->firstWhere('id', $key)) {
                if ($productAttribute->pivot->quantity < 1) {
                    throw new NotAvailableException;
                }
                $addOn = $productAttribute->pivot->price;


                return $carry + $addOn;
            }
//            $product->unsetRelation('attributes');
            return $carry;
        }, 0);
    }

    public function updateTotalPrice(Collection $attr = null, Collection $quantities = null)
    {

        $total = $this->products->sum(function (Product $product) use ($quantities, $attr) {

            if ($product->quantity < $quantities->get($product->id)) {
                throw new NotAvailableException;
            }

            $price = $product->price + $this->calculateAttributes($product, $attr);
            $discountedRatio = $product->getBestDiscount() * $price;
            return (($price - $discountedRatio) * $product->pivot->quantity);

        });


        $this->total_price = $total;
        $this->save();
        return $total;
    }


    public function hasProduct($product)
    {
        return $this->products()->where('product_id', $product?->id)->exists();
    }

    public function removeProduct(Product $product)
    {
        // TODO: Refactor later
//        $product = $this->products()->firstWhere('id', $id);
//        dd($product->pivot->quantity);

        $product->pivot->quantity -= 1;
        $product->pivot->save();
        if ($product->pivot->quantity == 0) {
            $this->products()->detach($product);
        }


        return $product->pivot->quantity;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'id']);
    }

    public function cart()
    {
        return $this->hasOne(Order::class);

    }

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
}
