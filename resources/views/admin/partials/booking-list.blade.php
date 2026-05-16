<section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Bookings</p>
    <h1 class="mt-2 text-3xl font-semibold text-stone-900">Incoming booking requests</h1>
    <div class="mt-6 rounded-[1.75rem] border border-sky-100 bg-sky-50 p-5">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Monthly Report</p>
                <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $monthlyReport['month_label'] }}</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">Track monthly booking totals, revenue, and confirmations. Export the selected month directly to Excel.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                    <div>
                        <label for="report-month" class="mb-2 block text-sm font-medium text-stone-700">Report month</label>
                        <select id="report-month" name="month" class="rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                            <option value="{{ $reportMonth->format('Y-m') }}">{{ $reportMonth->format('F Y') }}</option>
                            @foreach ($reportMonthOptions as $option)
                                @if ($option['value'] !== $reportMonth->format('Y-m'))
                                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">Apply Month</button>
                </form>
                <a href="{{ route('admin.bookings.export', ['month' => $monthlyReport['month_value']]) }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-white transition hover:bg-emerald-700">
                    Export Excel
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Bookings</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $monthlyReport['totals']['bookings'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Confirmed</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $monthlyReport['totals']['confirmed'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Completed</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $monthlyReport['totals']['completed'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Pending</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $monthlyReport['totals']['pending'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Guests</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $monthlyReport['totals']['guests'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Revenue (MYR)</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ number_format((float) $monthlyReport['totals']['revenue_myr'], 2) }}</p>
            </div>
        </div>
    </div>
    <div class="mt-6 space-y-4">
        @foreach ($bookings as $booking)
            <article class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-stone-900">{{ $booking->full_name }}</h2>
                        <p class="text-sm text-stone-500">{{ $booking->package_name }} · {{ $booking->destination }}</p>
                        <p class="mt-2 text-sm text-stone-600">{{ $booking->email }} · {{ $booking->phone }}</p>
                        <p class="mt-2 text-sm text-stone-600">Pickup: {{ $booking->pickup_location ?: 'Not set' }}</p>
                        <p class="mt-2 text-sm text-stone-600">{{ ucfirst($booking->service_type) }} · {{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                        <p class="mt-2 text-sm text-stone-600">Malaysian: {{ $booking->malaysian_adults }} adults, {{ $booking->malaysian_kids }} kids</p>
                        <p class="text-sm text-stone-600">International: {{ $booking->international_adults }} adults, {{ $booking->international_kids }} kids</p>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.18em]">
                            <span class="rounded-full bg-white px-3 py-1 text-stone-600">Ref {{ $booking->booking_reference ?: 'N/A' }}</span>
                            @if ($booking->invoice_number)
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">{{ $booking->invoice_number }}</span>
                            @endif
                            @if ($booking->confirmed_at)
                                <span class="rounded-full bg-white px-3 py-1 text-stone-600">Confirmed {{ $booking->confirmed_at->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-3">
                        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="rounded-2xl border border-stone-300 px-4 py-2 text-sm text-stone-800">
                                @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-white">Update</button>
                        </form>
                        @if (in_array($booking->status, ['confirmed', 'completed'], true))
                            <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full bg-sky-700 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white transition hover:bg-sky-800">
                                Open Invoice PDF
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>
