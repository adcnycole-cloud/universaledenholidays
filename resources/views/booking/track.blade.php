<x-layouts.app title="Track Booking | Universal Eden Holidays">
    <main class="mx-auto max-w-3xl px-6 py-10 lg:px-10">
        <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm lg:p-8">
            <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Booking Tracker</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900">Track your booking by Booking ID</h1>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                Enter your Booking ID to continue directly to the Billplz sandbox payment gateway.
            </p>

            <form method="POST" action="{{ route('bookings.track.find') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="booking_reference" class="mb-2 block text-sm font-medium text-stone-700">Booking ID</label>
                    <input
                        id="booking_reference"
                        name="booking_reference"
                        type="text"
                        value="{{ old('booking_reference') }}"
                        placeholder="Example: UEH-ABC12345"
                        class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800"
                        required
                    >
                    @error('booking_reference')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="inline-flex rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-stone-800">
                    Track Booking
                </button>
            </form>
        </section>
    </main>
</x-layouts.app>
