<x-layouts.app title="Admin Bookings | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        @include('admin.partials.booking-list', [
            'bookings' => $bookings,
            'bookingSearchSuggestions' => $bookingSearchSuggestions,
            'bookingReport' => $bookingReport,
            'reportType' => $reportType,
            'reportPeriodValue' => $reportPeriodValue,
            'reportPeriodOptions' => $reportPeriodOptions,
        ])
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('admin-booking-search');
            const searchForm = document.getElementById('admin-booking-search-form');

            if (!searchInput || !searchForm) {
                return;
            }

            let timeoutId = null;

            searchInput.addEventListener('input', () => {
                if (timeoutId) {
                    window.clearTimeout(timeoutId);
                }

                timeoutId = window.setTimeout(() => {
                    searchForm.submit();
                }, 300);
            });
        });
    </script>
</x-layouts.app>
