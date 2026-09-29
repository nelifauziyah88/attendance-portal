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

        $email = isset($attributes['email']) ? strtolower($attributes['email']) : null;

        if (User::query()->where('badge_id', $badgeId)->exists()) {
            throw $this->duplicateBadge($badgeId);
        }

        if ($email !== null && User::query()->where('email', $email)->exists()) {
            throw $this->duplicateEmail($email);
        }

        try {
            return User::query()->create([
                'badge_id' => $badgeId,
                'name' => $attributes['name'],
                'department' => $attributes['department'] ?? null,
                'position' => $attributes['position'] ?? null,
                'email' => $email,
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            if ($email !== null && str_contains($exception->getMessage(), 'email')) {
                throw $this->duplicateEmail($email);
            }

            throw $this->duplicateBadge($badgeId);
        }
    }

    private function duplicateEmail(string $email): ConflictException
    {
        return new ConflictException("Email {$email} sudah terdaftar");
    }

    private function duplicateBadge(string $badgeId): ConflictException
    {
        return new ConflictException("BADGE {$badgeId} sudah terdaftar");
    }
}
