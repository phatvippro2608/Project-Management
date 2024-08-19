<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
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
            'title' => $this->title,
            'board' => $this->board_id,
            // Directly using the cards array from the $this->cards property
            'cards' => $this->cards ? CardResource::collection(collect($this->cards)) : [],
        ];
    }
}
