@props(['participants', 'size' => 'md'])

@php
    $repeats = 6;

    $sizes = [
        'md' => [
            'root' => '[--h:3rem] sm:[--h:3.5rem]',
            'text' => 'text-base sm:text-xl',
        ],
        'lg' => [
            'root' => '[--h:2.75rem] sm:[--h:3.5rem] xl:[--h:4.5rem]',
            'text' => 'text-xl uppercase sm:text-3xl xl:text-5xl',
        ],
    ];

    $config = $sizes[$size] ?? $sizes['md'];
    $list = collect($participants)->values();
    $items = collect(range(1, $repeats))->flatMap(fn () => $list)->values();
@endphp

<div {{ $attributes->class(['relative w-full', $config['root']]) }} data-reel data-participants="{{ json_encode($list) }}">
    <div class="pointer-events-none absolute inset-x-0 top-1/2 z-10 h-[var(--h)] -translate-y-1/2 rounded-2xl border border-white/70 bg-white/15 shadow-[0_0_40px_rgba(120,160,255,.55)]"></div>

    <div class="h-[calc(var(--h)*5)] overflow-hidden [mask-image:linear-gradient(to_bottom,transparent,black_30%,black_70%,transparent)]">
        <ul data-strip class="transition-transform duration-[6500ms] ease-[cubic-bezier(.12,.7,.12,1)] will-change-transform" style="--slot: 2; transform: translateY(calc((var(--slot) - 2) * var(--h) * -1))">
            @foreach ($items as $item)
                <li class="flex h-[var(--h)] items-center justify-center gap-4 whitespace-nowrap font-semibold tracking-tight text-white {{ $config['text'] }}">
                    <span>{{ $item['badge'] }}</span>
                    <span class="opacity-60">&bull;</span>
                    <span>{{ $item['name'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>