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
        return [
            "id" => $this->id,
            "name" => $this->name,
            "value" => $this->whenPivotLoaded('values', fn() => $this->pivot->value),
            "display_type" => $this->whenPivotLoaded('values', fn() => $this->pivot->display_type),

        ];
    }
}
