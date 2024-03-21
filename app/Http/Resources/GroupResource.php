<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class GroupResource extends JsonResource
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
            'name' => $this->name,
            'end_date' => $this->end_date,
            'discount' => $this->whenLoaded('discount', new DiscountResource($this->discount)),
            // TODO: Check for refactor later
            'products' => $this->when(
                condition: $this->whenLoaded('products') instanceof MissingValue,
                value: $this->whenCounted('products'),
                default: ProductResource::collection($this->whenLoaded('products'))
            )
        ];
    }
}
