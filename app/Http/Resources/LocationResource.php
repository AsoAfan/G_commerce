<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'point' => [$this->latitude, $this->longitude],
            'city' => $this->city,
            'type' => $this->type,
            'note' => $this->whenNotNull($this->note)
        ];
    }
}
