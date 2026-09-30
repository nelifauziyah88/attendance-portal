<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin Display</title>
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
                transform: translateY(-14px) rotate(6deg)
            }
        }
    </style>
</head>
@php
    $event = ['name' => '', 'company' => 'Seatrium', 'logo' => 'images/logo.png'];

    $draw = $draw ?? 13;
    $eligible = $eligible ?? 528;

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

    $participants = collect($participants)
        ->values()
        ->map(
            fn($item, $index) => is_array($item)
                ? $item
                : [
                    'badge' => 'BDG-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'name' => $item,
                    'position' => '',
                    'department' => '',
                ],
        )
        ->all();

    $columns = [
        'badge' => 'Badge ID',
        'name' => 'Name',
        'position' => 'Position',
        'department' => 'Department',
        'prize' => 'Prize',
    ];

    $circles = ['left-6 top-0', 'left-0 top-8', 'left-12 top-8', 'left-6 top-16'];
@endphp

<body
    class="h-screen h-dvh overflow-hidden bg-gradient-to-r from-[#4468e8] via-[#3d68fa] to-[#6a94ff] font-normal text-white antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]"
    data-display data-draw="{{ $draw }}" data-eligible="{{ $eligible }}"
    data-prizes="{{ json_encode($prizes) }}">
    <div class="relative flex h-full flex-col p-3 sm:p-6">
        <div class="pointer-events-none absolute inset-3 rounded-[2rem] border border-white/40 sm:inset-6"></div>

        <div
            class="pointer-events-none absolute left-6 top-1/3 size-28 opacity-70 [animation:float_9s_ease-in-out_infinite] max-lg:hidden">
            @foreach ($circles as $circle)
                <span class="absolute h-16 w-14 rounded-full border border-white/40 {{ $circle }}"></span>
            @endforeach
        </div>
        <div
            class="pointer-events-none absolute bottom-24 right-8 size-28 opacity-70 [animation:float_11s_ease-in-out_infinite_reverse] max-lg:hidden">
            @foreach ($circles as $circle)
                <span class="absolute h-16 w-14 rounded-full border border-white/40 {{ $circle }}"></span>
            @endforeach
        </div>

        <header class="relative flex shrink-0 items-center justify-between gap-3 px-6 pt-4 sm:px-12 sm:pt-6">
            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                <span
                    class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20 sm:size-14">
                    <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}"
                        class="size-full object-contain p-2">
                </span>
                <span
                    class="hidden truncate text-sm font-medium tracking-wider min-[500px]:block sm:text-base">{{ $event['company'] }}</span>
            </div>
            <span
                class="min-w-0 truncate text-xs font-medium tracking-widest text-blue-100 sm:text-sm">{{ $event['name'] }}</span>
        </header>

        <main
            class="relative flex min-h-0 flex-1 flex-col items-center overflow-y-auto overscroll-contain px-4 text-center [animation:rise_.8s_ease-out_both] [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <div class="my-auto flex w-full min-w-0 flex-col items-center py-4">
                <p class="text-sm font-semibold tracking-widest text-blue-100 sm:text-lg">LUCKY DRAW</p>
                <h1
                    class="mt-1 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl lg:text-5xl">
                    Who will be our next lucky winner?</h1>

                <x-admin.reel :participants="$participants" size="lg" class="mx-auto mt-4 max-w-5xl sm:mt-6" />

                <button type="button" data-spin
                    class="mt-5 flex h-12 w-full max-w-xs items-center justify-center rounded-xl bg-white px-10 text-sm font-semibold text-[#3563ff] shadow-lg shadow-blue-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl active:scale-[.98] disabled:pointer-events-none disabled:opacity-60 sm:mt-6 sm:w-auto sm:max-w-none">
                    Draw Winner
                </button>

                <section data-winners-section
                    class="mt-5 hidden w-full min-w-0 max-w-5xl rounded-2xl border border-white/30 bg-white/10 p-4 text-left backdrop-blur-sm sm:mt-6 sm:p-5">
                    <h2 class="text-sm font-semibold tracking-widest text-blue-100">WINNERS</h2>

                    <div
                        class="mt-3 overflow-x-auto overscroll-x-contain [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                        <table class="w-full min-w-[40rem] text-left text-sm">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wider text-blue-100">
                                    @foreach ($columns as $label)
                                        <th class="whitespace-nowrap px-3 py-2 sm:px-4">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody data-winner-list class="divide-y divide-white/20"></tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>

        <div data-overlay
            class="pointer-events-none absolute inset-0 z-30 grid place-items-center bg-[#26346b]/50 opacity-0 backdrop-blur-sm transition duration-500">
            <div data-card
                class="mx-4 max-w-[calc(100%-2rem)] scale-90 rounded-3xl bg-white px-5 py-8 text-center text-[#26346b] shadow-2xl transition duration-500 sm:px-16 sm:py-10">
                <p data-winner-name
                    class="break-words text-3xl font-semibold tracking-tight min-[400px]:text-4xl sm:text-6xl"></p>
                <p data-winner-badge
                    class="mt-3 break-words text-xs font-semibold tracking-widest text-[#3563ff] sm:mt-4 sm:text-lg">
                </p>
                <p data-winner-prize
                    class="mt-4 inline-block break-words rounded-full bg-blue-50 px-5 py-2 text-xs font-semibold tracking-widest text-[#26346b] empty:hidden sm:mt-5 sm:text-lg">
                </p>
            </div>
        </div>
    </div>

    <template id="winner-template">
        <tr class="[animation:rise_.5s_ease-out_both]">
            @foreach ($columns as $field => $label)
                <td data-field="{{ $field }}"
                    class="px-3 py-3 sm:px-4 {{ $field === 'badge' ? 'whitespace-nowrap font-semibold' : '' }}">
                </td>
            @endforeach
        </tr>
    </template>

    <script>
        const root = document.querySelector('[data-display]');
        const reel = document.querySelector('[data-reel]');
        const strip = document.querySelector('[data-strip]');
        const triggers = document.querySelectorAll('[data-spin]');
        const overlay = document.querySelector('[data-overlay]');
        const card = document.querySelector('[data-card]');
        const name = document.querySelector('[data-winner-name]');
        const badge = document.querySelector('[data-winner-badge]');
        const prizeLabel = document.querySelector('[data-winner-prize]');
        const section = document.querySelector('[data-winners-section]');
        const list = document.querySelector('[data-winner-list]');
        const template = document.getElementById('winner-template');
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
            winners: saved.winners ?? Number(root.dataset.draw) - 1,
            eligible: saved.eligible ?? Number(root.dataset.eligible),
            slot: count + ((saved.slot ?? 0) % Math.max(count, 1)),
            speed: SPEEDS[saved.speed] ? saved.speed : 'normal',
            history: (saved.history ?? []).slice(0, 1),
            forfeited: saved.forfeited ?? [],
            last: saved.last ?? null,
            spinning: false,
            current: null,
        };

        const save = () => {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                    ...load(),
                    winners: state.winners,
                    eligible: state.eligible,
                    slot: state.slot,
                    history: state.history,
                    forfeited: state.forfeited,
                    last: state.last
                }));
            } catch {
                return;
            }
        };

        const durationFor = (speed) => SPEEDS[speed];

        const setSpinning = (value) => {
            state.spinning = value;
            triggers.forEach((trigger) => (trigger.disabled = value));
        };

        const toggleOverlay = (open) => {
            overlay.classList.toggle('opacity-0', !open);
            overlay.classList.toggle('pointer-events-none', !open);
            card.classList.toggle('scale-90', !open);
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
            section.classList.toggle('hidden', !rows.length);
        };

        const begin = (data) => {
            state.current = data;
            state.slot = data.slot;

            setSpinning(true);
            toggleOverlay(false);
            strip.style.transitionDuration = `${data.duration}s`;
            moveTo(data.slot);
            startConfetti();

            save();
        };

        const spin = () => {
            if (state.spinning || !count) return;

            const pool = participants
                .map((_, position) => position)
                .filter((position) => !state.forfeited.includes(participants[position].badge));

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
                prize,
                eligible
            } = state.current;

            const entry = {
                draw,
                ...winner,
                prize
            };

            name.textContent = winner.name;
            badge.textContent = `BADGE ID: ${winner.badge}`;
            prizeLabel.textContent = `PRIZE: ${prize}`;

            state.history = [entry];
            state.last = entry;
            state.winners = draw;
            state.eligible = eligible - 1;
            state.slot = count + (state.slot % count);
            state.current = null;

            jumpTo(state.slot);
            renderHistory();
            setSpinning(false);
            toggleOverlay(true);
            stopConfetti();
            celebrate();
            save();
        };

        const applyForfeit = () => {
            if (state.spinning || !state.last) return;

            state.forfeited = [...state.forfeited, state.last.badge];
            state.winners = state.last.draw - 1;
            state.history = [];
            state.last = null;

            toggleOverlay(false);
            renderHistory();
            save();
        };

        jumpTo(state.slot);
        renderHistory();

        channel?.addEventListener('message', ({
            data
        }) => {
            if (data.type === 'spin') begin(data);
            if (data.type === 'forfeit') applyForfeit();
            if (data.type === 'speed' && SPEEDS[data.value]) state.speed = data.value;
        });

        window.addEventListener('storage', (event) => {
            if (event.key !== STORAGE_KEY) return;

            const next = load();
            if (SPEEDS[next.speed]) state.speed = next.speed;
        });

        triggers.forEach((trigger) => trigger.addEventListener('click', spin));

        document.addEventListener('keydown', (event) => {
            if (event.code === 'Space' || event.code === 'Enter') {
                event.preventDefault();
                spin();
            }
        });

        strip.addEventListener('transitionend', (event) => {
            if (event.target === strip && event.propertyName === 'transform' && state.spinning) reveal();
        });

        overlay.addEventListener('click', () => toggleOverlay(false));
    </script>
</body>

</html>