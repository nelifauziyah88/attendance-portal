<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin Display</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
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

    $columns = ['badge' => 'Badge ID', 'name' => 'Name', 'position' => 'Position', 'department' => 'Department'];

    $circles = ['left-6 top-0', 'left-0 top-8', 'left-12 top-8', 'left-6 top-16'];
@endphp

<body
    class="h-screen overflow-hidden bg-gradient-to-r from-[#4468e8] via-[#3d68fa] to-[#6a94ff] font-normal text-white antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]"
    data-display data-draw="{{ $draw }}" data-eligible="{{ $eligible }}">
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

        <header class="relative flex items-center justify-between px-6 pt-4 sm:px-12 sm:pt-6">
            <div class="flex items-center gap-4">
                <span
                    class="grid size-12 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20 sm:size-14">
                    <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}"
                        class="size-full object-contain p-2">
                </span>
                <span
                    class="hidden text-sm font-medium tracking-wider min-[500px]:block sm:text-base">{{ $event['company'] }}</span>
            </div>
            <span class="text-xs font-medium tracking-widest text-blue-100 sm:text-sm">{{ $event['name'] }}</span>
        </header>

        <main
            class="relative flex min-h-0 flex-1 flex-col items-center overflow-y-auto px-4 text-center [animation:rise_.8s_ease-out_both]">
            <div class="my-auto flex w-full flex-col items-center py-4">
                <p class="text-sm font-semibold tracking-widest text-blue-100 sm:text-lg">LUCKY SPIN</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight sm:text-5xl">Who will be our next winner?</h1>

                <x-admin.reel :participants="$participants" size="lg" class="mx-auto mt-6 max-w-5xl" />

                <button type="button" data-spin
                    class="mt-4 flex h-12 items-center justify-center rounded-xl bg-white px-10 text-sm font-semibold text-[#3563ff] shadow-lg shadow-blue-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl active:scale-[.98] disabled:pointer-events-none disabled:opacity-60">
                    Draw Winner
                </button>

                <section data-winners-section
                    class="mt-6 hidden w-full max-w-4xl rounded-2xl border border-white/30 bg-white/10 p-4 text-left backdrop-blur-sm sm:p-5">
                    <h2 class="text-sm font-semibold tracking-widest text-blue-100">WINNERS</h2>

                    <div class="mt-3 overflow-x-auto">
                        <table class="w-full min-w-[32rem] text-left text-sm">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wider text-blue-100">
                                    @foreach ($columns as $label)
                                        <th class="px-4 py-2">{{ $label }}</th>
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
                class="mx-4 scale-90 rounded-3xl bg-white px-8 py-10 text-center text-[#26346b] shadow-2xl transition duration-500 sm:px-16">
                <p data-winner-name class="text-4xl font-semibold tracking-tight sm:text-6xl"></p>
                <p data-winner-badge class="mt-4 text-sm font-semibold tracking-widest text-[#3563ff] sm:text-lg"></p>
            </div>
        </div>
    </div>

    <template id="winner-template">
        <tr class="[animation:rise_.5s_ease-out_both]">
            @foreach ($columns as $field => $label)
                <td data-field="{{ $field }}" class="px-4 py-3 {{ $field === 'badge' ? 'font-semibold' : '' }}">
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

        const participants = JSON.parse(reel.dataset.participants);
        const count = participants.length;

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
                    history: state.history
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
                row.querySelectorAll('[data-field]').forEach((cell) => (cell.textContent = entry[cell.dataset.field] ?? ''));
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

            save();
        };

        const spin = () => {
            if (state.spinning || !count) return;

            const index = Math.floor(Math.random() * count);
            const delta = (index - (state.slot % count) + count) % count;

            const data = {
                type: 'spin',
                slot: state.slot + LOOPS * count + delta,
                winner: participants[index],
                draw: state.winners + 1,
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
                eligible
            } = state.current;

            name.textContent = winner.name;
            badge.textContent = `BADGE ID: ${winner.badge}`;

            state.history = [{
                draw,
                ...winner
            }];
            state.winners = draw;
            state.eligible = eligible - 1;
            state.slot = count + (state.slot % count);
            state.current = null;

            jumpTo(state.slot);
            renderHistory();
            setSpinning(false);
            toggleOverlay(true);
            save();
        };

        jumpTo(state.slot);
        renderHistory();

        channel?.addEventListener('message', ({
            data
        }) => {
            if (data.type === 'spin') begin(data);
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