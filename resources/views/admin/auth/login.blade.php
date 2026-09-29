<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0deg) } 50% { transform: translateY(-14px) rotate(6deg) } }
        @keyframes sheen { from { transform: translateX(-120%) } to { transform: translateX(220%) } }
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
<body class="flex min-h-screen flex-col bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <header class="flex h-16 shrink-0 items-center justify-between bg-white px-6 shadow-sm shadow-blue-100/60 sm:px-14">
        <div class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-xl bg-[#3563ff] text-lg font-semibold text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:rotate-6 hover:scale-110">E</span>
            <span class="text-sm font-semibold tracking-wide">EVENT PORTAL</span>
        </div>
        <span class="text-[11px] font-medium tracking-widest text-slate-500">ADMIN ACCESS</span>
    </header>

    <main class="grid flex-1 lg:grid-cols-2">
        <aside class="relative hidden overflow-hidden bg-gradient-to-r from-[#4468e8] via-[#3d68fa] to-[#6a94ff] p-16 text-white lg:flex lg:flex-col">
            @foreach ($clusters as $position)
                <div class="pointer-events-none absolute size-28 {{ $position }}">
                    @foreach ($circles as $circle)
                        <span class="absolute h-16 w-14 rounded-full border border-white/50 {{ $circle }}"></span>
                    @endforeach
                    <span class="absolute left-[3.25rem] top-[3.25rem] size-4 rounded-full border border-white/60"></span>
                </div>
            @endforeach

            <div class="relative [animation:rise_.8s_ease-out_both]">
                <span class="relative grid size-14 place-items-center overflow-hidden rounded-2xl bg-white shadow-xl shadow-blue-900/20">
                    <img src="{{ asset($brand['logo']) }}" alt="{{ $brand['company'] }}" class="size-full object-contain p-2">
                    <span class="absolute inset-y-0 left-0 w-1/3 -skew-x-12 bg-white/60 [animation:sheen_3.5s_ease-in-out_infinite]"></span>
                </span>
                <p class="mt-4 text-xs font-medium tracking-wider text-blue-50">{{ $brand['company'] }}</p>
            </div>

            <div class="relative my-auto max-w-md [animation:rise_.8s_.15s_ease-out_both]">
                <h1 class="text-5xl font-semibold leading-tight tracking-tight">{{ $brand['title'] }}</h1>
                <p class="mt-6 text-base font-light leading-relaxed text-blue-50">{{ $brand['description'] }}</p>
                <hr class="mt-8 w-full max-w-sm border-white/40">
                <p class="mt-5 text-[11px] font-medium tracking-widest text-blue-100">SECURE ADMIN WORKSPACE</p>
            </div>
        </aside>

        <section class="flex flex-col items-center justify-between px-6 py-10">
            <div class="flex w-full max-w-md flex-1 flex-col justify-center [animation:rise_.8s_.2s_ease-out_both]">
                <p class="text-xs font-semibold tracking-widest text-[#3563ff]">ADMIN SIGN IN</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight">Sign in to your account</h2>
                <p class="mt-2 text-sm text-slate-500">Enter your credentials to continue.</p>

                <form method="POST" action="#" class="mt-8 space-y-5 rounded-3xl border border-slate-200/80 bg-white p-8 shadow-xl shadow-blue-100/60">
                    @csrf

                    <div class="flex flex-col gap-1.5">
                        <label for="username" class="text-xs font-medium">Username</label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            placeholder="Enter your username"
                            autocomplete="username"
                            class="h-[52px] w-full rounded-xl border border-slate-200 bg-slate-50/60 px-4 text-sm outline-none transition duration-300 placeholder:text-slate-400 focus:-translate-y-0.5 focus:border-[#3563ff] focus:bg-white focus:ring-4 focus:ring-blue-100"
                        >
                        @error('username')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="password" class="text-xs font-medium">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                class="h-[52px] w-full rounded-xl border border-slate-200 bg-slate-50/60 pl-4 pr-12 text-sm outline-none transition duration-300 placeholder:text-slate-400 focus:-translate-y-0.5 focus:border-[#3563ff] focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                            <button type="button" data-toggle-password aria-label="Show password" class="absolute inset-y-0 right-0 grid w-12 place-items-center text-slate-400 transition duration-300 hover:text-[#3563ff]">
                                <svg data-eye class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg data-eye-off class="hidden size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18M10.6 6.1A10 10 0 0 1 12 6c6.5 0 10 6 10 6a17 17 0 0 1-3.2 3.9M6.6 6.6A17 17 0 0 0 2 12s3.5 6 10 6a10 10 0 0 0 4-.8"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex cursor-pointer items-center gap-2 text-slate-500">
                            <input type="checkbox" name="remember" class="size-5 cursor-pointer rounded-md border-slate-300 accent-[#3563ff]">
                            Remember me
                        </label>
                        <a href="#" class="text-xs font-medium text-[#3563ff] transition duration-300 hover:text-[#2a52e6] hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="flex h-14 w-full items-center justify-center rounded-xl bg-[#3563ff] text-sm font-medium text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:-translate-y-0.5 hover:bg-[#2a52e6] hover:shadow-xl hover:shadow-blue-400/40 active:scale-[.98]">
                        Sign in
                    </button>
                </form>

                <p class="mt-5 text-center text-xs text-slate-400">Authorized personnel only</p>
            </div>

            <p class="mt-8 text-center text-xs text-slate-400">&copy; {{ date('Y') }} Seatrium. All rights reserved.</p>
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