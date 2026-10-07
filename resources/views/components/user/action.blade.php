@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'size' => 'md'])

@php
    $tag = $href ? 'a' : 'button';

    $variants = [
        'primary' => 'bg-gradient-to-r from-fuchsia-600 to-violet-700 text-white shadow-lg shadow-fuchsia-500/30 ring-1 ring-cyan-300/40 hover:brightness-110 hover:shadow-xl hover:shadow-fuchsia-500/50 hover:ring-cyan-300/70',
        'outline' => 'border-2 border-violet-200 bg-white text-[#3b0764] hover:border-fuchsia-500 hover:text-fuchsia-600 hover:shadow-lg hover:shadow-fuchsia-100',
    ];

    $sizes = [
        'md' => 'h-14',
        'sm' => 'h-[52px]',
    ];
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->class([
        'flex items-center justify-center gap-2 whitespace-nowrap rounded-xl px-5 text-sm font-medium transition duration-300',
        'hover:-translate-y-0.5 active:scale-95 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-fuchsia-200',
        'disabled:pointer-events-none disabled:opacity-60',
        $sizes[$size],
        $variants[$variant],
    ]) }}
>
    {{ $slot }}
</{{ $tag }}>