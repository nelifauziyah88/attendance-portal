<?php
namespace App\Http\Controllers;

use App\Models\MasterAttendance;
use App\Models\EventControl;
use App\Models\Attendance;
use App\Models\Confirmation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function index()
    {
        $eventControl = EventControl::first();

        if (!$eventControl) {
            abort(404);
        }

        $now = Carbon::now();

        if ($now->lt($eventControl->event_start)) {
            $scheduleStatus = 'upcoming';
        } elseif ($now->gt($eventControl->event_end)) {
            $scheduleStatus = 'ended';
        } else {
            $scheduleStatus = 'active';
        }

        return view('users.attendance.index', [
            'eventControl' => $eventControl,
            'scheduleStatus' => $scheduleStatus,
        ]);
    }

    public function findEmployee(string $badgeId)
    {
        $employee = MasterAttendance::where('badge_id', $badgeId)->first();

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'Badge ID tidak ditemukan dalam data karyawan.',
            ], 404);
        }

        $confirmation = Confirmation::where('badge_id', $badgeId)->first();

        if (! $confirmation || ! $confirmation->is_attending) {
            return response()->json([
                'success' => false,
                'message' => $confirmation
                    ? 'RSVP Anda tercatat tidak hadir. Check-in tidak diizinkan.'
                    : 'Anda belum RSVP hadir melalui halaman invitation. Check-in tidak diizinkan.',
            ], 403);
        }

        $attendance = Attendance::where('badge_id', $badgeId)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'badge_id' => $employee->badge_id,
                'name' => $employee->name,
                'position' => $employee->position ?? '-',
                'department' => $employee->department ?? '-',
                'is_checked_in' => $attendance !== null,
                'checked_in_at' => $attendance?->check_in_at?->format('H:i:s - d M Y'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'badge_id' => 'required|string',
        ], [
            'badge_id.required' => 'Badge ID wajib diisi.',
        ]);   

        $employee = MasterAttendance::where('badge_id', $request->badge_id)->first();
        if (!$employee) {
            return back()->with('error', 'Badge ID tidak terdaftar dalam sistem.');
        }

        $isAttending = Confirmation::where('badge_id', $request->badge_id)
            ->where('is_attending', true)
            ->exists();

        if (! $isAttending) {
            return back()->with('error', 'Check-in hanya tersedia untuk peserta yang sudah RSVP hadir.');
        }

        $existing = Attendance::where('badge_id',$request->badge_id)->first();
        if ($existing) {
            return back()->with('error', "Karyawan {$employee->name} sudah melakukan Check-In sebelumnya pada jam {$existing->check_in_at->format('H:i')} WIB.");
        }

        Attendance::create([
            'badge_id' => $request->badge_id,
            'check_in_at' => now(),
        ]);

        return back()->with('success', "Check-In Berhasil! Selamat datang, {$employee->name}. Silakan masuk ke area acara.");
    }
}