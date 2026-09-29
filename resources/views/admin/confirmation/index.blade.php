<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation Attendance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes rise { from { opacity: 0; transform: translateY(18px) } to { opacity: 1; transform: translateY(0) } }
    </style>
</head>
@php
    $event = ['name' => ''];

    $columns = ['NO.', 'BADGE ID', 'NAME', 'POSITION', 'DEPARTMENT', 'STATUS'];

    $statuses = [
        'attending' => ['label' => 'Attending', 'class' => 'bg-emerald-50 text-emerald-700'],
        'pending' => ['label' => 'Pending', 'class' => 'bg-slate-100 text-slate-500'],
        'declined' => ['label' => 'Not attending', 'class' => 'bg-orange-50 text-red-600'],
    ];

    $employees = $employees ?? [
        ['badge' => 'BDG-1001', 'name' => 'Neli Fauziyah', 'position' => 'IT Intern', 'department' => 'IT', 'status' => 'attending'],
        ['badge' => 'BDG-1002', 'name' => 'Alya Putri', 'position' => 'Project Engineer', 'department' => 'Engineering', 'status' => 'attending'],
        ['badge' => 'BDG-1003', 'name' => 'Rizky Pratama', 'position' => 'QA/QC Inspector', 'department' => 'Quality Control', 'status' => 'pending'],
        ['badge' => 'BDG-1004', 'name' => 'Sarah Amelia', 'position' => 'HR Officer', 'department' => 'Human Resources', 'status' => 'declined'],
        ['badge' => 'BDG-1005', 'name' => 'Dimas Saputra', 'position' => 'System Analyst', 'department' => 'IT', 'status' => 'attending'],
        ['badge' => 'BDG-1006', 'name' => 'Kevin Wijaya', 'position' => 'Supervisor', 'department' => 'Operations', 'status' => 'pending'],
        ['badge' => 'BDG-1007', 'name' => 'Putri Maharani', 'position' => 'Admin Staff', 'department' => 'Administration', 'status' => 'attending'],
    ];
@endphp
<body class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="confirmation-attendance" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="[animation:rise_.7s_ease-out_both]">
                    <p class="text-xs font-medium text-[#3563ff]">{{ $event['name'] }}</p>
                    <h1 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Confirmation Attendance</h1>
                </div>

                <section class="mt-8 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-lg shadow-blue-100/50 sm:p-6 [animation:rise_.7s_.1s_ease-out_both]">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[820px] border-separate border-spacing-0 text-left text-sm">
                            <thead>
                                <tr class="bg-blue-50/60 text-[11px] font-semibold tracking-widest text-slate-500">
                                    @foreach ($columns as $column)
                                        <th @class(['px-5 py-5 font-semibold', 'rounded-l-xl' => $loop->first, 'rounded-r-xl text-center' => $loop->last])>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($employees as $employee)
                                    @php($status = $statuses[$employee['status']])
                                    <tr class="transition duration-300 even:bg-slate-50/60 hover:bg-blue-50/60 [animation:rise_.6s_ease-out_both]" style="animation-delay: {{ 0.2 + $loop->index * 0.06 }}s">
                                        <td class="border-b border-slate-100 px-5 py-8 text-slate-500">{{ sprintf('%02d', $loop->iteration) }}</td>
                                        <td class="border-b border-slate-100 px-5 py-8 text-slate-500">{{ $employee['badge'] }}</td>
                                        <td class="border-b border-slate-100 px-5 py-8 font-semibold text-[#26346b]">{{ $employee['name'] }}</td>
                                        <td class="border-b border-slate-100 px-5 py-8 text-slate-500">{{ $employee['position'] }}</td>
                                        <td class="border-b border-slate-100 px-5 py-8 text-slate-500">{{ $employee['department'] }}</td>
                                        <td class="border-b border-slate-100 px-5 py-8 text-center">
                                            <span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-medium transition duration-300 hover:scale-105 {{ $status['class'] }}">{{ $status['label'] }}</span>
                                        </td>
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