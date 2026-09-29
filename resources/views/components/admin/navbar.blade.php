@props(['user' => 'Admin User', 'role' => 'Administrator', 'company' => 'Seatrium', 'logo' => 'images/logo.png'])

@php
    $initial = mb_strtoupper(mb_substr($user, 0, 1));
@endphp

<header class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between border-b border-slate-200/80 bg-white px-4 sm:px-6">
    <div class="flex items-center gap-3">
        <label for="sidebar-toggle" aria-label="Toggle sidebar" class="grid size-10 cursor-pointer place-items-center rounded-xl text-slate-500 transition duration-300 hover:bg-blue-50 hover:text-[#3563ff] active:scale-90">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </label>
        <span class="grid size-10 place-items-center rounded-xl bg-[#3563ff] text-lg font-semibold text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:rotate-6 hover:scale-110">E</span>
        <span class="hidden text-sm font-semibold tracking-wide min-[400px]:block">EVENT PORTAL</span>
    </div>

    <div class="flex items-center gap-4 sm:gap-6">
        <div class="hidden items-center gap-3 md:flex">
            <span class="grid size-10 place-items-center overflow-hidden rounded-xl bg-blue-50">
                <img src="{{ asset($logo) }}" alt="{{ $company }}" class="size-full object-contain p-1.5">
            </span>
            <span class="text-sm font-medium tracking-wide">{{ $company }}</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-full bg-blue-50 text-sm font-semibold text-[#3563ff]">{{ $initial }}</span>
            <div class="hidden leading-tight sm:block">
                <p class="text-sm font-medium">{{ $user }}</p>
                <p class="text-[11px] text-slate-500">{{ $role }}</p>
            </div>
        </div>

        <form method="POST" action="#">
            @csrf
            <button type="submit" class="h-9 rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium transition duration-300 hover:-translate-y-0.5 hover:border-[#3563ff] hover:text-[#3563ff] hover:shadow-lg hover:shadow-blue-100 active:scale-95">Logout</button>
        </form>
    </div>
</header>