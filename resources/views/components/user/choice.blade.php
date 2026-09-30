@props(['name', 'value', 'label', 'sublabel' => null, 'checked' => false, 'disabled' => false])

@php
    $tones = [
        'yes' => [
            'box' => 'group-has-[:checked]:border-emerald-600 group-has-[:checked]:bg-emerald-50 group-has-[:checked]:shadow-emerald-200/60 group-has-[:focus-visible]:ring-emerald-100',
            'ring' => 'group-has-[:checked]:border-emerald-600',
            'dot' => 'from-emerald-400 to-emerald-700',
        ],
        'no' => [
            'box' => 'group-has-[:checked]:border-rose-600 group-has-[:checked]:bg-rose-50 group-has-[:checked]:shadow-rose-200/60 group-has-[:focus-visible]:ring-rose-100',
            'ring' => 'group-has-[:checked]:border-rose-600',
            'dot' => 'from-rose-400 to-red-700',
        ],
    ];

    $tone = $tones[$value] ?? [
        'box' => 'group-has-[:checked]:border-fuchsia-600 group-has-[:checked]:bg-fuchsia-50 group-has-[:checked]:shadow-fuchsia-200/60 group-has-[:focus-visible]:ring-fuchsia-100',
        'ring' => 'group-has-[:checked]:border-fuchsia-600',
        'dot' => 'from-fuchsia-500 to-violet-700',
    ];
@endphp

<label class="group relative block cursor-pointer">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" @checked($checked) @disabled($disabled) class="sr-only">

    <div class="{{ $tone['box'] }} flex min-h-[52px] items-center gap-3 rounded-xl border-2 border-violet-200 bg-white px-4 py-2.5 text-sm font-medium transition duration-300 hover:-translate-y-0.5 hover:border-fuchsia-300 hover:shadow-lg hover:shadow-fuchsia-100 group-has-[:checked]:shadow-md group-has-[:focus-visible]:ring-4">
        <span class="{{ $tone['ring'] }} grid size-5 shrink-0 place-items-center rounded-full border-2 border-violet-300 transition duration-300">
            <span class="{{ $tone['dot'] }} size-2.5 scale-0 rounded-full bg-gradient-to-br transition duration-300 group-has-[:checked]:scale-100"></span>
        </span>
        <span class="min-w-0">
            {{ $label }}
            @if ($sublabel)
                <span class="block text-xs font-normal italic text-slate-500">{{ $sublabel }}</span>
            @endif
        </span>
    </div>
</label>