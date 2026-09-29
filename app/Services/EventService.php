<?php

namespace App\Services;

use App\Enums\ConfirmationStatus;
use App\Exceptions\ConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventService
{
    public function list(): Collection
    {
        return $this->queryWithCounts()
            ->orderByDesc('event_date')
            ->orderByDesc('id')
            ->get();
    }

    public function find(int $eventId): Event
    {
        $event = $this->queryWithCounts()->find($eventId);

        if ($event === null) {
            throw new ResourceNotFoundException("Event dengan ID {$eventId} tidak ditemukan");
        }

        return $event;
    }

    public function findBySlug(string $slug): Event
    {
        $event = Event::query()->where('slug', strtolower($slug))->first();

        if ($event === null) {
            throw new ResourceNotFoundException('Event tidak ditemukan');
        }

        return $event;
    }

    public function create(array $attributes): Event
    {
        $attributes['slug'] ??= $this->generateSlug($attributes['name']);

        $event = Event::query()->create($attributes);

        return $this->find($event->id);
    }

    public function update(int $eventId, array $attributes): Event
    {
        return DB::transaction(function () use ($eventId, $attributes) {
            $event = Event::query()->lockForUpdate()->find($eventId);

            if ($event === null) {
                throw new ResourceNotFoundException("Event dengan ID {$eventId} tidak ditemukan");
            }

            $confirmed = $event->invitations()
                ->where('confirmation_status', ConfirmationStatus::Hadir)
                ->count();

            if ($attributes['capacity'] < $confirmed) {
                throw new ConflictException("Kapasitas tidak boleh kurang dari jumlah peserta yang sudah konfirmasi hadir ({$confirmed})");
            }

            $attributes['slug'] ??= $event->slug;

            $event->update($attributes);

            return $this->find($event->id);
        });
    }

    public function delete(int $eventId): void
    {
        $event = Event::query()->find($eventId);

        if ($event === null) {
            throw new ResourceNotFoundException("Event dengan ID {$eventId} tidak ditemukan");
        }

        $event->delete();
    }

    private function generateSlug(string $name): string
    {
        $base = Str::limit(Str::slug($name), 44, '') ?: 'event';
        $slug = $base;
        $suffix = 2;

        while (Event::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function queryWithCounts(): Builder
    {
        return Event::query()->withCount([
            'invitations',
            'invitations as confirmed_invitations_count' => fn (Builder $query) => $query
                ->where('confirmation_status', ConfirmationStatus::Hadir),
        ]);
    }
}
