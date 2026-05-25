<section class="mt-5 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Bookings</p>
    <h1 class="mt-2 text-2xl font-semibold text-stone-900">Incoming booking requests</h1>
    <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">This queue now shows reservations and bookings only. Customer questions submitted through the enquiry form are handled separately in the admin enquiries page.</p>
    <div class="mt-6 rounded-[1.75rem] border border-sky-100 bg-sky-50 p-4">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-start xl:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">{{ $reportType === 'yearly' ? 'Yearly Report' : 'Monthly Report' }}</p>
                <h2 class="mt-1 text-2xl font-semibold text-stone-900">{{ $bookingReport['period_label'] }}</h2>
                <p class="mt-1 text-sm leading-6 text-stone-600">Track booking totals, revenue, and confirmations by month or by year. Export the selected report directly to Excel.</p>
            </div>
            <div class="ml-auto flex w-full max-w-fit flex-col items-end gap-1.5">
                <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-col items-end gap-1.5">
                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-end">
                        <div>
                            <label for="report-type" class="mb-1.5 block text-sm font-medium text-stone-700">Report type</label>
                            <select id="report-type" name="report_type" class="rounded-2xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                <option value="monthly" @selected($reportType === 'monthly')>Monthly</option>
                                <option value="yearly" @selected($reportType === 'yearly')>Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label for="report-period" class="mb-1.5 block text-sm font-medium text-stone-700">{{ $reportType === 'yearly' ? 'Report year' : 'Report month' }}</label>
                            <select id="report-period" name="period" class="rounded-2xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
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
                        <button type="submit" class="inline-flex h-9 min-w-[6.5rem] items-center justify-center rounded-full border border-stone-300 bg-white px-4 text-[9px] font-semibold uppercase tracking-[0.08em] text-stone-700 transition hover:bg-stone-100">Apply {{ $reportType === 'yearly' ? 'Year' : 'Month' }}</button>
                        <a href="{{ route('admin.bookings.export', ['report_type' => $reportType, 'period' => $bookingReport['period_value']]) }}" class="inline-flex h-9 min-w-[6.5rem] items-center justify-center rounded-full bg-emerald-600 px-4 text-[9px] font-semibold uppercase tracking-[0.08em] text-white transition hover:bg-emerald-700">
                            Export Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-[1.25rem] bg-white p-3.5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Bookings</p>
                <p class="mt-1.5 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['bookings'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-3.5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Confirmed</p>
                <p class="mt-1.5 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['confirmed'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-3.5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Completed</p>
                <p class="mt-1.5 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['completed'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-3.5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Pending</p>
                <p class="mt-1.5 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['pending'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-3.5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Guests</p>
                <p class="mt-1.5 text-2xl font-semibold text-stone-900">{{ $bookingReport['totals']['guests'] }}</p>
            </div>
            <div class="rounded-[1.25rem] bg-white p-3.5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.18em] text-stone-500">Revenue (MYR)</p>
                <p class="mt-1.5 text-2xl font-semibold text-stone-900">{{ number_format((float) $bookingReport['totals']['revenue_myr'], 2) }}</p>
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
                        @php
                            $bookingStatus = strtolower((string) $booking->status);
                            $paymentStatus = strtolower((string) $booking->payment_status);
                            $canManageInvoice = in_array($bookingStatus, ['confirmed', 'completed'], true) || filled($booking->invoice_number);
                            $statusBadgeClass = match ($bookingStatus) {
                                'confirmed' => 'bg-emerald-50 text-emerald-700',
                                'completed' => 'bg-sky-50 text-sky-700',
                                'cancelled' => 'bg-rose-50 text-rose-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                            $paymentBadgeClass = $paymentStatus === 'paid'
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-amber-50 text-amber-700';
                            $statusLabel = $bookingStatus === 'pending' ? 'Awaiting Confirmation' : ucfirst((string) $booking->status);
                            $paymentLabel = ucwords(str_replace('_', ' ', (string) $booking->payment_status));
                        @endphp
                        <tr class="align-top text-stone-700">
                            <td class="px-2 py-3 md:px-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-stone-900">{{ $booking->full_name }}</p>
                                    <p class="mt-0.5 truncate text-[14px] text-stone-500">{{ $booking->email }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[18rem] space-y-1">
                                    <p class="font-semibold text-stone-900">{{ $booking->package_name }}</p>
                                    <p class="text-xs text-stone-500">{{ $booking->destination }}</p>
                                    <p class="text-xs text-stone-500">Pickup: {{ $booking->pickup_location ?: 'Not set' }}</p>
                                    <p class="text-xs text-stone-500">{{ ucfirst((string) $booking->service_type) }} | Ref {{ $booking->booking_reference ?: 'N/A' }}</p>
                                    <p class="text-xs text-stone-500">{{ optional($booking->check_in_date)->format('d M Y') }} to {{ optional($booking->check_out_date)->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="hidden px-4 py-4 xl:table-cell">
                                <div class="min-w-[10rem] space-y-1 text-xs text-stone-600">
                                    <p>MY adults: {{ $booking->malaysian_adults }}</p>
                                    <p>MY kids: {{ $booking->malaysian_kids }}</p>
                                    <p>INT adults: {{ $booking->international_adults }}</p>
                                    <p>INT kids: {{ $booking->international_kids }}</p>
                                    <p class="pt-1 font-semibold text-stone-900">Total: {{ $booking->total_guests }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-[9rem]">
                                    <p class="font-semibold text-stone-900">{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                                    <p class="mt-1 text-xs text-stone-500">MYR {{ number_format((float) $booking->amount_myr, 2) }}</p>
                                    <div class="mt-1 flex items-center gap-2 text-xs">
                                        <span class="text-stone-500">Payment:</span>
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.08em] {{ $paymentBadgeClass }}">
                                            {{ $paymentLabel }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden px-4 py-4 xl:table-cell">
                                <div class="min-w-[10rem] space-y-2">
                                    <span class="inline-flex rounded-full px-3 py-1 font-semibold uppercase tracking-[0.18em] {{ $bookingStatus === 'pending' ? 'text-[10px]' : 'text-xs' }} {{ $statusBadgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                    @if ($booking->confirmed_at)
                                        <p class="text-xs text-stone-500">Confirmed {{ $booking->confirmed_at->format('d M Y') }}</p>
                                    @endif

                                    @if ($booking->invoice_number)
                                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">{{ $booking->invoice_number }}</p>
                                    @else
                                        <p class="text-xs text-stone-400">Not issued yet</p>
                                    @endif

                                    @if ($canManageInvoice)
                                        <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-full items-center justify-center rounded-full border border-stone-300 bg-stone-100 px-2 py-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-stone-900 transition hover:bg-stone-200">
                                            Print Invoice
                                        </a>
                                        <form method="POST" action="{{ route('admin.bookings.invoice.email', $booking) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-emerald-300 bg-emerald-50 px-2 py-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-emerald-800 transition hover:bg-emerald-100">
                                                Email PDF Invoice
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="ml-auto min-w-[10rem] max-w-[10rem] space-y-2">
                                    <span class="inline-flex rounded-full px-3 py-1 font-semibold uppercase tracking-[0.18em] {{ $bookingStatus === 'pending' ? 'text-[10px]' : 'text-xs' }} {{ $statusBadgeClass }} xl:hidden">
                                        {{ $statusLabel }}
                                    </span>

                                    <div class="space-y-2 xl:hidden">
                                        @if ($booking->invoice_number)
                                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">{{ $booking->invoice_number }}</p>
                                        @else
                                            <p class="text-xs text-stone-400">Not issued yet</p>
                                        @endif

                                        @if ($canManageInvoice)
                                            <a href="{{ route('admin.bookings.invoice', $booking) }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-full items-center justify-center rounded-full border border-stone-300 bg-stone-100 px-2 py-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-stone-900 transition hover:bg-stone-200">
                                                Print Invoice
                                            </a>
                                            <form method="POST" action="{{ route('admin.bookings.invoice.email', $booking) }}" class="w-full">
                                                @csrf
                                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-emerald-300 bg-emerald-50 px-2 py-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-emerald-800 transition hover:bg-emerald-100">
                                                    Email PDF Invoice
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="w-full rounded-2xl border border-stone-300 px-3 py-2 text-sm text-stone-800">
                                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($bookingStatus === $status)>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-stone-300 bg-white px-3 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
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
