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
        class="h-[52px] w-full min-w-0 rounded-xl border border-violet-200 bg-violet-50/50 px-4 text-base outline-none transition duration-300 placeholder:text-violet-300 focus:-translate-y-0.5 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 read-only:cursor-default sm:text-sm"
    >

    @error($name)
        <p class="text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>