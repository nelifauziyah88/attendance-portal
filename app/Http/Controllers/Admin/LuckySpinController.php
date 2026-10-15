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
        $stats = $this->luckySpinService->getStatistics();

        $recentWinners = LuckySpin::activeWinner()->with(['masterAttendance', 'prize'])
            ->latest()
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'draw' => $item->id,
                'badge' => $item->badge_id,
                'name' => $item->masterAttendance->name ?? '-',
                'position' => $item->masterAttendance->position ?? '-',
                'department' => $item->masterAttendance->department ?? '-',
                'prize' => $item->prize->name ?? '-',
            ]);

        $prizes = $this->luckySpinService->getActivePrizes();

        return view('admin.lucky_spin.lucky_spin', [
            'checkedIn' => $stats['checkedIn'],
            'winners' => $stats['winners'],
            'winnerSlots' => $stats['winnerSlots'],
            'eligible' => $stats['eligible'],
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

    public function draw(Request $request)
    {
        $request->validate([
            'participant_badges'   => 'required|array|min:1',
            'participant_badges.*' => 'string',
        ]);

        try {
            $winner = $this->luckySpinService->drawWinner(
                $request->input('participant_badges')
            );

            return response()->json([
                'success' => true,
                'index'   => $winner['index'],
                'stats'   => $this->luckySpinService->getStatistics(),
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
                'message' => 'Forfeit berhasil. Slot undian tersedia kembali dan peserta tetap tidak eligible.',
                'stats' => $this->luckySpinService->getStatistics(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function remove($badge)
    {
        try {
            $this->luckySpinService->removeWinnerByBadge($badge);

            return response()->json([
                'success' => true,
                'message' => 'Catatan pemenang berhasil dihapus. Data check-in tetap tersimpan.',
                'stats' => $this->luckySpinService->getStatistics(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function resetDrawings()
    {
        try {
            $this->luckySpinService->resetDrawings();

            return response()->json([
                'success' => true,
                'message' => 'Semua riwayat drawing berhasil direset. Check-in tetap tersimpan.',
                'stats' => $this->luckySpinService->getStatistics(),
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
        $stats = $this->luckySpinService->getStatistics();
        $participants = $this->luckySpinService->getParticipants()
            ->map(fn ($participant) => [
                'badge' => $participant->badge_id,
                'name' => $participant->name,
                'position' => $participant->position ?? '-',
                'department' => $participant->department ?? '-',
            ])
            ->values();
        $recentWinners = LuckySpin::activeWinner()->with(['masterAttendance', 'prize'])
            ->latest()
            ->get()
            ->map(fn ($winner) => [
                'draw' => $winner->id,
                'badge' => $winner->badge_id,
                'name' => $winner->masterAttendance?->name ?? '-',
                'position' => $winner->masterAttendance?->position ?? '-',
                'department' => $winner->masterAttendance?->department ?? '-',
            ])
            ->values();

        return view('admin.lucky_spin.lucky_spin_display', [
            'draw' => $stats['winners'] + 1,
            'eligible' => $stats['eligible'],
            'winnerSlots' => $stats['winnerSlots'],
            'participants' => $participants,
            'recentWinners' => $recentWinners,
            'drawUrl' => route('admin.lucky-spin.draw'),
            'forfeitUrlTemplate' => route('admin.lucky-spin.forfeit', ['badge' => 'BADGE_PLACEHOLDER']),
            'csrfToken' => csrf_token(),
        ]);
    }
}
