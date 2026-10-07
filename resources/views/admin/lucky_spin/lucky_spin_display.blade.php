<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lucky Spin Display</title>
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
    $event = ['name' => '', 'company' => 'Seatrium', 'logo' => 'images/logo.png'];

    $draw = $draw ?? 1;
    $eligible = $eligible ?? 0;

    $participants = $participants ?? [];

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

    $recentWinners = $recentWinners ?? [];

    $columns = [
        'badge' => 'Badge ID',
        'name' => 'Name',
        'position' => 'Position',
        'department' => 'Department',
    ];

    $sounds = [
        'intro' => asset('audio/intro.mp3'),
        'draw' => asset('audio/draw.mp3'),
        'winner' => asset('audio/winner.mp3'),
    ];

    $stars =
        '[background-image:radial-gradient(1.5px_1.5px_at_20px_30px,#fff,transparent),radial-gradient(2px_2px_at_90px_140px,#fff,transparent),radial-gradient(1.5px_1.5px_at_160px_60px,#fff,transparent),radial-gradient(2px_2px_at_210px_190px,#fff,transparent),radial-gradient(1px_1px_at_50px_200px,#fff,transparent)] [background-size:240px_240px] [animation:twinkle_4s_ease-in-out_infinite]';
@endphp

<body
    class="h-screen h-dvh overflow-hidden bg-gradient-to-tr from-[#a0237c] via-[#5a1a85] to-[#2a0b5c] font-normal text-white antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]"
    data-display data-draw="{{ $draw }}" data-eligible="{{ $eligible }}"
    data-history="{{ json_encode($recentWinners) }}" data-draw-url="{{ $drawUrl ?? '' }}"
    data-csrf-token="{{ $csrfToken ?? csrf_token() }}" data-intro-sound="{{ $sounds['intro'] }}"
    data-draw-sound="{{ $sounds['draw'] }}" data-winner-sound="{{ $sounds['winner'] }}">
    <div class="{{ $stars }} pointer-events-none absolute inset-0"></div>

    <div class="relative flex h-full flex-col p-3 sm:p-6">
        <div
            class="pointer-events-none absolute inset-3 rounded-[2rem] border-2 border-[#5ad2ff]/60 shadow-[0_0_32px_rgba(74,168,255,.45),inset_0_0_32px_rgba(74,168,255,.2)] sm:inset-6">
        </div>

        <header class="relative flex shrink-0 items-center justify-between gap-3 px-6 pt-4 sm:px-12 sm:pt-6">
            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                <span
                    class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-[#2a0b5c]/40 sm:size-14">
                    <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}"
                        class="size-full object-contain p-2">
                </span>
                <span
                    class="hidden truncate text-sm font-medium tracking-wider min-[500px]:block sm:text-base">{{ $event['company'] }}</span>
            </div>
            <span
                class="min-w-0 truncate text-xs font-medium tracking-widest text-purple-200 sm:text-sm">{{ $event['name'] }}</span>
        </header>

        <main
            class="relative flex min-h-0 flex-1 flex-col items-center overflow-hidden px-4 text-center [animation:rise_.8s_ease-out_both]">
            <div class="flex h-full min-h-0 w-full min-w-0 flex-col items-center justify-center py-4">
                <p class="shrink-0 text-sm font-semibold tracking-widest text-[#bfe6ff] sm:text-lg">LUCKY DRAW</p>
                <h1
                    class="mt-1 shrink-0 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl lg:text-5xl">
                    Who will be our next lucky winner?</h1>

                <x-admin.reel :participants="$participants" size="lg" class="mx-auto mt-4 max-w-5xl shrink-0 sm:mt-6" />

                <button type="button" data-spin
                    class="mt-5 flex h-12 w-full max-w-xs shrink-0 items-center justify-center rounded-xl bg-white px-10 text-sm font-semibold text-[#7a2cc0] shadow-lg shadow-[#2a0b5c]/40 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-fuchsia-500/40 active:scale-[.98] disabled:pointer-events-none disabled:opacity-60 sm:mt-6 sm:w-auto sm:max-w-none">
                    Draw Winner
                </button>

                <section data-winners-section
                    class="mt-5 hidden min-h-0 w-full min-w-0 max-w-5xl flex-col rounded-2xl border border-[#5ad2ff]/50 bg-[#1b0850]/40 p-4 text-left shadow-[0_0_24px_rgba(74,168,255,.3)] backdrop-blur-sm [&:not(.hidden)]:flex sm:mt-6 sm:p-5">
                    <h2 class="shrink-0 text-sm font-semibold tracking-widest text-[#bfe6ff]">WINNERS</h2>

                    <div
                        class="mt-3 min-h-0 max-h-[13rem] flex-1 overflow-auto overscroll-contain [scrollbar-width:thin] [scrollbar-color:#5ad2ff_transparent]">
                        <table class="w-full min-w-[40rem] text-left text-sm">
                            <thead class="sticky top-0 z-10 bg-[#1b0850]">
                                <tr class="text-xs font-semibold tracking-wider text-purple-200">
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
            class="pointer-events-none absolute inset-0 z-30 grid place-items-center bg-[#1a0640]/60 opacity-0 backdrop-blur-sm transition duration-500">
            <div data-card
                class="mx-4 max-w-[calc(100%-2rem)] scale-90 rounded-3xl border-4 border-[#5ad2ff] bg-white px-5 py-8 text-center text-[#2a0b5c] shadow-[0_0_48px_rgba(74,168,255,.7)] transition duration-500 sm:px-16 sm:py-10">
                <p data-winner-name
                    class="break-words text-3xl font-semibold tracking-tight min-[400px]:text-4xl sm:text-6xl"></p>
                <p data-winner-badge
                    class="mt-3 break-words text-xs font-semibold tracking-widest text-[#b0249a] sm:mt-4 sm:text-lg">
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
        const section = document.querySelector('[data-winners-section]');
        const list = document.querySelector('[data-winner-list]');
        const template = document.getElementById('winner-template');
        const channel = 'BroadcastChannel' in window ? new BroadcastChannel('lucky-spin') : null;

        const STORAGE_KEY = 'lucky-spin-state';
        const MIN_DURATION = 10;
        const MAX_DURATION = 60;
        const LOOPS = 2;
        const FADE_DURATION = 1200;
        const FADE_INTERVAL = 50;
        const INTRO_VOLUME = 0.7;
        const MIN_RATE = 0.5;
        const MAX_RATE = 2;
        const CONFETTI_COLORS = ['#ffffff', '#e9b6ff', '#ff5fd2', '#5ad2ff', '#ffd666'];

        const participants = JSON.parse(reel.dataset.participants);
        const count = participants.length;

        const introSound = new Audio(root.dataset.introSound);
        const drawSound = new Audio(root.dataset.drawSound);
        const winnerSound = new Audio(root.dataset.winnerSound);

        introSound.preload = 'auto';
        drawSound.preload = 'auto';
        winnerSound.preload = 'auto';

        const fades = new Map();

        let confettiTimer = null;
        let unlocked = false;

        const clearFade = (audio) => {
            clearInterval(fades.get(audio));
            fades.delete(audio);
        };

        const stopSound = (audio) => {
            clearFade(audio);
            audio.pause();
            audio.currentTime = 0;
            audio.volume = 1;
            audio.playbackRate = 1;
            audio.loop = false;
        };

        const fadeTo = (audio, target, duration, done) => {
            clearFade(audio);

            const from = audio.volume;
            const steps = Math.max(1, Math.round(duration / FADE_INTERVAL));
            let current = 0;

            const timer = setInterval(() => {
                current += 1;
                audio.volume = Math.min(1, Math.max(0, from + (target - from) * (current / steps)));

                if (current >= steps) {
                    clearFade(audio);
                    done?.();
                }
            }, FADE_INTERVAL);

            fades.set(audio, timer);
        };

        const fadeOut = (audio) => {
            if (audio.paused) {
                stopSound(audio);
                return;
            }

            fadeTo(audio, 0, FADE_DURATION, () => stopSound(audio));
        };

        const startIntro = () => {
            if (!unlocked || state.spinning) return;

            introSound.loop = true;

            if (!introSound.paused) {
                fadeTo(introSound, INTRO_VOLUME, FADE_DURATION);
                return;
            }

            introSound.currentTime = 0;
            introSound.volume = 0;
            introSound.play().catch(() => {});
            fadeTo(introSound, INTRO_VOLUME, FADE_DURATION);
        };

        const playDraw = (seconds) => {
            const length = drawSound.duration;
            const ideal = Number.isFinite(length) && length > 0 ? length / seconds : 1;
            const rate = Math.min(MAX_RATE, Math.max(MIN_RATE, ideal));

            drawSound.currentTime = 0;
            drawSound.volume = 1;
            drawSound.playbackRate = rate;
            drawSound.loop = rate !== ideal;
            drawSound.play().catch(() => {});
        };

        const playWinner = () => {
            winnerSound.currentTime = 0;
            winnerSound.volume = 1;
            winnerSound.play().catch(() => startIntro());
        };

        const stopWinner = () => {
            stopSound(winnerSound);
            startIntro();
        };

        const primeSounds = (audios) => audios.forEach((audio) => {
            audio.muted = true;

            audio.play()
                .then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                })
                .catch(() => {})
                .finally(() => (audio.muted = false));
        });

        const unlockAudio = (event) => {
            if (unlocked) return;

            unlocked = true;

            const isSpin = event.target.closest?.('[data-spin]') ||
                (event.type === 'keydown' && (event.code === 'Space' || event.code === 'Enter'));

            primeSounds(isSpin ? [winnerSound] : [drawSound, winnerSound]);

            if (!isSpin) startIntro();
        };

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
            winners: Number(root.dataset.draw) - 1,
            eligible: Number(root.dataset.eligible),
            slot: count + ((saved.slot ?? 0) % Math.max(count, 1)),
            duration: clampDuration(saved.duration ?? MIN_DURATION),
            history: JSON.parse(root.dataset.history),
            spinning: false,
            current: null,
        };

        const save = () => {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                    slot: state.slot,
                    duration: state.duration,
                }));
            } catch {
                return;
            }
        };

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

            stopSound(winnerSound);
            stopSound(drawSound);
            fadeOut(introSound);
            playDraw(data.duration);

            save();
        };

        const spin = async () => {
            if (state.spinning || !count) return;

            setSpinning(true);

            try {
                const response = await fetch(root.dataset.drawUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': root.dataset.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({}),
                });
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'The draw could not be completed.');
                }

                const winner = result.data;
                const index = participants.findIndex((participant) => participant.badge === winner.badge);
                if (index < 0) throw new Error('The selected participant is not in the checked-in list.');

                const delta = (index - (state.slot % count) + count) % count;
                const data = {
                    type: 'spin',
                    slot: state.slot + LOOPS * count + delta,
                    winner,
                    draw: winner.draw,
                    stats: result.stats,
                    duration: state.duration,
                };

                begin(data);
                channel?.postMessage(data);
            } catch (error) {
                setSpinning(false);
                await Swal.fire({
                    icon: 'error',
                    title: 'Draw failed',
                    text: error.message,
                    confirmButtonColor: '#6a2fe0',
                });
            }
        };

        const reveal = () => {
            const {
                winner,
                draw,
                stats
            } = state.current;

            const entry = {
                draw,
                ...winner
            };

            name.textContent = winner.name;
            badge.textContent = `BADGE ID: ${winner.badge}`;

            state.history = [entry, ...state.history];
            state.winners = stats?.winners ?? state.winners + 1;
            state.eligible = stats?.eligible ?? Math.max(0, state.eligible - 1);
            state.slot = count + (state.slot % count);
            state.current = null;

            jumpTo(state.slot);
            renderHistory();
            setSpinning(false);
            toggleOverlay(true);
            stopConfetti();
            fadeOut(drawSound);
            playWinner();
            celebrate();
            save();
        };

        const removeWinner = (badgeId, stats = null) => {
            if (state.spinning) return;

            state.history = state.history.filter((item) => item.badge !== badgeId);
            state.winners = stats?.winners ?? Math.max(0, state.winners - 1);
            state.eligible = stats?.eligible ?? state.eligible + 1;

            toggleOverlay(false);
            stopWinner();
            renderHistory();
            save();
        };

        jumpTo(state.slot);
        renderHistory();

        channel?.addEventListener('message', ({
            data
        }) => {
            if (data.type === 'spin') begin(data);
            if (data.type === 'remove') removeWinner(data.badge, data.stats);
            if (data.type === 'duration') state.duration = clampDuration(data.value);
        });

        window.addEventListener('storage', (event) => {
            if (event.key !== STORAGE_KEY) return;

            const next = load();
            if (next.duration !== undefined) state.duration = clampDuration(next.duration);
        });

        document.addEventListener('pointerdown', unlockAudio, true);
        winnerSound.addEventListener('ended', () => startIntro());
        document.addEventListener('keydown', unlockAudio, true);

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

        overlay.addEventListener('click', () => {
            toggleOverlay(false);
            stopWinner();
        });
    </script>
</body>

</html>
