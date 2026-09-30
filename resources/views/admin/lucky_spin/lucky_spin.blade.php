<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin</title>
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
    </style>
</head>
@php
    $checkedIn = $checkedIn ?? 540;
    $winners = $winners ?? 12;
    $eligible = $checkedIn - $winners;

    $displayUrl = $displayUrl ?? '/admin/lucky-spin/display';

    $prizes = $prizes ?? ['Smart TV', 'Air Fryer', 'Electric Scooter', 'Espresso Machine', 'Smartwatch', 'Shopping Voucher'];

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

    $recentWinners = array_slice($recentWinners, 0, 1);

    $stats = [
        ['label' => 'CHECKED IN', 'key' => 'checked', 'value' => $checkedIn],
        ['label' => 'WINNERS', 'key' => 'winners', 'value' => $winners],
        ['label' => 'ELIGIBLE TO SPIN', 'key' => 'eligible', 'value' => $eligible],
    ];

    $speeds = ['slow' => 'Slow', 'normal' => 'Normal', 'fast' => 'Fast'];

    $columns = [
        'badge' => 'Badge ID',
        'name' => 'Name',
        'position' => 'Position',
        'department' => 'Department',
        'prize' => 'Prize',
    ];

    $card = 'rounded-2xl border border-slate-200/80 bg-white p-4 shadow-lg shadow-blue-100/50 sm:p-6';
@endphp

<body
    class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
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
                            class="{{ $card }} min-w-0 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-200/60 [animation:rise_.7s_ease-out_both]"
                            style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">{{ $stat['label'] }}</p>
                            <p data-stat="{{ $stat['key'] }}"
                                class="mt-2 text-4xl font-semibold tracking-tight sm:text-5xl">
                                {{ number_format($stat['value']) }}</p>
                        </article>
                    @endforeach
                </section>

                <section
                    class="mt-6 min-w-0 overflow-hidden rounded-2xl bg-gradient-to-br from-[#26346b] via-[#2f57e0] to-[#26346b] px-4 py-6 shadow-lg shadow-blue-200/60 sm:px-10 sm:py-8 [animation:rise_.7s_.4s_ease-out_both]">
                    <div class="mb-3 text-center sm:mb-4">
                        <p data-result-note class="break-words text-xs font-medium tracking-widest text-blue-100"></p>
                        <p data-result-name class="mt-1.5 break-words text-lg font-semibold leading-tight tracking-tight text-white empty:hidden sm:text-2xl"></p>
                        <p data-result-badge class="mt-0.5 break-words text-xs font-medium tracking-widest text-blue-100 empty:hidden sm:text-sm"></p>
                        <p data-result-prize class="mt-2 inline-block rounded-full bg-white/15 px-4 py-1 text-xs font-semibold tracking-widest text-white empty:hidden sm:text-sm"></p>
                    </div>

                    <x-admin.reel :participants="$participants" size="md" class="mx-auto max-w-3xl" />
                </section>

                <section class="mt-6 flex flex-col items-center gap-4">
                    <div class="{{ $card }} w-full max-w-md !p-4 text-center">
                        <p class="text-[10px] font-semibold tracking-widest text-slate-500">SPIN SPEED</p>
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            @foreach ($speeds as $key => $label)
                                <button type="button" data-speed-option="{{ $key }}" aria-pressed="false"
                                    class="h-9 rounded-full border border-slate-200 text-xs font-medium text-slate-500 transition duration-300 hover:border-[#3563ff] aria-pressed:border-[#3563ff] aria-pressed:bg-[#3563ff] aria-pressed:text-white">{{ $label }}</button>
                            @endforeach
                        </div>
                        <p class="mt-2 text-[10px] text-slate-400">Synced with display</p>
                    </div>

                    <div class="flex w-full max-w-md flex-wrap justify-center gap-3">
                        <button type="button" data-spin
                            class="flex h-12 min-w-[10rem] flex-1 items-center justify-center rounded-xl bg-[#3563ff] px-6 text-sm font-medium text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:-translate-y-0.5 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/40 active:scale-[.98] disabled:pointer-events-none disabled:opacity-60">
                            Draw Winner
                        </button>
                        <a href="{{ $displayUrl }}" target="_blank" rel="noopener"
                            class="flex h-12 min-w-[10rem] flex-1 items-center justify-center rounded-xl border-2 border-slate-200 bg-white px-6 text-sm font-medium text-[#3563ff] transition duration-300 hover:-translate-y-0.5 hover:border-[#3563ff] hover:shadow-lg hover:shadow-blue-100 active:scale-[.98]">
                            Open Display
                        </a>
                        <button type="button" data-forfeit
                            class="hidden h-12 w-full items-center justify-center rounded-xl border-2 border-red-200 bg-white px-6 text-sm font-medium text-red-600 transition duration-300 hover:-translate-y-0.5 hover:border-red-400 hover:bg-red-50 active:scale-[.98]">
                            Forfeit Winner (Not Present)
                        </button>
                    </div>
                </section>

                <section class="{{ $card }} mt-6 min-w-0 [animation:rise_.7s_.5s_ease-out_both]">
                    <h2 class="text-lg font-semibold tracking-tight sm:text-xl">Winners</h2>

                    <div class="mt-4 overflow-x-auto overscroll-x-contain">
                        <table class="w-full min-w-[40rem] text-left text-sm">
                            <thead>
                                <tr class="bg-blue-50/70 text-xs font-semibold text-[#26346b]">
                                    @foreach ($columns as $label)
                                        <th class="whitespace-nowrap px-3 py-3 sm:px-4">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody data-winner-list class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <template id="winner-template">
        <tr class="transition duration-300 hover:bg-blue-50/60 [animation:rise_.5s_ease-out_both]">
            @foreach ($columns as $field => $label)
                <td data-field="{{ $field }}"
                    class="px-3 py-3 sm:px-4 {{ $field === 'badge' ? 'whitespace-nowrap font-semibold' : '' }}"></td>
            @endforeach
        </tr>
    </template>

    <script>
        const root = document.querySelector('[data-draw]');
        const reel = document.querySelector('[data-reel]');
        const strip = document.querySelector('[data-strip]');
        const triggers = document.querySelectorAll('[data-spin]');
        const forfeitButton = document.querySelector('[data-forfeit]');
        const note = document.querySelector('[data-result-note]');
        const resultName = document.querySelector('[data-result-name]');
        const resultBadge = document.querySelector('[data-result-badge]');
        const resultPrize = document.querySelector('[data-result-prize]');
        const list = document.querySelector('[data-winner-list]');
        const template = document.getElementById('winner-template');
        const speedButtons = document.querySelectorAll('[data-speed-option]');
        const channel = 'BroadcastChannel' in window ? new BroadcastChannel('lucky-spin') : null;

        const STORAGE_KEY = 'lucky-spin-state';
        const SPEEDS = {
            slow: 10,
            normal: 6.5,
            fast: 3.5
        };
        const LOOPS = 2;
        const CONFETTI_COLORS = ['#ffffff', '#bcd0ff', '#6a94ff', '#3563ff', '#ffd666'];

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

        const saved = load();

        const state = {
            winners: saved.winners ?? Number(root.dataset.winners),
            eligible: saved.eligible ?? Number(root.dataset.eligible),
            slot: count + ((saved.slot ?? 0) % Math.max(count, 1)),
            speed: SPEEDS[saved.speed] ? saved.speed : 'normal',
            history: (saved.history ?? JSON.parse(root.dataset.history)).slice(0, 1),
            forfeited: saved.forfeited ?? [],
            won: saved.won ?? [],
            last: saved.last ?? null,
            spinning: false,
            current: null,
        };

        const eligiblePool = () => participants
            .map((_, position) => position)
            .filter((position) => !state.forfeited.includes(participants[position].badge))
            .filter((position) => !state.won.includes(participants[position].badge))
            .filter((position) => !/manager/i.test(participants[position].position ?? ''));

        const save = () => {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                    ...load(),
                    winners: state.winners,
                    eligible: state.eligible,
                    slot: state.slot,
                    speed: state.speed,
                    history: state.history,
                    forfeited: state.forfeited,
                    won: state.won,
                    last: state.last
                }));
            } catch {
                return;
            }
        };

        const durationFor = (speed) => SPEEDS[speed];

        const syncForfeit = () => {
            const visible = Boolean(state.last) && !state.spinning;
            forfeitButton.classList.toggle('hidden', !visible);
            forfeitButton.classList.toggle('flex', visible);
        };

        const setSpinning = (value) => {
            state.spinning = value;
            triggers.forEach((trigger) => (trigger.disabled = value));
            syncForfeit();
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

        const applySpeed = (value) => {
            state.speed = value;
            speedButtons.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.speedOption ===
                value)));
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
                row.querySelectorAll('[data-field]').forEach((cell) => (cell.textContent = entry[cell.dataset
                    .field] ?? ''));
                return row;
            });

            list.replaceChildren(...rows);
        };

        const showNext = () => {
            const next = state.winners + 1;
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
            if (state.winners >= prizes.length) return;

            const pool = eligiblePool();

            if (!pool.length) return;

            const index = pool[Math.floor(Math.random() * pool.length)];
            const delta = (index - (state.slot % count) + count) % count;
            const draw = state.winners + 1;

            const data = {
                type: 'spin',
                slot: state.slot + LOOPS * count + delta,
                winner: participants[index],
                draw,
                prize: prizeFor(draw),
                eligible: state.eligible,
                duration: durationFor(state.speed),
            };

            begin(data);
            channel?.postMessage(data);
        };

        const reveal = () => {
            const {
                winner,
                draw,
                prize
            } = state.current;

            const entry = {
                draw,
                ...winner,
                prize
            };

            setResult(`WINNER ${draw}`, winner.name, `BADGE ID: ${winner.badge}`, `PRIZE: ${prize}`);

            state.history = [entry];
            state.last = entry;
            state.winners = draw;
            state.won = [...state.won, winner.badge];
            state.eligible = eligiblePool().length;
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

        const applyForfeit = () => {
            if (state.spinning || !state.last) return;

            const {
                draw,
                badge,
                name
            } = state.last;

            state.forfeited = [...state.forfeited, badge];
            state.winners = draw - 1;
            state.history = [];
            state.last = null;

            renderHistory();
            setStat('winners', state.winners);
            setResult(`${name.toUpperCase()} FORFEITED`, '', '', `PRIZE: ${prizeFor(draw)} - DRAW AGAIN`);
            syncForfeit();
            save();
        };

        const forfeit = () => {
            if (state.spinning || !state.last) return;
            if (!window.confirm(`Forfeit ${state.last.name}? The prize will be drawn again.`)) return;

            applyForfeit();
            channel?.postMessage({
                type: 'forfeit'
            });
        };

        state.eligible = eligiblePool().length;

        applySpeed(state.speed);
        setStat('winners', state.winners);
        setStat('eligible', state.eligible);
        showNext();
        renderHistory();
        jumpTo(state.slot);
        syncForfeit();

        speedButtons.forEach((button) => button.addEventListener('click', () => {
            applySpeed(button.dataset.speedOption);
            save();
            channel?.postMessage({
                type: 'speed',
                value: state.speed
            });
        }));

        channel?.addEventListener('message', ({
            data
        }) => {
            if (data.type === 'spin') begin(data);
            if (data.type === 'speed') applySpeed(data.value);
        });

        triggers.forEach((trigger) => trigger.addEventListener('click', spin));
        forfeitButton.addEventListener('click', forfeit);

        strip.addEventListener('transitionend', (event) => {
            if (event.target === strip && event.propertyName === 'transform' && state.spinning) reveal();
        });
    </script>
</body>

</html>