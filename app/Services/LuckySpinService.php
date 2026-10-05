<?php

namespace App\Services;

use App\Models\MasterAttendance;
use App\Models\Prize;
use App\Models\LuckySpin;
use Illuminate\Support\Facades\DB;
use Exception;

class LuckySpinService
{
    /**
     * Mengambil daftar seluruh peserta yang sudah Check-In.
     */
    public function getParticipants()
    {
        return MasterAttendance::query()
            ->whereHas('attendance')
            ->get(['badge_id', 'name', 'position', 'department', 'is_manager']);
    }

    public function getEligibleParticipants()
    {
        return MasterAttendance::query()
            ->whereHas('attendance')
            ->where('is_manager', false)
            ->whereNotIn('badge_id', LuckySpin::query()->select('badge_id'));
    }

    public function getStatistics(): array
    {
        $winners = LuckySpin::activeWinner()->count();

        return [
            'checkedIn' => MasterAttendance::whereHas('attendance')->count(),
            'winners' => $winners,
            'winnerSlots' => Prize::sum('stock'),
            'eligible' => $this->getEligibleParticipants()->count(),
        ];
    }

    /**
     * Mengambil daftar ID Badge peserta yang sudah pernah menang Lucky Spin.
     */
    public function getExistingWinnerBadgeIds(): array
    {
        return LuckySpin::pluck('badge_id')->toArray();
    }

    /**
     * Mengambil hadiah aktif (stok > 0) teratas.
     */
    public function getCurrentPrize(): ?Prize
    {
        return Prize::where('stock', '>', 0)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Mengambil daftar nama seluruh hadiah yang masih memiliki stok.
     */
    public function getActivePrizes(): array
    {
        return Prize::where('stock', '>', 0)->pluck('name')->toArray();
    }

    /**
     * Proses Spin Pemenang (Acak dari kandidat non-manager yang eligible).
     */
    public function drawWinner(): array
    {
        return DB::transaction(function () {
            $stats = $this->getStatistics();

            if ($stats['winners'] >= $stats['winnerSlots']) {
                throw new Exception('Semua slot hadiah telah digunakan. Tidak ada drawing tersisa.');
            }

            $eligibleCandidates = $this->getEligibleParticipants()->get();

            if ($eligibleCandidates->isEmpty()) {
                throw new Exception('Tidak ada peserta eligible yang tersisa.');
            }

            // Pilih Pemenang Secara Acak
            $winner = $eligibleCandidates->random();

            // Simpan Record Pemenang
            $luckySpin = LuckySpin::create([
                'badge_id' => $winner->badge_id,
                'prize_id' => null,
                'won_at' => now(),
            ]);

            return [
                'id' => $luckySpin->id,
                'draw' => $luckySpin->id,
                'badge' => $winner->badge_id,
                'name' => $winner->name,
                'position' => $winner->position,
                'department' => $winner->department,
            ];
        });
    }

    /**
     * Proses Pembatalan / Forfeit Pemenang berdasarkan Badge ID.
     */
    public function forfeitWinnerByBadge(string $badgeId): void
    {
        DB::transaction(function () use ($badgeId) {
            $spinRecord = LuckySpin::activeWinner()->where('badge_id', $badgeId)->firstOrFail();

            $spinRecord->update(['won_at' => LuckySpin::FORFEITED_AT]);
        });
    }
}