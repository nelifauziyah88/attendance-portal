<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Event Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
        @keyframes drift { 0%, 100% { transform: translateY(-50%) scale(1) } 50% { transform: translateY(-54%) scale(1.06) } }
        @keyframes sheen { from { transform: translateX(-120%) } to { transform: translateX(220%) } }
    </style>
</head>
<body class="flex min-h-screen flex-col bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <header class="flex h-16 shrink-0 items-center justify-between bg-white px-6 shadow-sm shadow-blue-100/60 sm:px-16">
        <div class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-xl bg-[#3563ff] text-lg font-semibold text-white shadow-lg shadow-blue-400/30 transition duration-300 hover:rotate-6 hover:scale-110">E</span>
            <span class="text-sm font-semibold tracking-wide">EVENT PORTAL</span>
        </div>
        <span class="hidden text-[11px] font-medium tracking-widest text-slate-500 sm:block">EMPLOYEE INVITATION</span>
    </header>

    <main class="flex flex-1 flex-col">
        @yield('content')
    </main>
</body>
</html>