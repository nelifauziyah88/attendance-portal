<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
    </style>
</head>
@php
    $checkedIn = 540;
    $winners = 12;
    $eligible = $checkedIn - $winners;

    $displayUrl = $displayUrl ?? '/admin/lucky-spin/display';

    $participants = $participants ?? [
        'Kevin Wijaya', 'Sarah Amelia', 'Bima Kurniawan', 'Putri Maharani', 'Rizky Pratama', 'Siti Rahma',
        'Farhan Akbar', 'Rani Oktaviani', 'Andi Prasetyo', 'Maya Lestari', 'Clara Anjani', 'Yusuf Hidayat',
    ];

    $recentWinners = $recentWinners ?? [
        ['number' => 12, 'name' => 'Alya Putri'],
        ['number' => 11, 'name' => 'Dimas Saputra'],
        ['number' => 10, 'name' => 'Neli Fauziyah'],
    ];

    $stats = [
        ['label' => 'CHECKED IN', 'key' => 'checked', 'value' => $checkedIn],
        ['label' => 'WINNERS', 'key' => 'winners', 'value' => $winners],
        ['label' => 'ELIGIBLE TO SPIN', 'key' => 'eligible', 'value' => $eligible],
    ];

    $card = 'rounded-2xl border border-slate-200/80 bg-white p-6 shadow-lg shadow-blue-100/50';
@endphp
<body class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="lucky-spin" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8" data-draw data-winners="{{ $winners }}" data-eligible="{{ $eligible }}">
                <div class="flex flex-wrap items-start justify-between gap-4 [animation:rise_.7s_ease-out_both]">
                    <div>
                        <h1 class="mt-1 text-3xl font-semibold tracking-tight sm:text-4xl">Lucky Spin</h1>
                        <p class="mt-2 text-sm text-slate-500">Spin the wheel to select one eligible checked-in participant.</p>
                    </div>
                </div>

                <section class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($stats as $stat)
                        <article class="{{ $card }} transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-200/60 [animation:rise_.7s_ease-out_both]" style="animation-delay: {{ 0.1 + $loop->index * 0.1 }}s">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">{{ $stat['label'] }}</p>
                            <p data-stat="{{ $stat['key'] }}" class="mt-2 text-5xl font-semibold tracking-tight">{{ number_format($stat['value']) }}</p>
                        </article>
                    @endforeach
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-[3fr_2fr]">
                    <article class="{{ $card }} sm:p-8 [animation:rise_.7s_.4s_ease-out_both]">
                        <h2 class="text-xl font-semibold tracking-tight">Spin wheel</h2>
                        <p class="mt-1 text-xs text-slate-500">Only guests who have checked in and have not won are included.</p>

                        <x-admin.wheel :names="$participants" interactive class="mx-auto mt-10 w-full max-w-[26rem]" />

                        <div class="mx-auto mt-8 w-full max-w-md">
                            <div class="flex items-center justify-between">
                                <label for="speed" class="text-[10px] font-semibold tracking-widest text-slate-500">SPIN SPEED</label>
                                <span data-speed-label class="text-xs font-medium text-[#3563ff]"></span>
                            </div>
                            <input id="speed" type="range" min="1" max="10" step="1" value="7" data-speed class="mt-3 w-full cursor-pointer accent-[#3563ff]">
                            <div class="mt-1 flex justify-between text-[10px] text-slate-400">
                                <span>Slow</span>
                                <span>Fast</span>
                            </div>
                        </div>

                        <button type="button" data-spin class="mx-auto mt-6 flex h-14 w-full max-w-md items-center justify-center rounded-xl bg-[#3563ff] text-sm font-medium text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:-translate-y-0.5 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/40 active:scale-[.98] disabled:pointer-events-none disabled:opacity-60">
                            Start Spin
                        </button>
                    </article>

                    <article class="{{ $card }} flex flex-col [animation:rise_.7s_.5s_ease-out_both]">
                        <h2 class="text-xl font-semibold tracking-tight">Current draw</h2>
                        <p class="mt-1 text-xs text-slate-500">The result appears when the wheel stops.</p>

                        <div class="mt-5 grid min-h-48 place-items-center rounded-2xl border border-slate-200 bg-slate-50/60 p-6 text-center">
                            <div>
                                <p data-result-title class="text-2xl font-semibold tracking-tight">Waiting to spin</p>
                                <p data-result-note class="mt-2 text-sm text-slate-500">Winner {{ $winners + 1 }} is next</p>
                            </div>
                        </div>

                        <h3 class="mt-8 text-lg font-semibold tracking-tight">Recent winners</h3>

                        <ul data-winner-list class="mt-4 space-y-3">
                            @foreach ($recentWinners as $winner)
                                <li class="flex items-center gap-5 rounded-xl bg-slate-50/70 px-5 py-4 text-sm transition duration-300 hover:translate-x-1 hover:bg-blue-50/60">
                                    <span class="w-5 text-xs font-semibold text-[#3563ff]">{{ $winner['number'] }}</span>
                                    <span class="font-medium">{{ $winner['name'] }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ $displayUrl }}" target="_blank" rel="noopener" class="mt-auto flex h-12 items-center justify-center rounded-xl border-2 border-slate-200 bg-white text-xs font-medium text-[#3563ff] transition duration-300 hover:-translate-y-0.5 hover:border-[#3563ff] hover:shadow-lg hover:shadow-blue-100 active:scale-[.98]">
                            Open display screen
                        </a>
                    </article>
                </section>
            </main>
        </div>
    </div>

    <template id="winner-template">
        <li class="flex items-center gap-5 rounded-xl bg-slate-50/70 px-5 py-4 text-sm transition duration-300 hover:translate-x-1 hover:bg-blue-50/60 [animation:rise_.5s_ease-out_both]">
            <span data-number class="w-5 text-xs font-semibold text-[#3563ff]"></span>
            <span data-name class="font-medium"></span>
        </li>
    </template>

    <script>
        const root = document.querySelector('[data-draw]');
        const wheel = document.querySelector('[data-wheel]');
        const triggers = document.querySelectorAll('[data-spin]');
        const title = document.querySelector('[data-result-title]');
        const note = document.querySelector('[data-result-note]');
        const list = document.querySelector('[data-winner-list]');
        const template = document.getElementById('winner-template');
        const speedInput = document.querySelector('[data-speed]');
        const speedLabel = document.querySelector('[data-speed-label]');
        const channel = 'BroadcastChannel' in window ? new BroadcastChannel('lucky-spin') : null;

        const STORAGE_KEY = 'lucky-spin-state';
        const names = JSON.parse(wheel.dataset.names);

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
            rotation: saved.rotation ?? 0,
            speed: saved.speed ?? 7,
            spinning: false,
            current: null,
        };

        const save = () => {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({ ...load(), winners: state.winners, eligible: state.eligible, rotation: state.rotation, speed: state.speed }));
            } catch {
                return;
            }
        };

        const durationFor = (speed) => 13.5 - speed;

        const setSpinning = (value) => {
            state.spinning = value;
            triggers.forEach((trigger) => (trigger.disabled = value));
        };

        const setStat = (key, value) => {
            document.querySelector(`[data-stat="${key}"]`).textContent = value.toLocaleString();
        };

        const applySpeed = (value) => {
            state.speed = value;
            speedInput.value = value;
            speedLabel.textContent = `Level ${value} (${durationFor(value).toFixed(1)}s)`;
        };

        const restoreRotation = () => {
            wheel.style.transition = 'none';
            wheel.style.transform = `rotate(${state.rotation}deg)`;
            wheel.getBoundingClientRect();
            wheel.style.transition = '';
        };

        const begin = (data) => {
            state.current = data;
            state.rotation = data.rotation;

            setSpinning(true);
            title.textContent = 'Spinning...';
            note.textContent = `Drawing winner ${data.draw}`;
            wheel.style.transitionDuration = `${data.duration}s`;
            wheel.style.transform = `rotate(${data.rotation}deg)`;

            save();
        };

        const spin = () => {
            if (state.spinning) return;

            const index = Math.floor(Math.random() * names.length);
            const step = 360 / names.length;
            const stop = 360 - (index * step + step / 2 + (Math.random() - 0.5) * step * 0.6);
            const rotation = state.rotation + 360 * 6 + ((stop - (state.rotation % 360)) + 360) % 360;

            const data = {
                type: 'spin',
                rotation,
                name: names[index],
                draw: state.winners + 1,
                eligible: state.eligible,
                duration: durationFor(state.speed),
            };

            begin(data);
            channel?.postMessage(data);
        };

        const reveal = () => {
            const { name, draw, eligible } = state.current;

            title.textContent = name;
            note.textContent = `Winner ${draw} - Congratulations!`;
            title.animate([{ transform: 'scale(.8)', opacity: 0 }, { transform: 'scale(1)', opacity: 1 }], { duration: 500, easing: 'ease-out' });

            const item = template.content.firstElementChild.cloneNode(true);
            item.querySelector('[data-number]').textContent = draw;
            item.querySelector('[data-name]').textContent = name;
            list.prepend(item);
            [...list.children].slice(3).forEach((extra) => extra.remove());

            state.winners = draw;
            state.eligible = eligible - 1;
            setStat('winners', state.winners);
            setStat('eligible', state.eligible);
            setSpinning(false);
            save();
        };

        applySpeed(state.speed);
        setStat('winners', state.winners);
        setStat('eligible', state.eligible);
        note.textContent = `Winner ${state.winners + 1} is next`;
        restoreRotation();

        speedInput.addEventListener('input', () => {
            applySpeed(Number(speedInput.value));
            save();
            channel?.postMessage({ type: 'speed', value: state.speed });
        });

        channel?.addEventListener('message', ({ data }) => {
            if (data.type === 'spin') begin(data);
            if (data.type === 'speed') applySpeed(data.value);
        });

        triggers.forEach((trigger) => trigger.addEventListener('click', spin));

        wheel.addEventListener('transitionend', (event) => {
            if (event.propertyName === 'transform' && state.spinning) reveal();
        });
    </script>
</body>
</html>