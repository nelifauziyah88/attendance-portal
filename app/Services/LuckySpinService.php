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
                ->whereNotIn('badge_id', $this->managerBadgeIds())
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

    public function participants(): array
    {
        $badgeIds = Attendance::orderBy('check_in_at')->pluck('badge_id');
        $users = User::whereIn('badge_id', $badgeIds)->get()->keyBy('badge_id');

        return $badgeIds
            ->filter(fn (string $badgeId) => $users->has($badgeId))
            ->map(fn (string $badgeId) => [
                'badge' => $badgeId,
                'name' => $users->get($badgeId)->name,
                'position' => $users->get($badgeId)->position ?? '-',
                'department' => $users->get($badgeId)->department ?? '-',
            ])
            ->values()
            ->all();
    }

    public function prizes(): array
    {
        $awarded = LuckySpin::with('prize')
            ->orderBy('won_at')
            ->orderBy('id')
            ->get()
            ->map(fn (LuckySpin $luckySpin) => $luckySpin->prize?->name ?? '-');

        $remaining = Prize::where('stock', '>', 0)
            ->orderBy('id')
            ->get()
            ->flatMap(fn (Prize $prize) => array_fill(0, $prize->stock, $prize->name));

        return $awarded->concat($remaining)->values()->all();
    }

    public function recentWinners(): array
    {
        $latest = LuckySpin::with('prize')->latest('won_at')->latest('id')->first();

        if (! $latest) {
            return [];
        }

        $user = User::where('badge_id', $latest->badge_id)->first();

        return [[
            'draw' => LuckySpin::count(),
            'badge' => $latest->badge_id,
            'name' => $user?->name ?? '-',
            'position' => $user?->position ?? '-',
            'department' => $user?->department ?? '-',
            'prize' => $latest->prize?->name ?? '-',
        ]];
    }

    public function winnerCount(): int
    {
        return LuckySpin::count();
    }

    public function eligibleCount(): int
    {
        return Attendance::whereNotIn('badge_id', LuckySpin::pluck('badge_id'))
            ->whereNotIn('badge_id', $this->managerBadgeIds())
            ->count();
    }

    private function managerBadgeIds(): array
    {
        return User::where('position', 'ilike', '%manager%')->pluck('badge_id')->all();
    }
}