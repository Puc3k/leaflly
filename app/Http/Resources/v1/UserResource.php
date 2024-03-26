<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'emailVerifiedAt' => $this->email_verified_at,
            'password' => $this->password,
            'fcmToken' => $this->fcm_token,
            'rememberToken' => $this->remember_token,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'plants' => PlantResource::collection($this->whenLoaded('plants')),
            'notes' => NoteResource::collection($this->whenLoaded('notes')),
        ];
    }
}
