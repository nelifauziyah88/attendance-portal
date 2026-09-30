<?php
namespace App\Services;

use App\Models\Attendance;
use App\Models\LuckySpin;
use App\Models\Prize;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class LuckySpinService
{
    public function drawWinner()
    {
        return DB::transaction(function () {
            // 1. Ambil badge_id yang sudah pernah menang
            $existingWinners = LuckySpin::pluck('badge_id');

            // 2. Cari peserta yang hadir (Check-in Hari H) & BELUM pernah menang
            $eligibleParticipant = Attendance::whereNotIn('badge_id', $existingWinners)
                ->inRandomOrder()
                ->first();

            if (!$eligibleParticipant) {
                throw new Exception("Tidak ada peserta eligible yang tersisa untuk diundi.");
            }

            // 3. Cari stok hadiah yang masih ada
            $prize = Prize::where('stock', '>', 0)->inRandomOrder()->first();

            if (!$prize) {
                throw new Exception("Stok semua doorprize sudah habis.");
            }

            // 4. Catat pemenang & kurangi stok
            LuckySpin::create([
                'badge_id' => $eligibleParticipant->badge_id,
                'prize_id' => $prize->id,
                'won_at'   => now(),
            ]);

            $prize->decrement('stock');

            // 5. Ambil identitas pemenang dari DB Master
            $winnerUser = User::where('badge_id', $eligibleParticipant->badge_id)->first();

            return [
                'winner' => $winnerUser,
                'prize'  => $prize,
            ];
        });
    }
}