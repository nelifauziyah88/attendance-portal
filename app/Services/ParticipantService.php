<?php

namespace App\Services;

use App\Exceptions\ConflictException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\UniqueConstraintViolationException;

class ParticipantService
{
    public function list(): Collection
    {
        return User::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $attributes): User
    {
        $badgeId = $attributes['badgeId'];

        if (User::query()->where('badge_id', $badgeId)->exists()) {
            throw $this->duplicateBadge($badgeId);
        }

        try {
            return User::query()->create([
                'badge_id' => $badgeId,
                'name' => $attributes['name'],
                'department' => $attributes['department'] ?? null,
                'position' => $attributes['position'] ?? null,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateBadge($badgeId);
        }
    }

    private function duplicateBadge(string $badgeId): ConflictException
    {
        return new ConflictException("BADGE {$badgeId} sudah terdaftar");
    }
}
