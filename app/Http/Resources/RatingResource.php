<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //        dd($this[0]);


        return [
            'id' => $this->id,
            'username' => $this->username,
            'image_path' => $this->image_path,
            'image_name' => $this->image_name,
            'rating' => $this->rating,
            'review' => $this->review


        ];
    }
}
