@props(['participants', 'size' => 'md'])

@php
    $repeats = 6;

    $sizes = [
        'md' => [
            'root' => '[--h:4.5rem] sm:[--h:5.5rem]',
            'name' => 'text-base min-[400px]:text-lg sm:text-2xl',
            'badge' => 'text-[10px] sm:text-xs',
        ],
        'lg' => [
            'root' => '[--h:5rem] sm:[--h:6.5rem] lg:[--h:7.5rem] xl:[--h:9rem]',
            'name' => 'text-xl uppercase min-[400px]:text-2xl sm:text-4xl lg:text-5xl xl:text-6xl',
            'badge' => 'text-xs sm:text-sm lg:text-base xl:text-lg',
        ],
    ];

    $config = $sizes[$size] ?? $sizes['md'];
    $list = collect($participants)->values();
    $items = collect(range(1, $repeats))->flatMap(fn() => $list)->values();
@endphp

<div {{ $attributes->class(['relative w-full min-w-0', $config['root']]) }} data-reel
    data-participants="{{ json_encode($list) }}">
    <div
        class="relative h-[var(--h)] overflow-hidden rounded-xl border-2 border-[#5ad2ff] bg-[#1b0850]/50 shadow-[0_0_24px_rgba(74,168,255,.7),inset_0_0_18px_rgba(74,168,255,.35)] ring-4 ring-[#2f6bff]/40 sm:rounded-2xl sm:shadow-[0_0_40px_rgba(74,168,255,.7),inset_0_0_28px_rgba(74,168,255,.35)]">
        <div
            class="h-full overflow-hidden [mask-image:linear-gradient(to_bottom,transparent,black_12%,black_88%,transparent)]">
            <ul data-strip
                class="transition-transform duration-[6500ms] ease-[cubic-bezier(.12,.7,.12,1)] will-change-transform"
                style="--slot: 0; transform: translateY(calc(var(--slot) * var(--h) * -1))">
                @foreach ($items as $item)
                    <li
                        class="flex h-[var(--h)] min-w-0 flex-col items-center justify-center gap-0.5 overflow-hidden px-3 text-center text-white sm:gap-1 sm:px-4">
                        <span
                            class="w-full truncate whitespace-nowrap font-semibold leading-tight tracking-tight {{ $config['name'] }}">{{ $item['name'] }}</span>
                        <span
                            class="w-full truncate whitespace-nowrap font-medium leading-tight tracking-widest text-[#bfe6ff] {{ $config['badge'] }}">{{ $item['badge'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>