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

    $columns = ['NO.', 'BADGE ID', 'NAME', 'POSITION', 'DEPARTMENT'];

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
                    <form id="employee-search-form" method="GET" action="{{ url()->current() }}"
                        class="mb-4 w-full sm:ml-auto sm:max-w-sm">
                        <input id="employee-search-input" type="search" name="search" value="{{ $search }}"
                            placeholder="Search badge ID or employee..." aria-label="Search badge ID or employee"
                            autocomplete="off"
                            class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 px-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100">
                        <button type="submit" class="sr-only">Search</button>
                    </form>

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
                                        ])>{{ $column }}</th>
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
        const pagination = document.getElementById('employee-pagination');
        const table = document.getElementById('employee-table');
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

        function search() {
            const url = new URL(window.location.href);
            const query = input.value.trim();
            url.searchParams.delete('page');

            if (query) url.searchParams.set('search', query);
            else url.searchParams.delete('search');

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

        window.addEventListener('popstate', () => {
            input.value = new URLSearchParams(window.location.search).get('search') ?? '';
            loadResults(window.location.href, 'none');
        });
    });
</script>
</body>
</html>
