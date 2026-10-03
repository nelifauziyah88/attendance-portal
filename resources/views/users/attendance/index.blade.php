@extends('layouts.user')

@section('title', 'D&D 2026 Check-in')

@section('badge', 'EMPLOYEE CHECK-IN')

@php
    $event = [
        'poster' => 'images/poster.jpeg',
        'title' => 'D&D 2026',
    ];

    $details = [
        ['label' => 'Badge ID', 'id' => 'employeeBadge'],
        ['label' => 'Name', 'id' => 'inputName'],
        ['label' => 'Position', 'id' => 'inputPosition'],
        ['label' => 'Department', 'id' => 'inputDepartment'],
    ];

    $calendar = '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>';

    $steps = [
        ['number' => '01', 'label' => 'RSVP Complete', 'done' => true],
        ['number' => '02', 'label' => 'Event Check-in', 'done' => false],
    ];
@endphp

@section('content')

    <div
        @class([
            'transition-all duration-500',
            'blur-md pointer-events-none select-none' => $scheduleStatus !== 'active',
        ])
    >

    <section class="shrink-0 overflow-hidden bg-[#2e1065]">
        <button type="button" id="posterTrigger" aria-label="View poster in full screen"
            class="group relative block w-full cursor-zoom-in focus:outline-none focus-visible:ring-4 focus-visible:ring-fuchsia-400/70">
            <img src="{{ asset($event['poster']) }}" alt="{{ $event['title'] }}" class="block h-auto w-full"
                fetchpriority="high">
            <span
                class="pointer-events-none absolute bottom-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-black/55 px-3 py-1.5 text-xs font-medium text-white backdrop-blur-sm transition group-hover:bg-black/70 sm:bottom-4 sm:right-4">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" />
                </svg>
                Tap to enlarge
            </span>
        </button>
    </section>

    <div id="posterLightbox" role="dialog" aria-modal="true" aria-label="{{ $event['title'] }} poster"
        class="pointer-events-none invisible fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-3 opacity-0 backdrop-blur-sm transition-opacity duration-300 sm:p-6">
        <button type="button" id="posterClose" aria-label="Close poster"
            class="absolute right-3 top-[max(0.75rem,env(safe-area-inset-top))] z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-white backdrop-blur transition hover:bg-white/30 focus:outline-none focus-visible:ring-4 focus-visible:ring-fuchsia-400/70 sm:right-6 sm:top-6">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>
        <img id="posterLightboxImg" src="{{ asset($event['poster']) }}" alt="{{ $event['title'] }}"
            class="max-h-[calc(100dvh-1.5rem)] max-w-full scale-95 select-none rounded-lg object-contain shadow-2xl transition-transform duration-300 sm:max-h-[calc(100dvh-3rem)]">
    </div>

    <section class="relative z-10 flex flex-1 flex-col items-center px-3 pb-8 pt-8 sm:px-6 sm:pt-10">
        <article
            class="w-full max-w-xl rounded-3xl border border-violet-300/60 bg-white p-5 shadow-xl shadow-fuchsia-200/50 sm:p-10 [animation:rise_.8s_.2s_ease-out_both]">
            <dl class="w-full min-w-0">
                @foreach ($details as $detail)
                    <div @class([
                        'grid w-full grid-cols-[7rem_1fr] font-bold items-center gap-3 py-3 text-sm sm:grid-cols-[10rem_1fr] sm:py-4 sm:text-base',
                        'border-b border-violet-200' => !$loop->last,
                        'pt-0' => $loop->first,
                        'pb-0' => $loop->last,
                    ])>
                        <dt class="text-slate-500">{{ $detail['label'] }}</dt>
                        <dd id="{{ $detail['id'] }}" class="min-w-0 break-words font-medium">-</dd>
                    </div>
                @endforeach
            </dl>
            </div>
        </article>


        <div class="mx-auto mt-6 flex w-full max-w-xl flex-col gap-3 min-[480px]:flex-row min-[480px]:items-end">
            <div class="flex-1">
                <label for="badgeLookup" class="mb-2 block text-left text-sm font-medium">Employee Badge ID</label>
                <input id="badgeLookup" type="text" autocomplete="off" placeholder="Enter your Badge ID"
                    class="h-14 w-full min-w-0 rounded-xl border border-violet-200 bg-white px-4 text-base outline-none placeholder:text-violet-300 focus:border-fuchsia-600 focus:ring-4 focus:ring-fuchsia-100">
            </div>
            <button id="btnFind" type="button"
                class="flex h-14 shrink-0 items-center justify-center rounded-xl border-2 border-violet-200 bg-white px-5 text-sm font-medium transition hover:border-fuchsia-500 hover:text-fuchsia-600 disabled:opacity-60">
                <span id="btnFindText">Find employee</span>
                <span id="btnFindSpinner" class="hidden">Searching...</span>
            </button>
        </div>

        <div class="mx-auto mt-4 grid w-full max-w-xl gap-2 text-left text-sm sm:grid-cols-2">
            <p>RSVP: <span id="textRsvpStatus" class="font-semibold">-</span></p>
            <p>Check-in: <span id="textCheckinStatus" class="font-semibold">-</span></p>
        </div>

        <form method="POST" action="{{ route('checkin.store') }}"
            class="mx-auto mt-8 w-full max-w-xl text-center [animation:rise_.8s_.35s_ease-out_both]">
            @csrf
            <input type="hidden" id="badge_id" name="badge_id" value="">

            <button id="btnSubmit" type="submit" disabled
                class="relative flex h-16 w-full items-center justify-center overflow-hidden whitespace-nowrap rounded-xl bg-gradient-to-r from-fuchsia-600 to-violet-700 px-5 text-base font-medium text-white shadow-lg shadow-fuchsia-500/40 ring-1 ring-cyan-300/40 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-fuchsia-500/50 hover:ring-cyan-300/70 hover:brightness-110 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-fuchsia-200 active:scale-[.98] disabled:pointer-events-none disabled:opacity-50 sm:text-lg">
                Confirm My Attendance
                <span
                    class="pointer-events-none absolute inset-y-0 left-0 w-1/4 -skew-x-12 bg-white/20 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
            </button>

            <p class="mx-auto mt-4 max-w-xs text-xs text-slate-500 sm:max-w-none sm:text-sm">Please confirm only when you
                have arrived at the venue.</p>
        </form>

        <ol class="mt-10 flex flex-wrap items-center justify-center gap-x-4 gap-y-4 [animation:rise_.8s_.5s_ease-out_both]">
            @foreach ($steps as $step)
                @unless ($loop->first)
                    <li class="hidden h-px w-10 bg-violet-200 min-[420px]:block sm:w-16" aria-hidden="true"></li>
                @endunless

                <li class="flex items-center gap-3">
                    @if ($step['done'])
                        <span
                            class="grid size-10 shrink-0 place-items-center rounded-full bg-fuchsia-50 text-slate-400 sm:size-11">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12.5l4.5 4.5L19 7.5" />
                            </svg>
                        </span>
                    @else
                        <span
                            class="relative grid size-10 shrink-0 place-items-center rounded-full bg-gradient-to-br from-fuchsia-600 to-violet-700 text-sm font-medium text-white shadow-lg shadow-fuchsia-500/40 ring-1 ring-cyan-300/40 sm:size-11">
                            <span class="absolute inset-0 animate-ping rounded-full bg-fuchsia-500/30"></span>
                            <span class="relative">{{ $step['number'] }}</span>
                        </span>
                    @endif

                    <span @class([
                        'text-xs leading-tight sm:text-sm',
                        'text-slate-500' => $step['done'],
                        'text-fuchsia-600' => !$step['done'],
                    ])>
                        <span class="block text-[11px]">{{ $step['number'] }}</span>
                        {{ $step['label'] }}
                    </span>
                </li>
            @endforeach
        </ol>

        <p class="mt-10 text-center text-xs text-slate-400">&copy; {{ date('Y') }} Seatrium. All rights reserved.</p>
    </section>

    </div>

    @if ($scheduleStatus === 'upcoming')
        <div class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/50 px-5 backdrop-blur-[2px]">
            <div class="w-full max-w-md rounded-3xl border border-white/30 bg-white/95 p-7 text-center shadow-2xl sm:p-9">
                <div class="mx-auto grid size-16 place-items-center rounded-2xl bg-gradient-to-br from-fuchsia-600 to-violet-700 text-white shadow-lg shadow-violet-300/40">
                    <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-2xl font-bold text-[#2e1065]">Check-in Belum Dibuka</h2>
                <p class="mx-auto mt-3 max-w-sm text-sm leading-6 text-slate-500">Halaman check-in belum dapat diakses hingga waktu acara dimulai. Silakan kembali setelah waktu yang telah ditentukan.</p>
                <div class="mt-6 rounded-2xl border border-violet-100 bg-violet-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-widest text-violet-500">Acara Dimulai</p>
                    <p class="mt-1 text-lg font-bold text-violet-800">{{ $eventControl->event_start->format('d M Y, H:i') }}</p>
                </div>
                <div id="scheduleCountdown" class="mt-4 text-sm font-semibold text-violet-600">Menunggu waktu acara...</div>
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <p class="text-sm text-slate-500">Bagi yang belum memiliki invitation</p>
                    <a href="{{ route('invitation.index') }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-violet-700 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:shadow-xl">
                        View Invitation
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @elseif ($scheduleStatus === 'ended')
        <div class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/50 px-5 backdrop-blur-[2px]">
            <div class="w-full max-w-md rounded-3xl border border-white/30 bg-white/95 p-7 text-center shadow-2xl sm:p-9">
                <div class="mx-auto grid size-16 place-items-center rounded-2xl bg-slate-100 text-slate-500">
                    <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8 12h8"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-2xl font-bold text-[#2e1065]">Check-in Telah Ditutup</h2>
                <p class="mx-auto mt-3 max-w-sm text-sm leading-6 text-slate-500">Waktu check-in untuk acara ini telah berakhir. Halaman check-in sudah tidak dapat digunakan.</p>
                <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Check-in Berakhir</p>
                    <p class="mt-1 text-lg font-bold text-slate-700">{{ $eventControl->event_end->format('d M Y, H:i') }}</p>
                </div>
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <p class="text-sm text-slate-500">Bagi yang belum memiliki invitation</p>
                    <a href="{{ route('invitation.index') }}"class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-violet-200 bg-white px-5 py-3.5 text-sm font-semibold text-violet-700 transition hover:border-violet-400 hover:bg-violet-50">
                        View Invitation
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const posterTrigger = document.getElementById('posterTrigger');
            const posterLightbox = document.getElementById('posterLightbox');
            const posterLightboxImg = document.getElementById('posterLightboxImg');
            const posterClose = document.getElementById('posterClose');
            const scheduleStatus = @json($scheduleStatus);

            @if ($scheduleStatus === 'upcoming')

                const scheduleStart = new Date(
                    @json($eventControl->event_start->toIso8601String())
                ).getTime();

                const countdownElement =
                    document.getElementById('scheduleCountdown');


                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = scheduleStart - now;

                    if (distance <= 0) {
                        countdownElement.textContent =
                            'Acara sedang dimulai...';
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let text = 'Dimulai dalam ';

                    if (days > 0) {text += `${days} hari `;}
                    text += `${String(hours).padStart(2, '0')}:`;
                    text += `${String(minutes).padStart(2, '0')}:`;
                    text += `${String(seconds).padStart(2, '0')}`;

                    countdownElement.textContent = text;
                }
                updateCountdown();
                setInterval(updateCountdown, 1000);
            @endif

            function openPoster() {
                posterLightbox.classList.remove('invisible', 'pointer-events-none', 'opacity-0');
                posterLightbox.classList.add('opacity-100');
                posterLightboxImg.classList.remove('scale-95');
                posterLightboxImg.classList.add('scale-100');
                document.body.style.overflow = 'hidden';
                posterClose.focus();
            }

            function closePoster() {
                posterLightbox.classList.remove('opacity-100');
                posterLightbox.classList.add('opacity-0', 'pointer-events-none');
                posterLightboxImg.classList.remove('scale-100');
                posterLightboxImg.classList.add('scale-95');
                document.body.style.overflow = '';
                setTimeout(function() {
                    if (posterLightbox.classList.contains('opacity-0')) {
                        posterLightbox.classList.add('invisible');
                    }
                }, 300);
                posterTrigger.focus();
            }

            function isPosterOpen() {
                return posterLightbox.classList.contains('opacity-100');
            }

            posterTrigger.addEventListener('click', openPoster);
            posterClose.addEventListener('click', closePoster);

            posterLightbox.addEventListener('click', function(event) {
                if (event.target !== posterLightboxImg) {
                    closePoster();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && isPosterOpen()) {
                    closePoster();
                }
            });

            const btnFind = document.getElementById('btnFind');
            const btnFindText = document.getElementById('btnFindText');
            const btnFindSpinner = document.getElementById('btnFindSpinner');
            const btnSubmit = document.getElementById('btnSubmit');
            const inputBadgeId = document.getElementById('badgeLookup');
            const hiddenBadgeId = document.getElementById('badge_id');

            function showAlert(message, type = 'error') {
                const titles = {
                    success: 'Berhasil',
                    info: 'Informasi check-in',
                    warning: 'Periksa kembali',
                    error: 'Check-in tidak dapat diproses',
                };

                return window.Swal.fire({
                    icon: type,
                    title: titles[type] || titles.error,
                    text: message,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#3563ff',
                    color: '#26346b',
                    background: '#ffffff',
                });
            }

            function clearEmployee() {
                hiddenBadgeId.value = '';
                document.getElementById('employeeBadge').innerText = '-';
                document.getElementById('inputName').innerText = '-';
                document.getElementById('inputPosition').innerText = '-';
                document.getElementById('inputDepartment').innerText = '-';
                document.getElementById('textRsvpStatus').innerText = '-';
                document.getElementById('textCheckinStatus').innerText = '-';
                btnSubmit.disabled = true;
            }

            function findEmployee() {
                const badgeId = inputBadgeId.value.trim();

                if (!badgeId) {
                    clearEmployee();
                    showAlert('Masukkan Badge ID terlebih dahulu.', 'warning');
                    return;
                }

                btnFind.disabled = true;
                btnFindText.classList.add('hidden');
                btnFindSpinner.classList.remove('hidden');

                fetch(`{{ url('/api/check-in/employee') }}/${encodeURIComponent(badgeId)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(async response => ({
                        status: response.status,
                        body: await response.json()
                    }))
                    .then(result => {
                        if (badgeId !== inputBadgeId.value.trim()) {
                            return;
                        }

                        if (result.status !== 200 || !result.body.success) {
                            clearEmployee();
                            showAlert(result.body.message || 'Badge ID tidak dapat digunakan untuk check-in.',
                                'error');
                            return;
                        }

                        const employee = result.body.data;
                        hiddenBadgeId.value = employee.badge_id;
                        document.getElementById('employeeBadge').innerText = employee.badge_id;
                        document.getElementById('inputName').innerText = employee.name;
                        document.getElementById('inputPosition').innerText = employee.position;
                        document.getElementById('inputDepartment').innerText = employee.department;
                        document.getElementById('textRsvpStatus').innerText = 'Hadir';

                        if (employee.is_checked_in) {
                            document.getElementById('textCheckinStatus').innerText =
                                `Sudah check-in (${employee.checked_in_at})`;
                            btnSubmit.disabled = true;
                            showAlert(`Check-in sudah tercatat pada ${employee.checked_in_at}.`, 'info');
                        } else {
                            document.getElementById('textCheckinStatus').innerText = 'Belum check-in';
                            btnSubmit.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Check-in lookup failed:', error);
                        clearEmployee();
                        showAlert('Terjadi kesalahan saat memeriksa Badge ID.', 'error');
                    })
                    .finally(() => {
                        btnFind.disabled = false;
                        btnFindText.classList.remove('hidden');
                        btnFindSpinner.classList.add('hidden');
                    });
            }

            btnFind.addEventListener('click', findEmployee);
            inputBadgeId.addEventListener('input', function() {
                clearEmployee();
            });
            inputBadgeId.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    findEmployee();
                }
            });

            const flashSuccess = @json(session('success'));
            const flashError = @json(session('error') ?? $errors->first());

            if (flashSuccess) {
                showAlert(flashSuccess, 'success');
            } else if (flashError) {
                showAlert(flashError, 'error');
            }
        });
    </script>
@endsection
