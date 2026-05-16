<x-layouts.app title="Complete Booking Access | Universal Eden Holidays">
    <main class="mx-auto max-w-3xl px-6 py-10 lg:px-10">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm lg:p-8">
            <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Booking Access</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900">Complete your account and continue to payment</h1>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                This secure link is tied to booking reference <span class="font-semibold text-stone-900">{{ $booking->booking_reference }}</span>.
                Set your password once and we will connect this booking to your customer profile.
            </p>

            <div class="mt-6 rounded-3xl bg-stone-50 p-5">
                <div class="grid gap-3 text-sm text-stone-600 md:grid-cols-2">
                    <div>
                        <p class="font-medium text-stone-900">Booking</p>
                        <p>{{ $booking->package_name }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Travel dates</p>
                        <p>{{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Email</p>
                        <p>{{ $booking->email }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Amount due</p>
                        <p>{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                    </div>
                </div>
            </div>

            @if ($existingUser)
                <div class="mt-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm leading-6 text-sky-800">
                    An account already exists for this email. Submitting this page will refresh its password and link this booking to that account.
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    Please review the form below and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.access.complete', $token) }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-stone-700">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $existingUser->name ?? $booking->full_name) }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" required>
                </div>
                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium text-stone-700">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $existingUser->phone ?? $booking->phone) }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-stone-700">Create password</label>
                        <input id="password" name="password" type="password" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-stone-700">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" required>
                    </div>
                </div>
                <button type="submit" class="inline-flex rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-stone-800">
                    Continue to Payment
                </button>
            </form>
        </div>
    </main>
</x-layouts.app>
