@props(['number', 'title', 'subtitle'])

<div class="flex items-center gap-3">
    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-xs font-semibold text-[#3563ff] sm:size-11">{{ $number }}</span>

    <div class="min-w-0">
        <h3 class="text-base font-semibold leading-tight sm:text-lg">{{ $title }}</h3>
        <p class="mt-0.5 text-xs leading-snug text-slate-500">{{ $subtitle }}</p>
    </div>
</div>