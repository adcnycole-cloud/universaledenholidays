<x-layouts.app title="Booking Form | Universal Eden Holidays">
    @php
        $isEnquiry = ($formMode ?? 'booking') === 'enquiry';
        $selectedServiceType = old('service_type', $selectedProduct->category ?? 'package');
        $isProductLocked = $isProductLocked ?? false;
        $isReserveForm = !$isEnquiry && ($actionType ?? null) === 'reserve';
        $isInstantBookForm = !$isEnquiry && ($actionType ?? null) === 'instant_book';
        $actionTitle = match ($actionType ?? null) {
            'reserve' => 'Reserve your Sabah experience',
            'instant_book' => 'Instant book your Sabah experience',
            'book_now' => 'Complete your booking request',
            default => 'Book transport services and packages',
        };
        $bookingSubmitLabel = $isReserveForm
            ? 'Submit Reserve Form'
            : ($isInstantBookForm ? 'Submit Instant Booking' : 'Submit Booking');
        $rawPhoneValue = old('phone', auth()->user()->phone ?? '');
        $defaultPhoneCountryCode = '+60';
        $selectedPhoneCountryCode = old('phone_country_code');
        $phoneLocalNumber = old('phone_local_number');

        if (!$selectedPhoneCountryCode && is_string($rawPhoneValue) && $rawPhoneValue !== '') {
            foreach (array_keys($phoneCountryCodes ?? []) as $phoneCountryCode) {
                if (str_starts_with($rawPhoneValue, $phoneCountryCode)) {
                    $selectedPhoneCountryCode = $phoneCountryCode;
                    $phoneLocalNumber = ltrim(substr($rawPhoneValue, strlen($phoneCountryCode)), " \t\n\r\0\x0B-()");
                    break;
                }
            }
        }

        $selectedPhoneCountryCode = $selectedPhoneCountryCode ?: $defaultPhoneCountryCode;
        $phoneLocalNumber = is_string($phoneLocalNumber) ? $phoneLocalNumber : '';
        $selectedBookingPurpose = old('booking_purpose', '');
        $selectedProductPricingTiers = $selectedProduct
            ? ($productPricingTiers[$selectedProduct->category.':'.$selectedProduct->id] ?? ['malaysia' => [], 'international' => []])
            : ['malaysia' => [], 'international' => []];
    @endphp
    <main class="mx-auto max-w-[144rem] px-4 py-8 sm:px-5 lg:px-6">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">{{ $isEnquiry ? 'Enquiry Form' : ($isReserveForm ? 'Reserve Form' : 'Booking Form') }}</p>
                <h1 class="mt-2 text-2xl font-semibold text-stone-900">{{ $isEnquiry ? 'Send an enquiry for transport services and packages' : $actionTitle }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('bookings.track.form') }}" class="rounded-full border border-sky-300 px-4 py-2.5 text-sm font-semibold text-sky-700 transition hover:bg-sky-50">
                    Track Booking ID
                </a>
                <a href="{{ route('home') }}" class="rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                    Back to Home
                </a>
            </div>
        </div>

        <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm leading-6 text-stone-600">
                {{ $isEnquiry
                    ? 'Choose a product, share your contact details, and tell us what you need. Our team will reply with availability, recommendations, and next steps.'
                    : ($isReserveForm
                        ? 'Select a product, enter your details and travel information, then submit this reserve form so our team can hold and review your request.'
                        : 'Select a product, enter your details and guest information, choose your preferred dates and payment method, then submit to create a booking request.') }}
            </p>

            @if ($errors->any())
                <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    Please review the booking form and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.store') }}" class="mt-6 space-y-6" data-form-persist="customer-booking-{{ $isEnquiry ? 'enquiry' : ($actionType ?? 'booking') }}">
                @csrf
                <input type="hidden" name="form_mode" value="{{ $isEnquiry ? 'enquiry' : 'booking' }}">
                <input type="hidden" name="action_type" value="{{ $actionType }}">
                @unless ($isEnquiry)
                    <input type="hidden" id="payment_method" name="payment_method" value="{{ old('payment_method', 'bank_transfer') }}">
                    <input type="hidden" id="currency_code" name="currency_code" value="{{ old('currency_code', auth()->user()->preferred_currency ?? 'MYR') }}">
                @endunless
                @if ($isProductLocked && $selectedProduct)
                    <input type="hidden" name="locked_product_id" value="{{ $selectedProduct->id }}">
                    <input
                        type="hidden"
                        id="locked_product_data"
                        data-product-id="{{ $selectedProduct->id }}"
                        data-category="{{ $selectedProduct->category }}"
                        data-name="{{ $selectedProduct->name }}"
                        data-duration="{{ $selectedProduct->duration }}"
                        data-malaysia-adult="{{ $selectedProduct->discounted_malaysia_adult_price_myr }}"
                        data-malaysia-child="{{ $selectedProduct->discounted_malaysia_child_price_myr }}"
                        data-international-adult="{{ $selectedProduct->discounted_international_adult_price_myr }}"
                        data-international-child="{{ $selectedProduct->discounted_international_child_price_myr }}"
                    >
                @endif
                <div class="grid gap-4 md:grid-cols-2">
                    @if ($isProductLocked && $selectedProduct)
                        <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">
                    @else
                        <div>
                            <label for="product_id" class="mb-2 block text-sm font-medium text-stone-700">Service / Product <span class="text-rose-600">*</span></label>
                            <select id="product_id" name="product_id" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                <option value="">Select a product</option>
                                @foreach ($transportServices as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-category="transport"
                                        data-name="{{ $product->name }}"
                                        data-duration="{{ $product->duration }}"
                                        data-malaysia-adult="{{ $product->discounted_malaysia_adult_price_myr }}"
                                        data-malaysia-child="{{ $product->discounted_malaysia_child_price_myr }}"
                                        data-international-adult="{{ $product->discounted_international_adult_price_myr }}"
                                        data-international-child="{{ $product->discounted_international_child_price_myr }}"
                                        @selected(old('service_type', $selectedProduct->category ?? '') === 'transport' && (string) old('product_id', $selectedProduct?->id) === (string) $product->id)
                                    >{{ $product->name }} - Transport</option>
                                @endforeach
                                @foreach ($travelPackages as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-category="package"
                                        data-name="{{ $product->name }}"
                                        data-duration="{{ $product->duration }}"
                                        data-malaysia-adult="{{ $product->discounted_malaysia_adult_price_myr }}"
                                        data-malaysia-child="{{ $product->discounted_malaysia_child_price_myr }}"
                                        data-international-adult="{{ $product->discounted_international_adult_price_myr }}"
                                        data-international-child="{{ $product->discounted_international_child_price_myr }}"
                                        @selected(old('service_type', $selectedProduct->category ?? '') === 'package' && (string) old('product_id', $selectedProduct?->id) === (string) $product->id)
                                    >{{ $product->name }} - Package</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        @php
                            $selectedPackageType = null;
                            $showLockedPackageSummaryInline = false;

                            if ($isProductLocked && $selectedProduct && $selectedProduct->category === 'package') {
                                $packageDurationKey = strtolower(preg_replace('/\s+/', '', trim((string) $selectedProduct->duration)) ?? '');

                                $selectedPackageType = match (true) {
                                    str_contains($packageDurationKey, '4d3n'),
                                    str_contains($packageDurationKey, '4days3night'),
                                    str_contains($packageDurationKey, '4days3nights') => '4D3N',
                                    str_contains($packageDurationKey, '3d2n'),
                                    str_contains($packageDurationKey, '3days2night'),
                                    str_contains($packageDurationKey, '3days2nights') => '3D2N',
                                    str_contains($packageDurationKey, '2d1n'),
                                    str_contains($packageDurationKey, '2days1night'),
                                    str_contains($packageDurationKey, '2days1nights') => '2D1N',
                                    str_contains($packageDurationKey, 'daytrip'),
                                    str_contains($packageDurationKey, '1day'),
                                    str_contains($packageDurationKey, 'halfday') => 'Day Trip',
                                    default => $selectedProduct->duration ?: 'Package',
                                };

                                $showLockedPackageSummaryInline = !$isEnquiry;
                            }
                        @endphp
                        @if ($isProductLocked && $selectedProduct)
                            <input type="hidden" name="service_type" value="{{ $selectedProduct->category }}">
                            @if ($selectedPackageType && !$showLockedPackageSummaryInline)
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-stone-700">Package name <span class="text-rose-600">*</span></label>
                                        <select class="w-full appearance-none rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                            <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->name }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="service_type" class="mb-2 block text-sm font-medium text-stone-700">Tour type <span class="text-rose-600">*</span></label>
                                        <select id="service_type" class="w-full appearance-none rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                            <option value="{{ $selectedProduct->category }}" selected>{{ $selectedPackageType }}</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif (!$selectedPackageType)
                                <label for="service_type" class="mb-2 block text-sm font-medium text-stone-700">Booking type <span class="text-rose-600">*</span></label>
                                <select id="service_type" class="w-full appearance-none rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                    <option value="{{ $selectedProduct->category }}" selected>Transport service</option>
                                </select>
                            @endif
                        @else
                            <label for="service_type" class="mb-2 block text-sm font-medium text-stone-700">
                                {{ $isEnquiry ? 'Enquiry type' : ($isReserveForm ? 'Reserve type' : 'Booking type') }}
                                <span class="text-rose-600">*</span>
                            </label>
                            <select id="service_type" name="service_type" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                <option value="transport" @selected($selectedServiceType === 'transport')>Transport service</option>
                                <option value="package" @selected($selectedServiceType === 'package')>Travel package</option>
                            </select>
                        @endif
                    </div>
                </div>

                @if ($isEnquiry)
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.65fr)_minmax(20rem,0.75fr)] lg:items-start">
                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50/70 p-5 shadow-sm">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-stone-600">Your Details</p>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="full_name" class="mb-2 block text-sm font-medium text-stone-700">Full name <span class="text-rose-600">*</span></label>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name', auth()->user()->name ?? '') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-stone-700">Email <span class="text-rose-600">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" autocomplete="email" inputmode="email" spellcheck="false" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="phone_local_number" class="mb-2 block text-sm font-medium text-stone-700">Phone <span class="text-rose-600">*</span></label>
                            <div class="grid gap-3 sm:grid-cols-[12rem_minmax(0,1fr)]">
                                <select id="phone_country_code" name="phone_country_code" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" autocomplete="tel-country-code" required>
                                    @foreach ($phoneCountryCodes as $code => $label)
                                        <option value="{{ $code }}" @selected($selectedPhoneCountryCode === $code)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input id="phone_local_number" name="phone_local_number" type="tel" value="{{ $phoneLocalNumber }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" autocomplete="tel-national" inputmode="tel" maxlength="20" placeholder="12-345 6789" required>
                            </div>
                            <p class="mt-2 text-xs text-stone-500">Choose the country code, then enter the rest of your phone number without the leading `+`.</p>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50/70 p-5 shadow-sm">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-stone-600">Trip Preference</p>
                        <div>
                            <label for="preferred_travel_date" class="mb-2 block text-sm font-medium text-stone-700">Preferred travel date</label>
                            <input id="preferred_travel_date" name="preferred_travel_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_travel_date') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800">
                        </div>
                        <div class="mt-3">
                            <label for="estimated_guest_count" class="mb-2 block text-sm font-medium text-stone-700">Estimated guest count</label>
                            <input id="estimated_guest_count" name="estimated_guest_count" type="number" min="0" max="50" value="{{ old('estimated_guest_count') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Optional">
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5 shadow-sm">
                    <label for="special_requests" class="mb-2 block text-sm font-medium text-stone-700">Your enquiry</label>
                    <textarea id="special_requests" name="special_requests" rows="6" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Tell us what product you are interested in, your travel plan, number of travelers, or any questions you want us to answer.">{{ old('special_requests') }}</textarea>
                </div>

                {{-- PDPA notice --}}
                <div class="rounded-[1.5rem] border border-amber-100 bg-amber-50 px-5 py-4 text-sm leading-6 text-stone-600">
                    By submitting this form, you consent to the collection, processing, and storage of your personal data in accordance with our
                    <a href="{{ route('legal.privacy-policy') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-amber-700 underline hover:text-amber-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 rounded">Privacy Policy</a>.
                </div>

                <div class="rounded-[1.5rem] bg-white p-4 space-y-4">
                    {{-- Legal consent checkbox --}}
                    <label class="flex cursor-pointer items-start gap-3" for="legal_consent_enquiry">
                        <input
                            id="legal_consent_enquiry"
                            name="legal_consent"
                            type="checkbox"
                            value="1"
                            class="mt-0.5 h-4 w-4 flex-shrink-0 cursor-pointer rounded border-stone-300 accent-sky-600 focus-visible:ring-2 focus-visible:ring-sky-500"
                            required
                            aria-required="true"
                            aria-describedby="enquiry-consent-error"
                            @checked(old('legal_consent'))
                        >
                        <span class="text-sm leading-5 text-stone-600">
                            I have read and agree to the
                            <a href="{{ route('legal.terms-and-conditions') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-sky-700 underline hover:text-sky-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 rounded">Terms &amp; Conditions</a>
                            and
                            <a href="{{ route('legal.privacy-policy') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-sky-700 underline hover:text-sky-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 rounded">Privacy Policy</a>.
                        </span>
                    </label>
                    <p id="enquiry-consent-error" class="hidden text-sm text-rose-600" role="alert" aria-live="polite">
                        You must agree to the Terms &amp; Conditions and Privacy Policy before submitting.
                    </p>
                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-start">
                        <button type="submit" class="flex-1 rounded-full border border-sky-600 bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.22em] text-white transition hover:bg-sky-700">Submit Enquiry</button>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-50 sm:min-w-[9rem]">
                            Cancel
                        </a>
                    </div>
                </div>
                @else
                <div class="grid gap-6 lg:grid-cols-2 lg:items-start">
                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50/70 p-5 shadow-sm">
                        @if ($showLockedPackageSummaryInline)
                            <div class="mb-5 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-stone-700">Package name <span class="text-rose-600">*</span></label>
                                    <select class="w-full appearance-none rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                        <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="service_type" class="mb-2 block text-sm font-medium text-stone-700">Tour type <span class="text-rose-600">*</span></label>
                                    <select id="service_type" class="w-full appearance-none rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                        <option value="{{ $selectedProduct->category }}" selected>{{ $selectedPackageType }}</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-stone-600">Booking Purpose</p>
                        <div class="grid gap-3 md:grid-cols-2" data-booking-purpose-switcher>
                            <label class="block cursor-pointer">
                                <input type="radio" name="booking_purpose" value="leisure" class="sr-only" @checked($selectedBookingPurpose === 'leisure')>
                                <div class="rounded-[1.35rem] border border-stone-300 bg-white px-4 py-2.5 text-center text-stone-700 shadow-sm transition" data-booking-purpose-card="leisure">
                                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-inherit">Leisure</p>
                                </div>
                            </label>
                            <label class="block cursor-pointer">
                                <input type="radio" name="booking_purpose" value="business" class="sr-only" @checked($selectedBookingPurpose === 'business')>
                                <div class="rounded-[1.35rem] border border-stone-300 bg-white px-4 py-2.5 text-center text-stone-700 shadow-sm transition" data-booking-purpose-card="business">
                                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-inherit">Business</p>
                                </div>
                            </label>
                        </div>

                        <div class="mt-5 hidden" data-booking-personal-details>
                            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-stone-600">Your Details</p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="full_name" class="mb-2 block text-sm font-medium text-stone-700">Full name <span class="text-rose-600">*</span></label>
                                    <input id="full_name" name="full_name" type="text" value="{{ old('full_name', auth()->user()->name ?? '') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800">
                                </div>
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-medium text-stone-700">Email <span class="text-rose-600">*</span></label>
                                    <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" autocomplete="email" inputmode="email" spellcheck="false">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="phone_local_number" class="mb-2 block text-sm font-medium text-stone-700">Phone <span class="text-rose-600">*</span></label>
                                <div class="grid gap-3 sm:grid-cols-[12rem_minmax(0,1fr)]">
                                    <select id="phone_country_code" name="phone_country_code" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" autocomplete="tel-country-code">
                                        @foreach ($phoneCountryCodes as $code => $label)
                                            <option value="{{ $code }}" @selected($selectedPhoneCountryCode === $code)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input id="phone_local_number" name="phone_local_number" type="tel" value="{{ $phoneLocalNumber }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" autocomplete="tel-national" inputmode="tel" maxlength="20" placeholder="12-345 6789">
                                </div>
                                <p class="mt-2 text-xs text-stone-500">Choose the country code, then enter the rest of your phone number without the leading `+`.</p>
                            </div>
                            <div class="mt-3 rounded-[1.25rem] border border-stone-200 bg-white p-4 hidden" data-booking-purpose-panel="leisure">
                            <div class="mt-3">
                                <label for="identity_document_number" class="mb-2 block text-sm font-medium text-stone-700">IC Number / Passport Number <span class="text-rose-600">*</span></label>
                                <input id="identity_document_number" name="identity_document_number" type="text" value="{{ old('identity_document_number') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Example: 900101-12-1234 or P12345678">
                            </div>
                            </div>
                            <div class="mt-3 rounded-[1.25rem] border border-stone-200 bg-white p-4 hidden" data-booking-purpose-panel="business">
                                <div class="mt-3">
                                    <label for="company_number" class="mb-2 block text-sm font-medium text-stone-700">Company Number <span class="text-rose-600">*</span></label>
                                    <input id="company_number" name="company_number" type="text" value="{{ old('company_number') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Example: 202401012345">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="pickup_location" class="mb-2 block text-sm font-medium text-stone-700">Pickup location <span class="text-rose-600">*</span></label>
                                <input
                                    id="pickup_location"
                                    name="pickup_location"
                                    type="text"
                                    list="pickup_location_options"
                                    value="{{ old('pickup_location') }}"
                                    class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800"
                                    placeholder="Select or type pickup location"
                                >
                                <datalist id="pickup_location_options">
                                    @foreach ($pickupLocations as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50/70 p-4 shadow-sm">
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-stone-600">Travel Dates</p>
                        <div
                            class="rounded-[1.35rem] border border-stone-200 bg-[linear-gradient(180deg,_#fffdf9,_#faf8ff)] p-3.5"
                            data-date-picker
                            data-start="{{ old('check_in_date') }}"
                        >
                            <input id="check_in_date" name="check_in_date" type="hidden" value="{{ old('check_in_date') }}" required>
                            <input id="check_out_date" name="check_out_date" type="hidden" value="{{ old('check_out_date') }}" required>

                            <div class="flex flex-col gap-3">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-stone-700"><span class="text-rose-600"></span></label>
                                    <p class="font-semibold text-stone-900" data-date-label>Select your travel date</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.22em] text-stone-500" data-date-hint>Selected date will be used for booking</p>
                                </div>
                            </div>

                            <div class="mt-3 flex justify-center">
                                <div class="calendar-card w-full max-w-[22rem]">
                                    <div class="calendar-month">
                                        <ul>
                                            <li>
                                                <button type="button" class="calendar-nav prev" data-date-nav="prev" aria-label="Previous month">&#10094;</button>
                                            </li>
                                            <li>
                                                <button type="button" class="calendar-nav next" data-date-nav="next" aria-label="Next month">&#10095;</button>
                                            </li>
                                            <li>
                                                <span data-date-month></span><br>
                                                <span class="calendar-year" data-date-year></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <ul class="calendar-weekdays">
                                        <li>Mo</li>
                                        <li>Tu</li>
                                        <li>We</li>
                                        <li>Th</li>
                                        <li>Fr</li>
                                        <li>Sa</li>
                                        <li>Su</li>
                                    </ul>

                                    <ul class="calendar-days" data-date-grid></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="booking-market-prices" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 shadow-sm">
                    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-sky-700">Market Price</p>
                            <h2 class="mt-1 text-xl font-semibold text-stone-900" id="booking-price-title">
                                {{ $selectedProduct?->name ? $selectedProduct->name.' pricing' : 'Select a product to view pricing' }}
                            </h2>
                        </div>
                        <p class="text-xs text-stone-500">Rates shown in MYR before currency conversion, with package discounts applied automatically when available.</p>
                    </div>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        <div class="rounded-[1.25rem] border border-blue-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-700">Malaysia Market</p>
                            <div class="mt-3 space-y-3" id="booking-malaysia-tiers">
                                @forelse ($selectedProductPricingTiers['malaysia'] as $tier)
                                    <div class="rounded-[1rem] border border-blue-100 bg-blue-50/30 p-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Group Size</p>
                                        <p class="mt-1 text-sm font-semibold text-stone-900">{{ $tier['label'] }}</p>
                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                            <div>
                                                <p class="text-sm text-stone-500">Adult</p>
                                                <p class="mt-1 text-lg font-semibold text-stone-900">RM {{ number_format((float) $tier['adult_price'], 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-stone-500">Child</p>
                                                <p class="mt-1 text-lg font-semibold text-stone-900">RM {{ number_format((float) $tier['child_price'], 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-stone-500">Select a product to view pricing.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="rounded-[1.25rem] border border-amber-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">International Market</p>
                            <div class="mt-3 space-y-3" id="booking-international-tiers">
                                @forelse ($selectedProductPricingTiers['international'] as $tier)
                                    <div class="rounded-[1rem] border border-amber-100 bg-amber-50/30 p-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Group Size</p>
                                        <p class="mt-1 text-sm font-semibold text-stone-900">{{ $tier['label'] }}</p>
                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                            <div>
                                                <p class="text-sm text-stone-500">Adult</p>
                                                <p class="mt-1 text-lg font-semibold text-stone-900">RM {{ number_format((float) $tier['adult_price'], 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-stone-500">Child</p>
                                                <p class="mt-1 text-lg font-semibold text-stone-900">RM {{ number_format((float) $tier['child_price'], 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-stone-500">Select a product to view pricing.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50/70 p-5 shadow-sm">
                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-stone-600">Guest Breakdown</p>
                    <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_18rem]">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="malaysian_adults" class="mb-2 block text-sm font-medium text-stone-700">Malaysian adults <span class="text-rose-600">*</span></label>
                                <input id="malaysian_adults" name="malaysian_adults" type="number" min="0" max="50" value="{{ old('malaysian_adults', 1) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label for="malaysian_kids" class="mb-2 block text-sm font-medium text-stone-700">Malaysian kids <span class="text-rose-600">*</span></label>
                                <input id="malaysian_kids" name="malaysian_kids" type="number" min="0" max="50" value="{{ old('malaysian_kids', 0) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label for="international_adults" class="mb-2 block text-sm font-medium text-stone-700">International adults <span class="text-rose-600">*</span></label>
                                <input id="international_adults" name="international_adults" type="number" min="0" max="50" value="{{ old('international_adults', 0) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label for="international_kids" class="mb-2 block text-sm font-medium text-stone-700">International kids <span class="text-rose-600">*</span></label>
                                <input id="international_kids" name="international_kids" type="number" min="0" max="50" value="{{ old('international_kids', 0) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                        </div>

                        <div id="booking-live-estimate" class="rounded-[1.25rem] border border-emerald-200 bg-emerald-50/70 p-3.5">
                            <div>
                                <p class="text-xs uppercase tracking-[0.22em] text-emerald-700">Auto Calculation</p>
                                <h2 class="mt-1 text-base font-semibold text-stone-900">Estimated booking amount</h2>
                                <p class="mt-1 text-xs leading-5 text-stone-500">Updates automatically from your guest counts and selected product.</p>
                            </div>
                            <div class="mt-3 space-y-2.5">
                                <div class="rounded-[1rem] border border-white/80 bg-white p-3 shadow-sm">
                                    <p class="text-xs text-stone-500">Malaysia subtotal</p>
                                    <p class="mt-1 text-lg font-semibold text-stone-900" id="booking-malaysia-total">RM 0.00</p>
                                    <p class="mt-1 text-[11px] uppercase tracking-[0.18em] text-stone-400" id="booking-malaysia-count">0 guests</p>
                                </div>
                                <div class="rounded-[1rem] border border-white/80 bg-white p-3 shadow-sm">
                                    <p class="text-xs text-stone-500">International subtotal</p>
                                    <p class="mt-1 text-lg font-semibold text-stone-900" id="booking-international-total">RM 0.00</p>
                                    <p class="mt-1 text-[11px] uppercase tracking-[0.18em] text-stone-400" id="booking-international-count">0 guests</p>
                                </div>
                                <div class="rounded-[1rem] border border-emerald-300 bg-emerald-600 p-3 text-white shadow-sm">
                                    <p class="text-xs text-emerald-100">Grand total</p>
                                    <p class="mt-1 text-xl font-semibold" id="booking-grand-total">RM 0.00</p>
                                    <p class="mt-1 text-[11px] uppercase tracking-[0.18em] text-emerald-100" id="booking-grand-total-myr">Base MYR total: RM 0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5 shadow-sm">
                    <label for="special_requests" class="mb-2 block text-sm font-medium text-stone-700">Special requests</label>
                    <textarea id="special_requests" name="special_requests" rows="4" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Any additional information or special requirements for your booking?">{{ old('special_requests') }}</textarea>
                </div>

                {{-- PDPA notice --}}
                <div class="rounded-[1.5rem] border border-amber-100 bg-amber-50 px-5 py-4 text-sm leading-6 text-stone-600">
                    By submitting this form, you consent to the collection, processing, and storage of your personal data in accordance with our
                    <a href="{{ route('legal.privacy-policy') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-amber-700 underline hover:text-amber-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 rounded">Privacy Policy</a>.
                </div>

                <div class="space-y-3 pt-1">
                    {{-- Legal consent checkbox --}}
                    <label class="flex cursor-pointer items-start gap-3" for="legal_consent_booking">
                        <input
                            id="legal_consent_booking"
                            name="legal_consent"
                            type="checkbox"
                            value="1"
                            class="mt-0.5 h-4 w-4 flex-shrink-0 cursor-pointer rounded border-stone-300 accent-sky-600 focus-visible:ring-2 focus-visible:ring-sky-500"
                            required
                            aria-required="true"
                            aria-describedby="booking-consent-error"
                            @checked(old('legal_consent'))
                        >
                        <span class="text-sm leading-5 text-stone-600">
                            I have read and agree to the
                            <a href="{{ route('legal.terms-and-conditions') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-sky-700 underline hover:text-sky-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 rounded">Terms &amp; Conditions</a>
                            and
                            <a href="{{ route('legal.privacy-policy') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-sky-700 underline hover:text-sky-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 rounded">Privacy Policy</a>.
                        </span>
                    </label>
                    <p id="booking-consent-error" class="hidden text-sm text-rose-600" role="alert" aria-live="polite">
                        You must agree to the Terms &amp; Conditions and Privacy Policy before submitting.
                    </p>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="submit" class="flex-1 rounded-full bg-sky-600 px-5 py-2.5 text-sm font-semibold uppercase tracking-[0.22em] text-white transition hover:bg-sky-700">{{ $bookingSubmitLabel }}</button>
                        <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                            Cancel
                        </a>
                    </div>
                </div>
                @endif

            </form>
        </div>

    </main>

    <div class="h-12"></div>

    @include('partials.footer')

    <style>
        .calendar-card {
            overflow: hidden;
            border-radius: 1.5rem;
            background: #fff;
            box-shadow: 0 22px 50px -28px rgba(5, 150, 105, 0.45);
        }

        .calendar-month {
            padding: 1.5rem 1.75rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            text-align: center;
        }

        .calendar-month ul,
        .calendar-weekdays,
        .calendar-days {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .calendar-month ul {
            position: relative;
        }

        .calendar-month li:last-child {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .calendar-year {
            font-size: 0.95rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .calendar-nav {
            position: absolute;
            top: 50%;
            display: inline-flex;
            height: 2rem;
            width: 2rem;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 9999px;
            background: transparent;
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            transform: translateY(-50%);
            transition: background-color 0.2s ease;
        }

        .calendar-nav:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .calendar-nav.prev {
            left: 0;
        }

        .calendar-nav.next {
            right: 0;
        }

        .calendar-weekdays,
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            padding: 1rem 1rem 0;
        }

        .calendar-weekdays li {
            margin-bottom: 0.75rem;
            text-align: center;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            color: #78716c;
            text-transform: uppercase;
        }

        .calendar-days {
            gap: 0.45rem 0;
            padding-bottom: 1.1rem;
        }

        .calendar-days li {
            display: flex;
            justify-content: center;
        }

        .calendar-day-button {
            display: inline-flex;
            height: 2.4rem;
            width: 2.4rem;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 9999px;
            background: transparent;
            color: #44403c;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .calendar-day-button:hover {
            background: #d1fae5;
            color: #047857;
            transform: translateY(-1px);
        }

        .calendar-day-button.active {
            background: #10b981;
            color: #fff;
            box-shadow: 0 10px 22px -14px rgba(5, 150, 105, 0.95);
        }

        .calendar-day-button.muted,
        .calendar-day-button:disabled {
            color: #d6d3d1;
            cursor: not-allowed;
        }

        .calendar-day-button.muted:hover,
        .calendar-day-button:disabled:hover {
            background: transparent;
            color: #d6d3d1;
            transform: none;
        }

        .calendar-day-placeholder {
            display: inline-block;
            height: 2.4rem;
            width: 2.4rem;
        }
    </style>

    <script>
        // Legal consent validation for booking forms
        (function () {
            function attachConsentValidation(formSelector, checkboxId, errorId) {
                const form = document.querySelector(formSelector);
                const checkbox = document.getElementById(checkboxId);
                const errorEl = document.getElementById(errorId);
                if (!form || !checkbox || !errorEl) return;
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
            attachConsentValidation('[data-form-persist]', 'legal_consent_enquiry', 'enquiry-consent-error');
            attachConsentValidation('[data-form-persist]', 'legal_consent_booking', 'booking-consent-error');
        })();

        document.addEventListener('DOMContentLoaded', () => {
            const currencyRates = {
                @foreach ($currencyRates as $code => $rate)
                    {{ $code }}: {{ $rate }},
                @endforeach
            };

            const currencySymbols = {
                MYR: 'RM ',
                KRW: 'KRW ',
                USD: '$',
                SGD: 'S$',
                CNY: 'CNY ',
            };

            const formatPrice = (amount, currency) => `${currencySymbols[currency] ?? ''}${new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(Number(amount || 0))}`;

            const productSelector = document.querySelector('#product_id');
            const serviceSelector = document.querySelector('#service_type');
            const currencySelector = document.querySelector('#currency_code');
            const navbarCurrencySelector = document.querySelector('#currency-selector');
            const lockedProductData = document.querySelector('#locked_product_data');
            const productPricingTiers = @json($productPricingTiers);
            const malaysiaAdultsInput = document.querySelector('#malaysian_adults');
            const malaysiaKidsInput = document.querySelector('#malaysian_kids');
            const internationalAdultsInput = document.querySelector('#international_adults');
            const internationalKidsInput = document.querySelector('#international_kids');
            const malaysiaTiersContainer = document.querySelector('#booking-malaysia-tiers');
            const internationalTiersContainer = document.querySelector('#booking-international-tiers');
            const malaysiaTotal = document.querySelector('#booking-malaysia-total');
            const malaysiaCount = document.querySelector('#booking-malaysia-count');
            const internationalTotal = document.querySelector('#booking-international-total');
            const internationalCount = document.querySelector('#booking-international-count');
            const grandTotal = document.querySelector('#booking-grand-total');
            const grandTotalMyr = document.querySelector('#booking-grand-total-myr');
            const packageDuration = document.querySelector('#booking-package-duration');

            const getCount = (input) => Math.max(Number(input?.value || 0), 0);

            const formatGuestLabel = (adultCount, childCount) => {
                const totalGuests = adultCount + childCount;
                return totalGuests === 1 ? '1 guest' : `${totalGuests} guests`;
            };

            const parsePricingTierBounds = (label) => {
                const matches = String(label || '').match(/\d+/g) || [];
                const numbers = matches.map((value) => Number(value));

                if (numbers.length === 0) {
                    return { min: null, max: null };
                }

                const hasPlusSuffix = String(label || '').includes('+');

                if (numbers.length === 1) {
                    return {
                        min: numbers[0],
                        max: hasPlusSuffix ? null : numbers[0],
                    };
                }

                return {
                    min: Math.min(numbers[0], numbers[1]),
                    max: Math.max(numbers[0], numbers[1]),
                };
            };

            const resolvePricingTierForGuestCount = (tiers, guestCount) => {
                if (!Array.isArray(tiers) || tiers.length === 0 || guestCount < 1) {
                    return null;
                }

                const normalizedTiers = tiers.map((tier) => ({
                    tier,
                    ...parsePricingTierBounds(tier?.label || ''),
                }));

                const firstTier = normalizedTiers[0] || null;
                if (guestCount > 0 && firstTier && firstTier.min !== null && guestCount < firstTier.min) {
                    return firstTier.tier;
                }

                const matchingTier = normalizedTiers.find(({ min, max }) => {
                    if (min === null && max === null) {
                        return false;
                    }

                    if (min !== null && guestCount < min) {
                        return false;
                    }

                    if (max !== null && guestCount > max) {
                        return false;
                    }

                    return true;
                });

                return matchingTier?.tier || normalizedTiers[normalizedTiers.length - 1]?.tier || tiers[0];
            };

            const getSelectedOption = () => {
                if (productSelector) {
                    return productSelector.options[productSelector.selectedIndex] || null;
                }

                if (!lockedProductData) {
                    return null;
                }

                return {
                    value: lockedProductData.dataset.productId || '',
                    dataset: {
                        category: lockedProductData.dataset.category || '',
                        name: lockedProductData.dataset.name || '',
                        duration: lockedProductData.dataset.duration || '',
                        malaysiaAdult: lockedProductData.dataset.malaysiaAdult || '0',
                        malaysiaChild: lockedProductData.dataset.malaysiaChild || '0',
                        internationalAdult: lockedProductData.dataset.internationalAdult || '0',
                        internationalChild: lockedProductData.dataset.internationalChild || '0',
                    },
                };
            };

            const getSelectedDurationLabel = () => getSelectedOption()?.dataset.duration || '';

            const getSelectedDurationDays = () => {
                const durationLabel = getSelectedDurationLabel();
                const normalizedDurationLabel = durationLabel.replace(/\s+/g, '').toLowerCase();
                const compactDurationMatch = normalizedDurationLabel.match(/(\d+)d(\d+)n/);

                if (compactDurationMatch) {
                    return Number(compactDurationMatch[1]);
                }

                const durationMatch = durationLabel.match(/(\d+)\s*day/i);

                return durationMatch ? Number(durationMatch[1]) : 0;
            };

            const getSelectedProductPricing = () => {
                const selectedOption = getSelectedOption();
                const productId = selectedOption?.value || '';
                const productCategory = selectedOption?.dataset.category || '';

                return productPricingTiers[`${productCategory}:${productId}`] || { malaysia: [], international: [] };
            };

            const getActiveCurrency = () => navbarCurrencySelector?.value || currencySelector?.value || 'MYR';

            const syncBookingCurrency = () => {
                if (currencySelector) {
                    currencySelector.value = getActiveCurrency();
                }
            };

            const syncServiceTypeToProduct = () => {
                if (!productSelector || !serviceSelector || serviceSelector.disabled) {
                    return;
                }

                const selectedOption = getSelectedOption();

                if (!selectedOption || !selectedOption.value || !selectedOption.dataset.category) {
                    return;
                }

                serviceSelector.value = selectedOption.dataset.category;
            };

            const renderMarketPricingTiers = (container, tiers, marketClass) => {
                if (!container) {
                    return;
                }

                if (!Array.isArray(tiers) || tiers.length === 0) {
                    container.innerHTML = '<p class="text-sm text-stone-500">Select a product to view pricing.</p>';
                    return;
                }

                const currency = getActiveCurrency();
                const rate = currencyRates[currency] ?? 1;

                container.innerHTML = tiers.map((tier) => `
                    <div class="rounded-[1rem] border ${marketClass === 'blue' ? 'border-blue-100 bg-blue-50/30' : 'border-amber-100 bg-amber-50/30'} p-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Group Size</p>
                        <p class="mt-1 text-sm font-semibold text-stone-900">${tier.label || 'Per person'}</p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-sm text-stone-500">Adult</p>
                                <p class="mt-1 text-lg font-semibold text-stone-900">${formatPrice(Number(tier.adult_price || 0) * rate, currency)}</p>
                            </div>
                            <div>
                                <p class="text-sm text-stone-500">Child</p>
                                <p class="mt-1 text-lg font-semibold text-stone-900">${formatPrice(Number(tier.child_price || 0) * rate, currency)}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            };

            const updateMarketPriceCards = () => {
                const selectedOption = getSelectedOption();
                const pricing = getSelectedProductPricing();

                if (!selectedOption || !selectedOption.value) {
                    renderMarketPricingTiers(malaysiaTiersContainer, [], 'blue');
                    renderMarketPricingTiers(internationalTiersContainer, [], 'amber');
                    return;
                }

                renderMarketPricingTiers(malaysiaTiersContainer, pricing.malaysia || [], 'blue');
                renderMarketPricingTiers(internationalTiersContainer, pricing.international || [], 'amber');
            };

            const updateBookingEstimate = () => {
                if (
                    !currencySelector
                    || !malaysiaTotal
                    || !malaysiaCount
                    || !internationalTotal
                    || !internationalCount
                    || !grandTotal
                    || !grandTotalMyr
                ) {
                    return;
                }

                const selectedOption = getSelectedOption();

                if (!selectedOption || !selectedOption.value) {
                    const activeCurrency = getActiveCurrency();
                    if (packageDuration) {
                        packageDuration.textContent = 'Select a product to view duration';
                    }
                    updateMarketPriceCards();
                    malaysiaTotal.textContent = formatPrice(0, activeCurrency);
                    malaysiaCount.textContent = '0 guests';
                    internationalTotal.textContent = formatPrice(0, activeCurrency);
                    internationalCount.textContent = '0 guests';
                    grandTotal.textContent = formatPrice(0, activeCurrency);
                    grandTotalMyr.textContent = 'Base MYR total: RM 0.00';
                    return;
                }

                if (packageDuration) {
                    packageDuration.textContent = selectedOption.dataset.duration || 'Duration will be confirmed by our team';
                }

                syncBookingCurrency();
                updateMarketPriceCards();

                const malaysiaAdults = getCount(malaysiaAdultsInput);
                const malaysiaKids = getCount(malaysiaKidsInput);
                const internationalAdults = getCount(internationalAdultsInput);
                const internationalKids = getCount(internationalKidsInput);
                const malaysiaGuestCountValue = malaysiaAdults + malaysiaKids;
                const internationalGuestCountValue = internationalAdults + internationalKids;
                const pricing = getSelectedProductPricing();
                const selectedMalaysiaTier = resolvePricingTierForGuestCount(pricing.malaysia || [], malaysiaGuestCountValue);
                const selectedInternationalTier = resolvePricingTierForGuestCount(pricing.international || [], internationalGuestCountValue);
                const malaysiaAdultRate = Number(selectedMalaysiaTier?.adult_price ?? selectedOption.dataset.malaysiaAdult ?? 0);
                const malaysiaChildRate = Number(selectedMalaysiaTier?.child_price ?? selectedOption.dataset.malaysiaChild ?? 0);
                const internationalAdultRate = Number(selectedInternationalTier?.adult_price ?? selectedOption.dataset.internationalAdult ?? 0);
                const internationalChildRate = Number(selectedInternationalTier?.child_price ?? selectedOption.dataset.internationalChild ?? 0);
                const malaysiaSubtotalMyr = (malaysiaAdults * malaysiaAdultRate)
                    + (malaysiaKids * malaysiaChildRate);
                const internationalSubtotalMyr = (internationalAdults * internationalAdultRate)
                    + (internationalKids * internationalChildRate);
                const totalMyr = malaysiaSubtotalMyr + internationalSubtotalMyr;
                const currency = getActiveCurrency();
                const rate = currencyRates[currency] ?? 1;

                malaysiaTotal.textContent = formatPrice(malaysiaSubtotalMyr * rate, currency);
                malaysiaCount.textContent = selectedMalaysiaTier?.label
                    ? `${formatGuestLabel(malaysiaAdults, malaysiaKids)} · ${selectedMalaysiaTier.label} rate`
                    : formatGuestLabel(malaysiaAdults, malaysiaKids);
                internationalTotal.textContent = formatPrice(internationalSubtotalMyr * rate, currency);
                internationalCount.textContent = selectedInternationalTier?.label
                    ? `${formatGuestLabel(internationalAdults, internationalKids)} · ${selectedInternationalTier.label} rate`
                    : formatGuestLabel(internationalAdults, internationalKids);
                grandTotal.textContent = formatPrice(totalMyr * rate, currency);
                grandTotalMyr.textContent = `Base MYR total: ${formatPrice(totalMyr, 'MYR')}`;
            };

            [productSelector, currencySelector, navbarCurrencySelector, malaysiaAdultsInput, malaysiaKidsInput, internationalAdultsInput, internationalKidsInput].forEach((element) => {
                if (!element) {
                    return;
                }

                element.addEventListener('change', updateBookingEstimate);
                element.addEventListener('input', updateBookingEstimate);
            });

            productSelector?.addEventListener('change', syncServiceTypeToProduct);
            navbarCurrencySelector?.addEventListener('change', () => {
                syncBookingCurrency();
                updateBookingEstimate();
            });
            document.addEventListener('ueh:currencychange', () => {
                syncBookingCurrency();
                updateBookingEstimate();
            });
            syncBookingCurrency();
            syncServiceTypeToProduct();
            updateBookingEstimate();

            const bookingPurposeInputs = Array.from(document.querySelectorAll('input[name="booking_purpose"]'));
            const bookingPurposePanels = Array.from(document.querySelectorAll('[data-booking-purpose-panel]'));
            const bookingPurposeCards = Array.from(document.querySelectorAll('[data-booking-purpose-card]'));
            const bookingPersonalDetails = document.querySelector('[data-booking-personal-details]');
            const identityDocumentInput = document.getElementById('identity_document_number');
            const companyNumberInput = document.getElementById('company_number');
            const fullNameInput = document.getElementById('full_name');
            const emailInput = document.getElementById('email');
            const phoneCountryCodeInput = document.getElementById('phone_country_code');
            const phoneLocalNumberInput = document.getElementById('phone_local_number');
            const pickupLocationInput = document.getElementById('pickup_location');

            const syncBookingPurposePanels = () => {
                const selectedPurpose = bookingPurposeInputs.find((input) => input.checked)?.value || '';

                if (bookingPersonalDetails) {
                    bookingPersonalDetails.classList.toggle('hidden', selectedPurpose === '');
                }

                bookingPurposePanels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.bookingPurposePanel !== selectedPurpose);
                });

                bookingPurposeCards.forEach((card) => {
                    const isSelected = card.dataset.bookingPurposeCard === selectedPurpose;
                    card.dataset.selected = isSelected ? 'true' : 'false';
                    card.classList.toggle('border-emerald-600', isSelected);
                    card.classList.toggle('bg-emerald-600', isSelected);
                    card.classList.toggle('text-white', isSelected);
                    card.classList.toggle('shadow-[0_16px_30px_rgba(5,150,105,0.28)]', isSelected);
                    card.classList.toggle('border-stone-300', !isSelected);
                    card.classList.toggle('bg-white', !isSelected);
                    card.classList.toggle('text-stone-700', !isSelected);
                    card.classList.toggle('shadow-sm', !isSelected);
                });

                if (fullNameInput) fullNameInput.required = selectedPurpose !== '';
                if (emailInput) emailInput.required = selectedPurpose !== '';
                if (phoneCountryCodeInput) phoneCountryCodeInput.required = selectedPurpose !== '';
                if (phoneLocalNumberInput) phoneLocalNumberInput.required = selectedPurpose !== '';
                if (pickupLocationInput) pickupLocationInput.required = selectedPurpose !== '';
                if (identityDocumentInput) identityDocumentInput.required = selectedPurpose === 'leisure';
                if (companyNumberInput) companyNumberInput.required = selectedPurpose === 'business';
            };

            bookingPurposeInputs.forEach((input) => {
                input.addEventListener('change', syncBookingPurposePanels);
            });

            bookingPurposeCards.forEach((card) => {
                card.addEventListener('click', () => {
                    const matchingInput = bookingPurposeInputs.find((input) => input.value === card.dataset.bookingPurposeCard);

                    if (!matchingInput) {
                        return;
                    }

                    matchingInput.checked = true;
                    matchingInput.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            syncBookingPurposePanels();

            const datePicker = document.querySelector('[data-date-picker]');

            if (!datePicker) {
                return;
            }

            const startInput = datePicker.querySelector('#check_in_date');
            const endInput = datePicker.querySelector('#check_out_date');
            const monthLabel = datePicker.querySelector('[data-date-month]');
            const yearLabel = datePicker.querySelector('[data-date-year]');
            const dateGrid = datePicker.querySelector('[data-date-grid]');
            const dateLabel = datePicker.querySelector('[data-date-label]');
            const dateHint = datePicker.querySelector('[data-date-hint]');
            const prevButton = datePicker.querySelector('[data-date-nav="prev"]');
            const nextButton = datePicker.querySelector('[data-date-nav="next"]');

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const parseDate = (value) => {
                if (!value) {
                    return null;
                }

                const [year, month, day] = value.split('-').map(Number);

                if (!year || !month || !day) {
                    return null;
                }

                return new Date(year, month - 1, day);
            };

            const formatValue = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            };

            const formatDisplay = (date) => new Intl.DateTimeFormat('en-MY', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }).format(date);

            const addDays = (date, days) => {
                const nextDate = new Date(date);
                nextDate.setDate(nextDate.getDate() + days);
                return nextDate;
            };

            const isSameDay = (first, second) => first
                && second
                && first.getFullYear() === second.getFullYear()
                && first.getMonth() === second.getMonth()
                && first.getDate() === second.getDate();

            let startDate = parseDate(startInput.value || datePicker.dataset.start);
            let visibleMonth = startDate
                ? new Date(startDate.getFullYear(), startDate.getMonth(), 1)
                : new Date(today.getFullYear(), today.getMonth(), 1);

            const updateSummary = () => {
                if (startDate) {
                    const durationLabel = getSelectedDurationLabel();
                    const durationDays = getSelectedDurationDays();
                    const endDate = durationDays > 1 ? addDays(startDate, durationDays - 1) : startDate;

                    dateLabel.textContent = `${formatDisplay(startDate)} to ${formatDisplay(endDate)}`;
                    dateHint.textContent = durationLabel
                        ? ''
                        : 'Booking dates calculated from your selected start date';
                    return;
                }

                dateLabel.textContent = 'Select your travel date';
                dateHint.textContent = 'Pick one start date from the calendar';
            };

            const syncInputs = () => {
                startInput.value = startDate ? formatValue(startDate) : '';
                if (startDate) {
                    const durationDays = getSelectedDurationDays();
                    const endDate = durationDays > 1 ? addDays(startDate, durationDays - 1) : startDate;
                    endInput.value = formatValue(endDate);
                } else {
                    endInput.value = '';
                }
                updateSummary();
            };

            const renderCalendar = () => {
                monthLabel.textContent = new Intl.DateTimeFormat('en-MY', {
                    month: 'long',
                }).format(visibleMonth);
                yearLabel.textContent = new Intl.DateTimeFormat('en-MY', {
                    year: 'numeric',
                }).format(visibleMonth);

                dateGrid.innerHTML = '';

                const year = visibleMonth.getFullYear();
                const month = visibleMonth.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const leadingDays = (firstDay.getDay() + 6) % 7;
                const totalCells = Math.ceil((leadingDays + lastDay.getDate()) / 7) * 7;

                for (let cellIndex = 0; cellIndex < totalCells; cellIndex += 1) {
                    const dayNumber = cellIndex - leadingDays + 1;
                    const currentDate = new Date(year, month, dayNumber);
                    const inMonth = currentDate.getMonth() === month;
                    const isPast = currentDate < today;
                    const selectedStart = isSameDay(currentDate, startDate);
                    const dayItem = document.createElement('li');

                    if (!inMonth) {
                        const placeholder = document.createElement('span');
                        placeholder.className = 'calendar-day-placeholder';
                        dayItem.appendChild(placeholder);
                        dateGrid.appendChild(dayItem);
                        continue;
                    }

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = String(currentDate.getDate());
                    button.className = 'calendar-day-button';

                    if (isPast) {
                        button.classList.add('muted');
                        button.disabled = true;
                    } else if (selectedStart) {
                        button.classList.add('active');
                    }

                    if (!isPast) {
                        button.addEventListener('click', () => {
                            startDate = currentDate;
                            syncInputs();
                            renderCalendar();
                        });
                    }

                    dayItem.appendChild(button);
                    dateGrid.appendChild(dayItem);
                }
            };

            prevButton.addEventListener('click', () => {
                const previousMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() - 1, 1);
                const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);

                if (previousMonth >= currentMonth) {
                    visibleMonth = previousMonth;
                    renderCalendar();
                }
            });

            nextButton.addEventListener('click', () => {
                visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 1);
                renderCalendar();
            });

            productSelector?.addEventListener('change', syncInputs);

            syncInputs();
            renderCalendar();
        });
    </script>
</x-layouts.app>
