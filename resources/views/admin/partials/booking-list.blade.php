<section class="mt-5 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Bookings</p>
    <h1 class="mt-2 text-3xl font-semibold text-stone-900">Incoming booking requests</h1>
    <div class="mt-6 rounded-[1.75rem] border border-sky-100 bg-sky-50 p-5">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">{{ $reportType === 'yearly' ? 'Yearly Report' : 'Monthly Report' }}</p>
                <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $bookingReport['period_label'] }}</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">Track booking totals, revenue, and confirmations by month or by year. Export the selected report directly to Excel.</p>
            </div>
            <div class="ml-auto flex w-full max-w-fit flex-col items-end gap-2">
                <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-col items-end gap-2">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                        <div>
                            <label for="report-type" class="mb-2 block text-sm font-medium text-stone-700">Report type</label>
                            <select id="report-type" name="report_type" class="rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                <option value="monthly" @selected($reportType === 'monthly')>Monthly</option>
                                <option value="yearly" @selected($reportType === 'yearly')>Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label for="report-period" class="mb-2 block text-sm font-medium text-stone-700">{{ $reportType === 'yearly' ? 'Report year' : 'Report month' }}</label>
                            <select id="report-period" name="period" class="rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                <option value="{{ $reportPeriodValue }}">{{ $bookingReport['period_label'] }}</option>
                                @foreach ($reportPeriodOptions as $option)
                                    @if ($option['value'] !== $reportPeriodValue)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex h-8 items-center justify-center rounded-full border border-stone-300 bg-white px-3 text-[8px] font-semibold uppercase tracking-[0.06em] text-stone-700 transition hover:bg-stone-100">Apply {{ $reportType === 'yearly' ? 'Year' : 'Month' }}</button>
                        <a href="{{ route('admin.bookings.export', ['report_type' => $reportType, 'period' => $bookingReport['period_value']]) }}" class="inline-flex h-8 items-center justify-center rounded-full bg-emerald-600 px-3 text-[8px] font-semibold uppercase tracking-[0.06em] text-white transition hover:bg-emerald-700">
                            Export Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Bookings</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['bookings'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Confirmed</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['confirmed'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Completed</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['completed'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Pending</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['pending'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Guests</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['guests'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Revenue (MYR)</p>
                <p class="mt-2 text-2xl font-semibold text-stone-900">{{ number_format((float) $bookingReport['totals']['revenue_myr'], 2) }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white">
        <div class="flex flex-col gap-4 border-b border-stone-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
            <p id="admin-booking-results" class="sr-only">Showing booking entries</p>
            <div class="flex w-full items-center gap-3">
                <label class="relative block min-w-0 flex-1">
                    <input
                        id="admin-booking-search"
                        type="search"
                        placeholder="Search by customer, package, email, ref, or destination"
                        class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white"
                    >
                </label>
                <div class="ml-auto flex shrink-0 items-center gap-2">
                    <button
                        id="admin-booking-prev"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-stone-700 transition hover:bg-stone-100"
                        aria-label="Previous bookings page"
                    >
                        <span class="text-lg leading-none">&#8249;</span>
                    </button>
                    <button
                        id="admin-booking-next"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-stone-700 transition hover:bg-stone-100"
                        aria-label="Next bookings page"
                    >
                        <span class="text-lg leading-none">&#8250;</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="admin-booking-table" class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-100/90">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-stone-600">
                        <th class="px-4 py-4">Customer</th>
                        <th class="px-4 py-4">Booking</th>
                        <th class="px-4 py-4">Guests</th>
                        <th class="px-4 py-4">Amount</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Invoice</th>
                        <th class="px-4 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse ($bookings as $booking)
                        <tr data-admin-booking-item="true" class="align-top text-stone-700">
                            <td class="px-4 py-4">
                                <div class="min-w-[14rem]">
                                    <p class="font-semibold text-stone-900">{{ $booking->full_name }}</p>
                                    <p class="mt-1 text-xs text-stone-500">{{ $booking->email }}</p>
                                    <p class="mt-1 text-xs text-stone-500">{{ $booking->phone }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[18rem] space-y-1">
                                    <p class="font-semibold text-stone-900">{{ $booking->package_name }}</p>
                                    <p class="text-xs text-stone-500">{{ $booking->destination }}</p>
                                    <p class="text-xs text-stone-500">Pickup: {{ $booking->pickup_location ?: 'Not set' }}</p>
                                    <p class="text-xs text-stone-500">{{ ucfirst($booking->service_type) }} | Ref {{ $booking->booking_reference ?: 'N/A' }}</p>
                                    <p class="text-xs text-stone-500">{{ optional($booking->check_in_date)->format('d M Y') }} to {{ optional($booking->check_out_date)->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[10rem] space-y-1 text-xs text-stone-600">
                                    <p>MY adults: {{ $booking->malaysian_adults }}</p>
                                    <p>MY kids: {{ $booking->malaysian_kids }}</p>
                                    <p>INT adults: {{ $booking->international_adults }}</p>
                                    <p>INT kids: {{ $booking->international_kids }}</p>
                                    <p class="pt-1 font-semibold text-stone-900">Total: {{ $booking->total_guests }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php($isPaymentPaid = strtolower((string) $booking->payment_status) === 'paid')
                                <div class="min-w-[9rem]">
                                    <p class="font-semibold text-stone-900">{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                                    <p class="mt-1 text-xs text-stone-500">MYR {{ number_format((float) $booking->amount_myr, 2) }}</p>
                                    <div class="mt-1 flex items-center gap-2 text-xs">
                                        <span class="text-stone-500">Payment:</span>
                                        <span class="inline-flex rounded-full px-2.5 py-1 font-semibold uppercase tracking-[0.08em] {{ $isPaymentPaid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ ucwords(str_replace('_', ' ', $booking->payment_status)) }}
                                        </span>
                                    </div>
                                    @if ($booking->payment_gateway)
                                        <p class="mt-1 text-[11px] uppercase tracking-[0.12em] text-stone-500">
                                            Gateway: {{ strtoupper($booking->payment_gateway) }}
                                            @if ($booking->payment_gateway_status)
                                                ({{ str_replace('_', ' ', $booking->payment_gateway_status) }})
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[9rem] space-y-2">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]
                                        {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-sky-50 text-sky-700' : '' }}
                                        {{ $booking->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if ($booking->confirmed_at)
                                        <p class="text-xs text-stone-500">Confirmed {{ $booking->confirmed_at->format('d M Y') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[12rem] space-y-2">
                                    @if ($booking->invoice_number)
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">{{ $booking->invoice_number }}</p>
                                    @else
                                        <p class="text-xs text-stone-400">Not issued yet</p>
                                    @endif

                                    @if ($booking->status === 'confirmed' || $booking->invoice_number)
                                        <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-stone-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-stone-900 transition hover:bg-stone-200">
                                            Print Invoice
                                        </a>
                                    @endif
                                    @if ($booking->payment_status === 'paid' && $booking->booking_reference)
                                        <a href="{{ route('bookings.track.receipt.show', $booking->booking_reference) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full border border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-800 transition hover:bg-emerald-100">
                                            View Invoice
                                        </a>
                                        <a href="{{ route('bookings.track.receipt.pdf', $booking->booking_reference) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full border border-sky-300 bg-sky-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-sky-800 transition hover:bg-sky-100">
                                            Download PDF
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[12rem]">
                                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="w-full rounded-2xl border border-stone-300 px-4 py-2 text-sm text-stone-800">
                                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-full rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-sm text-stone-500">No bookings found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
