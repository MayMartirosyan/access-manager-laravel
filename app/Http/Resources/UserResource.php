<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'credits_remaining' => $this->credits_remaining,
            'is_admin' => $this->isAdmin(),
            'last_credits_reset_at' => $this->last_credits_reset_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}


