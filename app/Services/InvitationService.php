<?php

namespace App\Services;

use App\Enums\ConfirmationStatus;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\ConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class InvitationService
{
    public function list(): Collection
    {
        return Invitation::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    public function create(string $badgeId): Invitation
    {
        $user = User::query()->where('badge_id', $badgeId)->first();

        if ($user === null) {
            throw new ResourceNotFoundException("Peserta dengan BADGE {$badgeId} tidak ditemukan");
        }

        if ($user->invitation()->exists()) {
            throw $this->duplicateInvitation($badgeId);
        }

        try {
            $invitation = $user->invitation()->create([
                'confirmation_status' => ConfirmationStatus::Pending,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateInvitation($badgeId);
        }

        return $invitation->refresh()->setRelation('user', $user);
    }

    public function findByBadge(string $badgeId): Invitation
    {
        $invitation = $this->queryByBadge($badgeId)->with('user')->first();

        if ($invitation === null) {
            throw $this->accessDenied($badgeId);
        }

        return $invitation;
    }

    public function confirm(string $badgeId, bool $attending): Invitation
    {
        return DB::transaction(function () use ($badgeId, $attending) {
            $invitation = $this->queryByBadge($badgeId)->lockForUpdate()->first();

            if ($invitation === null) {
                throw $this->accessDenied($badgeId);
            }

            if ($invitation->confirmation_status !== ConfirmationStatus::Pending) {
                throw new ConflictException("Undangan sudah dikonfirmasi dengan status {$invitation->confirmation_status->value}");
            }

            $invitation->update([
                'confirmation_status' => $attending ? ConfirmationStatus::Hadir : ConfirmationStatus::TidakHadir,
                'confirmed_at' => now(),
            ]);

            return $invitation->load('user');
        });
    }

    public function invitationUrl(string $badgeId): string
    {
        return config('invitation.base_url').'/'.rawurlencode($badgeId);
    }

    private function queryByBadge(string $badgeId): Builder
    {
        return Invitation::query()->whereIn(
            'user_id',
            User::query()->select('id')->where('badge_id', $badgeId)
        );
    }

    private function duplicateInvitation(string $badgeId): ConflictException
    {
        return new ConflictException("Undangan untuk BADGE {$badgeId} sudah dibuat");
    }

    private function accessDenied(string $badgeId): AccessDeniedException
    {
        return new AccessDeniedException("Akses ditolak: BADGE {$badgeId} tidak terdaftar dalam daftar undangan");
    }
}
