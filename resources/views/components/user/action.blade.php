@props(['variant' => 'primary', 'href' => null])

@php
    $tag = $href ? 'a' : 'button';

    $styles = [
        'primary' => 'bg-[#3563ff] text-white shadow-lg shadow-blue-400/30 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/40',
        'outline' => 'border-2 border-slate-200 bg-white text-[#26346b] hover:border-[#3563ff] hover:text-[#3563ff] hover:shadow-lg hover:shadow-blue-100',
    ];
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="button" @endif
    {{ $attributes->class(['flex h-14 items-center justify-center gap-2 rounded-xl px-5 text-sm font-medium transition duration-300 hover:-translate-y-0.5 active:scale-95', $styles[$variant]]) }}
>
    {{ $slot }}
</{{ $tag }}>