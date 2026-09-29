@extends('layouts.user')

@section('title', 'On-site Check-in')

@section('badge', 'EMPLOYEE CHECK-IN')

@php
    $employee = $employee ?? [
        'badge' => 'EMP-00124',
        'name' => 'Alex Morgan',
        'position' => 'Software Engineer',
        'department' => 'Information Technology',
    ];

    $details = [
        'Badge ID' => $employee['badge'],
        'Name' => $employee['name'],
        'Position' => $employee['position'],
        'Department' => $employee['department'],
    ];

    $calendar = '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>';

    $info = [
        ['icon' => $calendar, 'text' => 'Annual Gala Dinner'],
        ['icon' => $calendar, 'text' => '15 November 2026'],
        ['icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>', 'text' => '7:00 PM'],
        ['icon' => '<path d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/>', 'text' => 'Grand Ballroom'],
    ];

    $steps = [
        ['number' => '01', 'label' => 'RSVP Complete', 'done' => true],
        ['number' => '02', 'label' => 'Event Check-in', 'done' => false],
    ];

    $clusters = [
        [
            'position' => '-left-4 [animation:drift_9s_ease-in-out_infinite]',
            'circles' => ['left-6 top-0', 'left-0 top-8', 'left-12 top-8', 'left-6 top-16'],
        ],
        [
            'position' => '-right-4 [animation:drift_11s_ease-in-out_infinite_reverse]',
            'circles' => ['right-6 top-0', 'right-0 top-8', 'right-12 top-8', 'right-6 top-16'],
        ],
    ];
@endphp

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-b from-[#4468e8] via-[#3d68fa] to-[#8fb0ff] px-4 pb-40 pt-10 text-center text-white sm:pb-44 sm:pt-16">
        @foreach ($clusters as $cluster)
            <div class="pointer-events-none absolute top-1/3 hidden size-28 opacity-70 md:block {{ $cluster['position'] }}">
                @foreach ($cluster['circles'] as $circle)
                    <span class="absolute h-16 w-14 rounded-full border border-white/40 {{ $circle }}"></span>
                @endforeach
            </div>
        @endforeach

        <div class="relative mx-auto max-w-5xl [animation:rise_.8s_ease-out_both]">
            <p class="text-[10px] font-medium tracking-[.3em] text-blue-100 sm:text-xs sm:tracking-[.4em]">ON-SITE CHECK-IN</p>
            <h1 class="mt-3 text-balance text-3xl font-semibold tracking-tight min-[400px]:text-4xl sm:text-5xl lg:text-6xl">Welcome to the Gala</h1>
            <p class="mx-auto mt-3 max-w-xs text-sm font-light text-blue-50 sm:max-w-none sm:text-lg">Confirm your arrival to record your attendance.</p>

            <div class="mx-auto mt-8 grid w-full max-w-4xl grid-cols-1 gap-3 rounded-2xl border border-white/30 bg-white/10 px-5 py-4 text-sm backdrop-blur-sm min-[520px]:grid-cols-2 lg:flex lg:items-center lg:justify-center lg:gap-8 lg:px-6">
                @foreach ($info as $item)
                    @unless ($loop->first)
                        <span class="hidden h-6 w-px bg-white/30 lg:block"></span>
                    @endunless

                    <span class="flex items-center justify-center gap-3 transition duration-300 hover:-translate-y-0.5">
                        <svg class="size-5 shrink-0 text-blue-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                        {{ $item['text'] }}
                    </span>
                @endforeach
            </div>
        </div>

        <svg class="pointer-events-none absolute inset-x-0 -bottom-px h-16 w-full sm:h-24" viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path d="M0 60 C240 10 480 10 720 55 S1200 100 1440 45 V100 H0 Z" fill="#ffffff" fill-opacity=".35"/>
            <path d="M0 55 C240 100 480 100 720 60 S1200 10 1440 50 V100 H0 Z" fill="#f5f8ff"/>
        </svg>
    </section>

    <section class="relative z-10 -mt-32 flex flex-1 flex-col items-center px-3 pb-8 sm:-mt-36 sm:px-6">
        <article class="w-full max-w-3xl rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xl shadow-blue-100/60 sm:p-10 [animation:rise_.8s_.2s_ease-out_both]">
            <div class="grid items-center gap-6 sm:grid-cols-[auto_1fr] sm:gap-12">
                <span class="mx-auto grid size-24 place-items-center rounded-full bg-gradient-to-br from-blue-50 to-blue-100 shadow-inner transition duration-500 hover:scale-105 sm:size-36">
                    <svg class="size-12 text-[#3563ff] sm:size-16" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4.2"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7z"/></svg>
                </span>

                <dl class="min-w-0">
                    @foreach ($details as $label => $value)
                        <div @class([
                            'grid grid-cols-[5.5rem_1fr] items-center gap-3 py-3 text-sm sm:grid-cols-[8rem_1fr] sm:py-3.5 sm:text-base',
                            'border-b border-slate-200' => ! $loop->last,
                            'pt-0' => $loop->first,
                            'pb-0' => $loop->last,
                        ])>
                            <dt class="text-slate-500">{{ $label }}</dt>
                            <dd class="min-w-0 break-words font-medium">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </article>

        <form method="POST" action="#" class="mt-8 w-full max-w-xl text-center [animation:rise_.8s_.35s_ease-out_both]">
            @csrf
            <input type="hidden" name="badge_id" value="{{ $employee['badge'] }}">

            <button type="submit" class="relative flex h-16 w-full items-center justify-center overflow-hidden whitespace-nowrap rounded-xl bg-[#3563ff] px-5 text-base font-medium text-white shadow-lg shadow-blue-400/40 transition duration-300 hover:-translate-y-0.5 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/50 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200 active:scale-[.98] sm:text-lg">
                Confirm My Attendance
                <span class="pointer-events-none absolute inset-y-0 left-0 w-1/4 -skew-x-12 bg-white/20 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
            </button>

            <p class="mx-auto mt-4 max-w-xs text-xs text-slate-500 sm:max-w-none sm:text-sm">Please confirm only when you have arrived at the venue.</p>
        </form>

        <ol class="mt-10 flex flex-wrap items-center justify-center gap-x-4 gap-y-4 [animation:rise_.8s_.5s_ease-out_both]">
            @foreach ($steps as $step)
                @unless ($loop->first)
                    <li class="hidden h-px w-10 bg-slate-300 min-[420px]:block sm:w-16" aria-hidden="true"></li>
                @endunless

                <li class="flex items-center gap-3">
                    @if ($step['done'])
                        <span class="grid size-10 shrink-0 place-items-center rounded-full bg-blue-50 text-slate-400 sm:size-11">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        </span>
                    @else
                        <span class="relative grid size-10 shrink-0 place-items-center rounded-full bg-[#3563ff] text-sm font-medium text-white shadow-lg shadow-blue-400/40 sm:size-11">
                            <span class="absolute inset-0 animate-ping rounded-full bg-[#3563ff]/30"></span>
                            <span class="relative">{{ $step['number'] }}</span>
                        </span>
                    @endif

                    <span @class(['text-xs leading-tight sm:text-sm', 'text-slate-500' => $step['done'], 'text-[#3563ff]' => ! $step['done']])>
                        <span class="block text-[11px]">{{ $step['number'] }}</span>
                        {{ $step['label'] }}
                    </span>
                </li>
            @endforeach
        </ol>

        <p class="mt-10 text-center text-xs text-slate-400">&copy; {{ date('Y') }} Seatrium. All rights reserved.</p>
    </section>
@endsection