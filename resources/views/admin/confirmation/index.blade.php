<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation Attendance</title>
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

@endphp

<body
    class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="confirmation-attendance" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="min-w-0 [animation:rise_.7s_ease-out_both]">
                    <p class="text-xs font-medium text-[#3563ff]">{{ $event['name'] }}</p>
                    <h1 class="mt-2 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">
                        Confirmation Attendance</h1>
                </div>

                <section
                    class="mt-6 min-w-0 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-lg shadow-blue-100/50 sm:mt-8 sm:p-6 [animation:rise_.7s_.1s_ease-out_both]">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <form id="confirmation-search-form" method="GET" action="{{ url()->current() }}"
                            class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                            <select id="confirmation-status-filter" name="status" aria-label="Filter by attendance"
                                class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 sm:w-48">
                                <option value="">All status</option>
                                <option value="attending" @selected(request('status') === 'attending')>Attend</option>
                                <option value="declined" @selected(request('status') === 'declined')>Not Attend</option>
                            </select>
                            <select id="confirmation-department-filter" name="department"
                                aria-label="Filter by department"
                                class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 sm:w-52">
                                <option value="">All departments</option>
                                @foreach ($departments ?? [] as $department)
                                    <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}
                                    </option>
                                @endforeach
                            </select>
                            <input id="confirmation-search-input" type="search" name="search"
                                value="{{ $search }}" placeholder="Search badge ID or employee..."
                                aria-label="Search badge ID or employee" autocomplete="off"
                                class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 sm:w-72">
                            <button type="submit" class="sr-only">Search</button>
                        </form>

                        <button type="button" id="confirmation-export"
                            class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-[#217346] px-5 text-sm font-medium text-white shadow-lg shadow-emerald-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-[#1a5c38] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-emerald-100 active:scale-[.98]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">
                                <path d="M12 3v12" />
                                <path d="m7 10 5 5 5-5" />
                                <path d="M5 21h14" />
                            </svg>
                            Export Excel
                        </button>
                    </div>

                    <div class="-mx-1 overflow-x-auto overscroll-x-contain px-1">
                        <table id="confirmation-table"
                            class="w-full min-w-[820px] border-separate border-spacing-0 text-left text-sm">
                            <thead>
                                <tr class="bg-violet-50/60 text-[11px] font-semibold tracking-widest text-slate-500">
                                    @foreach ($columns as $column)
                                        <th @class([
                                            'whitespace-nowrap px-3 py-4 font-semibold sm:px-5 sm:py-5',
                                            'rounded-l-xl' => $loop->first,
                                            'rounded-r-xl text-center' => $loop->last,
                                        ])>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($employees as $employee)
                                    @php($status = $statuses[$employee['status']])
                                    <tr class="transition duration-300 even:bg-slate-50/60 hover:bg-violet-50/60 [animation:rise_.6s_ease-out_both]"
                                        style="animation-delay: {{ 0.2 + $loop->index * 0.06 }}s">
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ sprintf('%02d', $loop->iteration) }}</td>
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $employee['badge'] }}</td>
                                        <td
                                            class="border-b border-slate-100 px-3 py-4 font-semibold text-[#26346b] sm:px-5 sm:py-8">
                                            {{ $employee['name'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $employee['position'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $employee['department'] }}</td>
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-center sm:px-5 sm:py-8">
                                            <span
                                                class="inline-flex items-center whitespace-nowrap rounded-full px-4 py-2 text-xs font-medium transition duration-300 hover:scale-105 {{ $status['class'] }}">{{ $status['label'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}"
                                            class="px-5 py-12 text-center text-sm text-slate-500">No RSVP records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div id="confirmation-pagination" class="mt-5">{{ $employees->links() }}</div>
                </section>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('confirmation-search-form');
            const input = document.getElementById('confirmation-search-input');
            const pagination = document.getElementById('confirmation-pagination');
            const table = document.getElementById('confirmation-table');
            const statusFilter = document.getElementById('confirmation-status-filter');
            const departmentFilter = document.getElementById('confirmation-department-filter');
            const exportButton = document.getElementById('confirmation-export');
            let debounceTimer;
            let activeRequest;

            async function loadResults(url, historyMode = 'replace') {
                activeRequest?.abort();
                activeRequest = new AbortController();
                table.setAttribute('aria-busy', 'true');

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: activeRequest.signal,
                    });

                    if (!response.ok) throw new Error('Unable to load confirmation results.');

                    const page = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const nextBody = page.querySelector('#confirmation-table tbody');
                    const nextPagination = page.getElementById('confirmation-pagination');

                    if (!nextBody) throw new Error('Confirmation table was not returned.');

                    table.tBodies[0].replaceWith(nextBody);
                    pagination.innerHTML = nextPagination?.innerHTML ?? '';

                    if (historyMode === 'push') history.pushState({}, '', url);
                    else if (historyMode === 'replace') history.replaceState({}, '', url);
                } catch (error) {
                    if (error.name !== 'AbortError') console.error(error);
                } finally {
                    table.removeAttribute('aria-busy');
                }
            }

            function syncFilterParams(url) {
                const query = input.value.trim();
                const status = statusFilter.value;
                const department = departmentFilter.value;

                if (query) url.searchParams.set('search', query);
                else url.searchParams.delete('search');

                if (status) url.searchParams.set('status', status);
                else url.searchParams.delete('status');

                if (department) url.searchParams.set('department', department);
                else url.searchParams.delete('department');
            }

            function search() {
                const url = new URL(window.location.href);
                url.searchParams.delete('page');

                syncFilterParams(url);

                loadResults(url.toString());
            }

            form.addEventListener('submit', event => {
                event.preventDefault();
                search();
            });

            input.addEventListener('input', () => {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(search, 300);
            });

            pagination.addEventListener('click', event => {
                const link = event.target.closest('a[href]');
                if (!link) return;

                event.preventDefault();
                loadResults(link.href, 'push');
            });

            statusFilter.addEventListener('change', search);

            departmentFilter.addEventListener('change', search);

            const EXPORT_MAX_PAGES = 500;
            const headers = [...table.tHead.rows[0].cells].map((cell) => cell.textContent.trim());

            function buildExportUrl(page) {
                const url = new URL(window.location.href);
                url.search = '';

                syncFilterParams(url);
                url.searchParams.set('page', page);

                return url.toString();
            }

            async function collectRows() {
                const rows = [];
                let previousKey = '';

                for (let page = 1; page <= EXPORT_MAX_PAGES; page++) {
                    const response = await fetch(buildExportUrl(page), {
                        headers: {
                            'Accept': 'text/html',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    });

                    if (!response.ok) throw new Error('Unable to load data for export.');

                    const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const pageRows = [...doc.querySelectorAll('#confirmation-table tbody tr')]
                        .filter((row) => row.cells.length === headers.length)
                        .map((row) => [...row.cells].map((cell) => cell.textContent.trim().replace(/\s+/g,
                            ' ')));

                    if (!pageRows.length) break;

                    const key = pageRows[0].join('|');
                    if (key === previousKey) break;
                    previousKey = key;

                    rows.push(...pageRows);
                }

                return rows;
            }

            function escapeHtml(value) {
                return value
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function buildWorkbook(rows) {
                const head = headers
                    .map((label) => `<th style="background:#ede9fe;font-weight:bold">${escapeHtml(label)}</th>`)
                    .join('');

                const body = rows
                    .map((row, index) => {
                        const cells = [String(index + 1).padStart(2, '0'), ...row.slice(1)];
                        const html = cells
                            .map((cell) => `<td style="mso-number-format:'\\@'">${escapeHtml(cell)}</td>`)
                            .join('');

                        return `<tr>${html}</tr>`;
                    })
                    .join('');

                return `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="UTF-8"></head><body><table border="1"><thead><tr>${head}</tr></thead><tbody>${body}</tbody></table></body></html>`;
            }

            function downloadFile(content, filename) {
                const blob = new Blob(['\ufeff', content], {
                    type: 'application/vnd.ms-excel;charset=utf-8'
                });
                const href = URL.createObjectURL(blob);
                const anchor = document.createElement('a');

                anchor.href = href;
                anchor.download = filename;
                document.body.appendChild(anchor);
                anchor.click();
                anchor.remove();

                window.setTimeout(() => URL.revokeObjectURL(href), 1000);
            }

            exportButton.addEventListener('click', async () => {
                console.log('export clicked');
                exportButton.disabled = true;
                exportButton.classList.add('opacity-60', 'pointer-events-none');

                try {
                    const rows = await collectRows();

                    if (!rows.length) {
                        window.alert('No data to export.');
                        return;
                    }

                    const date = new Date().toISOString().slice(0, 10);
                    downloadFile(buildWorkbook(rows), `confirmation-attendance-${date}.xls`);
                } catch (error) {
                    console.error(error);
                    window.alert(`Unable to export the data: ${error.message}`);
                } finally {
                    exportButton.disabled = false;
                    exportButton.classList.remove('opacity-60', 'pointer-events-none');
                }
            });

            window.addEventListener('popstate', () => {
                const params = new URLSearchParams(window.location.search);
                input.value = params.get('search') ?? '';
                statusFilter.value = params.get('status') ?? '';
                departmentFilter.value = params.get('department') ?? '';
                loadResults(window.location.href, 'none');
            });
        });
    </script>
</body>

</html>
