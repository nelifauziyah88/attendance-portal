<?php

namespace App\Services;

use App\Enums\ConfirmationStatus;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\ConflictException;
use App\Exceptions\UnprocessableException;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class InvitationService
{
    private const QUOTA_LOCK_NAMESPACE = 990001;

    public function __construct(private readonly EventService $events) {}

    public function listForEvent(int $eventId, ?ConfirmationStatus $status): Collection
    {
        $event = $this->events->find($eventId);

        return $event->invitations()
            ->with('user')
            ->when($status, fn ($query) => $query->where('confirmation_status', $status))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();
    }

    public function createForEvent(int $eventId, bool $inviteAll, array $badgeIds): array
    {
        $event = $this->events->find($eventId);

        $users = User::query()
            ->when(! $inviteAll, fn ($query) => $query->whereIn('badge_id', $badgeIds))
            ->orderBy('id')
            ->get(['id', 'badge_id']);

        $notFound = $inviteAll
            ? []
            : array_values(array_diff($badgeIds, $users->pluck('badge_id')->all()));

        $created = 0;

        foreach ($users->chunk(500) as $chunk) {
            $created += DB::table('invitations')->insertOrIgnore(
                $chunk->map(fn (User $user) => [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ])->values()->all()
            );
        }

        return [
            'created' => $created,
            'alreadyInvited' => $users->count() - $created,
            'notFound' => $notFound,
        ];
    }

    public function check(string $slug, string $badgeId): Invitation
    {
        $event = $this->events->findBySlug($slug);
        $invitation = $this->queryByBadge($event, $badgeId)->first();

        if ($invitation === null) {
            throw $this->accessDenied($badgeId);
        }

        return $invitation->setRelation('event', $event);
    }

    public function confirm(string $slug, string $badgeId, array $identity, bool $attending): Invitation
    {
        $event = $this->events->findBySlug($slug);

        return DB::transaction(function () use ($event, $badgeId, $identity, $attending) {
            $invitation = $this->queryByBadge($event, $badgeId)->lockForUpdate()->first();

            if ($invitation === null) {
                throw $this->accessDenied($badgeId);
            }

            $mismatched = $this->mismatchedIdentityFields($invitation->user, $identity);

            if ($mismatched !== []) {
                throw new UnprocessableException('Data peserta tidak sesuai dengan data karyawan: '.implode(', ', $mismatched));
            }

            if ($invitation->confirmation_status !== ConfirmationStatus::Pending) {
                throw new ConflictException("Undangan sudah dikonfirmasi dengan status {$invitation->confirmation_status->value}");
            }

            if ($attending) {
                DB::select('SELECT pg_advisory_xact_lock(?, ?)', [self::QUOTA_LOCK_NAMESPACE, $event->id]);

                if ($this->quota($event)['remaining'] <= 0) {
                    throw new ConflictException('Kuota penuh, konfirmasi kehadiran tidak dapat diproses');
                }
            }

            $invitation->update([
                'confirmation_status' => $attending ? ConfirmationStatus::Hadir : ConfirmationStatus::TidakHadir,
                'confirmed_at' => now(),
            ]);

            return $invitation->setRelation('event', $event);
        });
    }

    public function quota(Event $event): array
    {
        $confirmed = $event->invitations()
            ->where('confirmation_status', ConfirmationStatus::Hadir)
            ->count();

        return [
            'capacity' => $event->capacity,
            'confirmed' => $confirmed,
            'remaining' => max(0, $event->capacity - $confirmed),
        ];
    }

    private function queryByBadge(Event $event, string $badgeId): HasMany
    {
        return $event->invitations()
            ->with('user')
            ->whereIn('user_id', User::query()->select('id')->where('badge_id', $badgeId));
    }

    private function mismatchedIdentityFields(User $user, array $identity): array
    {
        return collect(['name', 'department', 'position'])
            ->reject(fn (string $field) => $this->normalize($user->{$field}) === $this->normalize($identity[$field]))
            ->values()
            ->all();
    }

    private function normalize(?string $value): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $value)));
    }

    private function accessDenied(string $badgeId): AccessDeniedException
    {
        return new AccessDeniedException("Akses ditolak: BADGE {$badgeId} tidak terdaftar dalam daftar undangan");
    }
}
