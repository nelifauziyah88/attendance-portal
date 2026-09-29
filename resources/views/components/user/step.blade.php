@props(['number', 'title', 'subtitle'])

<div class="flex items-center gap-3">
    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-xs font-semibold text-[#3563ff]">{{ $number }}</span>
    <div>
        <h3 class="text-lg font-semibold leading-tight">{{ $title }}</h3>
        <p class="text-xs text-slate-500">{{ $subtitle }}</p>
    </div>
</div>