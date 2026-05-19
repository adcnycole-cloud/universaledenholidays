<x-layouts.app title="Booking Payment | Universal Eden Holidays">
    <main class="mx-auto max-w-4xl px-6 py-10 lg:px-10">
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Booking Payment</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Review your booking and payment step</h1>
            </div>
            <a href="{{ auth()->check() ? route('profile.bookings') : route('bookings.lookup.show') }}" class="rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                {{ auth()->check() ? 'My Bookings' : 'Find Another Booking' }}
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-amber-600">Booking Details</p>
                        <h2 class="mt-2 text-sm text-stone-500">Booking ID:</h2>
                        <h2 class="mt-2 text-2xl font-extrabold text-stone-900">{{ $booking->booking_reference }}</h2>
                        <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $booking->package_name }}</h2>
                        <p class="mt-2 text-sm leading-6 text-stone-600">{{ $booking->destination }}</p>
                        <p class="mt-2 text-xs uppercase tracking-[0.24em] text-stone-400">Use this Booking ID with your booking email to trace or revisit this payment page.</p>
                    </div>
                    <span class="inline-flex rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">
                        {{ ucwords(str_replace('_', ' ', $booking->payment_status)) }}
                    </span>
                </div>

                <div class="mt-6 grid gap-4 text-sm text-stone-600 md:grid-cols-2">
                    <div>
                        <p class="font-medium text-stone-900">Travel dates</p>
                        <p>{{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Guests</p>
                        <p>{{ $booking->guest_count }} traveler{{ $booking->guest_count === 1 ? '' : 's' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Payment method</p>
                        <p>{{ ucwords(str_replace('_', ' ', $booking->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Amount due</p>
                        <p>{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                    </div>
                </div>
            </section>

            <aside class="rounded-[2rem] border border-stone-200 bg-stone-900 p-6 text-white shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-200">Next Step</p>
                <h2 class="mt-3 text-2xl font-semibold">Pay your bill</h2>
                <p class="mt-4 text-sm leading-7 text-stone-300">
                    You will be redirected to our payment gateway. After payment, the status will be updated on this booking page.
                </p>

                <form method="POST" action="{{ route('bookings.payment.submit', ['reference' => $booking->booking_reference]) }}" class="mt-6">
                    @csrf
                    <button
                        type="submit"
                        @disabled($booking->payment_status === 'paid')
                        class="inline-flex w-full items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-900 transition hover:bg-stone-100 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{ $booking->payment_status === 'paid' ? 'Payment Completed' : ($booking->billplz_bill_id ? 'Continue Payment on Billplz' : 'Payment') }}
                    </button>
                </form>

                <p class="mt-4 text-xs leading-6 text-stone-400">
                    @if ($booking->billplz_bill_id)
                        Bill ID: {{ $booking->billplz_bill_id }}
                    @else
                        A Billplz bill will be generated when you click the button above.
                    @endif
                </p>
            </aside>
        </div>
    </main>
</x-layouts.app>
