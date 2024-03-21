<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        dump($this->products());


        return [
            'id' => $this->id,
            'ratio' => $this->ratio,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
            'products' => ProductResource::collection($this->whenLoaded('products'))

        ];
    }
}
