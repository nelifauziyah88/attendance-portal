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
@endphp

@section('content')
    <section
        class="relative shrink-0 overflow-hidden bg-gradient-to-b from-[#4468e8] via-[#3d68fa] to-[#6a94ff] px-4 py-6 sm:px-6">
        <div class="pointer-events-none absolute -left-8 top-1/2 h-72 w-40 [animation:drift_9s_ease-in-out_infinite]">
            <span class="absolute left-0 top-0 size-32 rounded-full border border-white/40"></span>
            <span class="absolute left-10 top-16 size-32 rounded-full border border-white/40"></span>
            <span class="absolute left-0 top-32 size-32 rounded-full border border-white/40"></span>
        </div>
        <div
            class="pointer-events-none absolute -right-8 top-1/2 h-72 w-40 [animation:drift_11s_ease-in-out_infinite_reverse]">
            <span class="absolute right-0 top-0 size-32 rounded-full border border-white/40"></span>
            <span class="absolute right-10 top-16 size-32 rounded-full border border-white/40"></span>
            <span class="absolute right-0 top-32 size-32 rounded-full border border-white/40"></span>
        </div>

        <div
            class="relative mx-auto flex max-w-6xl flex-col items-center rounded-2xl border border-white/40 px-6 py-6 text-center text-white [animation:rise_.8s_ease-out_both]">
            <span
                class="relative grid size-16 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20">
                <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}" class="size-full object-contain p-2">
                <span
                    class="absolute inset-y-0 left-0 w-1/3 -skew-x-12 bg-white/60 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
            </span>

            <p class="mt-3 text-sm font-medium tracking-wider text-white/95">{{ $event['company'] }}</p>
            <p class="mt-2 flex items-center gap-3 text-[11px] font-medium tracking-widest text-blue-100">
                <span class="h-px w-3 bg-blue-100"></span>{{ $event['tagline'] }}<span class="h-px w-3 bg-blue-100"></span>
            </p>

            <p class="mt-4 text-lg font-light">You are cordially invited to</p>
            <h1 class="mt-1 text-4xl font-semibold tracking-tight sm:text-6xl">{{ $event['title'] }}</h1>

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

    <section class="flex flex-1 flex-col items-center px-4 py-8 sm:px-6">
        <div class="text-center [animation:rise_.8s_.15s_ease-out_both]">
            <p class="text-xs font-semibold tracking-widest text-[#3563ff]">YOUR INVITATION</p>
            <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">We hope to see you there.</h2>
            <p class="mt-2 text-sm text-slate-500">Please verify your information and confirm your attendance below.</p>
        </div>

        <form method="POST" action="#" class="mt-6 w-full max-w-3xl [animation:rise_.8s_.3s_ease-out_both]">
            @csrf

            <div class="space-y-5 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xl shadow-blue-100/60 sm:p-8">
                <x-user.step number="01" title="Employee information"
                    subtitle="Enter your badge ID to view your invitation details." />

                <div class="flex items-end gap-3">
                    <x-user.field class="flex-1" label="Badge ID" name="badge_id" placeholder="Enter your badge ID" />
                    <button type="button"
                        class="h-[52px] shrink-0 rounded-xl bg-[#3563ff] px-5 text-sm font-medium text-white transition duration-300 hover:-translate-y-0.5 hover:bg-[#2a52e6] hover:shadow-lg hover:shadow-blue-400/40 active:scale-95">
                        Find invitation
                    </button>
                </div>

                <x-user.field label="Name" name="name" placeholder="Your name will appear here" readonly />

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-user.field label="Position" name="position" placeholder="Your position will appear here" readonly />
                    <x-user.field label="Department" name="department" placeholder="Your department will appear here"
                        readonly />
                </div>

                <hr class="border-slate-200">

                <x-user.step number="02" title="Attendance confirmation"
                    subtitle="Let us know if you can join the celebration." />

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-user.choice name="attendance" value="yes" label="Yes, I will attend" checked />
                    <x-user.choice name="attendance" value="no" label="Sorry, I cannot attend" />
                </div>
            </div>

            <button type="submit"
                class="mt-4 flex h-14 w-full items-center justify-center rounded-xl bg-[#3563ff] text-sm font-medium text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:-translate-y-0.5 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/40 active:scale-[.98]">
                Submit response
            </button>
        </form>
    </section>
@endsection
