<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAttendance;
use App\Models\Confirmation;
use App\Models\Attendance;
use App\Models\Prize;
use App\Models\EventControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $eventControl = EventControl::first();
        $invited = MasterAttendance::count();
        $confirmed = Confirmation::where('is_attending', true)->count();
        $declined = Confirmation::where('is_attending', false)->count();
        $attendingBadgeIds = Confirmation::query()
            ->where('is_attending', true)
            ->select('badge_id');
        $checkedIn = Attendance::query()
            ->whereIn('badge_id', $attendingBadgeIds)
            ->count();

        // Kalkulasi porsi yang belum check-in
        $pending = max(0, $confirmed - $checkedIn);

        // Kalkulasi Persentase
        $confirmedRate  = $invited > 0 ? round(($confirmed / $invited) * 100) : 0;
        $declinedRate   = $invited > 0 ? round(($declined / $invited) * 100) : 0;
        $checkedRate    = $confirmed > 0 ? round(($checkedIn / $confirmed) * 100) : 0;
        $notCheckedRate = max(0, 100 - $checkedRate);
        $confirmationPending = max(0, $invited - $confirmed - $declined);
        $percentage = fn (int $value, int $total) => $total > 0 ? round(($value / $total) * 100, 1) : 0;

        $charts = [
            [
                'title' => 'Confirmation',
                'subtitle' => "Based on {$invited} invited employees",
                'total' => $invited,
                'segments' => [
                    [
                        'label' => 'Confirmation yes',
                        'value' => $confirmed,
                        'percent' => $percentage($confirmed, $invited),
                        'color' => '#10b981',
                        'class' => 'bg-emerald-500',
                    ],
                    [
                        'label' => 'Confirmation no',
                        'value' => $declined,
                        'percent' => $percentage($declined, $invited),
                        'color' => '#f97316',
                        'class' => 'bg-orange-500',
                    ],
                    [
                        'label' => 'Pending',
                        'value' => $confirmationPending,
                        'percent' => $percentage($confirmationPending, $invited),
                        'color' => '#64748b',
                        'class' => 'bg-slate-500',
                    ],
                ],
            ],
            [
                'title' => 'Attendance',
                'subtitle' => "Based on {$confirmed} confirmed attendees",
                'total' => $confirmed,
                'segments' => [
                    [
                        'label' => 'Checked in',
                        'value' => $checkedIn,
                        'percent' => $percentage($checkedIn, $confirmed),
                        'color' => '#3563ff',
                        'class' => 'bg-[#3563ff]',
                    ],
                    [
                        'label' => 'Not checked in',
                        'value' => $pending,
                        'percent' => $percentage($pending, $confirmed),
                        'color' => '#a855f7',
                        'class' => 'bg-purple-500',
                    ],
                ],
            ],
        ];

        return view('admin.dashboard', compact(
            'invited',
            'confirmed',
            'declined',
            'checkedIn',
            'pending',
            'confirmedRate',
            'declinedRate',
            'checkedRate',
            'notCheckedRate',
            'charts',
            'eventControl',
        ));
    }

    public function employee(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');
        $department = $request->query('department');
        $departments = MasterAttendance::query()
            ->whereNotNull('department')
            ->whereRaw("TRIM(department) <> ''")
            ->groupBy('department')
            ->orderBy('department')
            ->pluck('department');

        $employees = MasterAttendance::query()->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('badge_id', 'ilike', "%{$search}%")
                    ->orWhere('department', 'ilike', "%{$search}%");
            });
        })->when($department, fn ($query, $department) => $query->where('department', $department))
            ->paginate(15)
            ->withQueryString();

        $employees->getCollection()->transform(fn (MasterAttendance $employee) => [
            'badge' => $employee->badge_id,
            'name' => $employee->name,
            'position' => $employee->position ?? '-',
            'department' => $employee->department ?? '-',
            'is_manager' => (bool) $employee->is_manager,
        ]);

        return view('admin.employee.index', compact('employees', 'search', 'department', 'departments'));
    }

    public function updateManager(Request $request, string $badge)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'is_manager' => ['required', 'boolean'],
        ]);

        $employee = MasterAttendance::where('badge_id', $badge)->first();

        if (! $employee) {
            return response()->json(['message' => 'Employee not found.'], 404);
        }

        $employee->update(['is_manager' => $validated['is_manager']]);

        return response()->json([
            'success' => true,
            'badge_id' => $employee->badge_id,
            'is_manager' => (bool) $employee->is_manager,
        ]);
    }

    public function confirmation(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');
        $status = $request->query('status');
        $department = $request->query('department');

        $departments = MasterAttendance::query()
            ->whereNotNull('department')
            ->whereRaw("TRIM(department) <> ''")
            ->groupBy('department')
            ->orderBy('department')
            ->pluck('department');

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
            })
            ->when($department, fn ($query, $department) => $query->where('master_attendance.department', $department));

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
                if (is_null($isAttending)) {
                    $statusLabel = 'pending';
                } else {
                    $statusLabel = (bool) $isAttending ? 'attending' : 'declined';
                }
                return [
                    'badge' => $employee->badge_id,
                    'name' => $employee->name,
                    'position' => $employee->position ?? '-',
                    'department' => $employee->department ?? '-',
                    'status' => $statusLabel,
                    'confirmed_at' => $employee->rsvp_confirmed_at
                        ? Carbon::parse($employee->rsvp_confirmed_at)->format('H:i:s - d M Y')
                        : '-',
                ];
            });

        return view('admin.confirmation.index', compact('employees', 'search', 'status', 'department', 'departments'));
    }

    public function attendance(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');
        $department = trim($request->query('department', ''));

        $departments = MasterAttendance::query()
            ->whereNotNull('department')
            ->whereRaw("TRIM(department) <> ''")
            ->groupBy('department')
            ->orderBy('department')
            ->pluck('department');

        $departmentBadgeIds = null;
        if ($department !== '') {
            $departmentBadgeIds = MasterAttendance::query()
                ->where('department', $department)
                ->pluck('badge_id');
        }

        $searchBadgeIds = null; 
        if ($search !== '') { 
            $searchBadgeIds = MasterAttendance::query() 
            ->where(function ($query) use ($search) { 
                $query->where('badge_id', 'ilike', "%{$search}%") 
                ->orWhere('name', 'ilike', "%{$search}%") 
                ->orWhere('position', 'ilike', "%{$search}%") 
                ->orWhere('department', 'ilike', "%{$search}%"); 
            }) 
                ->pluck('badge_id'); 
            }

        $attendances = Attendance::query()

            ->when($searchBadgeIds !== null, function ($query) use ($searchBadgeIds) {
                $query->whereIn('badge_id', $searchBadgeIds);
            })
            ->when($departmentBadgeIds !== null, function ($query) use ($departmentBadgeIds) {
                $query->whereIn('badge_id', $departmentBadgeIds);
            })

            ->when($department, fn ($query, $department) => $query->whereHas('user', function ($query) use ($department) {
                $query->where('department', $department);
            }))

            ->latest('check_in_at')
            ->paginate(15)
            ->withQueryString();

        $badgeIds = $attendances
            ->pluck('badge_id')
            ->toArray();

        $employees = MasterAttendance::query() ->whereIn('badge_id', $badgeIds) ->get() ->keyBy('badge_id');

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

        return view('admin.attendance.index', compact('attendances', 'search', 'department', 'departments'));
    }


    public function prizes()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $prizes = Prize::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'stock',
                'current_stock',
            ])
            ->map(function (Prize $prize) {
                return [
                    'name' => $prize->name,
                    'stock' => $prize->stock,
                    'winner' => '-',
                    'badge' => '-',
                    'department' => '-',
                ];
            });

        return view('admin.prizes.index', compact('prizes'));
    }

    public function updateSchedule(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'event_start' => ['required', 'date'],
            'event_end' => ['required', 'date', 'after:event_start'],
        ]);

        $exists = DB::table('event_control')->exists();

        if ($exists) {
            DB::table('event_control')->update([
                'event_start' => $validated['event_start'],
                'event_end' => $validated['event_end'],
            ]);
        } else {
            DB::table('event_control')->insert([
                'event_start' => $validated['event_start'],
                'event_end' => $validated['event_end'],
            ]);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Check-in schedule updated successfully.');
    }
}
