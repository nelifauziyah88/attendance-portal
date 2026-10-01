@extends('layouts.user')

@section('title', 'D&D 2026 Invitation')

@php
    $event = [
        'poster' => 'images/poster.jpeg',
        'title' => 'D&D 2026',
    ];
@endphp

@section('content')
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

    <section class="flex flex-1 flex-col items-center px-3 py-6 sm:px-6 sm:py-8">
        <div class="text-center [animation:rise_.8s_.15s_ease-out_both]">
            <p class="text-xs font-semibold tracking-widest text-fuchsia-600">YOUR INVITATION</p>
            <h2 class="mt-2 text-balance text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">We hope to
                see you there.</h2>
            <p class="mx-auto mt-2 max-w-sm text-sm text-slate-500 sm:max-w-none">Please verify your information and confirm
                your attendance below.</p>
        </div>

        <form method="POST" action="{{ route('invitation.store') }}"
            class="mt-6 w-full max-w-3xl [animation:rise_.8s_.3s_ease-out_both]">
            @csrf

            <div
                class="space-y-5 rounded-3xl border border-violet-300/60 bg-white p-5 shadow-xl shadow-fuchsia-200/50 sm:p-8">
                <x-user.step number="01" title="Employee information"
                    subtitle="Enter your badge ID to view your invitation details." />

                <div class="flex flex-col gap-3 min-[480px]:flex-row min-[480px]:items-end">
                    <x-user.field class="flex-1" label="Badge ID" name="badge_id" placeholder="Enter your badge ID"
                        value="{{ old('badge_id') }}" required />

                    <x-user.action id="btnFind" size="sm" class="w-full shrink-0 min-[480px]:w-auto">
                        <span id="btnFindText">Find invitation</span>
                        <span id="btnFindSpinner" class="hidden">Searching...</span>
                    </x-user.action>
                </div>

                <x-user.field label="Name" name="name" placeholder="Your name will appear here" readonly />

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-user.field label="Position" name="position" placeholder="Your position will appear here" readonly />
                    <x-user.field label="Department" name="department" placeholder="Your department will appear here"
                        readonly />
                </div>

                <div id="attendanceConfirmation" class="hidden space-y-5">
                    <hr class="border-violet-200">

                    <x-user.step number="02" title="Attendance Confirmation"
                        subtitle="Let us know if you can join the celebration." />

                    <div class="grid gap-3 sm:grid-cols-2 sm:gap-4">
                        <x-user.choice name="attendance" value="yes" label="Yes, I will attend" sublabel="Ya, saya akan hadir" checked disabled />
                        <x-user.choice name="attendance" value="no" label="Sorry, I cannot attend" sublabel="Maaf, saya tidak bisa hadir" disabled />
                    </div>
                </div>
            </div>

            <div id="submitSection" class="mt-4 hidden">
                <x-user.action id="btnSubmit" type="submit" disabled class="w-full">Submit response</x-user.action>
            </div>
        </form>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const posterTrigger = document.getElementById('posterTrigger');
            const posterLightbox = document.getElementById('posterLightbox');
            const posterLightboxImg = document.getElementById('posterLightboxImg');
            const posterClose = document.getElementById('posterClose');

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

            const inputBadgeId = document.getElementById('badge_id');
            const inputName = document.getElementById('name');
            const inputPosition = document.getElementById('position');
            const inputDepartment = document.getElementById('department');

            const attendanceYes = document.querySelector('input[name="attendance"][value="yes"]');
            const attendanceNo = document.querySelector('input[name="attendance"][value="no"]');
            const btnSubmit = document.getElementById('btnSubmit');
            const attendanceConfirmation = document.getElementById('attendanceConfirmation');
            const submitSection = document.getElementById('submitSection');

            function setRsvpVisible(visible) {
                attendanceConfirmation.classList.toggle('hidden', !visible);
                submitSection.classList.toggle('hidden', !visible);
            }

            function setRsvpLocked(locked) {
                attendanceYes.disabled = locked;
                attendanceNo.disabled = locked;
                btnSubmit.disabled = locked;
            }

            function showAlert(message, type = 'error') {
                const titles = {
                    success: 'Berhasil',
                    info: 'Informasi RSVP',
                    warning: 'Periksa kembali',
                    error: 'Mohon maaf anda tidak masuk dalam daftar undangan',
                };

                return Swal.fire({
                    icon: type,
                    title: titles[type] || titles.error,
                    text: message,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#3563ff',
                    color: '#26346b',
                    background: '#ffffff',
                    buttonsStyling: true,
                });
            }

            inputBadgeId.addEventListener('input', function() {
                setRsvpVisible(false);
                setRsvpLocked(true);
                inputName.value = '';
                inputPosition.value = '';
                inputDepartment.value = '';
                attendanceYes.checked = true;
                attendanceNo.checked = false;
            });

            btnFind.addEventListener('click', function() {
                const badgeId = inputBadgeId.value.trim();

                if (!badgeId) {
                    setRsvpVisible(false);
                    setRsvpLocked(true);
                    showAlert('Harap masukkan Badge ID Anda terlebih dahulu.', 'warning');
                    return;
                }

                setRsvpVisible(false);
                setRsvpLocked(true);
                btnFind.disabled = true;
                btnFindText.classList.add('hidden');
                btnFindSpinner.classList.remove('hidden');

                fetch(`/api/employee/${encodeURIComponent(badgeId)}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(res => {
                        if (badgeId !== inputBadgeId.value.trim()) {
                            return;
                        }

                        if (res.status === 200 && res.body.success) {
                            const emp = res.body.data;
                            inputName.value = emp.name;
                            inputPosition.value = emp.position;
                            inputDepartment.value = emp.department;

                            attendanceYes.checked = emp.attendance !== 'no';
                            attendanceNo.checked = emp.attendance === 'no';
                            setRsvpVisible(true);

                            if (emp.has_confirmed) {
                                setRsvpLocked(true);
                                showAlert('Konfirmasi Anda sudah tersimpan sebagai ' + (emp
                                        .attendance === 'yes' ? 'Hadir' : 'Tidak Hadir') +
                                    '. Pilihan tidak dapat diubah atau dikirim ulang.', 'info');
                            } else {
                                setRsvpLocked(false);
                            }
                        } else {
                            setRsvpVisible(false);
                            setRsvpLocked(true);
                            inputName.value = '';
                            inputPosition.value = '';
                            inputDepartment.value = '';
                            showAlert(res.body.message || 'Badge ID tidak ditemukan.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching employee:', error);
                        setRsvpVisible(false);
                        setRsvpLocked(true);
                        showAlert('Terjadi kesalahan jaringan/server saat mengambil data.', 'error');
                    })
                    .finally(() => {
                        btnFind.disabled = false;
                        btnFindText.classList.remove('hidden');
                        btnFindSpinner.classList.add('hidden');
                    });
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
