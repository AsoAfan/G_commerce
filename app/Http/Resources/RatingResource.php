<?php

namespace App\Http\Resources;

use App\Models\User;
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
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'review' => $this->review,
            'user' => $this->whenLoaded('user', fn(User $user) => [
                'username' => $user->username,
                'image_path' => $user->image_path,
                'image_name' => $user->image_name
            ])
        ];
    }
}
