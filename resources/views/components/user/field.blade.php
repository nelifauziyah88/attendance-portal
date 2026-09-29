@props(['label', 'name', 'placeholder', 'readonly' => false])

<div {{ $attributes->class('flex flex-col gap-1.5') }}>
    <label for="{{ $name }}" class="text-xs font-medium">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="text"
        placeholder="{{ $placeholder }}"
        @readonly($readonly)
        class="h-[52px] w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 text-sm outline-none transition duration-300 placeholder:text-slate-400 focus:-translate-y-0.5 focus:border-[#3563ff] focus:bg-white focus:ring-4 focus:ring-blue-100 read-only:cursor-default"
    >
</div>