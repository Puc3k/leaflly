<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'categories' => $this->categories,
            'priority' => $this->priority,
            'photoPath' => $this->photoPath,
            'plants' => PlantResource::collection($this->whenLoaded('plants'))
        ];
    }
}
