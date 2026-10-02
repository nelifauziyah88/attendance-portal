<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes grow {
            from {
                transform: scaleX(0)
            }

            to {
                transform: scaleX(1)
            }
        }
    </style>
</head>
@php
    $stats = [
        ['label' => 'INVITED EMPLOYEES', 'value' => $invited],
        ['label' => 'CONFIRMED ATTENDING', 'value' => $confirmed],
        ['label' => 'CHECKED IN', 'value' => $checkedIn],
    ];

@endphp

<body
    class="bg-[#f8f4ff] font-normal text-[#2e1065] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="dashboard" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4 [animation:rise_.7s_ease-out_both]">
                    <div class="min-w-0">
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">Admin
                            Dashboard</h1>
                        <p class="mt-2 text-sm text-slate-500">Monitor invitations and on-site attendance for your
                            event.</p>
                    </div>
                </div>

            {{-- <button type="button" id="checkin-open" aria-haspopup="dialog"
                class="mt-6 flex w-full items-center justify-between gap-4 rounded-2xl border border-violet-200/70 bg-white p-5 text-left shadow-lg shadow-violet-100/50 transition duration-300 hover:shadow-xl hover:shadow-fuchsia-200/60 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-fuchsia-100 active:scale-[.99] sm:p-6 [animation:rise_.7s_.05s_ease-out_both]">
                <span class="flex min-w-0 items-center gap-4">
                    <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-fuchsia-600 to-violet-700 text-white shadow-md shadow-fuchsia-200/60">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="17" rx="2" />
                            <path d="M16 2v4" />
                            <path d="M8 2v4" />
                            <path d="M3 10h18" />
                            <path d="M12 13v3l2 1" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-[10px] font-semibold tracking-widest text-slate-500">SET CHECK-IN SCHEDULE</span>
                        <span id="checkin-summary" class="mt-1 block truncate text-sm font-semibold text-[#2e1065] sm:text-base">
                            @if(isset($setting) && $setting->checkin_start &&$setting->checkin_end)
                                {{ \Carbon\Carbon::parse($setting->checkin_start)->format('d M Y, H:i') }} - {{ \Carbon\Carbon::parse($setting->checkin_end)->format('d M Y, H:i') }}
                            @else
                                Not set yet
                            @endif
                        </span>
                    </span>
                </span>
                <span class="hidden shrink-0 items-center gap-1.5 rounded-xl border-2 border-violet-200 px-4 py-2 text-xs font-medium text-[#7a2cc0] sm:inline-flex">
                    Set schedule
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">
                        <path d="m9 6 6 6-6 6" />
                    </svg>
                </span>
            </button> --}}

                <section
                    class="relative mt-6 overflow-hidden rounded-2xl bg-gradient-to-r from-[#a3248f] via-[#5b1b7a] to-[#2e1065] px-5 py-6 text-white shadow-xl shadow-fuchsia-200/60 sm:px-8 sm:py-7 [animation:rise_.7s_.1s_ease-out_both]">
                    <p class="text-[11px] font-medium tracking-widest text-cyan-200">LIVE EVENT SUMMARY</p>
                    <h2 class="mt-2 break-words text-xl font-semibold tracking-tight sm:text-2xl">A clear view of every
                        guest.</h2>
                    <p class="mt-2 text-sm font-light text-violet-100">Track RSVP responses and check-ins as they
                        happen.
                    </p>
                </section>

                <section class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($stats as $stat)
                        <article
                            class="rounded-2xl border border-violet-200/70 bg-white p-5 shadow-lg shadow-violet-100/50 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-fuchsia-200/60 sm:p-6 [animation:rise_.7s_ease-out_both]"
                            style="animation-delay: {{ 0.2 + $loop->index * 0.1 }}s">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-4xl font-semibold tracking-tight sm:text-5xl">
                                {{ number_format($stat['value']) }}</p>
                        </article>
                    @endforeach
                </section>

                <div class="mt-8">
                    <h2 class="text-lg font-semibold tracking-tight sm:text-xl">Attendance overview</h2>
                    <p class="mt-1 text-xs text-slate-500">Live confirmation and check-in distribution</p>
                </div>

                <section class="mt-5 grid gap-4 sm:gap-6 lg:grid-cols-2">
                    @foreach ($charts as $chartIndex => $chart)
                        <article
                            class="min-w-0 rounded-2xl border border-violet-200/70 bg-white p-5 shadow-lg shadow-violet-100/50 sm:p-6 [animation:rise_.7s_.5s_ease-out_both]"
                            data-chart-card="{{ $chartIndex }}">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="break-words text-base font-semibold tracking-tight sm:text-lg">
                                        {{ $chart['title'] }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">{{ $chart['subtitle'] }}</p>
                                </div>
                                <span
                                    class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-[#7a2cc0]">{{ number_format($chart['total']) }}
                                    total</span>
                            </div>

                            <div class="mt-6 grid gap-6 md:grid-cols-[minmax(190px,240px),1fr] md:items-center">
                                <div class="relative mx-auto aspect-square w-full max-w-60">
                                    <svg viewBox="0 0 120 120" role="img"
                                        aria-label="{{ $chart['title'] }} pie chart" class="h-full w-full">
                                        <circle cx="60" cy="60" r="42" fill="none" stroke="#f1f5f9"
                                            stroke-width="22" />
                                        @php($offset = 0)
                                        @foreach ($chart['segments'] as $segmentIndex => $segment)
                                            @if ($segment['percent'] > 0)
                                                <circle cx="60" cy="60" r="42" fill="none"
                                                    stroke="{{ $segment['color'] }}" stroke-width="22"
                                                    pathLength="100"
                                                    stroke-dasharray="{{ $segment['percent'] }} {{ 100 - $segment['percent'] }}"
                                                    stroke-dashoffset="{{ -$offset }}"
                                                    transform="rotate(-90 60 60)" stroke-linecap="butt"
                                                    class="cursor-pointer outline-none transition duration-300 hover:opacity-80 focus:opacity-80"
                                                    tabindex="0" data-chart-segment="{{ $segmentIndex }}" />
                                            @endif
                                            @php($offset += $segment['percent'])
                                        @endforeach
                                    </svg>
                                    <div class="pointer-events-none absolute inset-0 grid place-items-center">
                                        <div class="text-center">
                                            <p data-chart-percent
                                                class="text-3xl font-semibold tracking-tight text-[#2e1065]">
                                                {{ $chart['segments'][0]['percent'] }}%</p>
                                            <p data-chart-label class="mt-1 text-xs font-medium text-slate-500">
                                                {{ $chart['segments'][0]['label'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @foreach ($chart['segments'] as $segmentIndex => $segment)
                                        <button type="button"
                                            class="flex w-full items-center justify-between gap-3 rounded-xl border border-violet-100 bg-violet-50/40 px-4 py-3 text-left transition duration-300 hover:border-violet-300 hover:bg-white focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-fuchsia-100"
                                            data-chart-segment="{{ $segmentIndex }}">
                                            <span class="flex min-w-0 items-center gap-3">
                                                <span @class(['size-3 shrink-0 rounded-full', $segment['class']])></span>
                                                <span class="min-w-0">
                                                    <span
                                                        class="block truncate text-sm font-semibold text-[#2e1065]">{{ $segment['label'] }}</span>
                                                    <span class="mt-0.5 block text-xs text-slate-500"
                                                        data-chart-detail="{{ $segmentIndex }}">{{ number_format($segment['value']) }}
                                                        data</span>
                                                </span>
                                            </span>
                                            <span
                                                class="shrink-0 text-sm font-semibold text-[#7a2cc0]">{{ $segment['percent'] }}%</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-5 rounded-xl border border-violet-100 bg-white px-4 py-3 text-sm">
                                <p class="font-semibold text-[#2e1065]" data-chart-active-title>
                                    {{ $chart['segments'][0]['label'] }}</p>
                                <p class="mt-1 text-xs text-slate-500" data-chart-active-detail>
                                    {{ number_format($chart['segments'][0]['value']) }} of
                                    {{ number_format($chart['total']) }} data,
                                    {{ $chart['segments'][0]['percent'] }}%
                                </p>
                            </div>
                        </article>
                    @endforeach
                </section>

                <p class="mt-8 text-xs text-slate-400">Statistics are based on the latest stored records.</p>
            </main>
        </div>
    </div>

    <dialog id="checkin-modal" aria-labelledby="checkin-title"
    class="m-auto w-[calc(100%-2rem)] max-w-lg rounded-2xl border border-violet-200/70 bg-white p-0 text-[#2e1065] shadow-2xl shadow-fuchsia-200/60 backdrop:bg-[#1a0640]/60 backdrop:backdrop-blur-sm">
    <form id="checkin-form" action="#" method="POST" class="p-5 sm:p-6">
        @csrf
        <input type="hidden" name="event_name" value="{{ $setting->event_name ?? 'D&D 2026' }}">
        <input type="hidden" id="checkin_start" name="checkin_start">
        <input type="hidden" id="checkin_end" name="checkin_end">

        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h2 id="checkin-title" class="text-lg font-semibold tracking-tight sm:text-xl">Set schedule</h2>
                <p class="mt-1 text-xs text-slate-500">Set when check-in opens and closes.</p>
            </div>
            <button type="button" data-checkin-close aria-label="Close"
                class="grid size-8 shrink-0 place-items-center rounded-lg text-slate-400 transition duration-300 hover:bg-violet-50 hover:text-[#2e1065]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-5 space-y-5">
            <fieldset>
                <legend class="text-[10px] font-semibold tracking-widest text-slate-500">CHECK-IN START</legend>
                <div class="mt-2 grid gap-3 sm:grid-cols-2">
                    <label class="block">
                        <span class="text-xs font-medium text-slate-500">Date</span>
                        <input type="date" id="checkin-start-date" required
                            value="{{ old('start_date', optional($setting->checkin_start ?? null)->format('Y-m-d')) }}"
                            class="mt-1 h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                    </label>
                    <label class="block">
                        <span class="text-xs font-medium text-slate-500">Time</span>
                        <input type="time" id="checkin-start-time" step="1" required
                            value="{{ old('start_time', optional($setting->checkin_start ?? null)->format('H:i:s')) }}"
                            class="mt-1 h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-[10px] font-semibold tracking-widest text-slate-500">CHECK-IN END</legend>
                <div class="mt-2 grid gap-3 sm:grid-cols-2">
                    <label class="block">
                        <span class="text-xs font-medium text-slate-500">Date</span>
                        <input type="date" id="checkin-end-date" required
                            value="{{ old('end_date', optional($setting->checkin_end ?? null)->format('Y-m-d')) }}"
                            class="mt-1 h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                    </label>
                    <label class="block">
                        <span class="text-xs font-medium text-slate-500">Time</span>
                        <input type="time" id="checkin-end-time" step="1" required
                            value="{{ old('end_time', optional($setting->checkin_end ?? null)->format('H:i:s')) }}"
                            class="mt-1 h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                    </label>
                </div>
            </fieldset>
        </div>

        <p id="checkin-error" role="alert" class="mt-4 hidden text-xs font-medium text-red-600"></p>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button type="button" data-checkin-close
                class="flex h-11 items-center justify-center rounded-xl border-2 border-violet-200 bg-white px-6 text-sm font-medium text-[#7a2cc0] transition duration-300 hover:border-[#7a2cc0] active:scale-[.98]">
                Cancel
            </button>
            <button type="submit"
                class="flex h-11 items-center justify-center rounded-xl bg-gradient-to-r from-[#c02a9c] to-[#6a2fe0] px-8 text-sm font-medium text-white shadow-lg shadow-fuchsia-400/40 transition duration-300 hover:-translate-y-0.5 hover:brightness-110 active:scale-[.98]">
                Confirm
            </button>
        </div>
    </form>
</dialog>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const charts = @json($charts);
        const modal = document.getElementById('checkin-modal');
        const openButton = document.getElementById('checkin-open');
        const form = document.getElementById('checkin-form');
        const summary = document.getElementById('checkin-summary');
        const error = document.getElementById('checkin-error');
        const startDate = document.getElementById('checkin-start-date');
        const startTime = document.getElementById('checkin-start-time');
        const endDate = document.getElementById('checkin-end-date');
        const endTime = document.getElementById('checkin-end-time');

        const formatDateTime = (value) => value.toLocaleString('en-GB', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
        const formatNumber = (value) => new Intl.NumberFormat('en-US').format(value);

        document.querySelectorAll('[data-chart-card]').forEach((card) => {
            const chart = charts[Number(card.dataset.chartCard)];
            const percent = card.querySelector('[data-chart-percent]');
            const label = card.querySelector('[data-chart-label]');
            const activeTitle = card.querySelector('[data-chart-active-title]');
            const activeDetail = card.querySelector('[data-chart-active-detail]');
            const triggers = card.querySelectorAll('[data-chart-segment]');

            const selectSegment = (segmentIndex) => {
                const segment = chart.segments[segmentIndex];

                percent.textContent = `${segment.percent}%`;
                label.textContent = segment.label;
                activeTitle.textContent = segment.label;
                activeDetail.textContent =
                    `${formatNumber(segment.value)} of ${formatNumber(chart.total)} data, ${segment.percent}%`;

                triggers.forEach((trigger) => {
                    const isActive = Number(trigger.dataset.chartSegment) === segmentIndex;
                    trigger.classList.toggle('ring-4', isActive);
                    trigger.classList.toggle('ring-fuchsia-100', isActive);
                    trigger.classList.toggle('border-violet-300', isActive);
                });
            };

            triggers.forEach((trigger) => {
                const segmentIndex = Number(trigger.dataset.chartSegment);
                trigger.addEventListener('click', () => selectSegment(segmentIndex));
                trigger.addEventListener('keydown', (event) => {
                    if (!['Enter', ' '].includes(event.key)) return;

                    event.preventDefault();
                    selectSegment(segmentIndex);
                });
            });
        });

        openButton.addEventListener('click', () => {
            error.classList.add('hidden');
            modal.showModal();
        });

        modal.querySelectorAll('[data-checkin-close]').forEach((button) => {
            button.addEventListener('click', () => modal.close());
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const start = new Date(`${startDate.value}T${startTime.value}`);
            const end = new Date(`${endDate.value}T${endTime.value}`);

            if (end <= start) {
                error.textContent = 'Check-in end must be after check-in start.';
                error.classList.remove('hidden');
                return;
            }

            summary.textContent = `${formatDateTime(start)} to ${formatDateTime(end)}`;
            modal.close();
        });
    });
</script>

</body>

</html>
