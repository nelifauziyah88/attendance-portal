<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Sign In</title>
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg)
            }

            50% {
                transform: translateY(-14px) rotate(6deg)
            }
        }

        @keyframes sheen {
            from {
                transform: translateX(-120%)
            }

            to {
                transform: translateX(220%)
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

        .sparkles {
            background-image:
                radial-gradient(circle, #fff 0 1.5px, transparent 2px),
                radial-gradient(circle, rgba(255, 255, 255, .8) 0 1px, transparent 1.5px),
                radial-gradient(circle, #fff 0 2px, transparent 2.5px);
            background-size: 140px 170px, 90px 110px, 220px 260px;
            background-position: 0 0, 40px 60px, 100px 30px;
        }
    </style>
</head>
@php
    $brand = [
        'logo' => 'images/logo.png',
        'company' => 'Seatrium',
        'title' => 'Welcome back to Event Portal.',
        'description' => 'Manage invitations, monitor attendance, and run the lucky spin in one place.',
    ];

    $clusters = [
        'right-10 top-6 [animation:float_9s_ease-in-out_infinite]',
        'bottom-16 left-4 [animation:float_11s_ease-in-out_infinite_reverse]',
    ];

    $circles = ['left-6 top-0', 'left-0 top-8', 'left-12 top-8', 'left-6 top-16'];
@endphp

<body
    class="flex min-h-screen flex-col bg-[#f8f4ff] font-normal text-[#2e1065] antialiased font-['Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">

    <main class="grid flex-1 lg:grid-cols-2">
        <aside
            class="relative hidden overflow-hidden bg-gradient-to-tr from-[#a3248f] via-[#5b1b7a] to-[#2e1065] p-16 text-white lg:flex lg:flex-col">
            <div class="sparkles pointer-events-none absolute inset-0 [animation:twinkle_4s_ease-in-out_infinite]"></div>

            <svg class="pointer-events-none absolute inset-x-0 bottom-0 h-56 w-full opacity-80" viewBox="0 0 800 240"
                preserveAspectRatio="xMidYMax slice" fill="none" aria-hidden="true">
                <defs>
                    <linearGradient id="peak" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#7c3aed" stop-opacity=".55" />
                        <stop offset="1" stop-color="#2e1065" stop-opacity=".1" />
                    </linearGradient>
                </defs>
                <path d="M0 240V150L110 90 210 160 330 60 450 150 560 100 680 170 800 120V240Z" fill="url(#peak)" />
                <path
                    d="M0 150L110 90 210 160 330 60 450 150 560 100 680 170 800 120M110 90L150 240M210 160L150 240M210 160L260 240M330 60L260 240M330 60L400 240M450 150L400 240M450 150L520 240M560 100L520 240M560 100L620 240M680 170L620 240M680 170L740 240M800 120L740 240M210 160L330 60M450 150L330 60M450 150L560 100M680 170L560 100"
                    stroke="#67d4ff" stroke-opacity=".75" stroke-width="1.2" stroke-linejoin="round" />
            </svg>

            @foreach ($clusters as $position)
                <div class="pointer-events-none absolute size-28 {{ $position }}">
                    @foreach ($circles as $circle)
                        <span class="absolute h-16 w-14 rounded-full border border-white/50 {{ $circle }}"></span>
                    @endforeach
                    <span
                        class="absolute left-[3.25rem] top-[3.25rem] size-4 rounded-full border border-white/60"></span>
                </div>
            @endforeach

            <div class="relative [animation:rise_.8s_ease-out_both]">
                <span
                    class="relative grid size-14 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-purple-950/30">
                    <img src="{{ asset($brand['logo']) }}" alt="{{ $brand['company'] }}"
                        class="size-full object-contain p-2">
                    <span
                        class="absolute inset-y-0 left-0 w-1/3 -skew-x-12 bg-white/60 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
                </span>
                <p class="mt-4 text-xs font-medium tracking-wider text-violet-100">{{ $brand['company'] }}</p>
            </div>

            <div class="relative my-auto max-w-md [animation:rise_.8s_.15s_ease-out_both]">
                <h1 class="text-5xl font-semibold leading-tight tracking-tight">{{ $brand['title'] }}</h1>
                <p class="mt-6 text-base font-light leading-relaxed text-violet-100">{{ $brand['description'] }}</p>
                <hr class="mt-8 w-full max-w-sm border-white/40">
                <p class="mt-5 text-[11px] font-medium tracking-widest text-cyan-200">SECURE ADMIN WORKSPACE</p>
            </div>
        </aside>

        <section class="flex flex-col items-center justify-between px-6 py-10">
            <div class="flex w-full max-w-md flex-1 flex-col justify-center [animation:rise_.8s_.2s_ease-out_both]">
                <p class="text-xs font-semibold tracking-widest text-fuchsia-600">ADMIN SIGN IN</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight">Sign in to your account</h2>
                <p class="mt-2 text-sm text-slate-500">Enter your credentials to continue.</p>

                @if (session('success'))
                    <p role="status"
                        class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">
                        {{ session('success') }}</p>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}"
                    class="mt-8 space-y-5 rounded-3xl border border-violet-200/70 bg-white p-8 shadow-xl shadow-fuchsia-200/50">
                    @csrf

                    <div class="flex flex-col gap-1.5">
                        <label for="email" class="text-xs font-medium">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            placeholder="Enter your admin email" autocomplete="email"
                            class="h-[52px] w-full rounded-xl border border-violet-200 bg-violet-50/40 px-4 text-sm outline-none transition duration-300 placeholder:text-violet-300 focus:-translate-y-0.5 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                        @error('email')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="password" class="text-xs font-medium">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" placeholder="Enter your password"
                                autocomplete="current-password"
                                class="h-[52px] w-full rounded-xl border border-violet-200 bg-violet-50/40 pl-4 pr-12 text-sm outline-none transition duration-300 placeholder:text-violet-300 focus:-translate-y-0.5 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                            <button type="button" data-toggle-password aria-label="Show password"
                                class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 transition duration-300 hover:text-fuchsia-600">
                                <svg data-eye class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-eye-off class="hidden size-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M3 3l18 18M10.6 6.1A10 10 0 0 1 12 6c6.5 0 10 6 10 6a17 17 0 0 1-3.2 3.9M6.6 6.6A17 17 0 0 0 2 12s3.5 6 10 6a10 10 0 0 0 4-.8" />
                                    <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end text-sm">
                        <span class="text-xs text-slate-400">Admin account only</span>
                    </div>

                    <button type="submit"
                        class="flex h-14 w-full items-center justify-center rounded-xl bg-gradient-to-r from-fuchsia-600 to-violet-700 text-sm font-medium text-white shadow-lg shadow-fuchsia-500/40 ring-1 ring-cyan-300/40 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-fuchsia-500/50 hover:ring-cyan-300/70 hover:brightness-110 active:scale-[.98]">
                        Sign in
                    </button>
                </form>

                <p class="mt-5 text-center text-xs text-slate-400">Authorized personnel only</p>
            </div>

            <p class="mt-8 text-center text-xs text-slate-400">&copy; {{ date('Y') }} Seatrium. All rights
                reserved.</p>
        </section>
    </main>

    <script>
        const toggle = document.querySelector('[data-toggle-password]');
        const input = document.getElementById('password');

        toggle.addEventListener('click', () => {
            const visible = input.type === 'text';

            input.type = visible ? 'password' : 'text';
            toggle.querySelector('[data-eye]').classList.toggle('hidden', !visible);
            toggle.querySelector('[data-eye-off]').classList.toggle('hidden', visible);
            toggle.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
        });
    </script>
</body>

</html>