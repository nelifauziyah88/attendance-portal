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
            'winnerSlots' => Prize::sum('stock') + $winners,
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

            // 1. Ambil Hadiah Pertama yang Stoknya Masih Ada
            $prize = $this->getCurrentPrize();

            if (!$prize) {
                throw new Exception('Semua stok hadiah telah habis!');
            }

            $eligibleCandidates = $this->getEligibleParticipants()->get();

            if ($eligibleCandidates->isEmpty()) {
                throw new Exception('Tidak ada peserta eligible (non-manager) yang tersisa.');
            }

            // 3. Pilih Pemenang Secara Acak
            $winner = $eligibleCandidates->random();

            // 4. Potong Stok Hadiah
            $prize->decrement('stock');

            // 5. Simpan Record Pemenang
            $luckySpin = LuckySpin::create([
                'badge_id' => $winner->badge_id,
                'prize_id' => $prize->id,
                'won_at' => now(),
            ]);

            return [
                'id' => $luckySpin->id,
                'draw' => $luckySpin->id,
                'badge' => $winner->badge_id,
                'name' => $winner->name,
                'position' => $winner->position,
                'department' => $winner->department,
                'prize' => $prize->name,
                'prize_id' => $prize->id,
                'remaining_stock' => $prize->fresh()->stock,
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
            $prize = Prize::find($spinRecord->prize_id);

            // Restore stok hadiah (+1)
            if ($prize) {
                $prize->increment('stock');
            }

            // Keep the badge reserved so a forfeited winner cannot be drawn again.
            $spinRecord->update(['won_at' => LuckySpin::FORFEITED_AT]);
        });
    }
}