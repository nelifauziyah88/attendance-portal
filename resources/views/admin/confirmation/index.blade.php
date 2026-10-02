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
        'attending' => ['label' => 'Attend', 'class' => 'bg-emerald-50 text-emerald-700'],
        'declined' => ['label' => 'Not attend', 'class' => 'bg-orange-50 text-red-600'],
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
                                    @php($status = $statuses[$employee['status'] ?? ''] ?? ['label' => 'Unknown', 'class' => 'bg-slate-100 text-slate-600'])
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

            function escapeXml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function columnName(index) {
                let name = '';

                while (index >= 0) {
                    name = String.fromCharCode(65 + (index % 26)) + name;
                    index = Math.floor(index / 26) - 1;
                }

                return name;
            }

            function columnWidth(value) {
                return Math.min(Math.max(String(value).length + 3, 10), 45);
            }

            function buildSheetXml(rows) {
                const sheetRows = [headers, ...rows.map((row, index) => [
                    String(index + 1).padStart(2, '0'),
                    ...row.slice(1),
                ])];

                const columns = headers.map((_, columnIndex) => {
                    const width = sheetRows.reduce((maxWidth, row) => {
                        return Math.max(maxWidth, columnWidth(row[columnIndex] ?? ''));
                    }, 10);

                    return `<col min="${columnIndex + 1}" max="${columnIndex + 1}" width="${width}" customWidth="1"/>`;
                }).join('');

                const body = sheetRows.map((row, rowIndex) => {
                    const cells = row.map((cell, cellIndex) => {
                        const reference = `${columnName(cellIndex)}${rowIndex + 1}`;
                        const style = rowIndex === 0 ? ' s="2"' : ' s="1"';

                        return `<c r="${reference}" t="inlineStr"${style}><is><t>${escapeXml(cell)}</t></is></c>`;
                    }).join('');

                    return `<row r="${rowIndex + 1}">${cells}</row>`;
                }).join('');

                return `<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><cols>${columns}</cols><sheetData>${body}</sheetData></worksheet>`;
            }

            function buildWorkbook(rows) {
                return createZip({
                    '[Content_Types].xml': '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>',
                    '_rels/.rels': '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
                    'xl/workbook.xml': '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Confirmation Attendance" sheetId="1" r:id="rId1"/></sheets></workbook>',
                    'xl/_rels/workbook.xml.rels': '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
                    'xl/styles.xml': '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF7A2CC0"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border><border><left style="thin"><color rgb="FFB7B7B7"/></left><right style="thin"><color rgb="FFB7B7B7"/></right><top style="thin"><color rgb="FFB7B7B7"/></top><bottom style="thin"><color rgb="FFB7B7B7"/></bottom><diagonal/></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="3"><xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="49" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"/><xf numFmtId="49" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/></cellXfs></styleSheet>',
                    'xl/worksheets/sheet1.xml': buildSheetXml(rows),
                });
            }

            function crc32(bytes) {
                const table = crc32.table ??= Array.from({
                    length: 256
                }, (_, index) => {
                    let value = index;

                    for (let bit = 0; bit < 8; bit++) {
                        value = value & 1 ? 0xedb88320 ^ (value >>> 1) : value >>> 1;
                    }

                    return value >>> 0;
                });
                let crc = 0xffffffff;

                for (const byte of bytes) {
                    crc = table[(crc ^ byte) & 0xff] ^ (crc >>> 8);
                }

                return (crc ^ 0xffffffff) >>> 0;
            }

            function bytesFromString(value) {
                return new TextEncoder().encode(value);
            }

            function uint16(value) {
                return [value & 0xff, (value >>> 8) & 0xff];
            }

            function uint32(value) {
                return [value & 0xff, (value >>> 8) & 0xff, (value >>> 16) & 0xff, (value >>> 24) & 0xff];
            }

            function createZip(files) {
                const parts = [];
                const centralDirectory = [];
                let offset = 0;

                for (const [name, content] of Object.entries(files)) {
                    const nameBytes = bytesFromString(name);
                    const data = bytesFromString(content);
                    const checksum = crc32(data);
                    const localHeader = new Uint8Array([
                        ...uint32(0x04034b50), ...uint16(20), ...uint16(0), ...uint16(0),
                        ...uint16(0), ...uint16(0), ...uint32(checksum),
                        ...uint32(data.length), ...uint32(data.length),
                        ...uint16(nameBytes.length), ...uint16(0),
                    ]);
                    const centralHeader = new Uint8Array([
                        ...uint32(0x02014b50), ...uint16(20), ...uint16(20), ...uint16(0),
                        ...uint16(0), ...uint16(0), ...uint16(0), ...uint32(checksum),
                        ...uint32(data.length), ...uint32(data.length),
                        ...uint16(nameBytes.length), ...uint16(0), ...uint16(0),
                        ...uint16(0), ...uint16(0), ...uint32(0), ...uint32(offset),
                    ]);

                    parts.push(localHeader, nameBytes, data);
                    centralDirectory.push(centralHeader, nameBytes);
                    offset += localHeader.length + nameBytes.length + data.length;
                }

                const centralSize = centralDirectory.reduce((size, part) => size + part.length, 0);
                const endRecord = new Uint8Array([
                    ...uint32(0x06054b50), ...uint16(0), ...uint16(0),
                    ...uint16(Object.keys(files).length), ...uint16(Object.keys(files).length),
                    ...uint32(centralSize), ...uint32(offset), ...uint16(0),
                ]);

                return new Blob([...parts, ...centralDirectory, endRecord], {
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
                    downloadFile(buildWorkbook(rows), `confirmation-attendance-${date}.xlsx`);
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
