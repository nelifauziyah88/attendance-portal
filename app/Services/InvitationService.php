<?php

namespace App\Services;

use App\Enums\ConfirmationStatus;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\ConflictException;
use App\Mail\InvitationMail;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\MasterAttendance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class InvitationService
{
    private const QUOTA_LOCK_NAMESPACE = 990001;

    public function __construct(
        private readonly EventService $events,
        private readonly QrCodeService $qrCodes,
    ) {}

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

        $users = MasterAttendance::query()
            ->when(! $inviteAll, fn ($query) => $query->whereIn('badge_id', $badgeIds))
            ->orderBy('id')
            ->get(['id', 'badge_id']);

        $notFound = $inviteAll
            ? []
            : array_values(array_diff($badgeIds, $users->pluck('badge_id')->all()));

        $created = 0;

        foreach ($users->chunk(500) as $chunk) {
            $created += DB::table('invitations')->insertOrIgnore(
                $chunk->map(fn (MasterAttendance $user) => [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'code' => Invitation::generateCode(),
                ])->values()->all()
            );
        }

        return [
            'created' => $created,
            'alreadyInvited' => $users->count() - $created,
            'notFound' => $notFound,
        ];
    }

    public function findByCode(string $code): Invitation
    {
        $invitation = Invitation::query()
            ->with(['user', 'event'])
            ->where('code', $code)
            ->first();

        if ($invitation === null) {
            throw $this->accessDenied();
        }

        return $invitation;
    }

    public function confirm(string $code, bool $attending): Invitation
    {
        return DB::transaction(function () use ($code, $attending) {
            $invitation = Invitation::query()->where('code', $code)->lockForUpdate()->first();

            if ($invitation === null) {
                throw $this->accessDenied();
            }

            if ($invitation->confirmation_status !== ConfirmationStatus::Pending) {
                throw new ConflictException("Undangan sudah dikonfirmasi dengan status {$invitation->confirmation_status->value}");
            }

            if ($attending) {
                DB::select('SELECT pg_advisory_xact_lock(?, ?)', [self::QUOTA_LOCK_NAMESPACE, $invitation->event_id]);

                if ($this->quota($invitation->event)['remaining'] <= 0) {
                    throw new ConflictException('Kuota penuh, konfirmasi kehadiran tidak dapat diproses');
                }
            }

            $invitation->update([
                'confirmation_status' => $attending ? ConfirmationStatus::Hadir : ConfirmationStatus::TidakHadir,
                'confirmed_at' => now(),
            ]);

            return $invitation->load(['user', 'event']);
        });
    }

    public function sendEmails(int $eventId, ?array $badgeIds, bool $resend): array
    {
        $event = $this->events->find($eventId);

        $invitations = $event->invitations()
            ->with('user')
            ->where('confirmation_status', ConfirmationStatus::Pending)
            ->when(! $resend, fn ($query) => $query->whereNull('sent_at'))
            ->when($badgeIds !== null, fn ($query) => $query->whereHas(
                'user',
                fn ($userQuery) => $userQuery->whereIn('badge_id', $badgeIds)
            ))
            ->orderBy('id')
            ->get();

        $sent = 0;
        $failed = [];

        foreach ($invitations as $invitation) {
            $invitation->setRelation('event', $event);

            if ($invitation->user->email === null) {
                $failed[] = ['badgeId' => $invitation->user->badge_id, 'reason' => 'Peserta belum memiliki email'];

                continue;
            }

            try {
                $this->sendEmail($invitation);
                $sent++;
            } catch (Throwable $exception) {
                Log::error('Gagal mengirim email undangan', [
                    'invitation_id' => $invitation->id,
                    'error' => $exception->getMessage(),
                ]);
                $failed[] = ['badgeId' => $invitation->user->badge_id, 'reason' => 'Email gagal dikirim'];
            }
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
        ];
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

    public function invitationUrl(string $code): string
    {
        return config('invitation.base_url').'/'.rawurlencode($code);
    }

    private function sendEmail(Invitation $invitation): void
    {
        $invitationUrl = $this->invitationUrl($invitation->code);

        Mail::to($invitation->user->email, $invitation->user->name)->send(
            new InvitationMail($invitation, $invitationUrl, $this->qrCodes->png($invitationUrl))
        );

        $invitation->update(['sent_at' => now()]);
    }

    private function accessDenied(): AccessDeniedException
    {
        return new AccessDeniedException('Akses ditolak: kode undangan tidak terdaftar dalam daftar undangan');
    }
}
