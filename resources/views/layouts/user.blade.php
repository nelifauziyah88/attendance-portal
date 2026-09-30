<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Event Portal')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
        @keyframes drift { 0%, 100% { transform: translateY(-50%) scale(1) } 50% { transform: translateY(-54%) scale(1.06) } }
        @keyframes sheen { from { transform: translateX(-120%) } to { transform: translateX(220%) } }
        @keyframes scan { 0%, 100% { top: 6% } 50% { top: 94% } }
    </style>
</head>
<body class="flex min-h-screen flex-col bg-[#faf5ff] bg-fixed bg-[radial-gradient(circle_at_top_left,rgba(217,70,239,0.14),transparent_45%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,0.12),transparent_45%)] font-normal text-[#3b0764] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <header class="flex h-16 shrink-0 items-center justify-between border-b border-fuchsia-200/60 bg-white px-6 shadow-sm shadow-fuchsia-100/60 sm:px-16">
        <div class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-xl bg-gradient-to-br from-fuchsia-600 to-violet-700 text-lg font-semibold text-white shadow-lg shadow-fuchsia-500/30 ring-1 ring-cyan-300/40 transition duration-300 hover:rotate-6 hover:scale-110">E</span>
            <span class="text-sm font-semibold tracking-wide">EVENT PORTAL</span>
        </div>
        <span class="hidden text-[11px] font-medium tracking-widest text-slate-500 sm:block">@yield('badge', 'EMPLOYEE INVITATION')</span>
    </header>

    <main class="flex flex-1 flex-col">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>