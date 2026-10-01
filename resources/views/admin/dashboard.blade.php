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
                ['label' => 'Not attending', 'percent' => $declinedRate, 'primary' => false],
            ],
            'footer' => [number_format($confirmed) . ' attending', number_format($declined) . ' declined'],
        ],
        [
            'title' => 'On-site attendance',
            'subtitle' => "Based on {$confirmed} confirmed attendees",
            'bars' => [
                ['label' => 'Checked in', 'percent' => $checkedRate, 'primary' => true],
                ['label' => 'Not checked in yet', 'percent' => $notCheckedRate, 'primary' => false],
            ],
            'footer' => [number_format($checkedIn) . ' checked in', number_format($pending) . ' pending'],
        ],
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
                            class="min-w-0 rounded-2xl border border-violet-200/70 bg-white p-5 shadow-lg shadow-violet-100/50 sm:p-6 [animation:rise_.7s_.5s_ease-out_both]">
                            <h3 class="break-words text-base font-semibold tracking-tight sm:text-lg">
                                {{ $panel['title'] }}</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ $panel['subtitle'] }}</p>

                            <div class="mt-5 space-y-5 sm:mt-6">
                                @foreach ($panel['bars'] as $bar)
                                    <div>
                                        <div class="flex items-center justify-between gap-3 text-sm font-medium">
                                            <span class="min-w-0 truncate">{{ $bar['label'] }}</span>
                                            <span @class(['shrink-0', 'text-fuchsia-600' => $bar['primary']])>{{ $bar['percent'] }}%</span>
                                        </div>
                                        <div class="mt-2 h-3 overflow-hidden rounded-full bg-violet-50">
                                            <div @class([
                                                'h-full origin-left rounded-full [animation:grow_1.2s_.6s_ease-out_both]',
                                                'bg-gradient-to-r from-fuchsia-600 to-violet-700' => $bar['primary'],
                                                'bg-[#2e1065]' => !$bar['primary'],
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

                <p class="mt-8 text-xs text-slate-400">Statistics are based on the latest stored records.</p>
            </main>
        </div>
    </div>
</body>

</html>
