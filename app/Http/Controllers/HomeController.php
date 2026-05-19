<?php

namespace App\Http\Controllers;

use App\Mail\CompleteBookingAccessMail;
use App\Models\Booking;
use App\Models\NewsFeature;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
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
            'pickupLocations' => self::PICKUP_LOCATIONS,
        ]);
    }

    public function showProduct(Product $product): View
    {
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->take(3)
            ->get();

        $recommendedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('is_featured', true)
            ->take(3)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'recommendedProducts' => $recommendedProducts,
            'testimonials' => Testimonial::where('is_featured', true)->take(3)->get(),
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
            'malaysiaPricingTiers' => $this->buildPricingTiers(
                (float) $product->malaysia_adult_price_myr,
                (float) $product->malaysia_child_price_myr,
            ),
            'internationalPricingTiers' => $this->buildPricingTiers(
                (float) $product->international_adult_price_myr,
                (float) $product->international_child_price_myr,
            ),
        ]);
    }

    private function buildPricingTiers(float $adultPrice, float $childPrice): array
    {
        return [
            [
                'label' => 'I have a Group of 16 - 29 Pax',
                'adult_price' => round($adultPrice * 0.78, 2),
                'child_price' => round($childPrice * 0.78, 2),
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 7 - 15 Pax',
                'adult_price' => round($adultPrice * 0.84, 2),
                'child_price' => round($childPrice * 0.84, 2),
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 4 - 6 Pax',
                'adult_price' => round($adultPrice * 0.9, 2),
                'child_price' => round($childPrice * 0.9, 2),
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 2 - 3 Pax',
                'adult_price' => round($adultPrice * 0.96, 2),
                'child_price' => round($childPrice * 0.96, 2),
                'enquire' => false,
            ],
            [
                'label' => 'I am a Single Traveler',
                'adult_price' => round($adultPrice, 2),
                'child_price' => round($childPrice, 2),
                'enquire' => false,
            ],
            [
                'label' => 'I have a Group of 30 Pax & Above',
                'adult_price' => null,
                'child_price' => null,
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
            'testimonials' => Testimonial::where('is_featured', true)->orderByDesc('rating')->get(),
            'recentBookings' => Booking::with('product')->latest()->take(5)->get(),
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
        ];
    }

    public function book(Request $request): RedirectResponse
    {
        $formMode = $request->input('form_mode') === 'enquiry' ? 'enquiry' : 'booking';

        $baseRules = [
            'product_id' => ['required', 'exists:products,id'],
            'service_type' => ['required', 'in:transport,package'],
            'action_type' => ['nullable', 'in:reserve,instant_book,book_now'],
            'locked_product_id' => ['nullable', 'exists:products,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
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
                'booking_reference' => 'UEH-'.Str::upper(Str::random(8)),
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
            ((float) $product->malaysia_adult_price_myr * (int) $validated['malaysian_adults']) +
            ((float) $product->malaysia_child_price_myr * (int) $validated['malaysian_kids']) +
            ((float) $product->international_adult_price_myr * (int) $validated['international_adults']) +
            ((float) $product->international_child_price_myr * (int) $validated['international_kids']);

        $amountDisplay = $amountMyr * self::CURRENCY_RATES[$validated['currency_code']];

        $booking = Booking::create([
            'user_id' => $request->user()?->id,
            'product_id' => $product->id,
            'booking_reference' => 'UEH-'.Str::upper(Str::random(8)),
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
            'payment_status' => 'awaiting_setup',
        ]);

        $accessToken = Str::random(64);

        $booking->update([
            'account_setup_token' => hash('sha256', $accessToken),
            'account_setup_expires_at' => now()->addDay(),
        ]);

        Mail::to($booking->email)->send(new CompleteBookingAccessMail(
            $booking->fresh(),
            route('bookings.access.show', $accessToken),
        ));

        $isReserveFlow = ($validated['action_type'] ?? null) === 'reserve';

        return redirect()->route('home')->with(
            'success',
            $isReserveFlow
                ? 'Your reserve request has been submitted. We sent a secure email link so you can complete your account and continue to payment.'
                : 'Your booking request has been submitted. We sent a secure email link so you can complete your account and continue to payment.'
        );
    }
}
