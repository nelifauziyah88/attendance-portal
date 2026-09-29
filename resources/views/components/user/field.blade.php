@props(['label', 'name', 'placeholder' => '', 'type' => 'text', 'value' => null, 'readonly' => false])

<div {{ $attributes->class('flex min-w-0 flex-col gap-1.5') }}>
    <label for="{{ $name }}" class="text-xs font-medium">{{ $label }}</label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @readonly($readonly)
        class="h-[52px] w-full min-w-0 rounded-xl border border-slate-200 bg-slate-50/60 px-4 text-base outline-none transition duration-300 placeholder:text-slate-400 focus:-translate-y-0.5 focus:border-[#3563ff] focus:bg-white focus:ring-4 focus:ring-blue-100 read-only:cursor-default sm:text-sm"
    >

    @error($name)
        <p class="text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>