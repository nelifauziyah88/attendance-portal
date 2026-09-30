@extends('layouts.user')

@section('title', 'Annual Gala Dinner Invitation')

@php
    $event = [
        'poster' => 'images/poster.jpeg',
        'title' => 'Annual Gala Dinner',
    ];
@endphp

@section('content')
    <section class="shrink-0 overflow-hidden bg-[#2e1065]">
        <img src="{{ asset($event['poster']) }}" alt="{{ $event['title'] }}" class="block h-auto w-full" fetchpriority="high">
    </section>

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
                    error: 'Tidak dapat melanjutkan',
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
