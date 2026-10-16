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
    $checkedIn = $checkedIn ?? 0;
    $winners = $winners ?? 0;
    $eligible = $eligible ?? 0;

    $displayUrl = $displayUrl ?? url('/admin/lucky-spin/display');
    $participants = $participants ?? [];
    $recentWinners = $recentWinners ?? [];
    $forfeited = $forfeited ?? [];

    $stats = [
        ['label' => 'CHECKED IN', 'key' => 'checked', 'value' => $checkedIn],
        ['label' => 'WINNERS', 'key' => 'winners', 'value' => number_format($winners).' / '.number_format($winnerSlots)],
        ['label' => 'ELIGIBLE TO SPIN', 'key' => 'eligible', 'value' => $eligible],
    ];

    $columns = [
        'badge' => 'Badge ID',
        'name' => 'Name',
        'position' => 'Position',
        'department' => 'Department',
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
                data-checked-in="{{ $checkedIn }}" data-winner-slots="{{ $winnerSlots }}" data-eligible="{{ $eligible }}"
                data-history="{{ json_encode($recentWinners) }}"
                data-forfeited="{{ json_encode($forfeited) }}"
                data-draw-url="{{ route('admin.lucky-spin.draw') }}"
                data-forfeit-url-template="{{ route('admin.lucky-spin.forfeit', ['badge' => 'BADGE_PLACEHOLDER']) }}"
                data-remove-url-template="{{ route('admin.lucky-spin.remove', ['badge' => 'BADGE_PLACEHOLDER']) }}"
                data-reset-drawings-url="{{ route('admin.lucky-spin.reset-drawings') }}"
                data-csrf-token="{{ csrf_token() }}">
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
                                {{ $stat['value'] }}</p>
                        </article>
                    @endforeach
                </section>

                <section
                    class="relative mt-6 min-w-0 overflow-hidden rounded-2xl border-2 border-[#5ad2ff]/70 bg-gradient-to-tr from-[#a0237c] via-[#5a1a85] to-[#2a0b5c] px-4 py-6 shadow-[0_0_32px_rgba(74,168,255,.45)] sm:px-10 sm:py-8 [animation:rise_.7s_.4s_ease-out_both]">
                    <div class="{{ $stars }} pointer-events-none absolute inset-0"></div>

                    <div class="relative text-center">
                        <p data-result-note class="break-words text-5xl font-medium tracking-widest text-purple-200"></p>
                        <p data-result-name
                            class="mt-1.5 break-words text-lg font-semibold leading-tight tracking-tight text-white empty:hidden sm:text-2xl">
                        </p>
                        <p data-result-badge
                            class="mt-0.5 break-words text-xs font-medium tracking-widest text-[#bfe6ff] empty:hidden sm:text-sm">
                        </p>
                    </div>

                    <div class="pointer-events-none absolute left-0 top-0 h-0 w-full overflow-hidden opacity-0" aria-hidden="true">
                        <x-admin.reel :participants="$participants" size="md" class="mx-auto max-w-3xl" />
                    </div>
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
                        <a href="{{ $displayUrl }}" target="_blank" rel="noopener"
                            class="flex h-12 min-w-[10rem] flex-1 items-center justify-center rounded-xl bg-gradient-to-r from-[#c02a9c] to-[#6a2fe0] px-6 text-sm font-medium text-white shadow-lg shadow-fuchsia-400/40 transition duration-300 hover:-translate-y-0.5 hover:brightness-110 hover:shadow-xl hover:shadow-fuchsia-400/50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-60">
                            Open Display
                        </a>
                        <button type="button" data-forfeit
                            class="hidden h-12 w-full items-center justify-center rounded-xl border-2 border-amber-200 bg-white px-6 text-sm font-medium text-amber-700 transition duration-300 hover:-translate-y-0.5 hover:border-amber-400 hover:bg-amber-50 active:scale-[.98]">
                            Forfeit Winner (Not Present)
                        </button>
                    </div>
                </section>

                <section class="{{ $card }} mt-6 min-w-0 [animation:rise_.7s_.5s_ease-out_both]">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold tracking-tight sm:text-xl">Winners</h2>

                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" data-forfeited-list
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-amber-200 bg-white px-4 text-xs font-medium text-amber-700 transition duration-300 hover:border-amber-400 hover:bg-amber-50 active:scale-[.98]">
                                Forfeited List
                                <span data-forfeited-count
                                    class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-semibold">0</span>
                            </button>

                            <button type="button" data-forfeit-all
                                class="hidden h-10 items-center justify-center rounded-xl border border-amber-200 bg-white px-4 text-xs font-medium text-amber-700 transition duration-300 hover:border-amber-400 hover:bg-amber-50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-50">
                                Forfeit All
                            </button>

                            <button type="button" data-remove-all title="Reset all lucky draw history and make checked-in participants eligible again"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border-2 border-rose-700 bg-rose-600 px-5 text-sm font-bold text-white shadow-lg shadow-rose-300/70 transition duration-200 hover:-translate-y-0.5 hover:bg-rose-700 hover:shadow-xl hover:shadow-rose-400/70 active:scale-[.97] disabled:pointer-events-none disabled:opacity-50">
                                <span aria-hidden="true">↻</span> Reset Drawing
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 max-h-[31rem] overflow-auto overscroll-contain rounded-xl border border-purple-100">
                        <table class="w-full min-w-[44rem] text-left text-sm">
                            <thead>
                                <tr class="text-[11px] font-semibold uppercase tracking-wider text-[#2a0b5c]">
                                    <th class="sticky top-0 z-10 w-14 whitespace-nowrap bg-purple-50 px-3 py-3 text-center sm:px-4">No</th>
                                    @foreach ($columns as $label)
                                        <th class="sticky top-0 z-10 whitespace-nowrap bg-purple-50 px-3 py-3 sm:px-4">{{ $label }}</th>
                                    @endforeach
                                    <th class="sticky top-0 z-10 whitespace-nowrap bg-purple-50 px-3 py-3 text-center sm:px-4">Action</th>
                                </tr>
                            </thead>
                            <tbody data-winner-list class="divide-y divide-purple-100 [counter-reset:row]"></tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>

        <template id="winner-template">
            <tr class="transition duration-300 hover:bg-purple-50/70 [animation:rise_.5s_ease-out_both] [counter-increment:row]">
                <td class="w-14 px-3 py-3 text-center text-slate-400 before:content-[counter(row)] sm:px-4"></td>
                @foreach ($columns as $field => $label)
                    <td data-field="{{ $field }}"
                        class="px-3 py-3 sm:px-4 {{ $field === 'badge' ? 'whitespace-nowrap font-semibold' : '' }}"></td>
                @endforeach
                <td class="px-3 py-3 sm:px-4">
                    <div class="flex flex-wrap justify-center gap-2">
                        <button type="button" data-remove title="Remove winner and return participant to eligible list"
                            class="inline-flex h-8 items-center justify-center rounded-lg border border-sky-200 bg-white px-3 text-xs font-medium text-sky-700 transition duration-300 hover:border-sky-400 hover:bg-sky-50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-50">
                            Remove
                        </button>
                        <button type="button" data-forfeit-winner title="Forfeit this winner"
                            class="inline-flex h-8 items-center justify-center rounded-lg border border-amber-200 bg-white px-3 text-xs font-medium text-amber-700 transition duration-300 hover:border-amber-400 hover:bg-amber-50 active:scale-[.98] disabled:pointer-events-none disabled:opacity-50">
                            Forfeit
                        </button>
                    </div>
                </td>
            </tr>
        </template>

    <script>
        const root = document.querySelector('[data-draw]');
        const reel = document.querySelector('[data-reel]');

        if (reel) {
            reel.classList.add('transition-all', 'duration-700', 'opacity-0', 'blur-2xl', 'pointer-events-none');
        }

        const strip = document.querySelector('[data-strip]');
        const triggers = document.querySelectorAll('[data-spin]');
        const forfeitButton = document.querySelector('[data-forfeit]');
        const removeAllButton = document.querySelector('[data-remove-all]');
        const forfeitAllButton = document.querySelector('[data-forfeit-all]');
        const forfeitedButton = document.querySelector('[data-forfeited-list]');
        const forfeitedCount = document.querySelector('[data-forfeited-count]');
        const note = document.querySelector('[data-result-note]');
        const resultName = document.querySelector('[data-result-name]');
        const resultBadge = document.querySelector('[data-result-badge]');
        const list = document.querySelector('[data-winner-list]');
        const template = document.getElementById('winner-template');
        const durationInput = document.querySelector('[data-duration-input]');
        const channel = 'BroadcastChannel' in window ? new BroadcastChannel('lucky-spin') : null;

        const STORAGE_KEY = 'lucky-spin-state';
        const MIN_DURATION = 10;
        const MAX_DURATION = 60;
        const LOOPS = 2;
        const CONFETTI_COLORS = ['#ffffff', '#e9b6ff', '#ff5fd2', '#5ad2ff', '#ffd666'];

        let participants = JSON.parse(reel.dataset.participants || '[]');

        participants = participants.filter(participant => {
            return participant.is_manager !== true;
        });

        let count = participants.length;

        let confettiTimer = null;

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
            checkedIn: Number(root.dataset.checkedIn),
            winners: Number(root.dataset.winners),
            winnerSlots: Number(root.dataset.winnerSlots),
            eligible: Number(root.dataset.eligible),
            slot: count + ((saved.slot ?? 0) % Math.max(count, 1)),
            duration: clampDuration(saved.duration ?? MIN_DURATION),
            history: JSON.parse(root.dataset.history),
            forfeited: JSON.parse(root.dataset.forfeited || '[]'),
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

        const syncForfeit = () => {
            const visible = state.history.length > 0 && !state.spinning;
            forfeitButton.classList.toggle('hidden', !visible);
            forfeitButton.classList.toggle('flex', visible);
        };

        const syncRemoveAll = () => {
            const visible = state.history.length > 0;
            removeAllButton.disabled = state.spinning;
            forfeitAllButton.classList.toggle('hidden', !visible);
            forfeitAllButton.classList.toggle('inline-flex', visible);
            forfeitAllButton.disabled = state.spinning;
        };

        const setSpinning = (value) => {
            state.spinning = value;
            triggers.forEach((trigger) => (trigger.disabled = value));
            durationInput.disabled = value;
            list.querySelectorAll('[data-remove], [data-forfeit-winner]').forEach((button) => (button.disabled = value));
            syncForfeit();
            syncRemoveAll();
        };

        const setStat = (key, value) => {
            const displayValue = key === 'winners'
                ? `${state.winners.toLocaleString()} / ${state.winnerSlots.toLocaleString()}`
                : value.toLocaleString();
            document.querySelector(`[data-stat="${key}"]`).textContent = displayValue;
        };

        const applyStats = (stats) => {
            if (!stats) return;

            state.checkedIn = stats.checkedIn;
            state.winners = stats.winners;
            state.winnerSlots = stats.winnerSlots;
            state.eligible = stats.eligible;
            setStat('checked', state.checkedIn);
            setStat('winners', state.winners);
            setStat('eligible', state.eligible);
        };

        const forfeitWinner = async (badge) => {
            const url = root.dataset.forfeitUrlTemplate.replace('BADGE_PLACEHOLDER', encodeURIComponent(badge));
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': root.dataset.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Winner could not be removed.');
            }

            return result;
        };

        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        }[char]));

        const nowLabel = () => new Date().toLocaleString('en-GB', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false, timeZone: 'Asia/Jakarta',
        }).replace(',', '');

        const syncForfeitedCount = () => {
            forfeitedCount.textContent = state.forfeited.length;
        };

        const addForfeited = (entry) => {
            state.forfeited = [
                {
                    badge: entry.badge,
                    name: entry.name,
                    position: entry.position,
                    department: entry.department,
                    forfeited_at: nowLabel(),
                },
                ...state.forfeited.filter((item) => item.badge !== entry.badge),
            ];
            syncForfeitedCount();
        };

        // Ini yang membangun modalnya
        const showForfeited = () => {
            const rows = state.forfeited.map((item, index) => `
                <tr class="border-b border-purple-100">
                    <td class="px-3 py-2">${index + 1}</td>
                    <td class="whitespace-nowrap px-3 py-2 font-semibold">${escapeHtml(item.badge)}</td>
                    <td class="px-3 py-2">${escapeHtml(item.name)}</td>
                    <td class="px-3 py-2">${escapeHtml(item.department)}</td>
                    <td class="whitespace-nowrap px-3 py-2">${escapeHtml(item.forfeited_at)}</td>
                </tr>`).join('');

            Swal.fire({
                title: `Forfeited (${state.forfeited.length})`,
                width: '52rem',
                showConfirmButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#b45309',
                html: state.forfeited.length
                    ? `<div style="max-height:24rem;overflow:auto;text-align:left">
                        <table style="width:100%;font-size:.85rem">
                        <thead>
                            <tr style="text-align:left;position:sticky;top:0;background:#f3e8ff">
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Badge ID</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Department</th>
                            <th class="px-3 py-2">Forfeited At</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                        </table>
                    </div>`
                    : '<p>Belum ada peserta yang di-forfeit.</p>',
            });
        };

        const removeWinnerRecord = async (badge) => {
            const url = root.dataset.removeUrlTemplate.replace('BADGE_PLACEHOLDER', encodeURIComponent(badge));
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': root.dataset.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Winner could not be removed.');
            }

            return result;
        };

        const resetAllDrawings = async () => {
            const response = await fetch(root.dataset.resetDrawingsUrl, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': root.dataset.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Drawing could not be reset.');
            }

            return result;
        };

        const setResult = (title, winnerName = '', winnerBadge = '') => {
            note.textContent = title;
            resultName.textContent = winnerName;
            resultBadge.textContent = winnerBadge;
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

        const removeParticipantFromReel = (badge) => {
            const items = reel.querySelectorAll('[data-reel-item]');

            items.forEach((item) => {
                if (item.dataset.badge === String(badge)) {
                    item.remove();
                }
            });
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

        const setReelVisibility = (visible) => {
            if (!reel) return;
            if (visible) {
                reel.classList.remove('opacity-0', 'blur-2xl', 'pointer-events-none');
                reel.classList.add('opacity-100', 'blur-none');
            } else {
                reel.classList.add('opacity-0', 'blur-2xl', 'pointer-events-none');
                reel.classList.remove('opacity-100', 'blur-none');
            }
        };

        const showNext = () => {
            setResult('READY TO DRAW', '', '');
            setReelVisibility(false);
        };

        const begin = (data) => {
            state.current = data;
            state.slot = data.slot;
            setSpinning(true);
            setReelVisibility(true);
            setResult('DRAWING WINNER');
            strip.style.transitionDuration =
                `${data.duration}s`;
            moveTo(data.slot);
            startConfetti();
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
                    body: JSON.stringify({ participant_badges: participants.map((participant) => participant.badge) }),
                });
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'The draw could not be completed.');
                }

                const index = Number(result.index);
                if (!Number.isInteger(index) || !participants[index]) {
                    throw new Error('The draw index does not match the participant reel. Refresh the page and try again.');
                }

                const winner = { ...participants[index], draw: state.winners + 1 };

                const delta = (index - (state.slot % count) + count) % count;
                const data = {
                    type: 'spin',
                    slot: state.slot + LOOPS * count + delta,
                    winner,
                    draw: winner.draw,
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
            if (!state.current) {
                setSpinning(false);
                return;
            }

            const {
                winner,
                draw,
                stats,
                // participants: serverParticipants
            } = state.current;

            const entry = {
                draw,
                ...winner
            };

            setResult(
                'WINNER',
                winner.name,
                `BADGE ID: ${winner.badge}`
            );

            const winnerIndex = participants.findIndex(
                (participant) => String(participant.badge) === String(winner.badge)
            );

            participants = participants.filter(
                (participant) =>
                    String(participant.badge) !== String(winner.badge)
            );

            count = participants.length;

            removeParticipantFromReel(winner.badge);

            // if (Array.isArray(serverParticipants)) {
            //     participants = serverParticipants.filter(
            //         (participant) =>
            //             String(participant.badge) !== String(winner.badge)
            //     );

            //     count = participants.length;
            // }

            state.history = [
                entry,
                ...state.history.filter(
                    (item) =>
                        String(item.badge) !== String(winner.badge)
                )
            ];

            if (stats) {
                applyStats(stats);
            } else {
                state.winners += 1;
                state.eligible = Math.max(
                    0,
                    state.eligible - 1
                );
                setStat(
                    'winners',
                    state.winners
                );
                setStat(
                    'eligible',
                    state.eligible
                );
            }

            if (count > 0) {
                state.slot = (winnerIndex >= 0 ? winnerIndex : 0) % count;
            } else {
                state.slot = 0;
            }

            state.current = null;

            if (count > 0) {
                jumpTo(state.slot);
            }

            renderHistory();
            showNext();
            setSpinning(false);
            stopConfetti();
            celebrate();
            save();
        };

        const forfeitWinnerEntry = async (badge) => {
            if (state.spinning) return;

            const entry = state.history.find((item) => item.badge === badge);
            if (!entry) return;

            const confirmation = await Swal.fire({
                title: `Forfeit ${entry.name}?`,
                text: 'This removes them from the active winners count. Their check-in stays saved, and they will not return to the eligible list.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, forfeit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#b45309',
                reverseButtons: true,
                focusCancel: true
            });

            if (!confirmation.isConfirmed) return;
            if (state.spinning) return;
            if (!state.history.some((item) => item.badge === badge)) return;

            setSpinning(true);

            try {
                const result = await forfeitWinner(badge);
                state.history = state.history.filter((item) => item.badge !== badge);
                applyStats(result.stats);
                addForfeited(entry);
                renderHistory();
                setResult(`${entry.name.toUpperCase()} FORFEITED - DRAW AGAIN`);
                channel?.postMessage({ type: 'refresh' });
                await Swal.fire({
                    icon: 'success',
                    title: 'Forfeit completed',
                    text: `The winner count decreased and a draw slot is available again. ${entry.name} stays excluded from eligible spins; check-in is unchanged.`,
                    confirmButtonColor: '#b45309',
                });
                window.location.reload();
            } catch (error) {
                await Swal.fire({ icon: 'error', title: 'Unable to forfeit winner', text: error.message });
            } finally {
                setSpinning(false);
            }
        };

        const removeWinner = async (badge) => {
            if (state.spinning) return;

            const entry = state.history.find((item) => String(item.badge) === String(badge));
            if (!entry) return;

            const confirmation = await Swal.fire({
                title: `Remove ${entry.name} from winners?`,
                text: 'The winner record will be deleted. Their check-in remains, and they will return to the eligible list.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0284c7',
                reverseButtons: true,
                focusCancel: true,
            });

            if (!confirmation.isConfirmed || state.spinning) return;

            setSpinning(true);
            try {
                const result = await removeWinnerRecord(badge);
                state.history = state.history.filter((item) => String(item.badge) !== String(badge));
                applyStats(result.stats);
                renderHistory();
                setResult(`${entry.name.toUpperCase()} REMOVED - ELIGIBLE AGAIN`);
                channel?.postMessage({ type: 'refresh' });
                await Swal.fire({ icon: 'success', title: 'Removed', text: `${entry.name} is eligible to spin again.` });
                window.location.reload();
            } catch (error) {
                await Swal.fire({ icon: 'error', title: 'Unable to remove winner', text: error.message });
            } finally {
                setSpinning(false);
            }
        };

        const removeAll = async () => {
            if (state.spinning) return;

            const confirmation = await Swal.fire({
                title: 'Reset lucky drawing from the beginning?',
                text: 'All drawing records will be permanently deleted, including active winners and forfeited entries. Check-in data will stay unchanged. After reset, every checked-in non-manager participant will be eligible to draw again.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, reset all drawings',
                confirmButtonColor: '#be123c',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            });

            if (!confirmation.isConfirmed) return;
            if (state.spinning) return;

            setSpinning(true);

            try {
                const result = await resetAllDrawings();

                state.history = [];
                applyStats(result.stats);
                setResult('DRAWING RESET - ELIGIBLE TO DRAW AGAIN');
                channel?.postMessage({ type: 'refresh' });
                await Swal.fire({
                    icon: 'success',
                    title: 'Drawing reset complete',
                    text: `All drawing records were deleted. Check-ins remain saved; ${Number(result.stats.eligible).toLocaleString()} checked-in non-manager participants are now eligible to draw again.`,
                    confirmButtonColor: '#7a2cc0',
                });
                window.location.reload();
            } catch (error) {
                await Swal.fire({ icon: 'error', title: 'Drawing was not reset', text: error.message });
            } finally {
                setSpinning(false);
            }
        };

        const forfeitAll = async () => {
            if (state.spinning || !state.history.length) return;

            const confirmation = await Swal.fire({
                title: `Forfeit all ${state.history.length} winners?`,
                text: 'Their active winners count will decrease. Check-ins stay saved, and these participants will remain excluded from eligible spins.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, forfeit all',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#b45309',
                reverseButtons: true,
                focusCancel: true,
            });

            if (!confirmation.isConfirmed || state.spinning || !state.history.length) return;

            const forfeited = [...state.history];
            setSpinning(true);
            try {
                let result;
                for (const entry of forfeited) {
                    result = await forfeitWinner(entry.badge);
                }

                state.history = [];
                applyStats(result.stats);
                renderHistory();
                setResult('ALL WINNERS FORFEITED - DRAW AGAIN');
                channel?.postMessage({ type: 'refresh' });
                await Swal.fire({
                    icon: 'success',
                    title: 'Forfeit all completed',
                    text: 'The winner count decreased and draw slots are available again. Check-ins are unchanged, and all participants remain excluded from eligible spins.',
                    confirmButtonColor: '#b45309',
                });
                window.location.reload();
            } catch (error) {
                await Swal.fire({ icon: 'error', title: 'Unable to forfeit all winners', text: error.message });
                window.location.reload();
            } finally {
                setSpinning(false);
            }
        };

        const forfeit = () => {
            if (state.spinning || !state.history.length) return;

            forfeitWinnerEntry(state.history[0].badge);
        };

        applyDuration(state.duration);
        setStat('checked', state.checkedIn);
        setStat('winners', state.winners);
        setStat('eligible', state.eligible);
        showNext();
        renderHistory();
        jumpTo(state.slot);
        syncForfeit();
        syncRemoveAll();
        syncForfeitedCount();

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
        forfeitAllButton.addEventListener('click', forfeitAll);
        forfeitedButton.addEventListener('click', showForfeited);

        list.addEventListener('click', (event) => {
            const button = event.target.closest('[data-remove], [data-forfeit-winner]');
            if (!button) return;

            const badge = button.closest('tr').dataset.badge;
            if (button.hasAttribute('data-forfeit-winner')) {
                forfeitWinnerEntry(badge);
            } else {
                removeWinner(badge);
            }
        });

        strip.addEventListener('transitionend', (event) => {
            if (event.target === strip && event.propertyName === 'transform' && state.spinning) reveal();
        });
    </script>
</body>

</html>
