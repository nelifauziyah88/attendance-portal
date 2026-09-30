@extends('layouts.user')

@section('title', 'Annual Gala Dinner Invitation')

@php
    $event = [
        'logo' => 'images/logo.png',
        'company' => 'Seatrium',
        'tagline' => 'Celebrating Success Together',
        'title' => 'Annual Gala Dinner',
        'details' => [
            ['label' => 'DATE', 'value' => 'Sunday, 15 November 2026'],
            ['label' => 'TIME', 'value' => '7:00 PM'],
            ['label' => 'VENUE', 'value' => 'Grand Ballroom'],
        ],
    ];

    $clusters = [
        [
            'position' => '-left-8 [animation:drift_9s_ease-in-out_infinite]',
            'circles' => ['left-0 top-0', 'left-10 top-16', 'left-0 top-32'],
        ],
        [
            'position' => '-right-8 [animation:drift_11s_ease-in-out_infinite_reverse]',
            'circles' => ['right-0 top-0', 'right-10 top-16', 'right-0 top-32'],
        ],
    ];
@endphp

@section('content')
    <section class="relative shrink-0 overflow-hidden bg-gradient-to-b from-[#4468e8] via-[#3d68fa] to-[#6a94ff] px-3 py-4 sm:px-6 sm:py-6">
        @foreach ($clusters as $cluster)
            <div class="pointer-events-none absolute top-1/2 hidden h-72 w-40 sm:block {{ $cluster['position'] }}">
                @foreach ($cluster['circles'] as $circle)
                    <span class="absolute size-32 rounded-full border border-white/40 {{ $circle }}"></span>
                @endforeach
            </div>
        @endforeach

        <div class="relative mx-auto flex max-w-6xl flex-col items-center rounded-2xl border border-white/40 px-4 py-6 text-center text-white sm:px-6 [animation:rise_.8s_ease-out_both]">
            <span class="relative grid size-14 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20 sm:size-16">
                <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}" class="size-full object-contain p-2">
                <span class="absolute inset-y-0 left-0 w-1/3 -skew-x-12 bg-white/60 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
            </span>

            <p class="mt-3 text-sm font-medium tracking-wider text-white/95">{{ $event['company'] }}</p>

            <p class="mt-2 flex items-center gap-3 text-[10px] font-medium tracking-widest text-blue-100 sm:text-[11px]">
                <span class="h-px w-3 shrink-0 bg-blue-100"></span>
                {{ $event['tagline'] }}
                <span class="h-px w-3 shrink-0 bg-blue-100"></span>
            </p>

            <p class="mt-4 text-base font-light sm:text-lg">You are cordially invited to</p>
            <h1 class="mt-1 text-balance text-3xl font-semibold tracking-tight min-[400px]:text-4xl sm:text-5xl lg:text-6xl">{{ $event['title'] }}</h1>

            <dl class="mt-5 grid w-full max-w-4xl grid-cols-1 gap-4 border-y border-white/30 py-4 sm:grid-cols-3">
                @foreach ($event['details'] as $detail)
                    <div class="transition duration-300 hover:-translate-y-1 sm:first:text-left sm:last:text-right">
                        <dt class="text-[11px] font-medium tracking-widest text-blue-100">{{ $detail['label'] }}</dt>
                        <dd class="mt-1 text-base font-medium">{{ $detail['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="flex flex-1 flex-col items-center px-3 py-6 sm:px-6 sm:py-8">
        <div class="text-center [animation:rise_.8s_.15s_ease-out_both]">
            <p class="text-xs font-semibold tracking-widest text-[#3563ff]">YOUR INVITATION</p>
            <h2 class="mt-2 text-balance text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">We hope to see you there.</h2>
            <p class="mx-auto mt-2 max-w-sm text-sm text-slate-500 sm:max-w-none">Please verify your information and confirm your attendance below.</p>
        </div>

        <form method="POST" action="{{ route('invitation.store') }}" class="mt-6 w-full max-w-3xl [animation:rise_.8s_.3s_ease-out_both]">
            @csrf

            <div class="space-y-5 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-xl shadow-blue-100/60 sm:p-8">
                <x-user.step number="01" title="Employee information" subtitle="Enter your badge ID to view your invitation details." />

                <div class="flex flex-col gap-3 min-[480px]:flex-row min-[480px]:items-end">
                    <x-user.field class="flex-1" label="Badge ID" name="badge_id" placeholder="Enter your badge ID" value="{{ old('badge_id') }}" required />

                    <x-user.action id="btnFind" size="sm" class="w-full shrink-0 min-[480px]:w-auto">
                        <span id="btnFindText">Find invitation</span>
                        <span id="btnFindSpinner" class="hidden">Searching...</span>
                    </x-user.action>
                </div>

                <x-user.field label="Name" name="name" placeholder="Your name will appear here" readonly />

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-user.field label="Position" name="position" placeholder="Your position will appear here" readonly />
                    <x-user.field label="Department" name="department" placeholder="Your department will appear here" readonly />
                </div>

                <div id="attendanceConfirmation" class="hidden space-y-5">
                    <hr class="border-slate-200">

                    <x-user.step number="02" title="Attendance Confirmation" subtitle="Let us know if you can join the celebration." />

                    <div class="grid gap-3 sm:grid-cols-2 sm:gap-4">
                        <x-user.choice name="attendance" value="yes" label="Yes, I will attend" checked disabled />
                        <x-user.choice name="attendance" value="no" label="Sorry, I cannot attend" disabled />
                    </div>
                </div>
            </div>

            <div id="submitSection" class="mt-4 hidden">
                <x-user.action id="btnSubmit" type="submit" disabled class="w-full">Submit response</x-user.action>
            </div>
        </form>
    </section>

<script>
document.addEventListener('DOMContentLoaded', function () {
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

    inputBadgeId.addEventListener('input', function () {
        setRsvpVisible(false);
        setRsvpLocked(true);
        inputName.value = '';
        inputPosition.value = '';
        inputDepartment.value = '';
        attendanceYes.checked = true;
        attendanceNo.checked = false;
    });

    btnFind.addEventListener('click', function () {
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
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
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
                    showAlert('Konfirmasi Anda sudah tersimpan sebagai ' + (emp.attendance === 'yes' ? 'Hadir' : 'Tidak Hadir') + '. Pilihan tidak dapat diubah atau dikirim ulang.', 'info');
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