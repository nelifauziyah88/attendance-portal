@props(['active' => 'dashboard'])

@php
    $items = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'href' => route('admin.dashboard'),
            'icon' =>
                '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
        ],
        [
            'key' => 'information-list',
            'label' => 'Information List',
            'href' => route('admin.employee.index'),
            'icon' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        ],
        [
            'key' => 'confirmation-attendance',
            'label' => 'Confirmation Attendance',
            'href' => route('admin.confirmation.index'),
            'icon' => '<path d="M9 11l3 3 8-8"/><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h9"/>',
        ],
        [
            'key' => 'attendance-list',
            'label' => 'Attendance List',
            'href' => route('admin.attendance.index'),
            'icon' =>
                '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1_0_7.8"/>',
        ],
        [
            'key' => 'lucky-spin',
            'label' => 'Lucky Spin',
            'href' => url('admin/lucky-spin'),
            'icon' => '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/>',
        ],
        [
            'key' => 'prizes',
            'label' => 'Prizes',
            'href' => url('admin/prizes'),
            'icon' => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
        ],
    ];
@endphp

<label for="sidebar-toggle"
    class="pointer-events-none fixed inset-0 top-16 z-20 bg-[#2e1065]/30 opacity-0 transition duration-300 group-has-[#sidebar-toggle:checked]/shell:pointer-events-auto group-has-[#sidebar-toggle:checked]/shell:opacity-100 lg:hidden"></label>

<aside
    class="fixed bottom-0 left-0 top-16 z-30 w-64 max-w-[85vw] -translate-x-full overflow-hidden border-r border-violet-200/70 bg-white shadow-2xl shadow-violet-900/10 transition-[width,transform] duration-300 group-has-[#sidebar-toggle:checked]/shell:translate-x-0 lg:sticky lg:bottom-auto lg:h-[calc(100vh-4rem)] lg:max-w-none lg:shrink-0 lg:translate-x-0 lg:shadow-none lg:group-has-[#sidebar-toggle:checked]/shell:w-0 lg:group-has-[#sidebar-toggle:checked]/shell:border-r-0">
    <div class="flex h-full w-64 flex-col">
        <nav class="flex-1 overflow-y-auto overscroll-contain p-3 sm:p-4">
            <ul class="mt-4 space-y-1.5">
                @foreach ($items as $item)
                    @php($isActive = $item['key'] === $active)
                    <li>
                        <a href={{ $item['href'] }} @class([
                            'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition duration-300',
                            'bg-fuchsia-50 font-medium text-fuchsia-600' => $isActive,
                            'text-slate-500 hover:translate-x-1 hover:bg-violet-50 hover:text-[#2e1065]' => !$isActive,
                        ])>
                            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <x-admin.footer />
    </div>
</aside>
