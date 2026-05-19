<x-layouts.app title="Payment Receipt | Universal Eden Holidays">
    <main class="mx-auto max-w-4xl px-6 py-10 lg:px-10">
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Payment Receipt</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Receipt for Booking ID {{ $booking->booking_reference }}</h1>
            </div>
            <a href="{{ route('bookings.track.form') }}" class="rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                Track Another Booking
            </a>
        </div>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-stone-900">{{ $booking->package_name }}</h2>
                    <p class="mt-1 text-sm text-stone-600">{{ $booking->destination }}</p>
                </div>
                <span class="inline-flex rounded-full bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700">
                    Paid
                </span>
            </div>

            <div class="mt-6 grid gap-4 text-sm text-stone-600 sm:grid-cols-2">
                <div>
                    <p class="font-medium text-stone-900">Booking ID</p>
                    <p>{{ $booking->booking_reference }}</p>
                </div>
                <div>
                    <p class="font-medium text-stone-900">Receipt / Invoice</p>
                    <p>{{ $booking->invoice_number_or_reference }}</p>
                </div>
                <div>
                    <p class="font-medium text-stone-900">Payment Date</p>
                    <p>{{ optional($booking->payment_submitted_at)->format('d M Y H:i') ?: 'Recorded' }}</p>
                </div>
                <div>
                    <p class="font-medium text-stone-900">Payment Gateway</p>
                    <p>{{ strtoupper($booking->payment_gateway ?: 'billplz') }}</p>
                </div>
                <div>
                    <p class="font-medium text-stone-900">Amount Paid</p>
                    <p>{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                </div>
                <div>
                    <p class="font-medium text-stone-900">Amount (MYR)</p>
                    <p>MYR {{ number_format((float) $booking->amount_myr, 2) }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('bookings.track.receipt.pdf', $booking->booking_reference) }}" class="inline-flex items-center justify-center rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-stone-800">
                    Download PDF Receipt
                </a>
                <a href="{{ route('bookings.track.show', $booking->booking_reference) }}" class="inline-flex items-center justify-center rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                    View Booking Details
                </a>
            </div>
        </section>
    </main>
</x-layouts.app>
