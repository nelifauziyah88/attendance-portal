<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LuckySpinService;
use App\Models\LuckySpin;
use Illuminate\Http\Request;
use Exception;

class LuckySpinController extends Controller
{
    protected LuckySpinService $luckySpinService;

    public function __construct(LuckySpinService $luckySpinService)
    {
        $this->luckySpinService = $luckySpinService;
    }

    public function index()
    {
        $allParticipants = $this->luckySpinService->getParticipants();
        $existingWinnerBadges = $this->luckySpinService->getExistingWinnerBadgeIds();

        $checkedInCount = $allParticipants->count();
        $winnersCount = count($existingWinnerBadges);

        $eligibleCount = $allParticipants->filter(function ($p) use ($existingWinnerBadges) {
            return !$p->is_manager && !in_array($p->badge_id, $existingWinnerBadges);
        })->count();

        $recentWinners = LuckySpin::with(['masterAttendance', 'prize'])
            ->latest()
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'draw' => $item->draw_number,
                'badge' => $item->badge_id,
                'name' => $item->masterAttendance->name ?? '-',
                'position' => $item->masterAttendance->position ?? '-',
                'department' => $item->masterAttendance->department ?? '-',
                'prize' => $item->prize->name ?? '-',
            ]);

        $prizes = $this->luckySpinService->getActivePrizes();

        return view('admin.lucky-spin.index', [
            'checkedIn' => $checkedInCount,
            'winners' => $winnersCount,
            'eligible' => $eligibleCount,
            'displayUrl' => route('admin.lucky-spin.display'),
            'participants' => $allParticipants->map(fn($p) => [
                'badge' => $p->badge_id,
                'name' => $p->name,
                'position' => $p->position,
                'department' => $p->department,
                'is_manager' => (bool)$p->is_manager,
            ])->values()->all(),
            'recentWinners' => $recentWinners,
            'prizes' => $prizes,
        ]);
    }

    /**
     * Endpoint AJAX: Mengundi Pemenang Baru dari Backend
     */
    public function draw()
    {
        try {
            $winnerData = $this->luckySpinService->drawWinner();

            return response()->json([
                'success' => true,
                'data' => $winnerData,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Endpoint AJAX: Pembatalan Pemenang berdasarkan Badge ID
     */
    public function forfeit($badge)
    {
        try {
            $this->luckySpinService->forfeitWinnerByBadge($badge);

            return response()->json([
                'success' => true,
                'message' => 'Pemenang berhasil dibatalkan dan stok hadiah telah dikembalikan.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Tampilan Terpisah untuk Layar Besar / Projector Display (Opsional)
     */
    public function display()
    {
        return view('admin.lucky-spin.display');
    }
}