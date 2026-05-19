<x-layouts.app title="Booking Payment | Universal Eden Holidays">
    @php
        $isTrackingFlow = $isTrackingFlow ?? false;
        $backRoute = $isTrackingFlow
            ? route('bookings.track.show', $booking->booking_reference)
            : route('profile.bookings');
        $submitRoute = $isTrackingFlow
            ? route('bookings.track.payment.submit', $booking->booking_reference)
            : route('bookings.payment.submit', $booking);
    @endphp
    <main class="mx-auto max-w-4xl px-6 py-10 lg:px-10">
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Booking Payment</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Review your booking and sandbox payment</h1>
            </div>
            <a href="{{ $backRoute }}" class="rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                {{ $isTrackingFlow ? 'Back to Tracker' : 'My Bookings' }}
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-sm text-stone-500">Reference {{ $booking->booking_reference }}</p>
                        <h2 class="mt-2 text-2xl font-semibold text-stone-900">{{ $booking->package_name }}</h2>
                        <p class="mt-2 text-sm leading-6 text-stone-600">{{ $booking->destination }}</p>
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
                <p class="text-xs uppercase tracking-[0.3em] text-amber-200">Sandbox Payment</p>
                <h2 class="mt-3 text-2xl font-semibold">Submit sandbox payment</h2>
                <p class="mt-4 text-sm leading-7 text-stone-300">
                    {{ match ($booking->payment_method) {
                        'credit_card' => 'Use this sandbox step to simulate card payment submission for this booking.',
                        'bank_transfer' => 'Use this sandbox step to simulate bank transfer submission for this booking.',
                        'e_wallet' => 'Use this sandbox step to simulate e-wallet payment submission for this booking.',
                        default => 'Use this sandbox step to simulate payment submission for this booking.',
                    } }}
                </p>

                <form method="POST" action="{{ $submitRoute }}" class="mt-6 space-y-3">
                    @csrf
                    <div>
                        <label for="sandbox_reference" class="mb-2 block text-xs uppercase tracking-[0.2em] text-stone-300">Sandbox transaction ref</label>
                        <input
                            id="sandbox_reference"
                            name="sandbox_reference"
                            type="text"
                            value="{{ old('sandbox_reference') }}"
                            placeholder="SBX-{{ $booking->booking_reference }}"
                            class="w-full rounded-2xl border border-stone-600 bg-stone-800 px-4 py-3 text-sm text-white"
                        >
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-900 transition hover:bg-stone-100">
                        {{ $booking->payment_status === 'paid' ? 'Payment Submitted' : 'Submit Sandbox Payment' }}
                    </button>
                </form>

                <p class="mt-4 text-xs leading-6 text-stone-400">
                    This sandbox confirms the payment flow. A payment receipt email will be sent once submitted.
                </p>
            </aside>
        </div>
    </main>
</x-layouts.app>
