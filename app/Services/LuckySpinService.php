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
        return MasterAttendance::whereHas('attendance', function ($q) {
            $q->where('is_attending', true);
        })
        ->get(['badge_id', 'name', 'position', 'department', 'is_manager']);
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
        return Prize::where('current_stock', '>', 0)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Mengambil daftar nama seluruh hadiah yang masih memiliki stok.
     */
    public function getActivePrizes(): array
    {
        return Prize::where('current_stock', '>', 0)->pluck('name')->toArray();
    }

    /**
     * Proses Spin Pemenang (Acak dari kandidat non-manager yang eligible).
     */
    public function drawWinner(): array
    {
        return DB::transaction(function () {
            // 1. Ambil Hadiah Pertama yang Stoknya Masih Ada
            $prize = $this->getCurrentPrize();

            if (!$prize) {
                throw new Exception('Semua stok hadiah telah habis!');
            }

            // 2. Filter Peserta Eligible: Sudah Check-In, Belum Menang, & BUKAN Manager
            $existingWinners = $this->getExistingWinnerBadgeIds();

            $eligibleCandidates = MasterAttendance::whereHas('attendance', function ($q) {
                $q->where('is_attending', true);
            })
            ->whereNotIn('badge_id', $existingWinners)
            ->where('is_manager', false)
            ->get();

            if ($eligibleCandidates->isEmpty()) {
                throw new Exception('Tidak ada peserta eligible (non-manager) yang tersisa.');
            }

            // 3. Pilih Pemenang Secara Acak
            $winner = $eligibleCandidates->random();

            // 4. Potong Stok Hadiah
            $prize->decrement('current_stock');

            // 5. Simpan Record Pemenang
            $luckySpin = LuckySpin::create([
                'badge_id' => $winner->badge_id,
                'prize_id' => $prize->id,
                'draw_number' => LuckySpin::count() + 1,
            ]);

            return [
                'id' => $luckySpin->id,
                'draw' => $luckySpin->draw_number,
                'badge' => $winner->badge_id,
                'name' => $winner->name,
                'position' => $winner->position,
                'department' => $winner->department,
                'prize' => $prize->name,
                'prize_id' => $prize->id,
                'remaining_stock' => $prize->fresh()->current_stock,
            ];
        });
    }

    /**
     * Proses Pembatalan / Forfeit Pemenang berdasarkan Badge ID.
     */
    public function forfeitWinnerByBadge(string $badgeId): void
    {
        DB::transaction(function () use ($badgeId) {
            $spinRecord = LuckySpin::where('badge_id', $badgeId)->firstOrFail();
            $prize = Prize::find($spinRecord->prize_id);

            // Restore stok hadiah (+1)
            if ($prize) {
                $prize->increment('current_stock');
            }

            // Hapus record pemenang
            $spinRecord->delete();
        });
    }
}