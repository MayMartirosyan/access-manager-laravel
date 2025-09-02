<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'display_date' => $this->display_date?->toDateString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}