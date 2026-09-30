<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg)
            }

            50% {
                transform: translateY(-10px) rotate(6deg)
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
    $event = ['name' => 'Annual Gala Dinner'];

    $invited = 900;
    $confirmed = 720;
    $checkedIn = 540;

    $declined = $invited - $confirmed;
    $pending = $confirmed - $checkedIn;
    $confirmedRate = round(($confirmed / $invited) * 100);
    $checkedRate = round(($checkedIn / $confirmed) * 100);

    $stats = [
        ['label' => 'INVITED EMPLOYEES', 'value' => $invited, 'note' => 'Total invitations sent'],
        ['label' => 'CONFIRMED ATTENDING', 'value' => $confirmed, 'note' => $confirmedRate . '% of invited'],
        ['label' => 'CHECKED IN', 'value' => $checkedIn, 'note' => $checkedRate . '% of confirmed'],
    ];

    $panels = [
        [
            'title' => 'Invitation responses',
            'subtitle' => "Based on {$invited} invited employees",
            'bars' => [
                ['label' => 'Confirmed attending', 'percent' => $confirmedRate, 'primary' => true],
                ['label' => 'Not attending', 'percent' => 100 - $confirmedRate, 'primary' => false],
            ],
            'footer' => [$confirmed . ' attending', $declined . ' declined'],
        ],
        [
            'title' => 'On-site attendance',
            'subtitle' => "Based on {$confirmed} confirmed attendees",
            'bars' => [
                ['label' => 'Checked in', 'percent' => $checkedRate, 'primary' => true],
                ['label' => 'Not checked in yet', 'percent' => 100 - $checkedRate, 'primary' => false],
            ],
            'footer' => [$checkedIn . ' checked in', $pending . ' pending'],
        ],
    ];

    $circles = ['left-6 top-0', 'left-0 top-8', 'left-12 top-8', 'left-6 top-16'];
@endphp

<body
    class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
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

                <section
                    class="relative mt-6 overflow-hidden rounded-2xl bg-gradient-to-r from-[#4468e8] via-[#3d68fa] to-[#6a94ff] px-5 py-6 text-white shadow-xl shadow-blue-200/60 sm:px-8 sm:py-7 sm:pr-40 [animation:rise_.7s_.1s_ease-out_both]">
                    <div class="pointer-events-none absolute right-8 top-1/2 hidden size-28 -translate-y-1/2 sm:block">
                        <div class="relative size-full [animation:float_9s_ease-in-out_infinite]">
                            @foreach ($circles as $circle)
                                <span
                                    class="absolute h-16 w-14 rounded-full border border-white/50 {{ $circle }}"></span>
                            @endforeach
                            <span
                                class="absolute left-[3.25rem] top-[3.25rem] size-4 rounded-full border border-white/60"></span>
                        </div>
                    </div>

                    <p class="text-[11px] font-medium tracking-widest text-blue-100">LIVE EVENT SUMMARY</p>
                    <h2 class="mt-2 break-words text-xl font-semibold tracking-tight sm:text-2xl">A clear view of every
                        guest.</h2>
                    <p class="mt-2 text-sm font-light text-blue-50">Track RSVP responses and check-ins as they happen.
                    </p>
                </section>

                <section class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($stats as $stat)
                        <article
                            class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-lg shadow-blue-100/50 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-200/60 sm:p-6 [animation:rise_.7s_ease-out_both]"
                            style="animation-delay: {{ 0.2 + $loop->index * 0.1 }}s">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-4xl font-semibold tracking-tight sm:text-5xl">
                                {{ number_format($stat['value']) }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $stat['note'] }}</p>
                        </article>
                    @endforeach
                </section>

                <div class="mt-8">
                    <h2 class="text-lg font-semibold tracking-tight sm:text-xl">Attendance overview</h2>
                    <p class="mt-1 text-xs text-slate-500">Sample figures for the dashboard layout</p>
                </div>

                <section class="mt-5 grid gap-4 sm:gap-6 lg:grid-cols-2">
                    @foreach ($panels as $panel)
                        <article
                            class="min-w-0 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-lg shadow-blue-100/50 sm:p-6 [animation:rise_.7s_.5s_ease-out_both]">
                            <h3 class="break-words text-base font-semibold tracking-tight sm:text-lg">
                                {{ $panel['title'] }}</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ $panel['subtitle'] }}</p>

                            <div class="mt-5 space-y-5 sm:mt-6">
                                @foreach ($panel['bars'] as $bar)
                                    <div>
                                        <div class="flex items-center justify-between gap-3 text-sm font-medium">
                                            <span class="min-w-0 truncate">{{ $bar['label'] }}</span>
                                            <span @class(['shrink-0', 'text-[#3563ff]' => $bar['primary']])>{{ $bar['percent'] }}%</span>
                                        </div>
                                        <div class="mt-2 h-3 overflow-hidden rounded-full bg-blue-50">
                                            <div @class([
                                                'h-full origin-left rounded-full [animation:grow_1.2s_.6s_ease-out_both]',
                                                'bg-[#3563ff]' => $bar['primary'],
                                                'bg-[#26346b]' => !$bar['primary'],
                                            ]) style="width: {{ $bar['percent'] }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div
                                class="mt-5 flex flex-wrap items-center justify-between gap-x-4 gap-y-1 text-xs text-slate-500 sm:mt-6">
                                @foreach ($panel['footer'] as $text)
                                    <span>{{ $text }}</span>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </section>

                <p class="mt-8 text-xs text-slate-400">Last updated: Event day &middot; Values shown are sample data</p>
            </main>
        </div>
    </div>
</body>

</html>
