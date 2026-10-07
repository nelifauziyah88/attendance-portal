<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance List</title>
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
    $columns = ['NO.', 'BADGE ID', 'NAME', 'POSITION', 'DEPARTMENT', 'CHECK-IN'];
@endphp

<body
    class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased font-['Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="attendance-list" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="min-w-0 [animation:rise_.7s_ease-out_both]">
                    <p class="text-xs font-medium text-[#3563ff]">{{ $event['name'] }}</p>
                    <h1 class="mt-2 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">
                        Attendance List</h1>
                </div>

                <section
                    class="mt-6 min-w-0 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-lg shadow-blue-100/50 sm:mt-8 sm:p-6 [animation:rise_.7s_.1s_ease-out_both]">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <select id="attendance-department-filter" name="department"
                            aria-label="Filter by department"
                            class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 sm:w-52">
                            <option value="">All departments</option>
                            @foreach ($departments ?? [] as $department)
                                <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}
                                </option>
                            @endforeach
                        </select>
                        <form id="attendance-search-form" method="GET" action="{{ url()->current() }}"
                            class="w-full sm:max-w-sm">
                            <input id="attendance-search-input" type="search" name="search" value="{{ $search }}"
                                placeholder="Search badge ID or employee..." aria-label="Search badge ID or employee"
                                autocomplete="off"
                                class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                            <button type="submit" class="sr-only">Search</button>
                        </form>

                        <button type="button" id="attendance-export"
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
                        <table id="attendance-table"
                            class="w-full min-w-[820px] border-separate border-spacing-0 text-left text-sm">
                            <thead>
                                <tr class="bg-violet-50/60 text-[11px] font-semibold tracking-widest text-slate-500">
                                    @foreach ($columns as $column)
                                        <th @class([
                                            'whitespace-nowrap px-3 py-4 font-semibold sm:px-5 sm:py-5',
                                            'rounded-l-xl' => $loop->first,
                                            'rounded-r-xl' => $loop->last,
                                        ])>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($attendances as $attendance)
                                    <tr class="transition duration-300 even:bg-slate-50/60 hover:bg-violet-50/60 animate-[rise_.6s_ease-out_both]"
                                        style="animation-delay: {{ 0.2 + $loop->index * 0.06 }}s">
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ sprintf('%02d', $loop->iteration) }}</td>
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $attendance['badge'] }}</td>
                                        <td
                                            class="border-b border-slate-100 px-3 py-4 font-semibold text-[#26346b] sm:px-5 sm:py-8">
                                            {{ $attendance['name'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $attendance['position'] }}</td>
                                        <td class="border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $attendance['department'] }}</td>
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ $attendance['checkin'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}"
                                            class="px-5 py-12 text-center text-sm text-slate-500">No attendance records
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div id="attendance-pagination" class="mt-5">{{ $attendances->links() }}</div>
                </section>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('attendance-search-form');
            const input = document.getElementById('attendance-search-input');
            const departmentFilter = document.getElementById('attendance-department-filter');
            const pagination = document.getElementById('attendance-pagination');
            const table = document.getElementById('attendance-table');
            const exportButton = document.getElementById('attendance-export');
            let debounceTimer;
            let activeRequest;

            async function loadResults(url, historyMode = 'replace') {
                activeRequest?.abort();
                activeRequest = new AbortController();
                table.setAttribute('aria-busy', 'true');

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'text/html',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: activeRequest.signal,
                    });

                    if (!response.ok) throw new Error('Unable to load attendance results.');

                    const page = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const nextBody = page.querySelector('#attendance-table tbody');
                    const nextPagination = page.getElementById('attendance-pagination');

                    if (!nextBody) throw new Error('Attendance table was not returned.');

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

                if (query) url.searchParams.set('search', query);
                else url.searchParams.delete('search');

                const department = departmentFilter.value;
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

            departmentFilter.addEventListener('change', search);

            pagination.addEventListener('click', event => {
                const link = event.target.closest('a[href]');
                if (!link) return;

                event.preventDefault();
                loadResults(link.href, 'push');
            });

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
                    const pageRows = [...doc.querySelectorAll('#attendance-table tbody tr')]
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

            function columnWidth(value) {
                return Math.min(Math.max(String(value).length + 3, 10), 45);
            }

            async function buildWorkbook(rows) {
                if (!window.ExcelJS) {
                    throw new Error('ExcelJS is not loaded.');
                }

                const workbook = new window.ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Attendance List');
                const sheetRows = [headers, ...rows.map((row, index) => [
                    String(index + 1).padStart(2, '0'),
                    ...row.slice(1),
                ])];

                worksheet.addRows(sheetRows);

                worksheet.columns = headers.map((_, columnIndex) => ({
                    width: sheetRows.reduce((maxWidth, row) => {
                        return Math.max(maxWidth, columnWidth(row[columnIndex] ?? ''));
                    }, 10),
                }));

                worksheet.getRow(1).eachCell((cell) => {
                    cell.font = {
                        bold: true,
                        color: {
                            argb: 'FFFFFFFF'
                        },
                    };
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: 'FF7A2CC0'
                        },
                    };
                });

                worksheet.eachRow((row) => {
                    row.eachCell((cell) => {
                        cell.numFmt = '@';
                        cell.border = {
                            top: {
                                style: 'thin',
                                color: {
                                    argb: 'FFB7B7B7'
                                },
                            },
                            left: {
                                style: 'thin',
                                color: {
                                    argb: 'FFB7B7B7'
                                },
                            },
                            bottom: {
                                style: 'thin',
                                color: {
                                    argb: 'FFB7B7B7'
                                },
                            },
                            right: {
                                style: 'thin',
                                color: {
                                    argb: 'FFB7B7B7'
                                },
                            },
                        };
                    });
                });

                const buffer = await workbook.xlsx.writeBuffer();

                return new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
            }

            function downloadFile(blob, filename) {
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
                exportButton.disabled = true;
                exportButton.classList.add('opacity-60', 'pointer-events-none');

                try {
                    const rows = await collectRows();

                    if (!rows.length) {
                        window.alert('No data to export.');
                        return;
                    }

                    const date = new Date().toISOString().slice(0, 10);
                    downloadFile(await buildWorkbook(rows), `attendance-list-${date}.xlsx`);
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
                departmentFilter.value = params.get('department') ?? '';
                loadResults(window.location.href, 'none');
            });
        });
    </script>
</body>

</html>
