<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Confirmation;
use App\Models\Attendance;
use App\Services\LuckySpinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()?->role !== 'ADMIN') {
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
        if (Auth::user()?->role !== 'ADMIN') {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');

        $employees = User::query()->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('badge_id', 'ilike', "%{$search}%")
                    ->orWhere('department', 'ilike', "%{$search}%");
            });
        })->paginate(15)->withQueryString();

        $employees->getCollection()->transform(fn (User $user) => [
            'badge' => $user->badge_id,
            'name' => $user->name,
            'position' => $user->position ?? '-',
            'department' => $user->department ?? '-',
        ]);

        return view('admin.employee.index', compact('employees', 'search'));
    }

    public function confirmation(Request $request)
    {
        if (Auth::user()?->role !== 'ADMIN') {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');
        $status = $request->query('status');

        $confirmations = Confirmation::query()->get()->keyBy('badge_id');
        $query = User::query()->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('badge_id', 'ilike', "%{$search}%")
                    ->orWhere('department', 'ilike', "%{$search}%");
            });
        });

        if ($status === 'attending') {
            $query->whereIn('badge_id', $confirmations->filter(fn ($item) => $item->is_attending)->keys());
        } elseif ($status === 'declined') {
            $query->whereIn('badge_id', $confirmations->reject(fn ($item) => $item->is_attending)->keys());
        } elseif ($status === 'pending') {
            $query->whereNotIn('badge_id', $confirmations->keys());
        }

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();
        $employees->getCollection()->transform(function (User $user) use ($confirmations) {
            $confirmation = $confirmations->get($user->badge_id);

            return [
                'badge' => $user->badge_id,
                'name' => $user->name,
                'position' => $user->position ?? '-',
                'department' => $user->department ?? '-',
                'status' => $confirmation === null ? 'pending' : ($confirmation->is_attending ? 'attending' : 'declined'),
            ];
        });

        return view('admin.confirmation.index', compact('employees', 'search', 'status'));
    }

    public function attendance(Request $request)
    {
        if (Auth::user()?->role !== 'ADMIN') {
            return redirect()->route('admin.login');
        }

        $search = $request->query('search');

        $attendances = Attendance::query()
            ->when($search, fn ($query) => $query->where('badge_id', 'ilike', "%{$search}%"))
            ->latest('check_in_at')
            ->paginate(15)
            ->withQueryString();

        $badgeIds = $attendances->pluck('badge_id')->toArray();
        $users = User::whereIn('badge_id', $badgeIds)->get()->keyBy('badge_id');

        $attendances->getCollection()->transform(function ($item) use ($users) {
            $user = $users->get($item->badge_id);

            return [
                'badge' => $item->badge_id,
                'name' => $user?->name ?? '-',
                'position' => $user?->position ?? '-',
                'department' => $user?->department ?? '-',
                'checkin' => $item->check_in_at?->format('H:i:s - d M Y') ?? '-',
            ];
        });

        return view('admin.attendance.index', compact('attendances', 'search'));
    }

    public function luckySpin(LuckySpinService $luckySpin)
    {
        if (Auth::user()?->role !== 'ADMIN') {
            return redirect()->route('admin.login');
        }

        return view('admin.lucky_spin.lucky_spin', [
            'displayUrl' => route('admin.lucky-spin.display'),
            'prizes' => $luckySpin->prizes(),
            'participants' => $luckySpin->participants(),
            'recentWinners' => $luckySpin->recentWinners(),
        ]);
    }

    public function luckySpinDisplay(LuckySpinService $luckySpin)
    {
        if (Auth::user()?->role !== 'ADMIN') {
            return redirect()->route('admin.login');
        }

        return view('admin.lucky_spin.lucky_spin_display', [
            'draw' => $luckySpin->winnerCount() + 1,
            'eligible' => $luckySpin->eligibleCount(),
            'prizes' => $luckySpin->prizes(),
            'participants' => $luckySpin->participants(),
        ]);
    }
}