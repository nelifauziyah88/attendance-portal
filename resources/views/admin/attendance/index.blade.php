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

    $employees = $employees ?? [
        ['badge' => 'BDG-1001', 'name' => 'Neli Fauziyah', 'position' => 'IT Intern', 'department' => 'IT', 'checkin' => '6:47 PM'],
        ['badge' => 'BDG-1002', 'name' => 'Alya Putri', 'position' => 'Project Engineer', 'department' => 'Engineering', 'checkin' => '6:52 PM'],
        ['badge' => 'BDG-1005', 'name' => 'Dimas Saputra', 'position' => 'System Analyst', 'department' => 'IT', 'checkin' => '6:58 PM'],
        ['badge' => 'BDG-1007', 'name' => 'Putri Maharani', 'position' => 'Admin Staff', 'department' => 'Administration', 'checkin' => '7:02 PM'],
        ['badge' => 'BDG-1011', 'name' => 'Bima Kurniawan', 'position' => 'Supervisor', 'department' => 'Operations', 'checkin' => '7:04 PM'],
        ['badge' => 'BDG-1014', 'name' => 'Siti Rahma', 'position' => 'HR Officer', 'department' => 'Human Resources', 'checkin' => '7:09 PM'],
        ['badge' => 'BDG-1018', 'name' => 'Farhan Akbar', 'position' => 'QA/QC Inspector', 'department' => 'Quality Control', 'checkin' => '7:12 PM'],
    ];
@endphp
<body class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
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
                                @foreach ($employees as $employee)
                                    <tr class="transition duration-300 even:bg-slate-50/60 hover:bg-blue-50/60 [animation:rise_.6s_ease-out_both]" style="animation-delay: {{ 0.2 + $loop->index * 0.06 }}s">
                                        <td class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ sprintf('%02d', $loop->iteration) }}</td>
                                        <td class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $employee['badge'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 font-semibold text-[#26346b] sm:px-5 sm:py-8">{{ $employee['name'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $employee['position'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $employee['department'] }}</td>
                                        <td class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">{{ $employee['checkin'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>