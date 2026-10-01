<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin</title>
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

        @keyframes twinkle {

            0%,
            100% {
                opacity: .45
            }

            50% {
                opacity: 1
            }
        }
    </style>
</head>
@php
    $checkedIn = 540;
    $winners = 12;
    $eligible = $checkedIn - $winners;

    $displayUrl = $displayUrl ?? url('/admin/lucky-spin/display');

    $prizes = $prizes ?? [
        'Smart TV',
        'Air Fryer',
        'Electric Scooter',
        'Espresso Machine',
        'Smartwatch',
        'Shopping Voucher',
    ];

    $participants =
        $participants ??
        collect([
            ['Kevin Wijaya', 'Software Engineer', 'Information Technology'],
            ['Sarah Amelia', 'Marketing Manager', 'Marketing'],
            ['Bima Kurniawan', 'Project Engineer', 'Engineering'],
            ['Putri Maharani', 'HR Specialist', 'Human Resources'],
            ['Rizky Pratama', 'Finance Analyst', 'Finance'],
            ['Siti Rahma', 'Procurement Officer', 'Procurement'],
            ['Farhan Akbar', 'QA Engineer', 'Information Technology'],
            ['Rani Oktaviani', 'Marketing Executive', 'Marketing'],
            ['Andi Prasetyo', 'Site Supervisor', 'Operations'],
            ['Maya Lestari', 'Accountant', 'Finance'],
            ['Clara Anjani', 'HR Business Partner', 'Human Resources'],
            ['Yusuf Hidayat', 'Senior Software Engineer', 'Information Technology'],
            ['Auga', 'Senior Software Engineer', 'Information Technology'],
            ['Dhani', 'Senior Software Engineer', 'Information Technology'],
            ['Adam', 'Senior Software Engineer', 'Information Technology']
        ])
            ->map(
                fn($row, $index) => [
                    'badge' => 'BDG-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'name' => $row[0],
                    'position' => $row[1],
                    'department' => $row[2],
                ],
            )
            ->all();

    $recentWinners = $recentWinners ?? [
        [
            'draw' => 12,
            'badge' => 'BDG-0021',
            'name' => 'Alya Putri',
            'position' => 'Marketing Manager',
            'department' => 'Marketing',
            'prize' => 'Shopping Voucher',
        ],
    ];

    $stats = [
        ['label' => 'CHECKED IN', 'key' => 'checked', 'value' => $checkedIn],
        ['label' => 'WINNERS', 'key' => 'winners', 'value' => $winners],
        ['label' => 'ELIGIBLE TO SPIN', 'key' => 'eligible', 'value' => $eligible],
    ];

    $columns = [
        'badge' => 'Badge ID',
        'name' => 'Name',
        'position' => 'Position',
        'department' => 'Department',
        'prize' => 'Prize',
    ];

    $card = 'rounded-2xl border border-purple-200/70 bg-white p-4 shadow-lg shadow-purple-200/50 sm:p-6';

    $stars =
        '[background-image:radial-gradient(1.5px_1.5px_at_20px_30px,#fff,transparent),radial-gradient(2px_2px_at_90px_140px,#fff,transparent),radial-gradient(1.5px_1.5px_at_160px_60px,#fff,transparent),radial-gradient(2px_2px_at_210px_190px,#fff,transparent),radial-gradient(1px_1px_at_50px_200px,#fff,transparent)] [background-size:240px_240px] [animation:twinkle_4s_ease-in-out_infinite]';
@endphp

<body
    class="bg-[#f6f0ff] font-normal text-[#2a0b5c] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="lucky-spin" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8" data-draw data-winners="{{ $winners }}"
                data-eligible="{{ $eligible }}" data-history="{{ json_encode($recentWinners) }}"
                data-prizes="{{ json_encode($prizes) }}">
                <div class="flex flex-wrap items-start justify-between gap-4 [animation:rise_.7s_ease-out_both]">
                    <div class="min-w-0">
                        <h1
                            class="mt-1 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">
                            Lucky Spin</h1>
                        <p class="mt-2 text-sm text-slate-500">Select one eligible checked-in participant as the next
                            winner.</p>
                    </div>
                </div>

                <section class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($stats as $stat)
                        <article
                            class="{{ $card }} min-w-0 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-300/60 [animation:rise_.7s_ease-out_both]"
                            style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">{{ $stat['label'] }}</p>
                            <p data-stat="{{ $stat['key'] }}"
                                class="mt-2 text-4xl font-semibold tracking-tight sm:text-5xl">
                                {{ number_format($stat['value']) }}</p>
                        </article>
                    @endforeach
                </section>

                <section
                    class="relative mt-6 min-w-0 overflow-hidden rounded-2xl border-2 border-[#5ad2ff]/70 bg-gradient-to-tr from-[#a0237c] via-[#5a1a85] to-[#2a0b5c] px-4 py-6 shadow-[0_0_32px_rgba(74,168,255,.45)] sm:px-10 sm:py-8 [animation:rise_.7s_.4s_ease-out_both]">
                    <div class="{{ $stars }} pointer-events-none absolute inset-0"></div>

                    <div class="relative mb-3 text-center sm:mb-4">
                        <p data-result-note class="break-words text-xs font-medium tracking-widest text-purple-200"></p>
                        <p data-result-name
                            class="mt-1.5 break-words text-lg font-semibold leading-tight tracking-tight text-white empty:hidden sm:text-2xl">
                        </p>
                        <p data-result-badge
                            class="mt-0.5 break-words text-xs font-medium tracking-widest text-[#bfe6ff] empty:hidden sm:text-sm">
                        </p>
                        <p data-result-prize
                            class="mt-2 inline-block rounded-full border border-[#5ad2ff]/60 bg-white/15 px-4 py-1 text-xs font-semibold tracking-widest text-white empty:hidden sm:text-sm">
                        </p>
                    </div>

                    <x-admin.reel :participants="$participants" size="md" class="mx-auto max-w-3xl" />
                </section>

                <section class="mt-6 flex flex-col items-center gap-4">
                    <div class="{{ $card }} w-full max-w-md !p-4 text-center">
                        <label for="spin-duration"
                            class="text-[10px] font-semibold tracking-widest text-slate-500">SPIN DURATION</label>
                        <div class="mt-3 flex items-center justify-center gap-2">
                            <input id="spin-duration" type="number" inputmode="numeric" min="10" max="60"
                                step="1" value="10" data-duration-input
                                class="h-11 w-24 rounded-xl border border-purple-200 bg-white px-3 text-center text-base font-semibold text-[#2a0b5c] outline-none transition duration-300 focus:border-[#a0237c] focus:ring-4 focus:ring-purple-100 disabled:opacity-60">
                            <span class="text-sm text-slate-500">seconds</span>
                        </div>
                        <p class="mt-2 text-[10px] text-slate-400">Min 10 - Max 60 - Synced with display</p>
                    </div>

                    <div class="flex w-full max-w-md flex-wrap justify-center gap-3">
                        <button type="button" data-spin
                            class="flex h-12 min-w-[10rem] flex-1 items-center justify-center rounded-xl bg-gradient-to-r from-[#c02a9c] to-[#6a2fe0] px-6 text-sm font-medium text-white shadow-lg shadow-fuchsia-400/40 transition duration-300 hover:-translate-y-0.5 hover:brightness-110 hover:shadow-xl hover:shadow-fuchsia-400/50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-60">
                            Draw Winner
                        </button>
                        <a href="{{ $displayUrl }}" target="_blank" rel="noopener"
                            class="flex h-12 min-w-[10rem] flex-1 items-center justify-center rounded-xl border-2 border-purple-200 bg-white px-6 text-sm font-medium text-[#7a2cc0] transition duration-300 hover:-translate-y-0.5 hover:border-[#7a2cc0] hover:shadow-lg hover:shadow-purple-100 active:scale-[.98]">
                            Open Display
                        </a>
                        <button type="button" data-forfeit
                            class="hidden h-12 w-full items-center justify-center rounded-xl border-2 border-red-200 bg-white px-6 text-sm font-medium text-red-600 transition duration-300 hover:-translate-y-0.5 hover:border-red-400 hover:bg-red-50 active:scale-[.98]">
                            Forfeit Winner (Not Present)
                        </button>
                    </div>
                </section>

                <section class="{{ $card }} mt-6 min-w-0 [animation:rise_.7s_.5s_ease-out_both]">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold tracking-tight sm:text-xl">Winners</h2>

                        <button type="button" data-remove-all
                            class="hidden h-9 items-center justify-center rounded-lg border border-red-200 bg-white px-4 text-xs font-medium text-red-600 transition duration-300 hover:border-red-400 hover:bg-red-50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-50">
                            Remove All
                        </button>
                    </div>

                    <div class="mt-4 overflow-x-auto overscroll-x-contain">
                        <table class="w-full min-w-[40rem] text-left text-sm">
                            <thead>
                                <tr class="bg-purple-50/80 text-xs font-semibold text-[#2a0b5c]">
                                    @foreach ($columns as $label)
                                        <th class="whitespace-nowrap px-3 py-3 sm:px-4">{{ $label }}</th>
                                    @endforeach
                                    <th class="whitespace-nowrap px-3 py-3 sm:px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody data-winner-list class="divide-y divide-purple-100"></tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <template id="winner-template">
        <tr class="transition duration-300 hover:bg-purple-50/70 [animation:rise_.5s_ease-out_both]">
            @foreach ($columns as $field => $label)
                <td data-field="{{ $field }}"
                    class="px-3 py-3 sm:px-4 {{ $field === 'badge' ? 'whitespace-nowrap font-semibold' : '' }}"></td>
            @endforeach
            <td class="px-3 py-3 sm:px-4">
                <button type="button" data-remove title="Remove if not present"
                    class="inline-flex h-8 items-center justify-center rounded-lg border border-red-200 bg-white px-3 text-xs font-medium text-red-600 transition duration-300 hover:border-red-400 hover:bg-red-50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-50">
                    Remove
                </button>
            </td>
        </tr>
    </template>

    <script>
        const root = document.querySelector('[data-draw]');
        const reel = document.querySelector('[data-reel]');
        const strip = document.querySelector('[data-strip]');
        const triggers = document.querySelectorAll('[data-spin]');
        const forfeitButton = document.querySelector('[data-forfeit]');
        const removeAllButton = document.querySelector('[data-remove-all]');
        const note = document.querySelector('[data-result-note]');
        const resultName = document.querySelector('[data-result-name]');
        const resultBadge = document.querySelector('[data-result-badge]');
        const resultPrize = document.querySelector('[data-result-prize]');
        const list = document.querySelector('[data-winner-list]');
        const template = document.getElementById('winner-template');
        const durationInput = document.querySelector('[data-duration-input]');
        const channel = 'BroadcastChannel' in window ? new BroadcastChannel('lucky-spin') : null;

        const STORAGE_KEY = 'lucky-spin-state';
        const MIN_DURATION = 10;
        const MAX_DURATION = 60;
        const LOOPS = 2;
        const CONFETTI_COLORS = ['#ffffff', '#e9b6ff', '#ff5fd2', '#5ad2ff', '#ffd666'];

        const participants = JSON.parse(reel.dataset.participants);
        const prizes = JSON.parse(root.dataset.prizes);
        const count = participants.length;

        let confettiTimer = null;

        const prizeFor = (draw) => prizes.length ? prizes[(draw - 1) % prizes.length] : 'Special Prize';

        const fire = (options) => window.confetti?.({
            colors: CONFETTI_COLORS,
            disableForReducedMotion: true,
            ...options
        });

        const stopConfetti = () => {
            clearInterval(confettiTimer);
            confettiTimer = null;
        };

        const startConfetti = () => {
            stopConfetti();

            confettiTimer = setInterval(() => {
                fire({
                    particleCount: 4,
                    angle: 60,
                    spread: 55,
                    startVelocity: 45,
                    origin: {
                        x: 0,
                        y: 0.75
                    }
                });
                fire({
                    particleCount: 4,
                    angle: 120,
                    spread: 55,
                    startVelocity: 45,
                    origin: {
                        x: 1,
                        y: 0.75
                    }
                });
            }, 160);
        };

        const celebrate = () => {
            fire({
                particleCount: 160,
                spread: 100,
                startVelocity: 45,
                origin: {
                    x: 0.5,
                    y: 0.6
                }
            });

            setTimeout(() => {
                fire({
                    particleCount: 90,
                    angle: 60,
                    spread: 70,
                    startVelocity: 55,
                    origin: {
                        x: 0,
                        y: 0.7
                    }
                });
                fire({
                    particleCount: 90,
                    angle: 120,
                    spread: 70,
                    startVelocity: 55,
                    origin: {
                        x: 1,
                        y: 0.7
                    }
                });
            }, 250);
        };

        const load = () => {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY)) ?? {};
            } catch {
                return {};
            }
        };

        const clampDuration = (value) => {
            const seconds = Math.round(Number(value));
            if (!Number.isFinite(seconds)) return MIN_DURATION;
            return Math.min(MAX_DURATION, Math.max(MIN_DURATION, seconds));
        };

        const saved = load();

        const state = {
            winners: saved.winners ?? Number(root.dataset.winners),
            top: saved.top ?? saved.winners ?? Number(root.dataset.winners),
            redraw: saved.redraw ?? [],
            eligible: saved.eligible ?? Number(root.dataset.eligible),
            slot: count + ((saved.slot ?? 0) % Math.max(count, 1)),
            duration: clampDuration(saved.duration ?? MIN_DURATION),
            history: saved.history ?? JSON.parse(root.dataset.history),
            forfeited: saved.forfeited ?? [],
            spinning: false,
            current: null,
        };

        const save = () => {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                    ...load(),
                    winners: state.winners,
                    top: state.top,
                    redraw: state.redraw,
                    eligible: state.eligible,
                    slot: state.slot,
                    duration: state.duration,
                    history: state.history,
                    forfeited: state.forfeited
                }));
            } catch {
                return;
            }
        };

        const nextDraw = () => state.redraw.length ? state.redraw[0] : state.top + 1;

        const syncForfeit = () => {
            const visible = state.history.length > 0 && !state.spinning;
            forfeitButton.classList.toggle('hidden', !visible);
            forfeitButton.classList.toggle('flex', visible);
        };

        const syncRemoveAll = () => {
            const visible = state.history.length > 0;
            removeAllButton.classList.toggle('hidden', !visible);
            removeAllButton.classList.toggle('inline-flex', visible);
            removeAllButton.disabled = state.spinning;
        };

        const setSpinning = (value) => {
            state.spinning = value;
            triggers.forEach((trigger) => (trigger.disabled = value));
            durationInput.disabled = value;
            list.querySelectorAll('[data-remove]').forEach((button) => (button.disabled = value));
            syncForfeit();
            syncRemoveAll();
        };

        const setStat = (key, value) => {
            document.querySelector(`[data-stat="${key}"]`).textContent = value.toLocaleString();
        };

        const setResult = (title, winnerName = '', winnerBadge = '', winnerPrize = '') => {
            note.textContent = title;
            resultName.textContent = winnerName;
            resultBadge.textContent = winnerBadge;
            resultPrize.textContent = winnerPrize;
        };

        const applyDuration = (value) => {
            state.duration = clampDuration(value);
            durationInput.value = state.duration;
        };

        const moveTo = (slot) => strip.style.setProperty('--slot', slot);

        const jumpTo = (slot) => {
            strip.style.transition = 'none';
            moveTo(slot);
            strip.getBoundingClientRect();
            strip.style.transition = '';
        };

        const renderHistory = () => {
            const rows = state.history.map((entry) => {
                const row = template.content.firstElementChild.cloneNode(true);
                row.dataset.badge = entry.badge;
                row.querySelectorAll('[data-field]').forEach((cell) => (cell.textContent = entry[cell.dataset
                    .field] ?? ''));
                return row;
            });

            list.replaceChildren(...rows);
        };

        const showNext = () => {
            const next = nextDraw();
            setResult(`WINNER ${next} IS NEXT`, '', '', `PRIZE: ${prizeFor(next)}`);
        };

        const begin = (data) => {
            state.current = data;
            state.slot = data.slot;

            setSpinning(true);
            setResult(`DRAWING WINNER ${data.draw}`, '', '', `PRIZE: ${data.prize}`);
            strip.style.transitionDuration = `${data.duration}s`;
            moveTo(data.slot);
            startConfetti();

            save();
        };

        const spin = () => {
            if (state.spinning || !count) return;

            const taken = state.history.map((item) => item.badge);

            const pool = participants
                .map((_, position) => position)
                .filter((position) => {
                    const badge = participants[position].badge;
                    return !state.forfeited.includes(badge) && !taken.includes(badge);
                });

            if (!pool.length) return;

            const index = pool[Math.floor(Math.random() * pool.length)];
            const delta = (index - (state.slot % count) + count) % count;
            const draw = nextDraw();

            const data = {
                type: 'spin',
                slot: state.slot + LOOPS * count + delta,
                winner: participants[index],
                draw,
                prize: prizeFor(draw),
                eligible: state.eligible,
                duration: state.duration,
            };

            begin(data);
            channel?.postMessage(data);
        };

        const reveal = () => {
            const {
                winner,
                draw,
                prize,
                eligible
            } = state.current;

            const entry = {
                draw,
                ...winner,
                prize
            };

            setResult(`WINNER ${draw}`, winner.name, `BADGE ID: ${winner.badge}`, `PRIZE: ${prize}`);

            state.history = [entry, ...state.history];
            state.winners += 1;

            if (state.redraw[0] === draw) {
                state.redraw = state.redraw.slice(1);
            } else {
                state.top = draw;
            }

            state.eligible = eligible - 1;
            state.slot = count + (state.slot % count);
            state.current = null;

            jumpTo(state.slot);
            renderHistory();
            setStat('winners', state.winners);
            setStat('eligible', state.eligible);
            setSpinning(false);
            stopConfetti();
            celebrate();
            save();
        };

        const removeWinner = async (badge) => {
            if (state.spinning) return;

            const entry = state.history.find((item) => item.badge === badge);
            if (!entry) return;

            const confirmation = await Swal.fire({
                title: `Remove ${entry.name}?`,
                text: 'The prize will be drawn again.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            });

            if (!confirmation.isConfirmed) return;
            if (state.spinning) return;
            if (!state.history.some((item) => item.badge === badge)) return;

            state.forfeited = [...state.forfeited, badge];
            state.history = state.history.filter((item) => item.badge !== badge);
            state.winners -= 1;
            state.redraw = [...state.redraw, entry.draw].sort((a, b) => a - b);

            renderHistory();
            setStat('winners', state.winners);
            setResult(`${entry.name.toUpperCase()} FORFEITED`, '', '', `PRIZE: ${prizeFor(entry.draw)} - DRAW AGAIN`);
            syncForfeit();
            syncRemoveAll();
            save();

            channel?.postMessage({
                type: 'remove',
                badge
            });
        };

        const removeAll = async () => {
            if (state.spinning || !state.history.length) return;

            const confirmation = await Swal.fire({
                title: `Remove all ${state.history.length} winners?`,
                text: 'All prizes will be drawn again.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove all',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            });

            if (!confirmation.isConfirmed) return;
            if (state.spinning || !state.history.length) return;

            const removed = [...state.history];

            state.forfeited = [...state.forfeited, ...removed.map((item) => item.badge)];
            state.history = [];
            state.winners -= removed.length;
            state.redraw = [...state.redraw, ...removed.map((item) => item.draw)].sort((a, b) => a - b);

            renderHistory();
            setStat('winners', state.winners);
            setResult('ALL WINNERS FORFEITED', '', '', `PRIZE: ${prizeFor(nextDraw())} - DRAW AGAIN`);
            syncForfeit();
            syncRemoveAll();
            save();

            removed.forEach((entry) => channel?.postMessage({
                type: 'remove',
                badge: entry.badge
            }));
        };

        const forfeit = () => {
            if (state.spinning || !state.history.length) return;

            removeWinner(state.history[0].badge);
        };

        applyDuration(state.duration);
        setStat('winners', state.winners);
        setStat('eligible', state.eligible);
        showNext();
        renderHistory();
        jumpTo(state.slot);
        syncForfeit();
        syncRemoveAll();

        durationInput.addEventListener('input', () => {
            const seconds = Number(durationInput.value);
            if (!Number.isInteger(seconds) || seconds < MIN_DURATION || seconds > MAX_DURATION) return;

            state.duration = seconds;
            save();
            channel?.postMessage({
                type: 'duration',
                value: state.duration
            });
        });

        durationInput.addEventListener('change', () => {
            applyDuration(durationInput.value);
            save();
            channel?.postMessage({
                type: 'duration',
                value: state.duration
            });
        });

        channel?.addEventListener('message', ({
            data
        }) => {
            if (data.type === 'spin') begin(data);
            if (data.type === 'duration') applyDuration(data.value);
        });

        triggers.forEach((trigger) => trigger.addEventListener('click', spin));
        forfeitButton.addEventListener('click', forfeit);
        removeAllButton.addEventListener('click', removeAll);

        list.addEventListener('click', (event) => {
            const button = event.target.closest('[data-remove]');
            if (!button) return;

            removeWinner(button.closest('tr').dataset.badge);
        });

        strip.addEventListener('transitionend', (event) => {
            if (event.target === strip && event.propertyName === 'transform' && state.spinning) reveal();
        });
    </script>
</body>

</html>