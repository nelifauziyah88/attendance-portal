@props(['names', 'interactive' => false])

@php
    use Illuminate\Support\Str;

    $count = count($names);
    $step = 360 / $count;
    $colors = ['#3563ff', '#5b86ff', '#26346b', '#4a6fd6', '#2f57e0', '#7a9cff'];
    $point = fn ($angle, $radius) => [round($radius * sin(deg2rad($angle)), 3), round(-$radius * cos(deg2rad($angle)), 3)];

    $slices = collect($names)->map(function ($name, $index) use ($step, $colors, $point) {
        [$x1, $y1] = $point($index * $step, 92);
        [$x2, $y2] = $point(($index + 1) * $step, 92);

        return [
            'path' => "M0 0 L{$x1} {$y1} A92 92 0 0 1 {$x2} {$y2} Z",
            'color' => $colors[$index % count($colors)],
            'label' => Str::before($name, ' '),
            'angle' => $index * $step + $step / 2 - 90,
        ];
    });

    $tag = $interactive ? 'button' : 'div';
@endphp

<div {{ $attributes->class('relative aspect-square') }}>
    <svg class="absolute -top-[3%] left-1/2 z-20 w-[9%] -translate-x-1/2 drop-shadow-lg" viewBox="0 0 50 48">
        <polygon points="2,2 48,2 25,46" fill="#ffca4a" stroke="#f0a92e" stroke-width="3" stroke-linejoin="round"/>
    </svg>

    <svg data-wheel data-names="{{ json_encode($names) }}" viewBox="-100 -100 200 200" style="transform: rotate(0deg)" class="size-full drop-shadow-2xl transition-transform duration-[6500ms] ease-[cubic-bezier(.12,.7,.12,1)]">
        <circle r="99" fill="#d6e1ff"/>
        @foreach ($slices as $slice)
            <path d="{{ $slice['path'] }}" fill="{{ $slice['color'] }}" stroke="#e6edff" stroke-width="1.2"/>
            <text transform="rotate({{ $slice['angle'] }}) translate(84 0)" text-anchor="end" dominant-baseline="middle" fill="#fff" font-size="7.5" font-weight="600" letter-spacing=".2">{{ $slice['label'] }}</text>
        @endforeach
        <circle r="20" fill="#c9d8ff"/>
    </svg>

    <{{ $tag }} @if ($interactive) type="button" data-spin @endif @class([
        'absolute left-1/2 top-1/2 z-10 grid size-[19%] -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full border-[5px] border-blue-200 bg-white text-[clamp(.75rem,1.6vw,1.5rem)] font-semibold tracking-wide text-[#3563ff] shadow-lg',
        'transition duration-300 hover:scale-105 active:scale-95 disabled:pointer-events-none disabled:opacity-70' => $interactive,
    ])>SPIN</{{ $tag }}>
</div>