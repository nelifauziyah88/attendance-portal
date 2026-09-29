@extends('layouts.user')

@section('title', 'Your Event Pass')

@php
    $qrSrc = $qrSrc ?? null;
    $bookingCode = $bookingCode ?? 'BK-2026-000123';
    $checkInUrl = $checkInUrl ?? 'https://event.example.com/check-in/INVITE-CODE';

    $event = [
        'logo' => 'images/logo.png',
        'company' => 'PT COMPANY NAME',
        'title' => 'Annual Gala Dinner',
        'meta' => ['Sunday, 15 November 2026', '7:00 PM', 'Grand Ballroom'],
    ];
@endphp

@section('content')
    <section class="relative shrink-0 overflow-hidden bg-gradient-to-b from-[#4468e8] via-[#3d68fa] to-[#6a94ff] px-4 pb-24 pt-6 sm:px-6">
        <div class="pointer-events-none absolute -left-8 top-1/2 h-72 w-40 [animation:drift_9s_ease-in-out_infinite]">
            <span class="absolute left-0 top-0 size-32 rounded-full border border-white/40"></span>
            <span class="absolute left-10 top-16 size-32 rounded-full border border-white/40"></span>
            <span class="absolute left-0 top-32 size-32 rounded-full border border-white/40"></span>
        </div>
        <div class="pointer-events-none absolute -right-8 top-1/2 h-72 w-40 [animation:drift_11s_ease-in-out_infinite_reverse]">
            <span class="absolute right-0 top-0 size-32 rounded-full border border-white/40"></span>
            <span class="absolute right-10 top-16 size-32 rounded-full border border-white/40"></span>
            <span class="absolute right-0 top-32 size-32 rounded-full border border-white/40"></span>
        </div>

        <div class="relative mx-auto flex max-w-6xl flex-col items-center rounded-2xl border border-white/40 px-6 pb-20 pt-5 text-center text-white [animation:rise_.8s_ease-out_both]">
            <span class="relative grid size-14 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20">
                <img src="{{ asset($event['logo']) }}" alt="{{ $event['company'] }}" class="size-full object-contain p-2">
                <span class="absolute inset-y-0 left-0 w-1/3 -skew-x-12 bg-white/60 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
            </span>

            <p class="mt-3 text-sm font-medium tracking-wider text-white/95">{{ $event['company'] }}</p>
            <p class="mt-3 text-[11px] font-medium tracking-widest text-blue-100">YOUR RESPONSE HAS BEEN RECEIVED</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight sm:text-5xl">You're on the guest list.</h1>
            <p class="mt-2 text-sm font-light text-blue-50 sm:text-base">Your invitation is ready. Keep this QR code for check-in at the event.</p>
        </div>
    </section>

    <section class="relative z-10 -mt-[4.5rem] flex flex-1 flex-col items-center px-4 pb-8 sm:px-6">
        <article class="w-full max-w-4xl overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-xl shadow-blue-100/60 [animation:rise_.8s_.2s_ease-out_both]">
            <div class="p-6 sm:px-10 sm:pt-8">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-semibold tracking-widest text-[#3563ff]">YOUR EVENT PASS</p>
                        <h2 class="mt-1 text-2xl font-semibold tracking-tight sm:text-3xl">{{ $event['title'] }}</h2>
                        <p class="mt-2 flex flex-wrap items-center gap-x-3 text-sm text-slate-500">
                            @foreach ($event['meta'] as $item)
                                @unless ($loop->first)
                                    <span class="size-1 rounded-full bg-slate-400"></span>
                                @endunless
                                <span>{{ $item }}</span>
                            @endforeach
                        </p>
                    </div>

                    <span class="flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-[11px] font-semibold tracking-wider text-emerald-700">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                        </span>
                        CONFIRMED
                    </span>
                </div>

                <hr class="my-6 border-slate-200">

                <div class="grid items-center gap-8 md:grid-cols-[auto_1fr]">
                    <figure class="flex flex-col items-center gap-3">
                        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-blue-100/60 transition duration-500 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-200/60">
                            @if ($qrSrc)
                                <img src="{{ $qrSrc }}" alt="Check-in QR code" class="size-52">
                            @else
                                <div class="grid size-52 place-items-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 text-slate-300">
                                    <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/></svg>
                                </div>
                            @endif
                            <span class="absolute inset-x-4 h-0.5 rounded-full bg-[#3563ff]/70 shadow-[0_0_12px_#3563ff] [animation:scan_3.5s_ease-in-out_infinite]"></span>
                        </div>
                        <figcaption class="text-xs text-slate-400">Scan this code at the entrance</figcaption>
                    </figure>

                    <div>
                        <p class="text-[11px] font-semibold tracking-widest text-[#3563ff]">CHECK-IN DETAILS</p>
                        <h3 class="mt-1 text-2xl font-semibold tracking-tight">You're all set.</h3>
                        <p class="mt-3 max-w-sm text-sm leading-relaxed text-slate-500">Show this QR code to event staff when you arrive. Save it to your phone or copy your check-in link.</p>

                        <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 transition duration-300 hover:border-blue-300">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">CHECK-IN URL</p>
                            <p class="mt-1 truncate text-sm text-[#26346b]">{{ $checkInUrl }}</p>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <x-user.action :href="$qrSrc" download="invitation-qr.svg" :class="$qrSrc ? '' : 'pointer-events-none opacity-60'">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0-4-4m4 4 4-4M5 21h14"/></svg>
                                Save QR image
                            </x-user.action>

                            <x-user.action variant="outline" data-copy="{{ $checkInUrl }}">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                                <span data-label>Copy URL</span>
                            </x-user.action>
                        </div>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 transition duration-300 hover:border-blue-300">
                            <p class="text-[10px] font-semibold tracking-widest text-slate-500">BOOKING CODE</p>
                            <p class="mt-1 font-mono text-sm font-medium tracking-widest text-[#26346b]">{{ $bookingCode }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-6 border-t border-slate-200 px-6 py-4 text-center text-sm text-slate-500">Please keep this pass available on your phone for event entry.</p>
        </article>

        <p class="mt-5 flex flex-wrap items-center justify-center gap-x-3 text-xs text-slate-400 [animation:rise_.8s_.4s_ease-out_both]">
            One QR code per invitation
            <span class="size-1 rounded-full bg-slate-300"></span>
            Present it at the check-in desk
        </p>
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-copy]').forEach((button) => {
            button.addEventListener('click', async () => {
                const label = button.querySelector('[data-label]');
                const original = label.textContent;

                await navigator.clipboard.writeText(button.dataset.copy);
                label.textContent = 'Copied';

                setTimeout(() => (label.textContent = original), 1800);
            });
        });
    </script>
@endpush