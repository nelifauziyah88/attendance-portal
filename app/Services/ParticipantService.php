<?php

namespace App\Services;

use App\Exceptions\ConflictException;
use App\Models\MasterAttendance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\UniqueConstraintViolationException;

class ParticipantService
{
    public function list(): Collection
    {
        return MasterAttendance::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $attributes): MasterAttendance
    {
        $badgeId = $attributes['badgeId'];

        if (MasterAttendance::query()->where('badge_id', $badgeId)->exists()) {
            throw $this->duplicateBadge($badgeId);
        }

        try {
            return MasterAttendance::query()->create([
                'badge_id' => $badgeId,
                'name' => $attributes['name'],
                'department' => $attributes['department'] ?? null,
                'position' => $attributes['position'] ?? null,
                'project' => $attributes['project'] ?? null,
                'company' => $attributes['company'] ?? null,
                'is_manager' => $attributes['isManager'] ?? false,
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            throw $this->duplicateBadge($badgeId);
        }
    }

    private function duplicateBadge(string $badgeId): ConflictException
    {
        return new ConflictException("BADGE {$badgeId} sudah terdaftar");
    }
}
