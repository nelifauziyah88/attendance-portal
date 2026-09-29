@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'size' => 'md'])

@php
    $tag = $href ? 'a' : 'button';

    $variants = [
        'primary' => 'bg-[#3563ff] text-white shadow-lg shadow-blue-400/30 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/40',
        'outline' => 'border-2 border-slate-200 bg-white text-[#26346b] hover:border-[#3563ff] hover:text-[#3563ff] hover:shadow-lg hover:shadow-blue-100',
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
        'hover:-translate-y-0.5 active:scale-95 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200',
        'disabled:pointer-events-none disabled:opacity-60',
        $sizes[$size],
        $variants[$variant],
    ]) }}
>
    {{ $slot }}
</{{ $tag }}>