<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class PlantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'species' => $this->species,
            'image' => $this->image,
            'soilType' => $this->soil_type,
            'targetHeight' => $this->target_height,
            'wateringFrequency' => $this->watering_frequency,
            'lastWatered' => $this->last_watered,
            'insolation' => $this->insolation,
            'cultivationDifficulty' => $this->cultivation_difficulty,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'notes' => NoteResource::collection($this->whenLoaded('notes'))
        ];
    }
}
