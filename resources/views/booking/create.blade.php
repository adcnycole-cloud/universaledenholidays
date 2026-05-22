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
    @endphp
    <main class="mx-auto max-w-[96rem] px-5 py-8 lg:px-10">
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
                @endif
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="product_id" class="mb-2 block text-sm font-medium text-stone-700">Service / Product <span class="text-rose-600">*</span></label>
                        @if ($isProductLocked && $selectedProduct)
                            <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">
                            <select id="product_id" class="w-full rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                <option
                                    value="{{ $selectedProduct->id }}"
                                    data-category="{{ $selectedProduct->category }}"
                                    data-name="{{ $selectedProduct->name }}"
                                    data-duration="{{ $selectedProduct->duration }}"
                                    data-malaysia-adult="{{ $selectedProduct->discounted_malaysia_adult_price_myr }}"
                                    data-malaysia-child="{{ $selectedProduct->discounted_malaysia_child_price_myr }}"
                                    data-international-adult="{{ $selectedProduct->discounted_international_adult_price_myr }}"
                                    data-international-child="{{ $selectedProduct->discounted_international_child_price_myr }}"
                                    selected
                                >{{ $selectedProduct->name }} - {{ ucfirst($selectedProduct->category) }}</option>
                            </select>
                            <p class="mt-2 text-xs text-stone-500">This booking is locked to the product you selected from the previous page.</p>
                        @else
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
                                        @selected(old('product_id') == $product->id || ($selectedProduct && $selectedProduct->id == $product->id))
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
                                        @selected(old('product_id') == $product->id || ($selectedProduct && $selectedProduct->id == $product->id))
                                    >{{ $product->name }} - Package</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div>
                        <label for="service_type" class="mb-2 block text-sm font-medium text-stone-700">{{ $isEnquiry ? 'Enquiry type' : ($isReserveForm ? 'Reserve type' : 'Booking type') }} <span class="text-rose-600">*</span></label>
                        @if ($isProductLocked && $selectedProduct)
                            <input type="hidden" name="service_type" value="{{ $selectedProduct->category }}">
                            <select id="service_type" class="w-full rounded-2xl border border-stone-300 bg-stone-100 px-4 py-3 text-stone-800" disabled>
                                <option value="{{ $selectedProduct->category }}" selected>
                                    {{ $selectedProduct->category === 'transport' ? 'Transport service' : 'Travel package' }}
                                </option>
                            </select>
                        @else
                            <select id="service_type" name="service_type" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                <option value="transport" @selected($selectedServiceType === 'transport')>Transport service</option>
                                <option value="package" @selected($selectedServiceType === 'package')>Travel package</option>
                            </select>
                        @endif
                    </div>
                </div>

                @if ($isEnquiry)
                <div class="grid gap-6 lg:grid-cols-2 lg:items-start">
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

                <div class="rounded-[1.5rem] bg-white p-4">
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
                        <div class="mt-3">
                            <label for="pickup_location" class="mb-2 block text-sm font-medium text-stone-700">Pickup location <span class="text-rose-600">*</span></label>
                            <select id="pickup_location" name="pickup_location" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                <option value="">Select pickup location</option>
                                @foreach ($pickupLocations as $value => $label)
                                    <option value="{{ $value }}" @selected(old('pickup_location') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50/70 p-4 shadow-sm">
                        <div class="mb-4 rounded-[1.25rem] border border-sky-200 bg-sky-50/80 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700">Selected Package Duration</p>
                            <p id="booking-package-duration" class="mt-2 text-lg font-semibold text-stone-900">{{ $selectedProduct?->duration ?: 'Select a product to view duration' }}</p>
                            <p class="mt-1 text-xs leading-5 text-stone-500">Shown from the package or service you selected for this booking or reserve request.</p>
                        </div>
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
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm text-stone-500">Adult</p>
                                    <p class="mt-1 text-xl font-semibold text-stone-900" id="booking-malaysia-adult">
                                        {{ $selectedProduct ? 'RM '.number_format((float) $selectedProduct->discounted_malaysia_adult_price_myr, 2) : '--' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-stone-500">Child</p>
                                    <p class="mt-1 text-xl font-semibold text-stone-900" id="booking-malaysia-child">
                                        {{ $selectedProduct ? 'RM '.number_format((float) $selectedProduct->discounted_malaysia_child_price_myr, 2) : '--' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-[1.25rem] border border-amber-200 bg-white p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">International Market</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm text-stone-500">Adult</p>
                                    <p class="mt-1 text-xl font-semibold text-stone-900" id="booking-international-adult">
                                        {{ $selectedProduct ? 'RM '.number_format((float) $selectedProduct->discounted_international_adult_price_myr, 2) : '--' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-stone-500">Child</p>
                                    <p class="mt-1 text-xl font-semibold text-stone-900" id="booking-international-child">
                                        {{ $selectedProduct ? 'RM '.number_format((float) $selectedProduct->discounted_international_child_price_myr, 2) : '--' }}
                                    </p>
                                </div>
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

                <div class="flex flex-col gap-3 pt-1 sm:flex-row">
                    <button type="submit" class="flex-1 rounded-full bg-sky-600 px-5 py-2.5 text-sm font-semibold uppercase tracking-[0.22em] text-white transition hover:bg-sky-700">{{ $bookingSubmitLabel }}</button>
                    <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                        Cancel
                    </a>
                </div>
                @endif

            </form>
        </div>

        <section class="mt-8 rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
            <div class="grid gap-6 lg:grid-cols-[1fr_1fr]">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">What Happens Next</p>
                    <h2 class="mt-2 text-xl font-semibold text-stone-900">{{ $isEnquiry ? 'Your enquiry process' : ($isReserveForm ? 'Your reserve process' : 'Your booking process') }}</h2>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-stone-600">
                        <li class="flex gap-3">
                            <span class="flex min-h-6 min-w-6 items-center justify-center rounded-full bg-sky-100 text-xs font-semibold text-sky-700">1</span>
                            <span>{{ $isEnquiry ? 'Submit your enquiry with the product you want and the details you already know.' : ($isReserveForm ? 'Submit your reserve form with product, guest details, and preferred travel dates.' : 'Submit your booking form with product, guest details, and travel dates.') }}</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex min-h-6 min-w-6 items-center justify-center rounded-full bg-sky-100 text-xs font-semibold text-sky-700">2</span>
                            <span>{{ $isEnquiry ? 'Our team reviews your request and replies with availability, recommendations, or clarifications.' : ($isReserveForm ? 'Our team reviews your reserve request and confirms the next steps with availability details.' : 'Our team reviews your request and sends a confirmation with pricing details.') }}</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex min-h-6 min-w-6 items-center justify-center rounded-full bg-sky-100 text-xs font-semibold text-sky-700">3</span>
                            <span>{{ $isEnquiry ? 'If you want to proceed, we will guide you into the booking step with the right details.' : 'You will receive a Booking ID by email right after submission.' }}</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex min-h-6 min-w-6 items-center justify-center rounded-full bg-sky-100 text-xs font-semibold text-sky-700">4</span>
                            <span>{{ $isEnquiry ? 'Our Sabah team follows up to help you choose the right transport service or package.' : 'Use your Booking ID to track details, confirm the booking, and continue to sandbox payment.' }}</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Need Help?</p>
                    <h2 class="mt-2 text-xl font-semibold text-stone-900">{{ $isEnquiry ? 'Enquiry support' : ($isReserveForm ? 'Reserve support' : 'Booking support') }}</h2>
                    <div class="mt-4 space-y-3 text-sm text-stone-600">
                        <div>
                            <p class="font-semibold text-stone-900">Product Questions</p>
                            <p>Unsure which package or transport option suits you? Browse our recommendations on the homepage.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-stone-900">Pricing Clarity</p>
                            <p>Check the product detail page for Malaysia and international market pricing tiers.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-stone-900">Payment Methods</p>
                            <p>We accept credit cards, bank transfers, e-wallets, and pay-at-counter options.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-stone-900">Changes After Booking</p>
                            <p>Contact our team to modify dates, guests, or special requests after submission.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-stone-200/80 bg-stone-950 text-stone-200">
        <div class="mx-auto grid max-w-[1700px] gap-8 px-6 py-12 lg:grid-cols-[1.2fr_0.8fr_0.8fr] lg:px-10">
            <div>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10">
                    <div>
                        <p class="font-['Prata'] text-xl text-white">Universal Eden Holidays</p>
                        <p class="text-sm uppercase tracking-[0.28em] text-sky-200/80">Sabah Packages & Transport</p>
                    </div>
                </div>
                <p class="mt-4 max-w-xl text-sm leading-7 text-stone-300">
                    Plan your Sabah trip with transport services, curated travel packages, and support from our local team. Use this form to send an enquiry, reserve your slot, or complete a booking request.
                </p>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.26em] text-white">Explore</p>
                <div class="mt-4 space-y-3 text-sm text-stone-300">
                    <a href="{{ route('home') }}#promos" class="block transition hover:text-white">Promos</a>
                    <a href="{{ route('home') }}#transport" class="block transition hover:text-white">Transport</a>
                    <a href="{{ route('home') }}#packages-showcase" class="block transition hover:text-white">Packages</a>
                    <a href="{{ route('home') }}#testimonials" class="block transition hover:text-white">Testimonials</a>
                    <a href="{{ route('home') }}#about-us" class="block transition hover:text-white">About Us</a>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.26em] text-white">Booking Help</p>
                <div class="mt-4 space-y-3 text-sm text-stone-300">
                    <a href="{{ route('bookings.track.form') }}" class="block transition hover:text-white">Track Booking ID</a>
                    <a href="{{ route('home') }}" class="block transition hover:text-white">Back to Home</a>
                    <p>Support hours: 9:00 AM - 6:00 PM</p>
                    <p>Email: support@universaledenholidays.com</p>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 px-6 py-4 text-center text-xs uppercase tracking-[0.22em] text-stone-400 lg:px-10">
            <p>Adcey &copy; Universal Eden Holidays - {{ now()->year }}</p>
        </div>
    </footer>

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
            const malaysiaAdultsInput = document.querySelector('#malaysian_adults');
            const malaysiaKidsInput = document.querySelector('#malaysian_kids');
            const internationalAdultsInput = document.querySelector('#international_adults');
            const internationalKidsInput = document.querySelector('#international_kids');
            const malaysiaAdultPrice = document.querySelector('#booking-malaysia-adult');
            const malaysiaChildPrice = document.querySelector('#booking-malaysia-child');
            const internationalAdultPrice = document.querySelector('#booking-international-adult');
            const internationalChildPrice = document.querySelector('#booking-international-child');
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

            const getSelectedOption = () => {
                if (!productSelector) {
                    return null;
                }

                return productSelector.options[productSelector.selectedIndex] || null;
            };

            const getSelectedDurationLabel = () => getSelectedOption()?.dataset.duration || '';

            const getSelectedDurationDays = () => {
                const durationLabel = getSelectedDurationLabel();
                const durationMatch = durationLabel.match(/(\d+)\s*day/i);

                return durationMatch ? Number(durationMatch[1]) : 0;
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

            const updateMarketPriceCards = () => {
                const selectedOption = getSelectedOption();
                const currency = getActiveCurrency();
                const rate = currencyRates[currency] ?? 1;

                if (!selectedOption || !selectedOption.value) {
                    if (malaysiaAdultPrice) {
                        malaysiaAdultPrice.textContent = '--';
                    }
                    if (malaysiaChildPrice) {
                        malaysiaChildPrice.textContent = '--';
                    }
                    if (internationalAdultPrice) {
                        internationalAdultPrice.textContent = '--';
                    }
                    if (internationalChildPrice) {
                        internationalChildPrice.textContent = '--';
                    }

                    return;
                }

                if (malaysiaAdultPrice) {
                    malaysiaAdultPrice.textContent = formatPrice(Number(selectedOption.dataset.malaysiaAdult || 0) * rate, currency);
                }
                if (malaysiaChildPrice) {
                    malaysiaChildPrice.textContent = formatPrice(Number(selectedOption.dataset.malaysiaChild || 0) * rate, currency);
                }
                if (internationalAdultPrice) {
                    internationalAdultPrice.textContent = formatPrice(Number(selectedOption.dataset.internationalAdult || 0) * rate, currency);
                }
                if (internationalChildPrice) {
                    internationalChildPrice.textContent = formatPrice(Number(selectedOption.dataset.internationalChild || 0) * rate, currency);
                }
            };

            const updateBookingEstimate = () => {
                if (
                    !productSelector
                    || !currencySelector
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
                    if (packageDuration) {
                        packageDuration.textContent = 'Select a product to view duration';
                    }
                    updateMarketPriceCards();
                    malaysiaTotal.textContent = 'RM 0.00';
                    malaysiaCount.textContent = '0 guests';
                    internationalTotal.textContent = 'RM 0.00';
                    internationalCount.textContent = '0 guests';
                    grandTotal.textContent = 'RM 0.00';
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
                const malaysiaSubtotalMyr = (malaysiaAdults * Number(selectedOption.dataset.malaysiaAdult || 0))
                    + (malaysiaKids * Number(selectedOption.dataset.malaysiaChild || 0));
                const internationalSubtotalMyr = (internationalAdults * Number(selectedOption.dataset.internationalAdult || 0))
                    + (internationalKids * Number(selectedOption.dataset.internationalChild || 0));
                const totalMyr = malaysiaSubtotalMyr + internationalSubtotalMyr;
                const currency = getActiveCurrency();
                const rate = currencyRates[currency] ?? 1;

                malaysiaTotal.textContent = formatPrice(malaysiaSubtotalMyr * rate, currency);
                malaysiaCount.textContent = formatGuestLabel(malaysiaAdults, malaysiaKids);
                internationalTotal.textContent = formatPrice(internationalSubtotalMyr * rate, currency);
                internationalCount.textContent = formatGuestLabel(internationalAdults, internationalKids);
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
            syncBookingCurrency();
            syncServiceTypeToProduct();
            updateBookingEstimate();

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
                    const endDate = durationDays > 0 ? addDays(startDate, durationDays) : startDate;

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
                    const endDate = durationDays > 0 ? addDays(startDate, durationDays) : startDate;
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
