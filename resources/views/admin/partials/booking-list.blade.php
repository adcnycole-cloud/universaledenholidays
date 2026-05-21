<section class="mt-5 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Bookings</p>
    <h1 class="mt-2 text-3xl font-semibold text-stone-900">Incoming booking requests</h1>
    <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">This queue now shows reservations and bookings only. Customer questions submitted through the enquiry form are handled separately in the admin enquiries page.</p>
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

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
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
        <div class="overflow-hidden rounded-b-[1.75rem]">
            <table id="admin-booking-table" class="w-full table-fixed divide-y divide-stone-200 text-xs md:text-sm">
                <thead class="bg-stone-100/90">
                    <tr class="text-left font-semibold uppercase tracking-[0.18em] text-stone-600">
                        <th class="w-[22%] px-3 py-4 md:px-4">Customer</th>
                        <th class="w-[23%] px-3 py-4 md:px-4">Booking</th>
                        <th class="hidden w-[10%] px-3 py-4 md:px-4 lg:table-cell">Guests</th>
                        <th class="w-[16%] px-3 py-4 md:px-4">Amount</th>
                        <th class="hidden w-[11%] px-3 py-4 md:px-4 xl:table-cell">Status</th>
                        <th class="hidden w-[10%] px-3 py-4 md:px-4 lg:table-cell">Invoice</th>
                        <th class="w-[19%] px-3 py-4 text-right md:px-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse ($bookings as $booking)
                        <tr data-admin-booking-item="true" class="align-top text-stone-700">
                            <td class="px-3 py-4 md:px-4">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $booking->full_name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500">{{ $booking->email }}</p>
                                </div>
                            </td>
                            <td class="px-3 py-4 md:px-4">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $booking->package_name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500">{{ $booking->destination }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500">Ref {{ $booking->booking_reference ?: 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="hidden px-3 py-4 text-xs md:px-4 lg:table-cell">
                                <p class="font-semibold text-stone-900">{{ $booking->total_guests }}</p>
                            </td>
                            <td class="px-3 py-4 md:px-4">
                                @php($isPaymentPaid = strtolower((string) $booking->payment_status) === 'paid')
                                <p class="break-words font-semibold text-stone-900">{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                            </td>
                            <td class="hidden px-3 py-4 md:px-4 xl:table-cell">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ strtolower((string) $booking->status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : (strtolower((string) $booking->status) === 'pending' ? 'bg-amber-100 text-amber-700' : (strtolower((string) $booking->status) === 'confirmed' ? 'bg-sky-100 text-sky-700' : 'bg-stone-100 text-stone-700')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="hidden px-3 py-4 md:px-4 lg:table-cell">
                                @if ($booking->invoice_path)
                                    <a href="{{ Storage::url($booking->invoice_path) }}" target="_blank" class="inline-flex items-center gap-1 rounded-full border border-sky-300 bg-sky-50 px-2 py-1 text-xs font-semibold text-sky-700 transition hover:border-sky-400 hover:bg-sky-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V9z"></path>
                                            <polyline points="13 2 13 9 20 9"></polyline>
                                        </svg>
                                        Invoice
                                    </a>
                                @else
                                    <span class="text-xs text-stone-400">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-right md:px-4">
                                <details class="relative inline-block text-left">
                                    <summary class="ml-auto list-none cursor-pointer rounded-full border border-stone-300 bg-white p-2 transition hover:bg-stone-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-stone-600" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </summary>
                                    <div class="absolute right-0 top-full z-50 mt-2 w-44 space-y-2 rounded-xl border border-stone-200 bg-white p-3 shadow-lg">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="block rounded-lg border border-stone-200 px-3 py-2 text-xs font-medium text-stone-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            View Details
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="block rounded-lg border border-stone-200 px-3 py-2 text-xs font-medium text-stone-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                            Edit
                                        </a>
                                    </div>
                                </details>
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
