<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        dd($this->pivot->price);
//        return [];
        return [
            "id" => $this->id,
            "name" => $this->name,
            "value" => $this->whenPivotLoaded('values', fn() => $this->pivot->value),
            "display_type" => $this->whenPivotLoaded('values', fn() => $this->pivot->display_type),
            'price' => $this->whenPivotLoaded('values', fn() => $this->pivot->price),
            'currency' => $this->whenPivotLoaded('values', fn() => $this->pivot->currency),
            'image_path' => $this->whenPivotLoaded('values', fn() => $this->pivot->image_path),
            'image_name' => $this->whenPivotLoaded('values', fn() => $this->pivot->image_name),

        ];
    }
}
