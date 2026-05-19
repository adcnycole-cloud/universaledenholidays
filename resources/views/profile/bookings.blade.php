<x-layouts.app title="My Bookings | Universal Eden Holidays">
    <main class="mx-auto max-w-4xl px-6 py-10 lg:px-10">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">My Bookings</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Customer booking history</h1>
            </div>
            <a href="{{ route('profile.show') }}" class="rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">
                Back to Profile
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($user->bookings as $booking)
                <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-stone-900">{{ $booking->package_name }}</h3>
                            <p class="mt-1 text-sm text-stone-500">{{ $booking->destination }} · Ref {{ $booking->booking_reference ?? 'Pending' }}</p>
                        </div>
                        <span class="inline-block rounded-full bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">{{ $booking->status }}</span>
                    </div>
                    <div class="mt-5 grid gap-4 text-sm text-stone-600 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <p class="font-medium text-stone-900">Type</p>
                            <p>{{ ucfirst($booking->service_type) }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">Payment Method</p>
                            <p>{{ ucwords(str_replace('_', ' ', $booking->payment_method)) }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">Pickup</p>
                            <p>{{ $booking->pickup_location ?: 'Not set' }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">Booking Dates</p>
                            <p>{{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">Amount</p>
                            <p>{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">Payment Status</p>
                            <p>{{ ucwords(str_replace('_', ' ', $booking->payment_status ?? 'not_required')) }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">Malaysian Guests</p>
                            <p>{{ $booking->malaysian_adults }} adults, {{ $booking->malaysian_kids }} kids</p>
                        </div>
                        <div>
                            <p class="font-medium text-stone-900">International Guests</p>
                            <p>{{ $booking->international_adults }} adults, {{ $booking->international_kids }} kids</p>
                        </div>
                    </div>
                    @if (in_array($booking->payment_status, ['awaiting_payment', 'payment_submitted'], true))
                        <div class="mt-5">
                            <a href="{{ route('bookings.payment.show', ['reference' => $booking->booking_reference]) }}" class="inline-flex rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-stone-800">
                                {{ $booking->payment_status === 'payment_submitted' ? 'View Payment Step' : 'Continue Payment' }}
                            </a>
                        </div>
                    @endif
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-8 text-center text-sm text-stone-600">
                    <p class="mb-4">No bookings linked to this profile yet.</p>
                    <p>Create one from the Sabah booking form on the <a href="{{ route('home') }}" class="font-semibold text-sky-600 hover:underline">homepage</a>.</p>
                </div>
            @endforelse
        </div>
    </main>
</x-layouts.app>
