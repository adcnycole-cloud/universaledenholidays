<x-layouts.app title="Booking Details | Universal Eden Holidays">
    <main class="mx-auto max-w-5xl px-6 py-10 lg:px-10">
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Booking Tracker</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Booking ID: {{ $booking->booking_reference }}</h1>
            </div>
            <a href="{{ route('bookings.track.form') }}" class="rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                Search Another ID
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-stone-900">{{ $booking->package_name }}</h2>
                <p class="mt-2 text-sm text-stone-600">{{ $booking->destination }}</p>

                <div class="mt-6 grid gap-4 text-sm text-stone-600 sm:grid-cols-2">
                    <div>
                        <p class="font-medium text-stone-900">Customer</p>
                        <p>{{ $booking->full_name }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Email</p>
                        <p>{{ $booking->email }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Travel dates</p>
                        <p>{{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Guests</p>
                        <p>{{ $booking->total_guests }} traveler{{ $booking->total_guests === 1 ? '' : 's' }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Payment method</p>
                        <p>{{ ucwords(str_replace('_', ' ', $booking->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Amount</p>
                        <p>{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Booking status</p>
                        <p>{{ ucfirst($booking->status) }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-stone-900">Payment status</p>
                        <p>{{ ucwords(str_replace('_', ' ', $booking->payment_status)) }}</p>
                    </div>
               
                </div>
            </section>

            <aside class="rounded-[2rem] border border-stone-200 bg-stone-900 p-6 text-white shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-200">Next Step</p>
                <h2 class="mt-3 text-2xl font-semibold">Confirm and Continue</h2>
                <p class="mt-4 text-sm leading-7 text-stone-300">
                    Confirm your booking details to continue with payment.
                </p>
                <p class="mt-4 text-sm leading-7 text-stone-300">
                    You will receive a booking confirmation email with your booking details and receipt once payment is completed. If you have already made payment, please allow a few moments for our system to process and update your booking status.
                </p>
                
                @if ((float) $booking->amount_myr > 0 && $booking->payment_status !== 'paid')
                    <form method="POST" action="{{ route('bookings.track.confirm', $booking->booking_reference) }}" class="mt-6 space-y-3" id="confirm-form">
                        @csrf
                        {{-- Legal consent checkbox --}}
                        <div class="rounded-2xl border border-stone-600 bg-stone-800/60 px-4 py-4">
                            <label class="flex cursor-pointer items-start gap-3" for="legal_consent_confirm">
                                <input
                                    id="legal_consent_confirm"
                                    name="legal_consent"
                                    type="checkbox"
                                    value="1"
                                    class="mt-0.5 h-4 w-4 flex-shrink-0 cursor-pointer rounded border-stone-500 accent-amber-500 focus-visible:ring-2 focus-visible:ring-amber-400"
                                    required
                                    aria-required="true"
                                    aria-describedby="confirm-consent-error"
                                >
                                <span class="text-xs leading-5 text-stone-300">
                                    I have read and agree to the
                                    <a href="{{ route('legal.terms-and-conditions') }}" target="_blank" rel="noopener noreferrer" class="text-amber-300 underline hover:text-amber-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 rounded">Terms &amp; Conditions</a>
                                    and
                                    <a href="{{ route('legal.privacy-policy') }}" target="_blank" rel="noopener noreferrer" class="text-amber-300 underline hover:text-amber-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 rounded">Privacy Policy</a>.
                                </span>
                            </label>
                            <p id="confirm-consent-error" class="mt-2 hidden text-xs text-rose-400" role="alert" aria-live="polite">
                                You must agree to the Terms &amp; Conditions and Privacy Policy before proceeding.
                            </p>
                        </div>
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-900 transition hover:bg-stone-100">
                            Confirm and Continue to Payment
                        </button>
                    </form>
                    <script>
                        (function () {
                            const form = document.getElementById('confirm-form');
                            const checkbox = document.getElementById('legal_consent_confirm');
                            const errorEl = document.getElementById('confirm-consent-error');
                            if (form && checkbox && errorEl) {
                                form.addEventListener('submit', function (e) {
                                    if (!checkbox.checked) {
                                        e.preventDefault();
                                        errorEl.classList.remove('hidden');
                                        checkbox.focus();
                                    } else {
                                        errorEl.classList.add('hidden');
                                    }
                                });
                                checkbox.addEventListener('change', function () {
                                    if (this.checked) errorEl.classList.add('hidden');
                                });
                            }
                        })();
                    </script>
                @elseif ($booking->payment_status === 'paid')
                    <div class="mt-6 rounded-2xl border border-emerald-300/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        Payment completed. Your receipt has been issued.
                    </div>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('bookings.track.receipt.show', $booking->booking_reference) }}" class="inline-flex w-full items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-900 transition hover:bg-stone-100">
                            View Receipt
                        </a>
                        <a href="{{ route('bookings.track.receipt.pdf', $booking->booking_reference) }}" class="inline-flex w-full items-center justify-center rounded-full border border-stone-400 px-6 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-stone-800">
                            Download PDF Receipt
                        </a>
                    </div>
                @else
                    <div class="mt-6 rounded-2xl border border-sky-300/40 bg-sky-500/10 px-4 py-3 text-sm text-sky-100">
                        This booking does not require payment.
                    </div>
                @endif
            </aside>
        </div>
    </main>
</x-layouts.app>
