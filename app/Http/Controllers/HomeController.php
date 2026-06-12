<?php

namespace App\Http\Controllers;

use App\Mail\BookingReferenceMail;
use App\Models\BlogPost;
use App\Models\Booking;
use App\Models\HomeHeroSlide;
use App\Models\NewsFeature;
use App\Models\Product;
use App\Models\Testimonial;
use App\Services\GooglePlaceReviewService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly GooglePlaceReviewService $googlePlaceReviewService,
    ) {}

    private const PROMO_POSTER_PORTRAIT = 'portrait';

    private const PROMO_POSTER_LANDSCAPE = 'landscape';

    private const PROMO_POSTER_UNKNOWN = 'unknown';

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

    private const TOUR_PAGE_DEFINITIONS = [
        'day-trip' => [
            'slug' => 'day-trip',
            'label' => 'Day Trip',
            'heading' => 'Day Trip Tours',
            'description' => 'Quick Sabah escapes that fit into a single day, perfect for easy sightseeing, island hopping, and short nature breaks.',
            'days' => 1,
            'nights' => 0,
        ],
        '2d1n-trip' => [
            'slug' => '2d1n-trip',
            'label' => '2D1N Trip',
            'heading' => '2D1N Tours',
            'description' => 'Short overnight getaways with enough time to explore, rest, and enjoy a fuller Sabah experience.',
            'days' => 2,
            'nights' => 1,
        ],
        '3d2n-trip' => [
            'slug' => '3d2n-trip',
            'label' => '3D2N Trip',
            'heading' => '3D2N Tours',
            'description' => 'Balanced multi-day tours for travellers who want more activities, more scenery, and a more complete itinerary.',
            'days' => 3,
            'nights' => 2,
        ],
        '4d3n-trip' => [
            'slug' => '4d3n-trip',
            'label' => '4D3N Trip',
            'heading' => '4D3N Tours',
            'description' => 'Longer Sabah journeys with added sightseeing time, deeper exploration, and a more relaxed pace.',
            'days' => 4,
            'nights' => 3,
        ],
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
        $packageTestimonials = Testimonial::where('display_location', 'package')
            ->where('product_id', $product->id)
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $googleReviewData = $this->googlePlaceReviewService->getPlaceReviews();

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
            ->take(4)
            ->get();

        if ($recommendedProducts->count() < 4) {
            $recommendedProducts = $recommendedProducts
                ->concat(
                    Product::where('is_active', true)
                        ->where('id', '!=', $product->id)
                        ->where('category', 'package')
                        ->whereNotIn('id', $recommendedProducts->pluck('id'))
                        ->take(4 - $recommendedProducts->count())
                        ->get()
                )
                ->values();
        }

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'recommendedProducts' => $recommendedProducts,
            'reviews' => $this->mergePublicReviews($packageTestimonials, $googleReviewData),
            'googleReviewData' => $googleReviewData,
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
            'malaysiaPricingTiers' => $this->resolveProductPricingTiers($product, 'malaysia'),
            'internationalPricingTiers' => $this->resolveProductPricingTiers($product, 'international'),
        ]);
    }

    public function showBlogIndex(): View
    {
        abort_unless(Schema::hasTable('blog_posts'), 404);

        return view('blog.index', [
            'blogPosts' => BlogPost::query()
                ->where('is_published', true)
                ->where(function ($query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->orderByDesc('published_at')
                ->latest()
                ->get(),
        ]);
    }

    public function showBlogPost(BlogPost $blogPost): View
    {
        abort_unless(Schema::hasTable('blog_posts'), 404);

        abort_unless(
            $blogPost->is_published
            && ($blogPost->published_at === null || $blogPost->published_at->lte(now())),
            404
        );

        return view('blog.show', [
            'blogPost' => $blogPost,
            'latestBlogPosts' => BlogPost::query()
                ->where('is_published', true)
                ->where(function ($query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->whereKeyNot($blogPost->id)
                ->orderByDesc('published_at')
                ->latest()
                ->take(3)
                ->get(),
        ]);
    }

    public function showTourCategory(string $tourType): View
    {
        $tourPage = self::TOUR_PAGE_DEFINITIONS[$tourType] ?? null;

        abort_unless($tourPage !== null, 404);

        $tourPackages = Product::query()
            ->where('is_active', true)
            ->where('category', 'package')
            ->orderBy('price_myr')
            ->get()
            ->filter(fn (Product $product) => $this->productMatchesTourCategory($product, $tourPage))
            ->values();

        return view('tours.show', [
            'tourPage' => $tourPage,
            'tourPackages' => $tourPackages,
            'tourPages' => array_values(self::TOUR_PAGE_DEFINITIONS),
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
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
        string $groupSizeLabel,
        float $adultPrice,
        float $childPrice,
        ?float $originalAdultPrice = null,
        ?float $originalChildPrice = null,
    ): array
    {
        return [
            [
                'label' => trim($groupSizeLabel) !== '' ? trim($groupSizeLabel) : 'Per person',
                'adult_price' => round($adultPrice, 2),
                'child_price' => round($childPrice, 2),
                'original_adult_price' => $originalAdultPrice !== null ? round($originalAdultPrice, 2) : null,
                'original_child_price' => $originalChildPrice !== null ? round($originalChildPrice, 2) : null,
                'enquire' => false,
            ],
        ];
    }

    private function resolveProductPricingTiers(Product $product, string $market): array
    {
        $pricingTiers = collect($product->pricing_tiers ?? [])
            ->filter(fn ($tier) => is_array($tier) && filled($tier['group_size_label'] ?? null))
            ->map(function (array $tier) use ($product, $market) {
                $adultKey = $market.'_adult_price_myr';
                $childKey = $market.'_child_price_myr';
                $adultPrice = round((float) ($tier[$adultKey] ?? 0), 2);
                $childPrice = round((float) ($tier[$childKey] ?? 0), 2);
                $discountMultiplier = $product->has_active_discount
                    ? max(0, min(100, 100 - (float) $product->discount_percentage)) / 100
                    : 1;

                return [
                    'label' => (string) $tier['group_size_label'],
                    'adult_price' => round($adultPrice * $discountMultiplier, 2),
                    'child_price' => round($childPrice * $discountMultiplier, 2),
                    'original_adult_price' => $product->has_active_discount ? $adultPrice : null,
                    'original_child_price' => $product->has_active_discount ? $childPrice : null,
                    'enquire' => false,
                ];
            })
            ->values()
            ->all();

        if ($pricingTiers !== []) {
            return $pricingTiers;
        }

        return $this->buildPricingTiers(
            (string) ($product->group_size_label ?? ''),
            (float) ($market === 'malaysia' ? $product->discounted_malaysia_adult_price_myr : $product->discounted_international_adult_price_myr),
            (float) ($market === 'malaysia' ? $product->discounted_malaysia_child_price_myr : $product->discounted_international_child_price_myr),
            (float) ($market === 'malaysia' ? $product->malaysia_adult_price_myr : $product->international_adult_price_myr),
            (float) ($market === 'malaysia' ? $product->malaysia_child_price_myr : $product->international_child_price_myr),
        );
    }

    private function productMatchesTourCategory(Product $product, array $tourPage): bool
    {
        $tourCode = strtoupper(trim((string) ($product->tour_code ?? '')));

        if ($tourPage['slug'] === 'day-trip' && str_starts_with($tourCode, 'DT-UEH')) {
            return true;
        }

        if ($tourPage['slug'] !== 'day-trip' && str_starts_with($tourCode, 'OT-UEH')) {
            return true;
        }

        $durationData = $this->parseProductDuration($product->duration);

        if (
            $durationData['days'] === (int) $tourPage['days']
            && $durationData['nights'] === (int) $tourPage['nights']
        ) {
            return true;
        }

        $durationLabel = Str::lower(trim((string) $product->duration));

        if ($tourPage['slug'] === 'day-trip') {
            return str_contains($durationLabel, 'day trip')
                || str_contains($durationLabel, '1 day');
        }

        return str_contains($durationLabel, Str::lower((string) $tourPage['label']));
    }

    private function parseProductDuration(?string $duration): array
    {
        $durationLabel = Str::lower(trim((string) $duration));
        $compactDurationLabel = preg_replace('/\s+/', '', $durationLabel) ?? $durationLabel;
        $days = 0;
        $nights = 0;

        if (preg_match('/(\d+)d(\d+)n/i', $compactDurationLabel, $matches)) {
            $days = (int) $matches[1];
            $nights = (int) $matches[2];

            return [
                'days' => $days,
                'nights' => $nights,
            ];
        }

        if (preg_match('/(\d+)\s*day/i', $durationLabel, $dayMatches)) {
            $days = (int) $dayMatches[1];
        }

        if (preg_match('/(\d+)\s*night/i', $durationLabel, $nightMatches)) {
            $nights = (int) $nightMatches[1];
        }

        return [
            'days' => $days,
            'nights' => $nights,
        ];
    }

    private function sharedPageData(): array
    {
        $products = Product::where('is_active', true)->orderBy('category')->orderBy('price_myr')->get();
        $landingTestimonials = Testimonial::where('display_location', 'landing')
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->get();
        $packageReviewStats = Testimonial::query()
            ->selectRaw('product_id, AVG(rating) as average_rating, COUNT(*) as reviews_count')
            ->where('display_location', 'package')
            ->where('is_featured', true)
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $products->each(function (Product $product) use ($packageReviewStats) {
            $stats = $packageReviewStats->get($product->id);

            $product->setAttribute('package_review_average', $stats ? round((float) $stats->average_rating, 1) : null);
            $product->setAttribute('package_review_count', $stats ? (int) $stats->reviews_count : 0);
        });

        $travelPackages = $products->where('category', 'package')->values();
        $transportServices = $products->where('category', 'transport')->values();
        $popularPackages = $travelPackages->where('is_featured', true)->take(3)->values();
        $transportImageMap = [
            '41/44 Seaters Bus' => asset('images/44pax.png'),
            '17 Seaters Van' => asset('images/17pax.png'),
            '9/14 Seaters Van' => asset('images/14pax.png'),
        ];
        $transportOptions = $transportServices->map(function (Product $transport) use ($transportImageMap) {
            return [
                'label' => $transport->name,
                'name' => $transport->name,
                'image' => $transportImageMap[$transport->name] ?? $transport->image_url,
                'url' => route('products.show', $transport),
            ];
        })->values();
        $transportFeatures = collect([
            ['label' => 'HYGIENE', 'icon' => 'spark'],
            ['label' => 'SAFETY', 'icon' => 'shield'],
            ['label' => 'PROFESIONAL DRIVER', 'icon' => 'driver'],
            ['label' => 'LICENSED VAN/BUS PERSIARAN', 'icon' => 'license'],
        ]);
        $packageSections = collect([
            [
                'key' => 'kundasang',
                'title' => 'KUNDASANG',
                'summary' => 'Discover Kundasang, a serene highland paradise nestled in the cool hills near Mount Kinabalu, offering breathtaking mountain views, fresh air, and a peaceful escape from the city.',
                'background' => asset('images/kundasang_bg.png'),
                'keywords' => ['kundasang', 'kinabalu', 'ranau', 'nabalu', 'desa'],
            ],
            [
                'key' => 'island',
                'title' => 'ISLAND HOPPING',
                'summary' => 'Sabah has around 395 islands, offering everything from easy day trips to world-class diving destinations.',
                'background' => asset('images/semporna.png'),
                'keywords' => ['island', 'marine', 'semporna', 'snork', 'div', 'sipadan', 'mabul', 'mataking', 'pom pom', 'bohey'],
            ],
            [
                'key' => 'kk-beach',
                'title' => 'KK BEACH',
                'summary' => 'Kota Kinabalu is home to some of Sabah\'s most scenic beaches, known for their breathtaking sunsets and relaxed coastal lifestyle.',
                'background' => asset('images/beach.png'),
                'keywords' => ['kota kinabalu', 'kk ', 'beach', 'tanjung aru', 'city', 'island hopping', 'manukan', 'sapi', 'mamutik'],
            ],
        ]);
        $defaultPackageSection = 'kundasang';
        $packagePageSize = 3;

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
        $currentPromoSlide = $currentPromo ? $this->buildPromoSlide($currentPromo, 'Current Offer') : null;
        $otherPromoSlides = NewsFeature::query()
            ->when($currentPromo, fn ($query) => $query->whereKeyNot($currentPromo->getKey()))
            ->latest()
            ->take(12)
            ->get()
            ->map(fn (NewsFeature $promo) => $this->buildPromoSlide($promo, $this->resolvePromoStatus($promo, $currentPromo)))
            ->values();
        $totalVisiblePromoPosters = 3;
        $secondaryPromoLimit = max(0, $totalVisiblePromoPosters - ($currentPromoSlide ? 1 : 0));
        $recentPromoSlides = $currentPromoSlide
            ? $otherPromoSlides->take($secondaryPromoLimit)->values()
            : $otherPromoSlides->take($totalVisiblePromoPosters)->values();
        $pastPromos = collect();
        $latestBlogPosts = Schema::hasTable('blog_posts')
            ? BlogPost::query()
                ->where('is_published', true)
                ->where(function ($query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->orderByDesc('published_at')
                ->latest()
                ->take(3)
                ->get()
            : collect();
        $heroSlides = Schema::hasTable('home_hero_slides')
            ? HomeHeroSlide::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->orderBy('id')
                ->take(5)
                ->get()
                ->map(fn (HomeHeroSlide $slide) => [
                    'id' => $slide->id,
                    'image_url' => $slide->image_url,
                ])
                ->filter(fn (array $slide) => filled($slide['image_url']))
                ->values()
            : collect();

        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                [
                    'id' => 'fallback-hero-slide',
                    'image_url' => asset('images/bg_image.png'),
                ],
            ]);
        }

        $landingTestimonials = Testimonial::where('display_location', 'landing')
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->get();

        return [
            'transportServices' => $transportServices,
            'transportOptions' => $transportOptions,
            'transportFeatures' => $transportFeatures,
            'packageSections' => $packageSections,
            'defaultPackageSection' => $defaultPackageSection,
            'packagePageSize' => $packagePageSize,
            'travelPackages' => $travelPackages,
            'popularPackages' => $popularPackages,
            'currentPromo' => $currentPromo,
            'currentPromoSlide' => $currentPromoSlide,
            'pastPromo' => $pastPromos->first(),
            'pastPromos' => $pastPromos,
            'recentPromoSlides' => $recentPromoSlides,
            'latestBlogPosts' => $latestBlogPosts,
            'heroSlides' => $heroSlides,
            'newsFeatures' => (clone $activeNewsQuery)->latest()->take(6)->get(),
            'testimonials' => $landingTestimonials,
            'websiteReviews' => $this->mapWebsiteReviews($landingTestimonials),
            'websiteReviewStats' => [
                'average_rating' => $landingTestimonials->isNotEmpty() ? round((float) $landingTestimonials->avg('rating'), 1) : null,
                'reviews_count' => $landingTestimonials->count(),
            ],
            'recentBookings' => Booking::with('product')->latest()->take(5)->get(),
            'currencyRates' => self::CURRENCY_RATES,
            'currencySymbols' => self::CURRENCY_SYMBOLS,
        ];
    }

    private function buildPromoSlide(NewsFeature $promo, string $status): array
    {
        $isActiveOffer = $this->isPromoActive($promo);

        return [
            'id' => $promo->getKey(),
            'title' => $promo->title,
            'summary' => $promo->summary,
            'poster_url' => $promo->poster_url,
            'poster_orientation' => $this->detectPromoPosterOrientation($promo),
            'is_active_offer' => $isActiveOffer,
            'promo_label' => $promo->promo_label ?: 'Discover Sabah',
            'date_label' => $isActiveOffer
                ? ($promo->ends_at ? 'Until '.$promo->ends_at->format('d F Y') : 'Available now')
                : ($promo->ends_at ? 'Ended '.$promo->ends_at->format('d M Y') : 'Recently featured'),
            'range_label' => ($promo->starts_at?->format('d M Y') ?: 'Available now').' - '.($promo->ends_at?->format('d M Y') ?: 'While active'),
            'status' => $status,
        ];
    }

    private function resolvePromoStatus(NewsFeature $promo, ?NewsFeature $currentPromo): string
    {
        if ($currentPromo && $promo->is($currentPromo)) {
            return 'Current Offer';
        }

        if ($this->isPromoActive($promo)) {
            return 'Current Offer';
        }

        if ($promo->ends_at && $promo->ends_at->isPast()) {
            return 'Past Offer';
        }

        return 'Current Offer';
    }

    private function isPromoActive(NewsFeature $promo): bool
    {
        $today = now()->toDateString();

        if ($promo->starts_at && $promo->starts_at->format('Y-m-d') > $today) {
            return false;
        }

        if ($promo->ends_at && $promo->ends_at->format('Y-m-d') < $today) {
            return false;
        }

        return (bool) $promo->is_active;
    }

    private function detectPromoPosterOrientation(NewsFeature $promo): string
    {
        if (blank($promo->poster_path)) {
            return self::PROMO_POSTER_UNKNOWN;
        }

        $posterFullPath = public_path('storage/'.$promo->poster_path);
        if (! is_file($posterFullPath)) {
            return self::PROMO_POSTER_UNKNOWN;
        }

        $posterSize = @getimagesize($posterFullPath);
        if (! is_array($posterSize) || empty($posterSize[0]) || empty($posterSize[1])) {
            return self::PROMO_POSTER_UNKNOWN;
        }

        return $posterSize[1] > $posterSize[0]
            ? self::PROMO_POSTER_PORTRAIT
            : self::PROMO_POSTER_LANDSCAPE;
    }

    private function mergePublicReviews(EloquentCollection $websiteTestimonials, array $googleReviewData): Collection
    {
        $websiteReviews = $this->mapWebsiteReviews($websiteTestimonials);

        return $websiteReviews
            ->concat(collect($googleReviewData['reviews'] ?? []))
            ->values();
    }

    private function mapWebsiteReviews(EloquentCollection $websiteTestimonials): Collection
    {
        return $websiteTestimonials->map(function (Testimonial $testimonial) {
            return [
                'source' => 'website',
                'source_label' => 'Website review',
                'name' => $testimonial->name,
                'location' => $testimonial->location,
                'trip_name' => $testimonial->trip_name,
                'quote' => $testimonial->quote,
                'rating' => $testimonial->rating,
                'profile_photo_url' => $testimonial->profile_photo_url,
                'published_label' => '',
                'review_url' => null,
            ];
        });
    }

    public function book(Request $request): RedirectResponse
    {
        [$resolvedCountryCode, $resolvedLocalNumber] = $this->resolvePhoneInputParts(
            (string) $request->input('phone_country_code', ''),
            (string) $request->input('phone_local_number', ''),
            (string) $request->input('phone', ''),
        );

        $request->merge([
            'phone_country_code' => $resolvedCountryCode,
            'phone_local_number' => $resolvedLocalNumber,
            'phone' => $this->composePhoneNumber($resolvedCountryCode, $resolvedLocalNumber),
        ]);

        $formMode = $request->input('form_mode') === 'enquiry' ? 'enquiry' : 'booking';

        $baseRules = [
            'product_id' => ['required', 'exists:products,id'],
            'service_type' => ['required', 'in:transport,package'],
            'booking_purpose' => ['nullable', 'in:leisure,business'],
            'action_type' => ['nullable', 'in:reserve,instant_book,book_now'],
            'locked_product_id' => ['nullable', 'exists:products,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email:rfc,dns'],
            'identity_document_number' => ['nullable', 'string', 'max:100'],
            'company_number' => ['nullable', 'string', 'max:100'],
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
        $bookingPurpose = ($validated['booking_purpose'] ?? 'leisure') === 'business' ? 'business' : 'leisure';

        if ($formMode !== 'enquiry' && $bookingPurpose === 'leisure' && blank($validated['identity_document_number'] ?? null)) {
            return back()->withErrors([
                'identity_document_number' => 'Please provide the IC Number / Passport Number for leisure bookings.',
            ])->withInput();
        }

        if ($formMode !== 'enquiry' && $bookingPurpose === 'business' && blank($validated['company_number'] ?? null)) {
            return back()->withErrors([
                'company_number' => 'Please provide the company number for business bookings.',
            ])->withInput();
        }

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
                'booking_purpose' => $bookingPurpose,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'identity_document_number' => null,
                'company_number' => null,
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
            'booking_purpose' => $bookingPurpose,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'identity_document_number' => $bookingPurpose === 'leisure'
                ? trim((string) ($validated['identity_document_number'] ?? ''))
                : null,
            'company_number' => $bookingPurpose === 'business'
                ? trim((string) ($validated['company_number'] ?? ''))
                : null,
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
            'is_featured' => $product === null,
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

                $hasRepeatedLocalNumber = collect(array_keys(self::SUPPORTED_PHONE_PATTERNS))
                    ->contains(function (string $countryCode) use ($digitsOnly) {
                        if (! str_starts_with($digitsOnly, $countryCode)) {
                            return false;
                        }

                        $localDigits = substr($digitsOnly, strlen($countryCode));

                        return $localDigits !== '' && preg_match('/^(\d)\1+$/', $localDigits) === 1;
                    });

                if ($hasRepeatedLocalNumber) {
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

    private function resolvePhoneInputParts(string $countryCode, string $localNumber, string $rawPhone): array
    {
        $countryCode = array_key_exists($countryCode, self::PHONE_COUNTRY_CODES) ? $countryCode : '';
        $localNumber = trim($localNumber);

        if ($countryCode !== '' && $localNumber !== '') {
            return [$countryCode, $localNumber];
        }

        $rawPhone = trim($rawPhone);

        if ($rawPhone === '') {
            return [$countryCode ?: '+60', $localNumber];
        }

        foreach (array_keys(self::PHONE_COUNTRY_CODES) as $supportedCode) {
            if (! str_starts_with($rawPhone, $supportedCode)) {
                continue;
            }

            return [
                $supportedCode,
                ltrim(substr($rawPhone, strlen($supportedCode)), " \t\n\r\0\x0B-()"),
            ];
        }

        return [$countryCode ?: '+60', $localNumber !== '' ? $localNumber : $rawPhone];
    }

    private function generateUniqueBookingReference(): string
    {
        do {
            $reference = 'UEH-'.Str::upper(Str::random(8));
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }
}
