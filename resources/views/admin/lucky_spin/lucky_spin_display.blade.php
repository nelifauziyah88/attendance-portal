<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin Display</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0deg) } 50% { transform: translateY(-14px) rotate(6deg) } }
    </style>
</head>
@php
    $event = ['name' => '', 'company' => 'Seatrium', 'logo' => 'images/logo.png'];

    $draw = $draw ?? 13;
    $eligible = $eligible ?? 528;

    $participants = $participants ?? [
        'Kevin Wijaya', 'Sarah Amelia', 'Bima Kurniawan', 'Putri Maharani', 'Rizky Pratama', 'Siti Rahma',
        'Farhan Akbar', 'Rani Oktaviani', 'Andi Prasetyo', 'Maya Lestari', 'Clara Anjani', 'Yusuf Hidayat',
    ];

    $badges = $badges ?? collect($participants)->keys()->map(fn ($index) => 'BDG-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT))->all();

    $circles = ['left-6 top-0', 'left-0 top-8', 'left-12 top-8', 'left-6 top-16'];
@endphp
<body class="h-screen overflow-hidden bg-gradient-to-r from-[#4468e8] via-[#3d68fa] to-[#6a94ff] font-normal text-white antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]" data-display data-draw="{{ $draw }}" data-eligible="{{ $eligible }}" data-badges="{{ json_encode($badges) }}">
    <div class="relative flex h-full flex-col p-3 sm:p-6">
        <div class="pointer-events-none absolute inset-3 rounded-[2rem] border border-white/40 sm:inset-6"></div>

        <div class="pointer-events-none absolute left-6 top-1/3 size-28 opacity-70 [animation:float_9s_ease-in-out_infinite] max-lg:hidden">
            @foreach ($circles as $circle)
                <span class="absolute h-16 w-14 rounded-full border border-white/40 {{ $circle }}"></span>
            @endforeach
        </div>
        <div class="pointer-events-none absolute bottom-24 right-8 size-28 opacity-70 [animation:float_11s_ease-in-out_infinite_reverse] max-lg:hidden">
            @foreach ($circles as $circle)
                <span class="absolute h-16 w-14 rounded-full border border-white/40 {{ $circle }}"></span>
            @endforeach
        </div>

        <header class="relative flex items-center justify-between px-6 pt-4 sm:px-12 sm:pt-6">
            <div class="flex items-center gap-4">
                <span class="grid size-12 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20 sm:size-14">
                    <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}" class="size-full object-contain p-2">
                </span>
                <span class="hidden text-sm font-medium tracking-wider min-[500px]:block sm:text-base">{{ $event['company'] }}</span>
            </div>
            <span class="text-xs font-medium tracking-widest text-blue-100 sm:text-sm">{{ $event['name'] }}</span>
        </header>

        <main class="relative flex min-h-0 flex-1 flex-col items-center justify-center px-4 text-center [animation:rise_.8s_ease-out_both]">
            <p class="text-sm font-semibold tracking-widest text-blue-100 sm:text-lg">LUCKY SPIN</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight sm:text-5xl">Who will be our next winner?</h1>

            <x-admin.wheel :names="$participants" interactive class="mt-8 h-[min(56vh,84vw)]" />
        </main>

        <div data-overlay class="pointer-events-none absolute inset-0 z-30 grid place-items-center bg-[#26346b]/50 opacity-0 backdrop-blur-sm transition duration-500">
            <div data-card class="mx-4 scale-90 rounded-3xl bg-white px-8 py-10 text-center text-[#26346b] shadow-2xl transition duration-500 sm:px-16">
                <p data-winner-name class="text-4xl font-semibold tracking-tight sm:text-6xl"></p>
                <p data-winner-badge class="mt-4 text-sm font-semibold tracking-widest text-[#3563ff] sm:text-lg"></p>
            </div>
        </div>
    </div>

    <script>
        const root = document.querySelector('[data-display]');
        const wheel = document.querySelector('[data-wheel]');
        const triggers = document.querySelectorAll('[data-spin]');
        const overlay = document.querySelector('[data-overlay]');
        const card = document.querySelector('[data-card]');
        const name = document.querySelector('[data-winner-name]');
        const badge = document.querySelector('[data-winner-badge]');
        const channel = 'BroadcastChannel' in window ? new BroadcastChannel('lucky-spin') : null;

        const STORAGE_KEY = 'lucky-spin-state';
        const names = JSON.parse(wheel.dataset.names);
        const badges = JSON.parse(root.dataset.badges);

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

        const toggleOverlay = (open) => {
            overlay.classList.toggle('opacity-0', !open);
            overlay.classList.toggle('pointer-events-none', !open);
            card.classList.toggle('scale-90', !open);
        };

        const setSpinning = (value) => {
            state.spinning = value;
            triggers.forEach((trigger) => (trigger.disabled = value));
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
            toggleOverlay(false);
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
                badge: badges[index],
                draw: state.winners + 1,
                eligible: state.eligible,
                duration: durationFor(state.speed),
            };

            begin(data);
            channel?.postMessage(data);
        };

        const reveal = () => {
            const { name: winner, badge: badgeId, draw, eligible } = state.current;

            name.textContent = winner;
            badge.textContent = `BADGE ID: ${badgeId}`;
            state.winners = draw;
            state.eligible = eligible - 1;
            state.current = null;

            setSpinning(false);
            toggleOverlay(true);
            save();
        };

        restoreRotation();

        channel?.addEventListener('message', ({ data }) => {
            if (data.type === 'spin') begin(data);

            if (data.type === 'speed') {
                state.speed = data.value;
                save();
            }
        });

        triggers.forEach((trigger) => trigger.addEventListener('click', spin));

        wheel.addEventListener('transitionend', (event) => {
            if (event.propertyName === 'transform' && state.spinning) reveal();
        });

        overlay.addEventListener('click', () => toggleOverlay(false));
    </script>
</body>
</html>