<x-layouts.app title="Admin Bookings | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-10 lg:px-10">
        @include('admin.partials.overview-cards', ['stats' => $stats])
        @include('admin.partials.booking-list', [
            'bookings' => $bookings,
            'monthlyReport' => $monthlyReport,
            'reportMonth' => $reportMonth,
            'reportMonthOptions' => $reportMonthOptions,
        ])
    </main>

    <script>
        setTimeout(function () {
            window.location.reload();
        }, 30000);
    </script>
</x-layouts.app>
