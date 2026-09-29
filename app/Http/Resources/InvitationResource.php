<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'badgeId' => $this->user->badge_id,
            'name' => $this->user->name,
            'department' => $this->user->department,
            'position' => $this->user->position,
            'confirmationStatus' => $this->confirmation_status->value,
            'confirmedAt' => $this->confirmed_at?->utc()->toIso8601String(),
            'createdAt' => $this->created_at?->utc()->toIso8601String(),
            'event' => EventResource::make($this->whenLoaded('event')),
        ];
    }
}
