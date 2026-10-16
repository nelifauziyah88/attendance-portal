@props(['target', 'placeholder' => 'Search...'])

<div {{ $attributes->class(['relative w-full sm:max-w-xs']) }}>
    <svg class="pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
    <input
        type="search"
        data-table-search="{{ $target }}"
        placeholder="{{ $placeholder }}"
        aria-label="{{ $placeholder }}"
        autocomplete="off"
        class="h-11 w-full rounded-xl border border-violet-200 bg-violet-50/60 pl-11 pr-4 text-base outline-none transition duration-300 placeholder:text-slate-400 focus:border-fuchsia-600 focus:bg-white focus:ring-4 focus:ring-fuchsia-100 sm:text-sm"
        >
</div>

@once
    <script>
        const ensureEmptyRow = (table) => {
            let row = table.querySelector('[data-search-empty]');

            if (!row) {
                const cell = document.createElement('td');
                cell.colSpan = table.querySelectorAll('thead th').length;
                cell.className = 'px-5 py-10 text-center text-sm text-slate-500';
                cell.textContent = 'No results found.';

                row = document.createElement('tr');
                row.setAttribute('data-search-empty', '');
                row.className = 'hidden';
                row.appendChild(cell);

                table.tBodies[0].appendChild(row);
            }

            return row;
        };

        document.addEventListener('input', (event) => {
            const input = event.target.closest?.('[data-table-search]');
            if (!input) return;

            const table = document.getElementById(input.dataset.tableSearch);
            if (!table) return;

            const terms = input.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
            let visible = 0;

            table.tBodies[0].querySelectorAll('tr:not([data-search-empty])').forEach((row) => {
                const text = row.textContent.toLowerCase();
                const match = terms.every((term) => text.includes(term));

                row.classList.toggle('hidden', !match);
                if (match) visible += 1;
            });

            ensureEmptyRow(table).classList.toggle('hidden', visible > 0);
        });
    </script>
@endonce