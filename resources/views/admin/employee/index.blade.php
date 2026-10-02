<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Information List</title>
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

    $columns = ['NO.', 'BADGE ID', 'NAME', 'POSITION', 'DEPARTMENT', 'ACTION'];

@endphp

<body
    class="bg-[#f5f8ff] font-normal text-[#26346b] antialiased [font-family:'Plus_Jakarta_Sans',ui-sans-serif,system-ui,sans-serif]">
    <div class="group/shell flex min-h-screen flex-col">
        <input type="checkbox" id="sidebar-toggle" class="sr-only">

        <x-admin.navbar />

        <div class="flex flex-1">
            <x-admin.sidebar active="information-list" />

            <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="min-w-0 [animation:rise_.7s_ease-out_both]">
                    <p class="text-xs font-medium text-[#3563ff]">{{ $event['name'] }}</p>
                    <h1 class="mt-2 break-words text-2xl font-semibold tracking-tight min-[400px]:text-3xl sm:text-4xl">
                        Information List</h1>
                </div>

                <section
                    class="mt-6 min-w-0 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-lg shadow-blue-100/50 sm:mt-8 sm:p-6 [animation:rise_.7s_.1s_ease-out_both]">
                    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <select id="confirmation-department-filter" name="department"
                            aria-label="Filter by department"
                            class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 sm:w-52">
                            <option value="">All departments</option>
                            @foreach ($departments ?? [] as $department)
                                <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}
                                </option>
                            @endforeach
                        </select>
                        <form id="employee-search-form" method="GET" action="{{ url()->current() }}"
                            class="w-full sm:max-w-sm">
                            <input id="employee-search-input" type="search" name="search" value="{{ $search }}"
                                placeholder="Search badge ID or employee..." aria-label="Search badge ID or employee"
                                autocomplete="off"
                                class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                            <button type="submit" class="sr-only">Search</button>
                        </form>

                        <button type="button" id="employee-export"
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
                        <table id="employee-table"
                            class="w-full min-w-[720px] border-separate border-spacing-0 text-left text-sm">
                            <thead>
                                <tr class="bg-violet-50/60 text-[11px] font-semibold tracking-widest text-slate-500">
                                    @foreach ($columns as $column)
                                        <th @class([
                                            'whitespace-nowrap px-3 py-4 font-semibold sm:px-5 sm:py-5',
                                            'rounded-l-xl' => $loop->first,
                                            'rounded-r-xl' => $loop->last,
                                        ])>
                                            @if ($column === 'ACTION')
                                                <span class="relative inline-flex items-center gap-1.5">
                                                    {{ $column }}
                                                    <button type="button" data-info-toggle aria-expanded="false"
                                                        aria-controls="action-info" aria-label="About the action column"
                                                        class="grid size-5 place-items-center rounded-full text-slate-400 transition duration-300 hover:text-[#582764] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-100 aria-expanded:text-[#582764]">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="size-4" aria-hidden="true">
                                                            <circle cx="12" cy="12" r="9" />
                                                            <path d="M12 11v5" />
                                                            <path d="M12 8h.01" />
                                                        </svg>
                                                    </button>
                                                    <div id="action-info" role="tooltip" hidden
                                                        class="absolute right-0 top-full z-20 mt-3 w-56 whitespace-normal rounded-2xl rounded-tr-sm bg-[#582764] px-4 py-3 text-left text-xs font-normal normal-case leading-relaxed tracking-normal text-white shadow-xl shadow-blue-200/60">
                                                        <span
                                                            class="absolute -top-1 right-2 size-3 rotate-45 bg-[#582764]"></span>
                                                        <p class="relative">Tindakan ini digunakan untuk menandai
                                                            karyawan jika mereka adalah seorang manager.</p>
                                                    </div>
                                                </span>
                                            @else
                                                {{ $column }}
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr class="transition duration-300 even:bg-slate-50/60 hover:bg-violet-50/60 [animation:rise_.6s_ease-out_both]"
                                        style="animation-delay: {{ 0.2 + $loop->index * 0.06 }}s">
                                        <td
                                            class="whitespace-nowrap border-b border-slate-100 px-3 py-4 text-slate-500 sm:px-5 sm:py-8">
                                            {{ sprintf('%02d', $employees->firstItem() + $loop->index) }}</td>
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
                                        <td class="border-b border-slate-100 px-3 py-4 sm:px-5 sm:py-8">
                                            <button type="button" data-manager data-badge="{{ $employee['badge'] }}"
                                                data-manager-url="{{ route('admin.employee.manager.update', ['badge' => $employee['badge']]) }}"
                                                data-employee-name="{{ $employee['name'] }}"
                                                data-manager-state="{{ $employee['is_manager'] ? '1' : '0' }}"
                                                data-csrf-token="{{ csrf_token() }}"
                                                aria-pressed="{{ $employee['is_manager'] ? 'true' : 'false' }}"
                                                aria-label="{{ $employee['is_manager'] ? 'Remove manager status from' : 'Mark as manager' }} {{ $employee['name'] }}"
                                                title="{{ $employee['is_manager'] ? 'Remove manager status' : 'Mark as manager' }}"
                                                class="grid size-9 place-items-center rounded-lg border-2 border-slate-200 bg-white text-transparent transition duration-300 hover:border-[#3563ff] hover:text-slate-300 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-100 active:scale-95 aria-pressed:border-emerald-500 aria-pressed:bg-emerald-500 aria-pressed:text-white">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                    class="size-5" aria-hidden="true">
                                                    <path d="M5 12.5l4.5 4.5L19 7.5" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}"
                                            class="px-5 py-12 text-center text-sm text-slate-500">No employees found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div id="employee-pagination" class="mt-5">{{ $employees->links() }}</div>
                </section>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employee-search-form');
            const input = document.getElementById('employee-search-input');
            const departmentFilter = document.getElementById('confirmation-department-filter');
            const pagination = document.getElementById('employee-pagination');
            const table = document.getElementById('employee-table');
            const exportButton = document.getElementById('employee-export');
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

                    if (!response.ok) throw new Error('Unable to load employee results.');

                    const page = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const nextBody = page.querySelector('#employee-table tbody');
                    const nextPagination = page.getElementById('employee-pagination');

                    if (!nextBody) throw new Error('Employee table was not returned.');

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

            table.addEventListener('click', async event => {
                const button = event.target.closest('[data-manager]');
                if (!button || button.disabled) return;

                const isManager = button.dataset.managerState !== '1';
                const employeeName = button.dataset.employeeName;
                const confirmation = await window.Swal.fire({
                    icon: 'question',
                    title: isManager ? 'Mark as manager?' : 'Remove manager status?',
                    text: isManager ?
                        `Are you sure you want to mark ${employeeName} as a manager?` :
                        `Are you sure you want to remove manager status from ${employeeName}?`,
                    showCancelButton: true,
                    confirmButtonText: isManager ? 'Yes, mark as manager' :
                        'Yes, remove status',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3563ff',
                    cancelButtonColor: '#64748b',
                });

                if (!confirmation.isConfirmed) return;

                button.disabled = true;

                try {
                    const response = await fetch(button.dataset.managerUrl, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': button.dataset.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            is_manager: isManager
                        }),
                    });
                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Unable to update manager status.');
                    }

                    button.dataset.managerState = result.is_manager ? '1' : '0';
                    button.setAttribute('aria-pressed', result.is_manager ? 'true' : 'false');
                    button.title = result.is_manager ? 'Remove manager status' : 'Mark as manager';
                    button.setAttribute('aria-label', `${button.title} ${employeeName}`);
                } catch (error) {
                    await window.Swal.fire({
                        icon: 'error',
                        title: 'Update failed',
                        text: error.message,
                        confirmButtonColor: '#3563ff',
                    });
                } finally {
                    button.disabled = false;
                }
            });

            const infoToggle = document.querySelector('[data-info-toggle]');
            const infoBubble = document.getElementById('action-info');

            function setInfo(open) {
                infoBubble.hidden = !open;
                infoToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            infoToggle.addEventListener('click', event => {
                event.stopPropagation();
                setInfo(infoBubble.hidden);
            });

            document.addEventListener('click', event => {
                if (!infoBubble.contains(event.target)) setInfo(false);
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') setInfo(false);
            });

            const EXPORT_MAX_PAGES = 500;
            const headers = [...table.tHead.rows[0].cells]
                .slice(0, -1)
                .map((cell) => cell.textContent.trim());

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
                    const pageRows = [...doc.querySelectorAll('#employee-table tbody tr')]
                        .filter((row) => row.cells.length > headers.length)
                        .map((row) => [...row.cells]
                            .slice(0, headers.length)
                            .map((cell) => cell.textContent.trim().replace(/\s+/g, ' ')));

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
                const worksheet = workbook.addWorksheet('Information List');
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
                    downloadFile(await buildWorkbook(rows), `information-list-${date}.xlsx`);
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
