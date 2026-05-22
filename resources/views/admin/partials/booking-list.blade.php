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
        <div class="flex flex-col gap-2 border-b border-stone-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-stone-700">
                    Showing {{ $bookings->firstItem() ?? 0 }}-{{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} bookings
                </p>
                <p class="text-xs text-stone-500">Page {{ $bookings->currentPage() }} of {{ $bookings->lastPage() }}</p>
            </div>
            <form id="admin-booking-search-form" method="GET" action="{{ route('admin.bookings') }}" class="flex w-full max-w-xl items-center gap-2 sm:w-auto">
                <input type="hidden" name="report_type" value="{{ $reportType }}">
                <input type="hidden" name="period" value="{{ $reportPeriodValue }}">
                <label for="admin-booking-search" class="sr-only">Search bookings</label>
                <input
                    id="admin-booking-search"
                    name="q"
                    type="search"
                    value="{{ request('q', '') }}"
                    list="admin-booking-search-suggestions"
                    placeholder="Search name, email, ref"
                    class="w-full rounded-xl border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 outline-none transition focus:border-sky-400"
                    autocomplete="off"
                >
                <datalist id="admin-booking-search-suggestions">
                    @foreach ($bookingSearchSuggestions ?? [] as $suggestion)
                        <option value="{{ $suggestion }}"></option>
                    @endforeach
                </datalist>
                <button type="submit" class="inline-flex shrink-0 items-center rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">Search</button>
                @if (request('q'))
                    <a href="{{ route('admin.bookings', ['report_type' => $reportType, 'period' => $reportPeriodValue]) }}" class="inline-flex shrink-0 items-center rounded-lg border border-stone-300 bg-white px-3 py-2 text-xs font-semibold text-stone-700 transition hover:bg-stone-100">Clear</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto rounded-b-[1.75rem]">
            <table id="admin-booking-table" class="w-full table-fixed divide-y divide-stone-200 text-[15px]">
                <thead class="bg-stone-100/90">
                    <tr class="text-left font-semibold uppercase tracking-[0.18em] text-stone-600">
                        <th class="w-[19%] px-2 py-3 md:px-3">Customer</th>
                        <th class="w-[31%] px-2 py-3 md:px-3">Booking</th>
                        <th class="hidden w-[8%] px-2 py-3 md:px-3 xl:table-cell">Guests</th>
                        <th class="w-[12%] px-2 py-3 md:px-3">Amount</th>
                        <th class="hidden w-[8%] px-2 py-3 md:px-3 xl:table-cell">Invoice</th>
                        <th class="w-[22%] px-2 py-3 text-right md:px-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse ($bookings as $booking)
                        <tr class="align-top text-stone-700">
                            <td class="px-2 py-3 md:px-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $booking->full_name }}</p>
                                    <p class="mt-0.5 truncate text-[14px] text-stone-500">{{ $booking->email }}</p>
                                </div>
                            </td>
                            <td class="px-2 py-3 md:px-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $booking->package_name }}</p>
                                    <p class="mt-0.5 truncate text-[14px] text-stone-500">{{ $booking->destination }}</p>
                                    <p class="mt-0.5 truncate text-[14px] text-stone-500">Ref {{ $booking->booking_reference ?: 'N/A' }}</p>
                                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ strtolower((string) $booking->status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : (strtolower((string) $booking->status) === 'pending' ? 'bg-amber-100 text-amber-700' : (strtolower((string) $booking->status) === 'confirmed' ? 'bg-sky-100 text-sky-700' : 'bg-stone-100 text-stone-700')) }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ strtolower((string) $booking->payment_status) === 'paid' ? 'bg-emerald-100 text-emerald-700' : (strtolower((string) $booking->payment_status) === 'awaiting_confirmation' ? 'bg-amber-100 text-amber-700' : 'bg-stone-100 text-stone-700') }}">
                                            {{ ucwords(str_replace('_', ' ', (string) $booking->payment_status)) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden px-2 py-3 text-xs md:px-3 xl:table-cell">
                                <p class="font-semibold text-stone-900">{{ $booking->total_guests }}</p>
                            </td>
                            <td class="px-2 py-3 md:px-3">
                                @php($isPaymentPaid = strtolower((string) $booking->payment_status) === 'paid')
                                <p class="break-words font-semibold text-stone-900">{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                            </td>
                            <td class="hidden px-2 py-3 md:px-3 xl:table-cell">
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
                            <td class="px-2 py-3 text-right md:px-3">
                                @php($canManageInvoice = in_array(strtolower((string) $booking->status), ['confirmed', 'completed'], true))
                                <div class="space-y-2">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">
                                        View
                                        </a>
                                        @if ($canManageInvoice)
                                            <a href="{{ route('admin.bookings.invoice', $booking->id) }}" target="_blank" class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100">
                                                Download
                                            </a>
                                            <form method="POST" action="{{ route('admin.bookings.invoice.email', $booking->id) }}" class="inline-flex">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-700 transition hover:border-amber-300 hover:bg-amber-100">
                                                    Send Email
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex cursor-not-allowed items-center rounded-lg border border-dashed border-stone-300 bg-stone-50 px-3 py-2 text-sm font-semibold text-stone-400">
                                                Download
                                            </span>
                                            <span class="inline-flex cursor-not-allowed items-center rounded-lg border border-dashed border-stone-300 bg-stone-50 px-3 py-2 text-sm font-semibold text-stone-400">
                                                Send Email
                                            </span>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}" class="ml-auto flex w-full max-w-[12rem] items-center justify-end gap-1.5">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="min-w-0 flex-1 rounded-lg border border-stone-300 bg-white px-2 py-2 text-sm text-stone-800">
                                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="inline-flex items-center rounded-lg border border-stone-300 bg-white px-2 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-stone-500">No bookings found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-stone-200 px-4 py-4">
            {{ $bookings->onEachSide(1)->links() }}
        </div>
    </div>
</section>
