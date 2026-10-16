@props(['user' => 'Admin User', 'role' => 'Administrator', 'company' => 'Seatrium', 'logo' => 'images/logo.png'])

@php
    $initial = mb_strtoupper(mb_substr($user, 0, 1));
@endphp

<header class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between gap-2 border-b border-violet-200/70 bg-white px-3 text-[#2e1065] shadow-sm shadow-violet-100/60 sm:px-6">
    <div class="flex min-w-0 items-center gap-2 sm:gap-3">
        <label for="sidebar-toggle" aria-label="Toggle sidebar" class="grid size-10 shrink-0 cursor-pointer place-items-center rounded-xl text-slate-500 transition duration-300 hover:bg-fuchsia-50 hover:text-fuchsia-600 active:scale-90">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </label>
        <span class="hidden truncate text-sm font-semibold tracking-wide min-[400px]:block">D&D 2026</span>
    </div>

    <div class="flex min-w-0 items-center gap-2 sm:gap-4 md:gap-6">
        <div class="hidden items-center gap-3 md:flex">
            <span class="grid size-10 shrink-0 place-items-center overflow-hidden rounded-xl bg-violet-50">
                <img src="{{ asset($logo) }}" alt="{{ $company }}" class="size-full object-contain p-1.5">
            </span>
            <span class="text-sm font-medium tracking-wide">{{ $company }}</span>
        </div>

        <div class="flex min-w-0 items-center gap-3">
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-gradient-to-br from-fuchsia-600 to-violet-700 text-sm font-semibold text-white shadow-lg shadow-fuchsia-400/30">{{ $initial }}</span>
            <div class="hidden min-w-0 leading-tight sm:block">
                <p class="max-w-[10rem] truncate text-sm font-medium">{{ $user }}</p>
                <p class="max-w-[10rem] truncate text-[11px] text-slate-500">{{ $role }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="h-9 rounded-lg border border-violet-200 bg-white px-3 text-xs font-medium transition duration-300 hover:-translate-y-0.5 hover:border-fuchsia-600 hover:text-fuchsia-600 hover:shadow-lg hover:shadow-fuchsia-100 active:scale-95">Logout</button>
        </form>
    </div>
</header>