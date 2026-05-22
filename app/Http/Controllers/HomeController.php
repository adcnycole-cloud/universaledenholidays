<?php

namespace App\Http\Controllers;

use App\Mail\BookingReferenceMail;
use App\Models\Booking;
use App\Models\NewsFeature;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const PHONE_COUNTRY_CODES = [
        '+60' => 'Malaysia (+60)',
        '+65' => 'Singapore (+65)',
        '+82' => 'South Korea (+82)',
        '+1' => 'United States / Canada (+1)',
        '+86' => 'China (+86)',
    ];

    private const SUPPORTED_PHONE_PATTERNS = [
        '60' => '/^(?:60)(?:1\d{8,9}|[3-9]\d{7,8})$/',
        '65' => '/^(?:65)(?:[3689]\d{7})$/',
        '82' => '/^(?:82)(?:1\d{8,9}|[2-6]\d{7,9})$/',
        '1' => '/^(?:1)(?:[2-9]\d{2}[2-9]\d{6})$/',
        '86' => '/^(?:86)(?:1[3-9]\d{9})$/',
    ];

    private const PICKUP_LOCATIONS = [
        'KKIA' => 'KKIA',
        'Universal Motor Sdn Bhd' => 'Universal Motor Sdn Bhd',
        'KK Terminal' => 'KK Terminal',
    ];

    private const CURRENCY_RATES = [
        'MYR' => 1,
        'KRW' => 308.50,
        'USD' => 0.21,
        'SGD' => 0.28,
        'CNY' => 1.716,
    ];

    private const CURRENCY_SYMBOLS = [
        'MYR' => 'RM',
        'KRW' => 'KRW ',
        'USD' => '$',
        'SGD' => 'S$',
        'CNY' => 'CNY ',
    ];

    public function index(): View
    {
        return view('home', $this->sharedPageData());
    }

    public function showBookingForm(Request $request): View
    {
        $selectedProductId = $request->query('product_id');
        $formMode = $request->query('mode') === 'enquiry' ? 'enquiry' : 'booking';
        $actionType = $request->query('action');
        $products = Product::where('is_active', true)->orderBy('category')->orderBy('price_myr')->get();

        $selectedProduct = null;
        if ($selectedProductId) {
            $selectedProduct = Product::findOrFail($selectedProductId);
        }

        return view('booking.create', [
            'transportServices' => $products->where('category', 'transport')->values(),
            'travelPackages' => $products->where('category', 'package')->values(),
            'selectedProduct' => $selectedProduct,
            'isProductLocked' => $selectedProduct !== null,
            'formMode' => $formMode,
            'actionType' => in_array($actionType, ['reserve', 'instant_book', 'book_now'], true) ? $actionType : null,
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
            'phoneCountryCodes' => self::PHONE_COUNTRY_CODES,
            'pickupLocations' => self::PICKUP_LOCATIONS,
        ]);
    }

    public function showProduct(Product $product): View
    {
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category', 'package')
            ->where('category', $product->category)
            ->take(3)
            ->get();

        $recommendedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category', 'package')
            ->where('is_featured', true)
            ->take(3)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'recommendedProducts' => $recommendedProducts,
            'testimonials' => Testimonial::where('display_location', 'package')
                ->where('product_id', $product->id)
                ->where('is_featured', true)
                ->orderByDesc('rating')
                ->take(3)
                ->get(),
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
            'malaysiaPricingTiers' => $this->buildPricingTiers(
                (float) $product->discounted_malaysia_adult_price_myr,
                (float) $product->discounted_malaysia_child_price_myr,
                (float) $product->malaysia_adult_price_myr,
                (float) $product->malaysia_child_price_myr,
            ),
            'internationalPricingTiers' => $this->buildPricingTiers(
                (float) $product->discounted_international_adult_price_myr,
                (float) $product->discounted_international_child_price_myr,
                (float) $product->international_adult_price_myr,
                (float) $product->international_child_price_myr,
            ),
        ]);
    }

    public function storeLandingTestimonial(Request $request): RedirectResponse
    {
        $this->storePublicTestimonial($request, null);

        return redirect()->to(route('home').'#testimonials')->with(
            'success',
            'Thanks for sharing your review. Our team will check it before publishing it on the landing page.',
        );
    }

    public function storeProductTestimonial(Request $request, Product $product): RedirectResponse
    {
        abort_if($product->category !== 'package', 404);

        $this->storePublicTestimonial($request, $product);

        return redirect()->to(route('products.show', $product).'#reviews')->with(
            'success',
            'Thanks for sharing your package review. Our team will check it before publishing it on this page.',
        );
    }

    private function buildPricingTiers(
        float $adultPrice,
        float $childPrice,
        ?float $originalAdultPrice = null,
        ?float $originalChildPrice = null,
    ): array
    {
        return [
            [
                'label' => 'I have a Group of 16 - 29 Pax',
                'adult_price' => round($adultPrice * 0.78, 2),
                'child_price' => round($childPrice * 0.78, 2),
                'original_adult_price' => $originalAdultPrice !== null ? round($originalAdultPrice * 0.78, 2) : null,
                'original_child_price' => $originalChildPrice !== null ? round($originalChildPrice * 0.78, 2) : null,
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 7 - 15 Pax',
                'adult_price' => round($adultPrice * 0.84, 2),
                'child_price' => round($childPrice * 0.84, 2),
                'original_adult_price' => $originalAdultPrice !== null ? round($originalAdultPrice * 0.84, 2) : null,
                'original_child_price' => $originalChildPrice !== null ? round($originalChildPrice * 0.84, 2) : null,
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 4 - 6 Pax',
                'adult_price' => round($adultPrice * 0.9, 2),
                'child_price' => round($childPrice * 0.9, 2),
                'original_adult_price' => $originalAdultPrice !== null ? round($originalAdultPrice * 0.9, 2) : null,
                'original_child_price' => $originalChildPrice !== null ? round($originalChildPrice * 0.9, 2) : null,
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 2 - 3 Pax',
                'adult_price' => round($adultPrice * 0.96, 2),
                'child_price' => round($childPrice * 0.96, 2),
                'original_adult_price' => $originalAdultPrice !== null ? round($originalAdultPrice * 0.96, 2) : null,
                'original_child_price' => $originalChildPrice !== null ? round($originalChildPrice * 0.96, 2) : null,
                'enquire' => false,
            ],
            [
                'label' => 'I am a Single Traveler',
                'adult_price' => round($adultPrice, 2),
                'child_price' => round($childPrice, 2),
                'original_adult_price' => $originalAdultPrice !== null ? round($originalAdultPrice, 2) : null,
                'original_child_price' => $originalChildPrice !== null ? round($originalChildPrice, 2) : null,
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 30 Pax & Above',
                'adult_price' => null,
                'child_price' => null,
                'original_adult_price' => null,
                'original_child_price' => null,
                'enquire' => true,
            ],
        ];
    }

    private function sharedPageData(): array
    {
        $products = Product::where('is_active', true)->orderBy('category')->orderBy('price_myr')->get();
        $travelPackages = $products->where('category', 'package')->values();
        $popularPackages = $travelPackages->where('is_featured', true)->take(3)->values();

        if ($popularPackages->count() < 3) {
            $popularPackages = $travelPackages->take(3)->values();
        }

        $activeNewsQuery = NewsFeature::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhereDate('starts_at', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhereDate('ends_at', '>=', now()->toDateString());
            });

        $currentPromo = (clone $activeNewsQuery)->latest()->first();
        $pastPromos = NewsFeature::query()
            ->whereNotNull('ends_at')
            ->whereDate('ends_at', '<', now()->toDateString())
            ->latest('ends_at')
            ->take(12)
            ->get();

        return [
            'transportServices' => $products->where('category', 'transport')->values(),
            'travelPackages' => $travelPackages,
            'popularPackages' => $popularPackages,
            'currentPromo' => $currentPromo,
            'pastPromo' => $pastPromos->first(),
            'pastPromos' => $pastPromos,
            'newsFeatures' => (clone $activeNewsQuery)->latest()->take(6)->get(),
            'testimonials' => Testimonial::where('display_location', 'landing')
                ->where('is_featured', true)
                ->orderByDesc('rating')
                ->get(),
            'recentBookings' => Booking::with('product')->latest()->take(5)->get(),
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
        ];
    }

    public function book(Request $request): RedirectResponse
    {
        $request->merge([
            'phone' => $this->composePhoneNumber(
                (string) $request->input('phone_country_code', '+60'),
                (string) $request->input('phone_local_number', $request->input('phone', '')),
            ),
        ]);

        $formMode = $request->input('form_mode') === 'enquiry' ? 'enquiry' : 'booking';

        $baseRules = [
            'product_id' => ['required', 'exists:products,id'],
            'service_type' => ['required', 'in:transport,package'],
            'action_type' => ['nullable', 'in:reserve,instant_book,book_now'],
            'locked_product_id' => ['nullable', 'exists:products,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email:rfc,dns'],
            'phone_country_code' => ['required', 'string', 'in:'.implode(',', array_keys(self::PHONE_COUNTRY_CODES))],
            'phone_local_number' => ['required', 'string', 'max:20'],
            'phone' => $this->phoneRules(),
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ];

        $bookingRules = [
            'pickup_location' => ['required', 'in:KKIA,Universal Motor Sdn Bhd,KK Terminal'],
            'malaysian_adults' => ['required', 'integer', 'min:0', 'max:50'],
            'malaysian_kids' => ['required', 'integer', 'min:0', 'max:50'],
            'international_adults' => ['required', 'integer', 'min:0', 'max:50'],
            'international_kids' => ['required', 'integer', 'min:0', 'max:50'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after_or_equal:check_in_date'],
            'payment_method' => ['required', 'in:credit_card,bank_transfer,e_wallet,pay_at_counter'],
            'currency_code' => ['required', 'in:MYR,KRW,USD,SGD,CNY'],
        ];

        $enquiryRules = [
            'preferred_travel_date' => ['nullable', 'date', 'after_or_equal:today'],
            'estimated_guest_count' => ['nullable', 'integer', 'min:0', 'max:50'],
        ];

        $validated = $request->validate($baseRules + ($formMode === 'enquiry' ? $enquiryRules : $bookingRules));

        $product = Product::findOrFail($validated['product_id']);

        if (!empty($validated['locked_product_id']) && (int) $validated['locked_product_id'] !== $product->id) {
            return back()->withErrors([
                'product_id' => 'Please book the product you originally selected.',
            ])->withInput();
        }

        if (($validated['service_type'] ?? null) !== $product->category) {
            return back()->withErrors([
                'service_type' => 'The selected booking type does not match this product.',
            ])->withInput();
        }

        if ($formMode === 'enquiry') {
            $preferredDate = $validated['preferred_travel_date'] ?? now()->toDateString();
            $estimatedGuests = (int) ($validated['estimated_guest_count'] ?? 0);
            $enquiryNotes = trim(collect([
                $validated['special_requests'] ?? null,
                $estimatedGuests > 0 ? 'Estimated guests: '.$estimatedGuests : null,
                !empty($validated['preferred_travel_date']) ? 'Preferred travel date: '.$validated['preferred_travel_date'] : null,
            ])->filter()->implode("\n"));

            $booking = Booking::create([
                'user_id' => $request->user()?->id,
                'product_id' => $product->id,
                'booking_reference' => $this->generateUniqueBookingReference(),
                'service_type' => $validated['service_type'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'pickup_location' => null,
                'destination' => $product->location,
                'package_name' => $product->name,
                'malaysian_adults' => 0,
                'malaysian_kids' => 0,
                'international_adults' => 0,
                'international_kids' => 0,
                'guest_count' => $estimatedGuests,
                'check_in_date' => $preferredDate,
                'check_out_date' => $preferredDate,
                'special_requests' => $enquiryNotes ?: null,
                'payment_method' => 'bank_transfer',
                'currency_code' => 'MYR',
                'amount_myr' => 0,
                'amount_display' => 0,
                'status' => 'pending',
                'payment_status' => 'not_required',
            ]);

            return redirect()->route('home')->with('success', 'Your enquiry has been submitted. Our Sabah team will contact you shortly.');
        }

        $guestCount = (int) $validated['malaysian_adults']
            + (int) $validated['malaysian_kids']
            + (int) $validated['international_adults']
            + (int) $validated['international_kids'];

        if ($guestCount < 1) {
            return back()->withErrors([
                'malaysian_adults' => 'Please add at least one guest before submitting the booking.',
            ])->withInput();
        }

        $amountMyr =
            ((float) $product->discounted_malaysia_adult_price_myr * (int) $validated['malaysian_adults']) +
            ((float) $product->discounted_malaysia_child_price_myr * (int) $validated['malaysian_kids']) +
            ((float) $product->discounted_international_adult_price_myr * (int) $validated['international_adults']) +
            ((float) $product->discounted_international_child_price_myr * (int) $validated['international_kids']);

        $amountDisplay = $amountMyr * self::CURRENCY_RATES[$validated['currency_code']];

        $booking = Booking::create([
            'user_id' => $request->user()?->id,
            'product_id' => $product->id,
            'booking_reference' => $this->generateUniqueBookingReference(),
            'service_type' => $validated['service_type'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $product->location,
            'package_name' => $product->name,
            'malaysian_adults' => $validated['malaysian_adults'],
            'malaysian_kids' => $validated['malaysian_kids'],
            'international_adults' => $validated['international_adults'],
            'international_kids' => $validated['international_kids'],
            'guest_count' => $guestCount,
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'special_requests' => $validated['special_requests'] ?? null,
            'payment_method' => $validated['payment_method'],
            'currency_code' => $validated['currency_code'],
            'amount_myr' => $amountMyr,
            'amount_display' => $amountDisplay,
            'status' => 'pending',
            'payment_status' => 'awaiting_confirmation',
        ]);

        $bookingReferenceSent = $this->sendMailSafely(
            $booking->email,
            new BookingReferenceMail($booking->fresh()),
            'booking reference email',
            [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
            ],
        );

        $isReserveFlow = ($validated['action_type'] ?? null) === 'reserve';
        $successMessage = $isReserveFlow
            ? 'Your reserve request has been submitted. Your Booking ID is '.$booking->booking_reference.'. You can track and continue payment using this ID.'
            : 'Your booking request has been submitted. Your Booking ID is '.$booking->booking_reference.'. You can track and continue payment using this ID.';

        if (! $bookingReferenceSent) {
            $successMessage .= ' We could not send the confirmation email right now, but your booking was saved successfully.';
        }

        return redirect()->route('bookings.track.show', $booking->booking_reference)->with(
            'success',
            $successMessage
        );
    }

    public function storeLandingTestimonial(Request $request): RedirectResponse
    {
        $this->storePublicTestimonial($request, null);

        return redirect()->route('home')->with('success', 'Thank you for your review. Our team will verify it before publishing.');
    }

    public function storeProductTestimonial(Request $request, Product $product): RedirectResponse
    {
        if ($product->category !== 'package') {
            return redirect()->route('products.show', $product)
                ->withErrors(['product' => 'Reviews can only be submitted for package products.'])
                ->withInput();
        }

        $this->storePublicTestimonial($request, $product);

        return redirect()->route('products.show', $product)
            ->with('success', 'Thank you for your review. Our team will verify it before publishing.');
    }

    private function storePublicTestimonial(Request $request, ?Product $product): void
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email:rfc,dns'],
            'location' => ['required', 'string', 'max:255'],
            'trip_name' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('testimonial-profiles', 'public');
        }

        Testimonial::create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'trip_name' => $validated['trip_name'],
            'quote' => $validated['quote'],
            'rating' => $validated['rating'],
            'profile_photo_path' => $profilePhotoPath,
            'display_location' => $product ? 'package' : 'landing',
            'product_id' => $product?->id,
            'is_featured' => $product === null,
        ]);
    }

    private function sendMailSafely(string $email, object $mailable, string $mailType, array $context = []): bool
    {
        try {
            Mail::to($email)->send($mailable);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Unable to send '.$mailType.'.', $context + [
                'email' => $email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function storePublicTestimonial(Request $request, ?Product $product): void
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email:rfc,dns'],
            'location' => ['required', 'string', 'max:255'],
            'trip_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('testimonial-profiles', 'public');
        }

        Testimonial::create([
            ...$validated,
            'display_location' => $product ? 'package' : 'landing',
            'product_id' => $product?->id,
            'is_featured' => false,
            'profile_photo_path' => $profilePhotoPath,
        ]);
    }

    private function phoneRules(): array
    {
        return [
            'required',
            'string',
            'max:25',
            function (string $attribute, mixed $value, \Closure $fail) {
                $phone = trim((string) $value);

                if (! preg_match('/^\+[0-9][0-9\s\-()]{7,24}$/', $phone)) {
                    $fail('Please enter a valid phone number with a country code, for example +60 12-345 6789.');

                    return;
                }

                $digitsOnly = preg_replace('/\D+/', '', $phone) ?? '';
                $digitCount = strlen($digitsOnly);

                if ($digitCount < 8 || $digitCount > 15) {
                    $fail('Please enter a valid phone number between 8 and 15 digits.');

                    return;
                }

                if (preg_match('/^(\d)\1+$/', $digitsOnly) === 1) {
                    $fail('Please enter a real phone number, not a repeated-digit number.');

                    return;
                }

                $matchesSupportedCountry = collect(self::SUPPORTED_PHONE_PATTERNS)
                    ->contains(fn ($pattern) => preg_match($pattern, $digitsOnly) === 1);

                if (! $matchesSupportedCountry) {
                    $fail('Please enter a valid phone number with a supported country code and prefix.');
                }
            },
        ];
    }

    private function composePhoneNumber(string $countryCode, string $localNumber): string
    {
        $countryCode = array_key_exists($countryCode, self::PHONE_COUNTRY_CODES) ? $countryCode : '+60';
        $normalizedCountryCode = ltrim($countryCode, '+');
        $digitsOnly = preg_replace('/\D+/', '', $localNumber) ?? '';

        if ($digitsOnly === '') {
            return $countryCode;
        }

        if (str_starts_with($digitsOnly, $normalizedCountryCode)) {
            $digitsOnly = substr($digitsOnly, strlen($normalizedCountryCode));
        }

        if ($countryCode !== '+1') {
            $digitsOnly = ltrim($digitsOnly, '0');
        }

        return '+'.$normalizedCountryCode.$digitsOnly;
    }

    private function generateUniqueBookingReference(): string
    {
        do {
            $reference = 'UEH-'.Str::upper(Str::random(8));
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }
}
