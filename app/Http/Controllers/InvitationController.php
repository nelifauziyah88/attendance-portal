<?php

namespace App\Http\Controllers;

use App\Models\MasterAttendance;
use App\Models\Confirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{

    public function index()
    {
        return view('users.invitations.index');
    }
public function findEmployee($badgeId)
    {
        try {
            $employee = MasterAttendance::where('badge_id', $badgeId)->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Badge ID tidak ditemukan dalam data karyawan.'
                ], 404);
            }

            $existingConfirmation = Confirmation::where('badge_id', $badgeId)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'badge_id'      => $employee->badge_id,
                    'name'          => $employee->name,
                    'position'      => $employee->position ?? '-',
                    'department'    => $employee->department ?? '-',
                    'has_confirmed' => $existingConfirmation ? true : false,
                    'attendance'    => $existingConfirmation ? ($existingConfirmation->is_attending ? 'yes' : 'no') : null,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error finding employee: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mencari data.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'badge_id'   => 'required|string',
            'attendance' => 'required|in:yes,no',
        ], [
            'badge_id.required'   => 'Badge ID wajib diisi.',
            'attendance.required' => 'Silakan pilih status kehadiran Anda.',
        ]);

        $employee = MasterAttendance::where('badge_id', $request->badge_id)->first();
        if (!$employee) {
            return back()->withInput()->with('error', 'Badge ID tidak ditemukan dalam sistem.');
        }

        if (Confirmation::where('badge_id', $request->badge_id)->exists()) {
            return back()->withInput()->with('error', 'Konfirmasi kehadiran Anda sudah tersimpan dan tidak dapat diubah.');
        }

        Confirmation::create([
            'badge_id' => $request->badge_id,
            'is_attending' => $request->attendance === 'yes',
            'confirmed_at' => now(),
        ]);

        $statusText = $request->attendance === 'yes' ? 'Hadir' : 'Tidak Hadir';

        return back()->with('success', "Terima kasih {$employee->name}! Konfirmasi kehadiran Anda ($statusText) telah berhasil disimpan.");
    }
}