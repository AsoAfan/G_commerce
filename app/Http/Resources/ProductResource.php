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
        return [
            'id' => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "currency" => $this->currency,
            "rating" => $this->rating,
            "image_path" => $this->image_path,
            "attributes" => AttributeResource::collection($this->whenLoaded('attributes')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at


        ];
    }

//    public static function collection($resource)
//    {
//        return parent::collection($resource);
//    }
}
