<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAttendance;
use App\Models\Invitation;
use App\Models\Confirmation;
use App\Models\Attendance;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $invited   = Invitation::count();
        $confirmed = Confirmation::where('is_attending', true)->count();
        $declined  = Confirmation::where('is_attending', false)->count();
        $checkedIn = Attendance::count();

        // 2. Kalkulasi porsi yang belum check-in
        $pending = max(0, $confirmed - $checkedIn);

        // 3. Kalkulasi Persentase
        $confirmedRate  = $invited > 0 ? round(($confirmed / $invited) * 100) : 0;
        $declinedRate   = $invited > 0 ? round(($declined / $invited) * 100) : 0;
        $checkedRate    = $confirmed > 0 ? round(($checkedIn / $confirmed) * 100) : 0;
        $notCheckedRate = max(0, 100 - $checkedRate);

        return view('admin.dashboard', compact(
            'invited',
            'confirmed',
            'declined',
            'checkedIn',
            'pending',
            'confirmedRate',
            'declinedRate',
            'checkedRate',
            'notCheckedRate'
        ));
    }

    public function employee(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');

        $employees = MasterAttendance::query()->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('badge_id', 'ilike', "%{$search}%")
                    ->orWhere('department', 'ilike', "%{$search}%");
            });
        })->paginate(15)->withQueryString();

        $employees->getCollection()->transform(fn (MasterAttendance $employee) => [
            'badge' => $employee->badge_id,
            'name' => $employee->name,
            'position' => $employee->position ?? '-',
            'department' => $employee->department ?? '-',
        ]);

        return view('admin.employee.index', compact('employees', 'search'));
    }

    public function confirmation(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');
        $status = $request->query('status');

        $confirmations = Confirmation::query()
            ->select('badge_id', 'is_attending', 'confirmed_at');

        $query = MasterAttendance::query()
            ->leftJoinSub($confirmations, 'rsvp', function ($join) {
                $join->on('master_attendance.badge_id', '=', 'rsvp.badge_id');
            })
            ->select([
                'master_attendance.*',
                'rsvp.is_attending as rsvp_is_attending',
                'rsvp.confirmed_at as rsvp_confirmed_at',
            ])
            ->when($search, function ($query, $search) {
                $term = '%'.mb_strtolower(trim($search)).'%';
                $query->where(function ($query) use ($term) {
                    $query->whereRaw('LOWER(master_attendance.name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(master_attendance.badge_id) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(master_attendance.department) LIKE ?', [$term]);
                });
            });

        if ($status === 'attending') {
            $query->where('rsvp.is_attending', true);
        } elseif ($status === 'declined') {
            $query->where('rsvp.is_attending', false);
        } elseif ($status === 'pending') {
            $query->whereNull('rsvp.badge_id');
        }

        $employees = $query
            ->orderByRaw('CASE WHEN rsvp.confirmed_at IS NULL THEN 1 ELSE 0 END')
            ->orderByDesc('rsvp.confirmed_at')
            ->orderBy('master_attendance.name')
            ->paginate(15)
            ->withQueryString();

        $employees->getCollection()->transform(function (MasterAttendance $employee) {
            $isAttending = $employee->rsvp_is_attending;
            return [
                'badge' => $employee->badge_id,
                'name' => $employee->name,
                'position' => $employee->position ?? '-',
                'department' => $employee->department ?? '-',
                'status' => $isAttending === null ? 'pending' : ((bool) $isAttending ? 'attending' : 'declined'),
            ];
        });

        return view('admin.confirmation.index', compact('employees', 'search', 'status'));
    }

    public function attendance(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');

        $attendances = Attendance::query()
            ->when($search, fn ($query) => $query->where('badge_id', 'ilike', "%{$search}%"))
            ->latest('check_in_at')
            ->paginate(15)
            ->withQueryString();

        $badgeIds = $attendances->pluck('badge_id')->toArray();
        $employees = MasterAttendance::whereIn('badge_id', $badgeIds)->get()->keyBy('badge_id');

        $attendances->getCollection()->transform(function ($item) use ($employees) {
            $employee = $employees->get($item->badge_id);

            return [
                'badge' => $item->badge_id,
                'name' => $employee?->name ?? '-',
                'position' => $employee?->position ?? '-',
                'department' => $employee?->department ?? '-',
                'checkin' => $item->check_in_at?->format('H:i:s - d M Y') ?? '-',
            ];
        });

        return view('admin.attendance.index', compact('attendances', 'search'));
    }

    public function prizes()
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $prizes = Prize::query()
            ->with(['luckySpins.masterAttendance'])
            ->orderBy('name')
            ->get()
            ->map(function (Prize $prize) {
                $winners = $prize->luckySpins
                    ->map(fn ($spin) => $spin->masterAttendance)
                    ->filter();

                return [
                    'name' => $prize->name,
                    'stock' => $prize->stock,
                    'winner' => $winners->pluck('name')->unique()->implode(', '),
                    'badge' => $prize->luckySpins->pluck('badge_id')->implode(', '),
                    'department' => $winners->pluck('department')->filter()->unique()->implode(', '),
                ];
            });

        return view('admin.prizes.index', compact('prizes'));
    }
}