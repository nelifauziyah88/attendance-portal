<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance List</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
    </style>
</head>
@php
    $event = ['name' => ''];
    $columns = ['NO.', 'BADGE ID', 'NAME', 'POSITION', 'DEPARTMENT', 'CHECK-IN'];
@endphp
<body class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased font-['Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="attendance-list" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="min-w-0 [animation:rise_.7s_ease-out_both]">
                    <p class="text-xs font-medium text-[#3563ff]">{{ $event['name'] }}</p>
                    <h1 class="mt-2 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">Attendance List</h1>
                </div>

                <section class="mt-6 min-w-0 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-lg shadow-blue-100/50 sm:mt-8 sm:p-6 [animation:rise_.7s_.1s_ease-out_both]">
                    <x-admin.table_search target="attendance-table" placeholder="Search attendance..." class="mb-4 sm:ml-auto" />

                    <div class="-mx-1 overflow-x-auto overscroll-x-contain px-1">
                        <table id="attendance-table" class="w-full min-w-[820px] border-separate border-spacing-0 text-left text-sm">
                            <thead>
                                <tr class="bg-blue-50/60 text-[11px] font-semibold tracking-widest text-slate-500">
                                    @foreach ($columns as $column)
                                        <th @class(['whitespace-nowrap px-3 py-4 font-semibold sm:px-5 sm:py-5', 'rounded-l-xl' => $loop->first, 'rounded-r-xl' => $loop->last])>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($attendances as $attendance)
                                    <tr class="transition duration-300 even:bg-slate-50/60 hover:bg-blue-50/60 animate-[rise_.6s_ease-out_both]" style="animation-delay: {{ 0.2 + $loop->index * 0.06 }}s">
                                        <td class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ sprintf('%02d', $loop->iteration) }}</td>
                                        <td class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $attendance['badge'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 font-semibold text-[#26346b] sm:px-5 sm:py-8">{{ $attendance['name'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $attendance['position'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $attendance['department'] }}</td>
                                        <td class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $attendance['checkin'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}" class="px-5 py-12 text-center text-sm text-slate-500">No attendance records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5">{{ $attendances->links() }}</div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>