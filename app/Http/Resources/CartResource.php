<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public $productsCount;


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'products' => $this->whenLoaded('products', function (Collection $products) {
                $this->productsCount = $products->count();
                return ProductResource::collection($products);
            }),
            'total_price' => $this->total_price,
            'products_count' => $this->whenNotNull($this->productsCount)
        ];
    }
}
