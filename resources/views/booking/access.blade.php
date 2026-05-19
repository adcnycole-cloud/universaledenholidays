<x-layouts.app title="Find Booking | Universal Eden Holidays">
    <main class="mx-auto max-w-3xl px-6 py-10 lg:px-10">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm lg:p-8">
            <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Booking Access</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900">Trace your booking with Booking ID</h1>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                Enter the Booking ID we emailed to you and the same email used in your booking form to review status and continue with payment.
            </p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    Please check your Booking ID and email, then try again.
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.lookup.submit') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="booking_reference" class="mb-2 block text-sm font-medium text-stone-700">Booking ID</label>
                    <input id="booking_reference" name="booking_reference" type="text" value="{{ $bookingReference }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" placeholder="UEH-XXXXXXXX" required>
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-stone-700">Booking email</label>
                    <input id="email" name="email" type="email" value="{{ $email }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" required>
                </div>
                <button type="submit" class="inline-flex rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-stone-800">
                    Find Booking
                </button>
            </form>
        </div>
    </main>
</x-layouts.app>
