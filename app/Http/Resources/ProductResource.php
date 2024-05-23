<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


//        dd('sok');
        return [
            'id' => $this->id,
            "name" => $this->name,
            'description' => $this->description,
            "price" => $this->price,
            "currency" => $this->currency,
            "quantity" => $this->quantity,
            "image_name" => $this->image_name,
            "image_path" => $this->image_path,

            "cart_quantity" => $this->whenPivotLoaded('cart_product', fn() => $this->pivot->quantity),

            'avg_rating' => $this->whenNotNull($this->averageRating()),
            'count_rating' => $this->whenCounted('ratings'),
            "brand" => $this->whenLoaded('brand'),
            "category" => $this->whenLoaded('category'),
            "discount" => $this->whenLoaded('discount'),
            "subcategories" => SubCategoryResource::collection($this->whenLoaded('subCategories')),
            "attributes" => AttributeResource::collection($this->whenLoaded('attributes')),
            'ratings' => RatingResource::collection($this->whenLoaded('ratings')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }

//    public static function collection($resource)
//    {
//        return parent::collection($resource);
//    }
}
