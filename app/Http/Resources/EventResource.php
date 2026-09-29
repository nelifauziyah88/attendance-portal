<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => $this->url(),
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'eventDate' => $this->event_date,
            'startTime' => substr($this->start_time, 0, 5),
            'endTime' => $this->end_time === null ? null : substr($this->end_time, 0, 5),
            'capacity' => $this->capacity,
            'invited' => $this->whenCounted('invitations'),
            'confirmed' => $this->whenCounted('confirmed_invitations'),
            'remaining' => $this->when(
                isset($this->confirmed_invitations_count),
                fn () => max(0, $this->capacity - $this->confirmed_invitations_count)
            ),
            'createdAt' => $this->created_at?->utc()->toIso8601String(),
            'updatedAt' => $this->updated_at?->utc()->toIso8601String(),
        ];
    }
}
