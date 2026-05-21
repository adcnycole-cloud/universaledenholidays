<x-layouts.app title="Admin Bookings | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        @include('admin.partials.booking-list', [
            'bookings' => $bookings,
            'bookingReport' => $bookingReport,
            'reportType' => $reportType,
            'reportPeriodValue' => $reportPeriodValue,
            'reportPeriodOptions' => $reportPeriodOptions,
        ])
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('admin-booking-search');
            const prevButton = document.getElementById('admin-booking-prev');
            const nextButton = document.getElementById('admin-booking-next');
            const resultsLabel = document.getElementById('admin-booking-results');
            const table = document.getElementById('admin-booking-table');

            if (!searchInput || !prevButton || !nextButton || !resultsLabel || !table) {
                return;
            }

            const items = Array.from(table.querySelectorAll('[data-admin-booking-item]'));
            const pageSize = 8;
            let activePage = 0;
            let filteredItems = items;

            const updateResults = () => {
                const total = filteredItems.length;

                if (!total) {
                    resultsLabel.textContent = 'No booking entries match your search.';
                    return;
                }

                const start = activePage * pageSize + 1;
                const end = Math.min(total, start + pageSize - 1);
                resultsLabel.textContent = `Showing ${start}-${end} of ${total} booking entries`;
            };

            const renderPage = () => {
                const pageCount = Math.max(1, Math.ceil(filteredItems.length / pageSize));
                activePage = Math.min(activePage, pageCount - 1);

                items.forEach((item) => {
                    item.style.display = filteredItems.includes(item) ? 'table-row' : 'none';
                });

                filteredItems.forEach((item, index) => {
                    const pageIndex = Math.floor(index / pageSize);
                    item.style.display = pageIndex === activePage ? 'table-row' : 'none';
                });

                prevButton.disabled = activePage === 0;
                nextButton.disabled = activePage >= pageCount - 1 || filteredItems.length === 0;
                prevButton.classList.toggle('opacity-50', prevButton.disabled);
                nextButton.classList.toggle('opacity-50', nextButton.disabled);
                prevButton.classList.toggle('cursor-not-allowed', prevButton.disabled);
                nextButton.classList.toggle('cursor-not-allowed', nextButton.disabled);

                updateResults();
            };

            const applySearch = () => {
                const query = searchInput.value.trim().toLowerCase();
                filteredItems = items.filter((item) => item.textContent.toLowerCase().includes(query));
                activePage = 0;
                renderPage();
            };

            prevButton.addEventListener('click', () => {
                if (activePage === 0) {
                    return;
                }

                activePage -= 1;
                renderPage();
            });

            nextButton.addEventListener('click', () => {
                const pageCount = Math.max(1, Math.ceil(filteredItems.length / pageSize));

                if (activePage >= pageCount - 1) {
                    return;
                }

                activePage += 1;
                renderPage();
            });

            searchInput.addEventListener('input', applySearch);
            renderPage();
        });
    </script>
</x-layouts.app>
