<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'badgeId' => $this->badge_id,
            'name' => $this->name,
            'department' => $this->department,
            'position' => $this->position,
            'email' => $this->email,
            'createdAt' => $this->created_at?->utc()->toIso8601String(),
        ];
    }
}
