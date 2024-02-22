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
            'quantity' => $this->quantity ?: 0,
            /*"price" => $this->price,
            "currency" => $this->currency,
            "image_name" => $this->image_name,
            "image_path" => $this->image_path,*/
            'avg_rating' => $this->averageRating() ?: 0,
            'count_rating' => $this->whenCounted('ratings'),
            "attributes" => AttributeResource::collection($this->whenLoaded('attributes')),
            "discount" => $this->whenLoaded('discount', new DiscountResource($this->discount)),
            'ratings' => $this->whenLoaded('ratings', RatingResource::collection($this->ratings)),
            "brand" => $this->whenLoaded('brand', new BrandResource($this->brand)),
            "category" => $this->whenLoaded('category', new CategoryResource($this->category)),
            "subcategory" => $this->whenLoaded('subCategories', SubCategoryResource::collection($this->subCategories)),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at


        ];
    }

//    public static function collection($resource)
//    {
//        return parent::collection($resource);
//    }
}
