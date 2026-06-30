<x-layouts.app :title="$product->name.' | Universal Eden Holidays'">
    <style>
        .price-info-tooltip {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .price-info-tooltip-bubble {
            position: absolute;
            left: 50%;
            bottom: calc(100% + 0.6rem);
            z-index: 20;
            width: 14rem;
            transform: translateX(-50%);
            border-radius: 0.35rem;
            background: rgba(17, 24, 39, 0.96);
            padding: 0.55rem 0.7rem;
            color: #ffffff;
            font-size: 0.72rem;
            line-height: 1.4;
            text-align: center;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.22);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.18s ease;
        }

        .price-info-tooltip-bubble::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translateX(-50%);
            border-width: 0.42rem 0.38rem 0 0.38rem;
            border-style: solid;
            border-color: rgba(17, 24, 39, 0.96) transparent transparent transparent;
        }

        .price-info-tooltip:hover .price-info-tooltip-bubble,
        .price-info-tooltip:focus-within .price-info-tooltip-bubble {
            opacity: 1;
        }

        [data-package-detail-html] {
            color: inherit;
            word-break: break-word;
        }

        [data-package-detail-html] p {
            margin: 0 0 0.5rem;
        }

        [data-package-detail-html] p:last-child {
            margin-bottom: 0;
        }

        [data-package-detail-html] ul,
        [data-package-detail-html] ol {
            margin: 0.5rem 0;
            padding-left: 1.35rem;
            list-style: none;
        }

        [data-package-detail-html] ul[data-point-style="tick"],
        [data-package-detail-html] ul[data-point-style="round"],
        [data-package-detail-html] ul[data-point-style="x"],
        [data-package-detail-html] ul[data-point-style="warning"] {
            list-style: none;
            padding-left: 0;
        }

        [data-package-detail-html] ul[data-point-style="tick"] li,
        [data-package-detail-html] ul[data-point-style="round"] li,
        [data-package-detail-html] ul[data-point-style="x"] li,
        [data-package-detail-html] ul[data-point-style="warning"] li {
            position: relative;
            padding-left: 1.6rem;
        }

        [data-package-detail-html] ul[data-point-style="tick"] li::before,
        [data-package-detail-html] ul[data-point-style="round"] li::before,
        [data-package-detail-html] ul[data-point-style="x"] li::before,
        [data-package-detail-html] ul[data-point-style="warning"] li::before {
            position: absolute;
            left: 0;
            top: 0;
            font-weight: 700;
        }

        [data-package-detail-html] ul[data-point-style="tick"] li::before {
            content: "\2713";
            color: rgb(21 128 61);
        }

        [data-package-detail-html] ul[data-point-style="round"] li::before {
            content: "\2022";
            color: rgb(87 83 78);
        }

        [data-package-detail-html] ul[data-point-style="x"] li::before {
            content: "\2716";
            color: rgb(185 28 28);
        }

        [data-package-detail-html] ul[data-point-style="warning"] li::before {
            content: "\26A0";
            color: rgb(180 83 9);
        }
    </style>
        <div class="flex min-h-[calc(100vh-var(--app-header-offset,0px))] flex-col" style="background-color: #ffffff;">
    <main class="mx-auto w-full px-6 py-10 lg:px-10" style="max-width: {{ $product->category === 'package' ? '92rem' : '120rem' }}; background-color: #ffffff;">
        <div class="mb-6 text-stone-500" style="font-size: 1.2rem; line-height: 1.25;">
            <a href="{{ route('home') }}" class="hover:text-sky-700">Home</a>
            <span class="mx-2">›</span>
            <span class="capitalize">{{ $product->category }}</span>
            <span class="mx-2">›</span>
            <span class="text-stone-700">{{ $product->name }}</span>
        </div>

        @php
            $isTransport = $product->category === 'transport';
            $bookingRouteParameter = $product->category === 'package' ? 'package_id' : 'product_id';
            $startingPriceTier = collect($malaysiaPricingTiers ?? [])
                ->filter(fn ($tier) => is_array($tier) && isset($tier['adult_price']))
                ->sortBy('adult_price')
                ->first();
            $galleryImages = $product->gallery_urls;
            $primaryImage = $galleryImages[0] ?? null;
            $thumbnailImages = collect($galleryImages)->slice(1, 4)->values();
            $remainingGalleryCount = max(count($galleryImages) - 5, 0);
            $startingPrice = (float) ($startingPriceTier['adult_price'] ?? $product->discounted_malaysia_adult_price_myr);
            $originalStartingPrice = (float) ($startingPriceTier['original_adult_price'] ?? $product->malaysia_adult_price_myr);
            $startingPriceTooltip = $startingPriceTier
                ? 'Based on 1 adult at the lowest Malaysian market price under the '.$startingPriceTier['label'].' group size.'
                : 'Based on 1 adult from the Malaysian market pricing.';
            $previewImages = collect($galleryImages)
                ->map(fn ($image, $index) => [
                    'src' => $image,
                    'alt' => $index === 0
                        ? $product->name
                        : $product->name.' gallery image '.($index + 1),
                ])
                ->values();
            $serviceInclusions = is_array($product->service_inclusions) ? $product->service_inclusions : [];
            $packageDetails = is_array($product->package_details) ? $product->package_details : [];
            $structuredServiceInclusions = collect($serviceInclusions)
                ->filter(fn ($item) => is_array($item) && array_key_exists('value', $item))
                ->values();
            $minimumAgeLabel = filled($product->minimum_age) ? $product->minimum_age : 'No Limit';
            $detailCards = [
                [
                    'icon' => 'code',
                    'label' => 'Tour Code',
                    'value' => $product->tour_code ?: 'To be confirmed',
                ],
                [
                    'icon' => 'time',
                    'label' => 'Departure Time',
                    'value' => $product->departure_time ?: 'Anytime',
                ],
                [
                    'icon' => 'age',
                    'label' => 'Minimum Age',
                    'value' => $minimumAgeLabel,
                ],
                [
                    'icon' => 'calendar',
                    'label' => 'Availability',
                    'value' => 'Everyday',
                ],
                [
                    'icon' => 'location',
                    'label' => 'Location',
                    'value' => $product->location ?: 'To be confirmed',
                ],
                [
                    'icon' => 'pickup',
                    'label' => 'Pick Up',
                    'value' => $product->pickup_location ?: 'To be confirmed',
                ],
                [
                    'icon' => 'dropoff',
                    'label' => 'Drop Off',
                    'value' => $product->dropoff_location ?: 'To be confirmed',
                ],
                [
                    'icon' => 'pax',
                    'label' => 'Minimum Pax',
                    'value' => '1 Person',
                ],
                [
                    'icon' => 'duration',
                    'label' => 'Duration',
                    'value' => $product->duration ?: 'To be confirmed',
                ],
            ];
            $serviceInfoSections = collect([
                'inclusion' => ['title' => 'Inclusion', 'items' => []],
                'exclusion' => ['title' => 'Exclusion', 'items' => []],
                'things_to_bring' => ['title' => 'Things to Bring', 'items' => []],
                'important_notes' => ['title' => 'Important Notes', 'items' => []],
            ]);
            $cleanPackageDetailHtml = fn ($html) => preg_replace(
                '/(<(?:p|div|li)[^>]*>\s*(?:<(?:strong|b|em|i|u)[^>]*>\s*)*)(?:[•●○◦▪▫✓✔✕✖✗❌⚠!]+|\d+[.)])\s*/u',
                '$1',
                (string) $html
            ) ?? (string) $html;
            $buildPackageContentHtml = function ($rows) use ($cleanPackageDetailHtml) {
                return collect(is_array($rows) ? $rows : [])
                    ->map(function ($row) use ($cleanPackageDetailHtml) {
                        if (is_array($row)) {
                            $html = trim($cleanPackageDetailHtml((string) ($row['html'] ?? '')));

                            if ($html !== '') {
                                return $html;
                            }

                            $text = trim((string) ($row['text'] ?? $row['value'] ?? ''));

                            return $text === '' ? null : '<p>'.e($text).'</p>';
                        }

                        $text = trim((string) $row);

                        return $text === '' ? null : '<p>'.e($text).'</p>';
                    })
                    ->filter()
                    ->implode('');
            };
            $tourHighlightsHtml = $buildPackageContentHtml($product->tour_highlights ?? []);
            $pushServiceSectionItem = function (string $key, string $value, ?string $symbol = null, ?string $html = null) use (&$serviceInfoSections) {
                $trimmedValue = trim($value);

                if ($trimmedValue === '') {
                    return;
                }

                $section = $serviceInfoSections->get($key);
                $defaultSymbol = match ($key) {
                    'exclusion' => 'x',
                    'things_to_bring', 'important_notes' => 'exclamation',
                    default => 'tick',
                };
                $section['items'][] = [
                    'symbol' => $symbol ?: $defaultSymbol,
                    'text' => $trimmedValue,
                    'html' => $html,
                ];
                $serviceInfoSections->put($key, $section);
            };

            $packageDetailsSections = [
                'inclusion' => collect($packageDetails['includes'] ?? [])
                    ->map(function ($item) use ($cleanPackageDetailHtml) {
                        $text = trim((string) ($item['text'] ?? $item['value'] ?? ''));

                        if ($text === '') {
                            return null;
                        }

                        return [
                            'symbol' => ($item['symbol'] ?? 'tick') === 'round' ? 'round' : 'tick',
                            'text' => $text,
                            'html' => filled($item['html'] ?? null) ? $cleanPackageDetailHtml((string) $item['html']) : null,
                        ];
                    })
                    ->filter()
                    ->values(),
                'exclusion' => collect($packageDetails['excludes'] ?? [])
                    ->map(function ($item) use ($cleanPackageDetailHtml) {
                        $text = trim((string) ($item['text'] ?? $item['value'] ?? ''));

                        if ($text === '') {
                            return null;
                        }

                        return [
                            'symbol' => ($item['symbol'] ?? 'x') === 'round' ? 'round' : 'x',
                            'text' => $text,
                            'html' => filled($item['html'] ?? null) ? $cleanPackageDetailHtml((string) $item['html']) : null,
                        ];
                    })
                    ->filter()
                    ->values(),
                'things_to_bring' => collect($packageDetails['things_to_bring'] ?? [])
                    ->map(function ($item) use ($cleanPackageDetailHtml) {
                        $text = trim((string) ($item['text'] ?? $item['value'] ?? ''));

                        if ($text === '') {
                            return null;
                        }

                        return [
                            'symbol' => ($item['symbol'] ?? 'exclamation') === 'round' ? 'round' : 'exclamation',
                            'text' => $text,
                            'html' => filled($item['html'] ?? null) ? $cleanPackageDetailHtml((string) $item['html']) : null,
                        ];
                    })
                    ->filter()
                    ->values(),
                'important_notes' => collect($packageDetails['important_notes'] ?? [])
                    ->map(function ($item) use ($cleanPackageDetailHtml) {
                        $text = trim((string) ($item['text'] ?? $item['value'] ?? ''));

                        if ($text === '') {
                            return null;
                        }

                        return [
                            'symbol' => ($item['symbol'] ?? 'exclamation') === 'round' ? 'round' : 'exclamation',
                            'text' => $text,
                            'html' => filled($item['html'] ?? null) ? $cleanPackageDetailHtml((string) $item['html']) : null,
                        ];
                    })
                    ->filter()
                    ->values(),
            ];

            if (collect($packageDetailsSections)->flatten()->isNotEmpty()) {
                    foreach ($packageDetailsSections as $sectionKey => $items) {
                        foreach ($items as $item) {
                            $pushServiceSectionItem($sectionKey, $item['text'] ?? '', $item['symbol'] ?? null, $item['html'] ?? null);
                        }
                    }
            } elseif ($structuredServiceInclusions->isNotEmpty()) {
                foreach ($structuredServiceInclusions as $item) {
                    $label = trim((string) ($item['label'] ?? ''));
                    $value = trim((string) ($item['value'] ?? ''));
                    $normalizedLabel = Str::lower($label);

                    $targetKey = match (true) {
                        str_contains($normalizedLabel, 'exclusion') => 'exclusion',
                        str_contains($normalizedLabel, 'bring') => 'things_to_bring',
                        str_contains($normalizedLabel, 'important'), str_contains($normalizedLabel, 'note') => 'important_notes',
                        default => 'inclusion',
                    };

                    $pushServiceSectionItem(
                        $targetKey,
                        $label !== '' && !in_array($normalizedLabel, ['inclusion', 'exclusion', 'things to bring', 'important notes', 'note'], true)
                            ? $label.': '.$value
                            : $value
                    );
                }
            } else {
                $pushServiceSectionItem('inclusion', (string) (($serviceInclusions['inclusion'] ?? '') ?: 'Core service delivery, support coordination, and supplier-side arrangements as stated.'));
                $pushServiceSectionItem('inclusion', (string) (($serviceInclusions['meals'] ?? '') ? 'Meals: '.$serviceInclusions['meals'] : 'Meals: Subject to package or service arrangement.'));
                $pushServiceSectionItem('inclusion', (string) (($serviceInclusions['accommodation'] ?? '') ? 'Accommodation: '.$serviceInclusions['accommodation'] : 'Accommodation: Included where relevant for package and overnight products.'));
                $pushServiceSectionItem('exclusion', (string) (($serviceInclusions['exclusion'] ?? '') ?: 'Flights, personal travel insurance, personal spending, and unlisted add-ons.'));
                $pushServiceSectionItem('things_to_bring', 'Personal identification, comfortable clothing, and any trip-specific essentials confirmed after booking.');
                $pushServiceSectionItem('important_notes', 'Timing, weather, and supplier arrangements may vary depending on the selected package and travel date.');
            }
            $recommendedAttireHtml = $buildPackageContentHtml($product->recommended_attire ?? []);
            $thingsYouShouldKnowHtml = $buildPackageContentHtml($product->things_to_know ?? []);
            $usefulTravelTipsHtml = $buildPackageContentHtml($product->travel_tips ?? []);
            $optionalActivitiesData = is_array($product->optional_activities) ? $product->optional_activities : [];
            $optionalActivitiesDescription = trim((string) ($optionalActivitiesData['description'] ?? ''));
            $optionalActivitiesRows = collect(is_array($optionalActivitiesData['rows'] ?? null) ? $optionalActivitiesData['rows'] : [])
                ->map(function ($row) {
                    if (! is_array($row)) {
                        return null;
                    }

                    $name = trim((string) ($row['name'] ?? ''));
                    $rate = trim((string) ($row['rate'] ?? ($row['details'] ?? '')));

                    if ($name === '' && $rate === '') {
                        return null;
                    }

                    return [
                        'name' => $name,
                        'rate' => $rate,
                    ];
                })
                ->filter()
                ->values();

            if ($optionalActivitiesRows->isEmpty()) {
                $optionalActivitiesRows = collect(is_array($optionalActivitiesData['items'] ?? null) ? $optionalActivitiesData['items'] : [])
                    ->map(function ($item) {
                        $name = trim((string) $item);

                        if ($name === '') {
                            return null;
                        }

                        return [
                            'name' => $name,
                            'rate' => '',
                        ];
                    })
                    ->filter()
                    ->values();
            }

            $showOptionalActivities = filled($optionalActivitiesDescription) || $optionalActivitiesRows->isNotEmpty();
            $itineraryItems = collect($product->itinerary_items ?? [])->filter()->values();
            $structuredItineraryItems = $itineraryItems
                ->filter(fn ($item) => is_array($item) && array_key_exists('activity', $item))
                ->values();
            $groupedItineraryDays = $structuredItineraryItems
                ->groupBy(fn ($item, $index) => trim((string) ($item['day_number'] ?? '')) ?: 'Day '.($index + 1))
                ->map(fn ($items, $dayLabel) => [
                    'label' => $dayLabel,
                    'items' => $items->values(),
                ])
                ->values();
            $groupedItineraryDayRows = $groupedItineraryDays->chunk(2)->values();
        @endphp

        @if ($isTransport)
        <section class="grid gap-8 lg:grid-cols-5 lg:items-stretch">
            <div class="h-full lg:col-span-3">
                <div>
                    <div class="flex h-full flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                        @if ($primaryImage)
                            <div class="px-5 pt-5" data-product-carousel>
                                <div class="relative mx-auto overflow-hidden bg-white" style="width: min(100%, 720px); height: 420px;">
                                    <button
                                        type="button"
                                        class="relative block h-full w-full overflow-hidden text-left"
                                        data-product-carousel-open
                                        aria-label="Open image preview"
                                    >
                                        <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-full w-full object-cover" data-product-carousel-image>
                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-12 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                                    </button>
                                    @if ($previewImages->count() > 1)
                                        <button type="button" class="absolute left-4 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-800/75 text-3xl leading-none text-white transition hover:bg-slate-900" data-product-carousel-prev aria-label="Previous image">&#8249;</button>
                                        <button type="button" class="absolute right-4 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-800/75 text-3xl leading-none text-white transition hover:bg-slate-900" data-product-carousel-next aria-label="Next image">&#8250;</button>
                                    @endif
                                </div>
                                @if ($previewImages->count() > 1)
                                    <div class="flex items-center justify-center gap-3 py-5" data-product-carousel-dots>
                                        @foreach ($previewImages as $imageIndex => $image)
                                            <button
                                                type="button"
                                                class="h-3 w-3 rounded-full border border-stone-400 bg-white transition"
                                                data-product-carousel-dot="{{ $imageIndex }}"
                                                aria-label="Show image {{ $imageIndex + 1 }}"
                                                aria-pressed="{{ $imageIndex === 0 ? 'true' : 'false' }}"
                                            ></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex h-[26rem] items-center justify-center bg-[linear-gradient(135deg,_#dbeafe,_#fff7ed_55%,_#ecfeff)] px-8 text-center">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                                    <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                                </div>
                            </div>
                        @endif
                        <div class="flex-1 p-8">
                            <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                            <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                            <p class="mt-4 text-sm leading-7 text-stone-500">View the gallery for this transport option and review the service details in the card beside it.</p>
                        </div>
                    </div>

                </div>
            </div>

            <aside class="space-y-6 h-full lg:col-span-2">
                <section class="h-full rounded-[2rem] border border-emerald-500/30 bg-white p-6 shadow-sm">
                        <div class="rounded-3xl bg-stone-50 p-5">
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-700">Product Description</p>
                            <p class="mt-3 text-sm leading-7 text-stone-600">
                                {{ $product->summary ?: $product->description }}
                            </p>
                        </div>

                    <div class="mt-6 space-y-4 text-sm text-stone-700">
                        <div class="flex justify-between gap-4"><span class="font-semibold">Description</span><span>{{ $product->name }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Region</span><span>{{ $product->location }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Duration</span><span>{{ $product->duration }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Capacity</span><span>{{ $product->capacity ?? 'Flexible' }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Availability</span><span>Daily / Subject to booking</span></div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <div class="rounded-[1.25rem] border border-stone-200 bg-stone-50 px-4 py-3 text-sm leading-6 text-stone-600">
                            Transport listings are information-only here. Please review the vehicle details and contact the team directly for arrangement support.
                        </div>
                        <a href="{{ route('booking.create', [$bookingRouteParameter => $product->id, 'mode' => 'enquiry']) }}" class="rounded-full border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-600 transition hover:bg-amber-50">Send Enquiry</a>
                    </div>
                </section>
            </aside>
        </section>
        @else
        <section class="grid gap-8 lg:grid-cols-2">
            <div>
                <div>
                    <div class="overflow-hidden" style="background: #ffffff;">
                        @if ($primaryImage)
                            <div class="px-5 pt-5" data-product-carousel>
                                <div class="relative mx-auto overflow-hidden bg-white" style="width: min(100%, 720px); height: 420px;">
                                    <button
                                        type="button"
                                        class="relative block h-full w-full overflow-hidden text-left"
                                        data-product-carousel-open
                                        aria-label="Open image preview"
                                    >
                                        <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-full w-full object-cover" data-product-carousel-image>
                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-12 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                                    </button>
                                    @if ($previewImages->count() > 1)
                                        <button type="button" class="absolute left-4 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-800/75 text-3xl leading-none text-white transition hover:bg-slate-900" data-product-carousel-prev aria-label="Previous image">&#8249;</button>
                                        <button type="button" class="absolute right-4 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-800/75 text-3xl leading-none text-white transition hover:bg-slate-900" data-product-carousel-next aria-label="Next image">&#8250;</button>
                                    @endif
                                </div>
                                @if ($previewImages->count() > 1)
                                    <div class="flex items-center justify-center gap-3 py-5" data-product-carousel-dots>
                                        @foreach ($previewImages as $imageIndex => $image)
                                            <button
                                                type="button"
                                                class="h-3 w-3 rounded-full border border-stone-400 bg-white transition"
                                                data-product-carousel-dot="{{ $imageIndex }}"
                                                aria-label="Show image {{ $imageIndex + 1 }}"
                                                aria-pressed="{{ $imageIndex === 0 ? 'true' : 'false' }}"
                                            ></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex h-[26rem] items-center justify-center bg-[linear-gradient(135deg,_#dbeafe,_#fff7ed_55%,_#ecfeff)] px-8 text-center">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                                    <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <section class="p-0" style="background: #ffffff;">
                    <div class="border-b border-stone-200 pb-6">
                        <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                        <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                    </div>

                    <div class="mt-6 overflow-hidden rounded-[1.2rem] border" style="border-color: #455499;">
                        <div class="grid overflow-hidden md:grid-cols-[1fr_13rem]">
                            <div class="flex items-center gap-4 bg-white px-6 py-5">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl text-white" style="background: #455499; box-shadow: 0 10px 18px rgba(69,84,153,0.28);">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path d="M10.59 13.41 9.17 12a1 1 0 0 0-1.41 1.41l2.12 2.12a1 1 0 0 0 1.41 0l5.66-5.66A1 1 0 0 0 15.54 8.46z"/><path d="M12 2a3 3 0 0 0-3 3v1H7a3 3 0 0 0-3 3v3.59a3 3 0 0 0 .88 2.12l5.41 5.41a3 3 0 0 0 2.12.88H17a3 3 0 0 0 3-3v-7.59a3 3 0 0 0-.88-2.12l-6-6A3 3 0 0 0 12 2zm-1 4V5a1 1 0 0 1 2 0v1z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-stone-500">Starting From</p>
                                    @if ($product->has_active_discount)
                                        <p class="mt-1 text-sm font-medium text-stone-400 line-through">
                                            <span class="currency-price" data-myr="{{ $originalStartingPrice }}" data-currency-decimals="2">MYR {{ number_format($originalStartingPrice, 2) }}</span>
                                        </p>
                                    @endif
                                    <div class="mt-1 flex items-end gap-2">
                                        <span class="currency-price text-3xl font-bold leading-none" data-myr="{{ $startingPrice }}" data-currency-decimals="0" style="color: #455499;">MYR {{ number_format($startingPrice, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-2 px-5 py-5 text-center text-sm font-semibold uppercase tracking-[0.12em] text-white" style="background: #455499;">
                                <span class="price-info-tooltip">
                                    <span class="inline-flex h-5 w-5 items-center justify-center">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-6h2zm0-8h-2V7h2z"/></svg>
                                    </span>
                                    <span class="price-info-tooltip-bubble">{{ $startingPriceTooltip }}</span>
                                </span>
                                <span>Price Value Guarantee</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-[1.5rem] border border-stone-200 bg-white px-6 py-5">
                        <div class="grid gap-x-10 gap-y-5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($detailCards as $detail)
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center text-[#8cb000]">
                                        @switch($detail['icon'])
                                            @case('code')
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16"/><path d="M7 4h10"/><path d="M6 12h12"/><path d="M5 17h14"/><path d="M8 20h8"/></svg>
                                                @break
                                            @case('time')
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="4" width="14" height="14" rx="2"/><path d="M8 2v4"/><path d="M16 2v4"/><path d="M5 9h14"/></svg>
                                                @break
                                            @case('age')
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0z"/></svg>
                                                @break
                                            @case('calendar')
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M3 11h18"/><path d="M8 15h.01"/><path d="M12 15h.01"/><path d="M16 15h.01"/></svg>
                                                @break
                                            @case('pickup')
                                            @case('dropoff')
                                            @case('location')
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path d="M12 22s6-5.69 6-11a6 6 0 1 0-12 0c0 5.31 6 11 6 11zm0-8a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                                                @break
                                            @case('pax')
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>
                                                @break
                                            @default
                                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 2.5"/></svg>
                                        @endswitch
                                    </span>
                                    <div class="text-[1rem] leading-7 text-stone-700">
                                        <span class="font-semibold text-stone-800">{{ $detail['label'] }} :</span>
                                        <span>{{ $detail['value'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('booking.create', [$bookingRouteParameter => $product->id, 'mode' => 'enquiry']) }}" class="rounded-full border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-600 transition hover:bg-amber-50">Send Enquiry</a>
                        <a href="{{ route('booking.create', [$bookingRouteParameter => $product->id, 'action' => 'reserve']) }}" class="rounded-full border border-emerald-600 px-5 py-3 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50">Reserve Now</a>
                        <a href="{{ route('booking.create', [$bookingRouteParameter => $product->id, 'action' => 'instant_book']) }}" class="rounded-full bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">Instant Book</a>
                    </div>
                </section>
            </aside>
        </section>
        @endif

        @if ($product->category !== 'transport')
            <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-3xl font-semibold text-stone-900">2026 Pricing</h2>
                        <p class="mt-2 text-sm leading-6 text-stone-600">Malaysia and international price lists are shown side by side for easier comparison.</p>
                    </div>
                </div>
                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="overflow-hidden border bg-white" style="border-color: #455499;">
                        <div class="px-5 py-4 text-center text-lg font-semibold" style="background-color: rgba(69, 84, 153, 0.12); color: #455499;">Malaysian Price</div>
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-stone-100 text-stone-700">
                                <tr>
                                    <th class="px-5 py-3">Group Size</th>
                                    <th class="px-5 py-3">Price Per Person</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-stone-700">
                                @foreach ($malaysiaPricingTiers as $tier)
                                    <tr class="border-t border-stone-200">
                                        <td class="px-5 py-4">
                                            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Group Size</div>
                                            <div class="mt-2 font-semibold" style="color: #455499;">{{ $tier['label'] }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if ($tier['enquire'])
                                                <div class="font-semibold text-stone-900">Please Enquire</div>
                                            @else
                                                <div class="grid gap-3 md:grid-cols-2">
                                                    <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
                                                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Adult</div>
                                                        <div class="mt-1 font-semibold text-stone-900">
                                                            <span class="currency-price" data-myr="{{ $tier['adult_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['adult_price'], 2) }}</span>
                                                        </div>
                                                        @if (($tier['original_adult_price'] ?? null) !== null && $tier['original_adult_price'] > $tier['adult_price'])
                                                            <div class="mt-1 text-xs text-stone-400 line-through">
                                                                <span class="currency-price" data-myr="{{ $tier['original_adult_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['original_adult_price'], 2) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="rounded-2xl border px-4 py-3" style="border-color: #f4d35e; background-color: #fff8db;">
                                                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Child / Kid</div>
                                                        <div class="mt-1 font-semibold" style="color: #111111;">
                                                            <span class="currency-price" data-myr="{{ $tier['child_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['child_price'], 2) }}</span>
                                                        </div>
                                                        @if (($tier['original_child_price'] ?? null) !== null && $tier['original_child_price'] > $tier['child_price'])
                                                            <div class="mt-1 text-xs line-through" style="color: #6b7280;">
                                                                <span class="currency-price" data-myr="{{ $tier['original_child_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['original_child_price'], 2) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="overflow-hidden border bg-white" style="border-color: #22c55e;">
                        <div class="px-5 py-4 text-center text-lg font-semibold" style="background-color: rgba(34, 197, 94, 0.18); color: #15803d;">Non-Malaysian Price</div>
                        <table class="min-w-full text-left text-sm">
                            <thead class="text-stone-700" style="background-color: #f3f4f6;">
                                <tr>
                                    <th class="px-5 py-3">Group Size</th>
                                    <th class="px-5 py-3">Price Per Person</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-stone-700">
                                @foreach ($internationalPricingTiers as $tier)
                                    <tr class="border-t border-stone-200">
                                        <td class="px-5 py-4">
                                            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Group Size</div>
                                            <div class="mt-2 font-semibold" style="color: #15803d;">{{ $tier['label'] }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if ($tier['enquire'])
                                                <div class="font-semibold text-stone-900">Please Enquire</div>
                                            @else
                                                <div class="grid gap-3 md:grid-cols-2">
                                                    <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
                                                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Adult</div>
                                                        <div class="mt-1 font-semibold text-stone-900">
                                                            <span class="currency-price" data-myr="{{ $tier['adult_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['adult_price'], 2) }}</span>
                                                        </div>
                                                        @if (($tier['original_adult_price'] ?? null) !== null && $tier['original_adult_price'] > $tier['adult_price'])
                                                            <div class="mt-1 text-xs text-stone-400 line-through">
                                                                <span class="currency-price" data-myr="{{ $tier['original_adult_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['original_adult_price'], 2) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="rounded-2xl border px-4 py-3" style="border-color: #f4d35e; background-color: #fff8db;">
                                                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Child / Kid</div>
                                                        <div class="mt-1 font-semibold" style="color: #111111;">
                                                            <span class="currency-price" data-myr="{{ $tier['child_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['child_price'], 2) }}</span>
                                                        </div>
                                                        @if (($tier['original_child_price'] ?? null) !== null && $tier['original_child_price'] > $tier['child_price'])
                                                            <div class="mt-1 text-xs line-through" style="color: #6b7280;">
                                                                <span class="currency-price" data-myr="{{ $tier['original_child_price'] }}" data-currency-decimals="2">MYR {{ number_format($tier['original_child_price'], 2) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="mt-4 w-full min-w-0 overflow-hidden" data-itinerary-section>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                            data-itinerary-toggle
                            aria-expanded="false"
                        >
                            <span class="flex items-center gap-4">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M9.55 16.6 4.8 11.85l1.4-1.4 3.35 3.35 8.25-8.25 1.4 1.4z"/></svg>
                                </span>
                                <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Itinerary</span>
                            </span>
                            <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-itinerary-icon>
                                <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                            </span>
                        </button>
                        @if ($structuredItineraryItems->isNotEmpty())
                            <div class="mt-4 hidden w-full min-w-0 overflow-hidden" data-itinerary-panel>
                                <div class="grid gap-5 justify-items-center xl:gap-6">
                                @foreach ($groupedItineraryDayRows as $dayRow)
                                    <div class="flex w-full flex-wrap justify-center items-start gap-5 xl:gap-6">
                                    @foreach ($dayRow as $day)
                                    <section class="max-w-full min-w-0 overflow-hidden border border-[#d8e3f7] bg-white shadow-[0_16px_34px_rgba(15,23,42,0.08)]" style="width: 600px; max-width: 600px; flex: 0 0 600px;">
                                        <div class="flex w-full items-center justify-between gap-2 border-b border-[#d8e3f7] px-3 py-3" style="background: linear-gradient(135deg, #f8fbff 0%, #edf4ff 100%);">
                                            <h3 class="text-base font-semibold text-stone-900" style="font-family: 'Oswald', sans-serif; letter-spacing: 0.04em;">{{ $day['label'] }}</h3>
                                            <div class="flex items-center gap-3">
                                                <span class="rounded-full px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-[0.12em] text-emerald-700" style="background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%); border: 1px solid rgba(16,185,129,0.18);">
                                                    {{ $day['items']->count() }} stop{{ $day['items']->count() === 1 ? '' : 's' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="w-full min-w-0 px-0 pb-0 pt-0">
                                            <div class="w-full min-w-0 overflow-hidden bg-white">
                                                <div class="w-full min-w-0 overflow-hidden">
                                                    <table class="w-full min-w-0 table-fixed border-collapse text-[0.9rem]" style="width: 600px; max-width: 600px;">
                                                    <colgroup>
                                                        <col style="width: 124px;">
                                                        <col style="width: auto;">
                                                    </colgroup>
                                                    <thead class="text-white" style="background: linear-gradient(135deg, #315fbd 0%, #244b98 100%);">
                                                        <tr>
                                                            <th class="border-b border-[#244b98] px-3.5 py-1.5 text-center text-[0.7rem] font-semibold uppercase tracking-[0.12em]">Time</th>
                                                            <th class="border-b border-[#244b98] px-6 py-1.5 text-left text-[0.7rem] font-semibold uppercase tracking-[0.12em]">Activity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white text-stone-700">
                                                        @foreach ($day['items'] as $item)
                                                            <tr class="{{ $loop->last ? '' : 'border-b border-[#e8eef6]' }}">
                                                                <td class="px-3.5 py-2 text-center align-top text-[0.7rem] font-semibold text-sky-700 whitespace-nowrap">
                                                                    {{ filled($item['time'] ?? null) ? $item['time'] : 'Flexible time' }}
                                                                </td>
                                                                <td class="px-6 py-2 text-left text-[0.7rem] leading-4 text-stone-700" style="word-break: break-word; overflow-wrap: anywhere;">
                                                                    {{ $item['activity'] ?? '' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    @endforeach
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @elseif ($itineraryItems->isNotEmpty())
                            <div class="mt-4 hidden w-full min-w-0 overflow-hidden" data-itinerary-panel>
                                <div class="w-full overflow-hidden rounded-[1.5rem] border border-stone-200">
                                    @foreach ($itineraryItems as $item)
                                        <div class="{{ $loop->last ? '' : 'border-b border-stone-200' }} px-5 py-4 text-sm leading-7 text-stone-700">
                                            {{ $item }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-4 hidden w-full min-w-0 overflow-hidden" data-itinerary-panel>
                                <div class="rounded-[1.5rem] border border-stone-200 bg-white px-5 py-5 text-sm leading-7 text-stone-500">
                                    Detailed itinerary will be updated soon for this product.
                                </div>
                            </div>
                        @endif
            </section>

            <section class="mt-4 w-full min-w-0 overflow-hidden" data-package-details-section>
                <button
                    type="button"
                    class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                    data-package-details-toggle
                    aria-expanded="false"
                >
                    <span class="flex items-center gap-4">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M9.55 16.6 4.8 11.85l1.4-1.4 3.35 3.35 8.25-8.25 1.4 1.4z"/></svg>
                        </span>
                        <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Package Details</span>
                    </span>
                    <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-package-details-icon>
                        <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                    </span>
                </button>
                <div class="mt-4 hidden" data-package-details-panel>
                    <section data-package-details style="background: #ffffff;">
                        <div class="grid gap-0 border-y border-stone-200 md:grid-cols-4" style="background-color: #455499;">
                            @foreach ($serviceInfoSections as $key => $section)
                                <button
                                    type="button"
                                    class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-[0.22em] transition"
                                    style="background-color: {{ $loop->first ? '#ffffff' : '#455499' }}; color: {{ $loop->first ? '#111111' : '#ffffff' }}; border: 1px solid rgba(0,0,0,0.65);"
                                    data-package-detail-tab="{{ $key }}"
                                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                >
                                    {{ $key === 'inclusion' ? 'Includes' : ($key === 'exclusion' ? 'Excludes' : $section['title']) }}
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-2">
                            @foreach ($serviceInfoSections as $key => $section)
                                <div @class(['hidden' => !$loop->first]) data-package-detail-panel="{{ $key }}">
                                    @if (!empty($section['items']))
                                        @php
                                            $singleRichEntry = count($section['items']) === 1
                                                && filled($section['items'][0]['html'] ?? null);
                                        @endphp
                                        @if ($singleRichEntry)
                                            <div class="text-sm leading-8 text-stone-700" data-package-detail-html>{!! $section['items'][0]['html'] !!}</div>
                                        @else
                                            <ul class="space-y-3 text-sm leading-8 text-stone-600">
                                                @foreach ($section['items'] as $entry)
                                                    @php
                                                        $entrySymbol = $entry['symbol'] ?? ($key === 'exclusion' ? 'x' : ($key === 'inclusion' ? 'tick' : 'exclamation'));
                                                        $entryText = $entry['text'] ?? '';
                                                        $entryHtml = $entry['html'] ?? null;
                                                        $entryDisplaySymbol = match ($entrySymbol) {
                                                            'round' => '•',
                                                            'x' => '✕',
                                                            'exclamation' => '!',
                                                            default => '✓',
                                                        };
                                                    @endphp
                                                    <li class="flex items-start gap-3 {{ $entrySymbol === 'round' ? 'pl-8 text-stone-500' : 'font-semibold text-stone-700' }}">
                                                        <span class="mt-1 text-base font-bold leading-none {{ $entrySymbol === 'round' ? 'text-stone-400' : 'text-stone-600' }}">{{ $entryDisplaySymbol }}</span>
                                                        <div class="min-w-0 flex-1" data-package-detail-html>{!! filled($entryHtml) ? $entryHtml : e($entryText) !!}</div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @else
                                        <p class="text-sm leading-7 text-stone-500">Details will be confirmed with this package.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </section>

            @if ($showOptionalActivities)
                <section class="mt-4 w-full min-w-0 overflow-hidden" data-optional-activities-section>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                        data-optional-activities-toggle
                        aria-expanded="false"
                    >
                        <span class="flex items-center gap-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M9.55 16.6 4.8 11.85l1.4-1.4 3.35 3.35 8.25-8.25 1.4 1.4z"/></svg>
                            </span>
                            <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Optional Activities</span>
                        </span>
                        <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-optional-activities-icon>
                            <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                        </span>
                    </button>
                    <div class="mt-4 hidden" data-optional-activities-panel>
                        <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5">
                            @if (filled($optionalActivitiesDescription))
                                <p class="text-sm leading-7 text-stone-600">{{ $optionalActivitiesDescription }}</p>
                            @endif
                            @if ($optionalActivitiesRows->isNotEmpty())
                                <div class="{{ filled($optionalActivitiesDescription) ? 'mt-4' : '' }} mx-auto max-w-4xl overflow-x-auto rounded-[1rem] border border-stone-200">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-stone-100 text-stone-700">
                                            <tr>
                                                <th class="px-4 py-3 text-center font-semibold">Activity</th>
                                                <th class="px-4 py-3 text-center font-semibold">Rate / Price</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-stone-200 bg-white text-stone-600">
                                            @foreach ($optionalActivitiesRows as $activity)
                                                <tr>
                                                    <td class="px-4 py-3 text-center align-top font-medium text-stone-700">{{ $activity['name'] ?: '-' }}</td>
                                                    <td class="px-4 py-3 text-center align-top">{{ $activity['rate'] ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            @if (filled($recommendedAttireHtml))
                <section class="mt-4 w-full min-w-0 overflow-hidden" data-recommended-attire-section>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                        data-recommended-attire-toggle
                        aria-expanded="false"
                    >
                        <span class="flex items-center gap-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M12 3a2 2 0 0 1 2 2v1h1.25a2.75 2.75 0 0 1 2.75 2.75V10H6V8.75A2.75 2.75 0 0 1 8.75 6H10V5a2 2 0 0 1 2-2Zm-6 8h12v6.75A2.25 2.25 0 0 1 15.75 20h-7.5A2.25 2.25 0 0 1 6 17.75Z"/></svg>
                            </span>
                            <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Recommended Attire</span>
                        </span>
                        <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-recommended-attire-icon>
                            <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                        </span>
                    </button>
                    <div class="mt-4 hidden" data-recommended-attire-panel>
                        <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5 text-sm leading-7 text-stone-700" data-package-detail-html>{!! $recommendedAttireHtml !!}</div>
                        </div>
                </section>
            @endif

            @if (filled($tourHighlightsHtml))
                <section class="mt-4 w-full min-w-0 overflow-hidden" data-tour-highlight-section>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                        data-tour-highlight-toggle
                        aria-expanded="false"
                    >
                        <span class="flex items-center gap-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M9.55 16.6 4.8 11.85l1.4-1.4 3.35 3.35 8.25-8.25 1.4 1.4z"/></svg>
                            </span>
                            <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Tour Highlight</span>
                        </span>
                        <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-tour-highlight-icon>
                            <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                        </span>
                    </button>
                    <div class="mt-4 hidden" data-tour-highlight-panel>
                        <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5 text-sm leading-7 text-stone-700" data-package-detail-html>{!! $tourHighlightsHtml !!}</div>
                    </div>
                </section>
            @endif

            @if (filled($thingsYouShouldKnowHtml))
            <section class="mt-4 w-full min-w-0 overflow-hidden" data-things-you-should-know-section>
                <button
                    type="button"
                    class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                    data-things-you-should-know-toggle
                    aria-expanded="false"
                >
                    <span class="flex items-center gap-4">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 15h-2v-6h2zm0-8h-2V7h2z"/></svg>
                        </span>
                        <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Things You Should Know</span>
                    </span>
                    <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-things-you-should-know-icon>
                        <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                    </span>
                </button>
                <div class="mt-4 hidden" data-things-you-should-know-panel>
                    <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5 text-sm leading-7 text-stone-700" data-package-detail-html>{!! $thingsYouShouldKnowHtml !!}</div>
                </div>
            </section>
            @endif

            @if (filled($usefulTravelTipsHtml))
            <section class="mt-4 w-full min-w-0 overflow-hidden" data-useful-travel-tips-section>
                <button
                    type="button"
                    class="flex w-full items-center justify-between gap-4 rounded-[1rem] border border-stone-300 bg-stone-50/80 px-5 py-5 text-left"
                    data-useful-travel-tips-toggle
                    aria-expanded="false"
                >
                    <span class="flex items-center gap-4">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: #455499;">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true"><path d="M12 2 9.5 8.5 3 9.3l5 4.5L6.6 21 12 17.7 17.4 21 16 13.8l5-4.5-6.5-.8z"/></svg>
                        </span>
                        <span class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-800">Useful Travel Tips</span>
                    </span>
                    <span class="inline-flex text-stone-400 transition-[transform,color] duration-200" data-useful-travel-tips-icon>
                        <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                    </span>
                </button>
                <div class="mt-4 hidden" data-useful-travel-tips-panel>
                    <div class="rounded-[1.5rem] border border-stone-200 bg-white p-5 text-sm leading-7 text-stone-700" data-package-detail-html>{!! $usefulTravelTipsHtml !!}</div>
                </div>
            </section>
            @endif

            <section class="relative mt-8 overflow-hidden rounded-[1.75rem] border border-stone-200/80 shadow-sm">
                <!-- WHY CHOOSE US BOX -->
                <div class="transport-box" style="margin-top: 1rem; border-radius: 1rem; background: #ffffff; padding: 1.8rem 2.5rem 2.5rem; min-height: 240px; box-shadow: 0 14px 30px rgba(15,23,42,0.12);">

                    <h3 style="margin: 0; text-align: center; font-family: 'Oswald', sans-serif; font-size: 1.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: #9b4a14;">
                        Why Choose Us?
                    </h3>

                    <div class="transport-features" style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.9rem;">
                        @foreach ($transportFeatures as $feature)
                            <div class="transport-feature-item" style="position: relative; display: flex; width: 8.5rem; flex-direction: column; align-items: center; text-align: center;">

                                <div style="display: flex; height: 6.4rem; width: 6.4rem; align-items: center; justify-content: center; border-radius: 999px; border: 2.5px solid #2f63bc; background: #fff; color: #2f63bc; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

                                    @if ($feature['icon'] === 'spark')
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="24" cy="31" r="12"/>
                                            <path d="M21 31h6"/>
                                            <path d="M24 28v6"/>
                                            <path d="M39 20c5 0 9 4 9 9 0 8-9 14-16 20-2-1-4-3-6-5"/>
                                            <path d="m41 18 2 4 4 2-4 2-2 4-2-4-4-2 4-2 2-4Z"/>
                                        </svg>
                                    @elseif ($feature['icon'] === 'shield')
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M32 10 48 16v13c0 12-7 19-16 25-9-6-16-13-16-25V16l16-6Z"/>
                                            <path d="m24 31 6 6 10-12"/>
                                        </svg>
                                    @elseif ($feature['icon'] === 'driver')
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="17" r="7"/>
                                            <path d="M19 50v-8c0-8 6-13 13-13s13 5 13 13v8"/>
                                            <circle cx="32" cy="42" r="8"/>
                                            <path d="M24 42h16"/>
                                            <path d="M32 34v16"/>
                                        </svg>
                                    @else
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="14" y="15" width="28" height="34" rx="4"/>
                                            <path d="M21 24h14"/>
                                            <path d="M21 31h14"/>
                                            <path d="M21 38h8"/>
                                            <rect x="41" y="21" width="10" height="16" rx="2"/>
                                            <path d="m44 30 2 2 4-5"/>
                                        </svg>
                                    @endif

                                </div>

                                <p style="max-width: 8rem; margin-top: 0.95rem; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; line-height: 1.1rem; letter-spacing: 0.1em; color: #9b4a14;">
                                    {{ $feature['label'] }}
                                </p>

                            </div>
                        @endforeach
                    </div>

                </div>
            </section>

        @endif

    </main>

    @include('partials.footer')
</div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const packageDetailsSection = document.querySelector('[data-package-details-section]');
            const tourHighlightSection = document.querySelector('[data-tour-highlight-section]');
            const optionalActivitiesSection = document.querySelector('[data-optional-activities-section]');
            const recommendedAttireSection = document.querySelector('[data-recommended-attire-section]');

            if (packageDetailsSection?.parentNode) {
                if (tourHighlightSection) {
                    packageDetailsSection.parentNode.insertBefore(tourHighlightSection, packageDetailsSection);
                }

                if (optionalActivitiesSection) {
                    packageDetailsSection.parentNode.insertBefore(optionalActivitiesSection, packageDetailsSection.nextSibling);
                }

                if (recommendedAttireSection) {
                    packageDetailsSection.parentNode.insertBefore(recommendedAttireSection, optionalActivitiesSection ? optionalActivitiesSection.nextSibling : packageDetailsSection.nextSibling);
                }
            }

            document.querySelectorAll('[data-package-details]').forEach((detailsBlock) => {
                const tabs = Array.from(detailsBlock.querySelectorAll('[data-package-detail-tab]'));
                const panels = Array.from(detailsBlock.querySelectorAll('[data-package-detail-panel]'));

                if (!tabs.length || !panels.length) {
                    return;
                }

                const activateTab = (targetKey) => {
                    tabs.forEach((tab) => {
                        const isActive = tab.dataset.packageDetailTab === targetKey;
                        tab.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                        tab.style.backgroundColor = isActive ? '#ffffff' : '#455499';
                        tab.style.color = isActive ? '#111111' : '#ffffff';
                        tab.style.borderColor = 'rgba(0,0,0,0.65)';
                    });

                    panels.forEach((panel) => {
                        panel.classList.toggle('hidden', panel.dataset.packageDetailPanel !== targetKey);
                    });
                };

                tabs.forEach((tab) => {
                    tab.addEventListener('click', () => {
                        activateTab(tab.dataset.packageDetailTab || '');
                    });
                });

                activateTab(tabs[0].dataset.packageDetailTab || '');
            });

            document.querySelectorAll('[data-package-details-section]').forEach((section) => {
                const toggle = section.querySelector('[data-package-details-toggle]');
                const panel = section.querySelector('[data-package-details-panel]');
                const icon = section.querySelector('[data-package-details-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panel.classList.toggle('hidden', !expanded);
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            document.querySelectorAll('[data-itinerary-section]').forEach((section) => {
                const toggle = section.querySelector('[data-itinerary-toggle]');
                const panels = Array.from(section.querySelectorAll('[data-itinerary-panel]'));
                const icon = section.querySelector('[data-itinerary-icon]');

                if (!toggle || !panels.length || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panels.forEach((panel) => {
                        panel.classList.toggle('hidden', !expanded);
                    });
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            document.querySelectorAll('[data-recommended-attire-section]').forEach((section) => {
                const toggle = section.querySelector('[data-recommended-attire-toggle]');
                const panel = section.querySelector('[data-recommended-attire-panel]');
                const icon = section.querySelector('[data-recommended-attire-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panel.classList.toggle('hidden', !expanded);
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            document.querySelectorAll('[data-optional-activities-section]').forEach((section) => {
                const toggle = section.querySelector('[data-optional-activities-toggle]');
                const panel = section.querySelector('[data-optional-activities-panel]');
                const icon = section.querySelector('[data-optional-activities-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panel.classList.toggle('hidden', !expanded);
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            document.querySelectorAll('[data-things-you-should-know-section]').forEach((section) => {
                const toggle = section.querySelector('[data-things-you-should-know-toggle]');
                const panel = section.querySelector('[data-things-you-should-know-panel]');
                const icon = section.querySelector('[data-things-you-should-know-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panel.classList.toggle('hidden', !expanded);
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            document.querySelectorAll('[data-useful-travel-tips-section]').forEach((section) => {
                const toggle = section.querySelector('[data-useful-travel-tips-toggle]');
                const panel = section.querySelector('[data-useful-travel-tips-panel]');
                const icon = section.querySelector('[data-useful-travel-tips-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panel.classList.toggle('hidden', !expanded);
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            document.querySelectorAll('[data-tour-highlight-section]').forEach((section) => {
                const toggle = section.querySelector('[data-tour-highlight-toggle]');
                const panel = section.querySelector('[data-tour-highlight-panel]');
                const icon = section.querySelector('[data-tour-highlight-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                const setExpanded = (expanded) => {
                    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    panel.classList.toggle('hidden', !expanded);
                    icon.classList.toggle('rotate-180', expanded);
                    icon.classList.toggle('text-stone-400', !expanded);
                    icon.classList.toggle('text-slate-700', expanded);
                };

                toggle.addEventListener('click', () => {
                    setExpanded(toggle.getAttribute('aria-expanded') !== 'true');
                });

                setExpanded(false);
            });

            const previewImages = @json($previewImages);

            document.querySelectorAll('[data-product-carousel]').forEach((carousel) => {
                const image = carousel.querySelector('[data-product-carousel-image]');
                const openButton = carousel.querySelector('[data-product-carousel-open]');
                const prev = carousel.querySelector('[data-product-carousel-prev]');
                const next = carousel.querySelector('[data-product-carousel-next]');
                const dots = Array.from(carousel.querySelectorAll('[data-product-carousel-dot]'));

                if (!image || !openButton || !previewImages.length) {
                    return;
                }

                let carouselIndex = 0;

                const renderCarousel = () => {
                    const currentImage = previewImages[carouselIndex];

                    if (!currentImage) {
                        return;
                    }

                    image.src = currentImage.src;
                    image.alt = currentImage.alt;

                    dots.forEach((dot) => {
                        const dotIndex = Number.parseInt(dot.dataset.productCarouselDot || '0', 10);
                        const isActive = dotIndex === carouselIndex;
                        dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                        dot.style.backgroundColor = isActive ? '#9ca3af' : '#ffffff';
                    });
                };

                prev?.addEventListener('click', (event) => {
                    event.stopPropagation();
                    carouselIndex = (carouselIndex - 1 + previewImages.length) % previewImages.length;
                    renderCarousel();
                });

                next?.addEventListener('click', (event) => {
                    event.stopPropagation();
                    carouselIndex = (carouselIndex + 1) % previewImages.length;
                    renderCarousel();
                });

                dots.forEach((dot) => {
                    dot.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const dotIndex = Number.parseInt(dot.dataset.productCarouselDot || '0', 10);

                        if (Number.isInteger(dotIndex)) {
                            carouselIndex = dotIndex;
                            renderCarousel();
                        }
                    });
                });

                openButton.addEventListener('click', () => {
                    openModal(carouselIndex);
                });

                renderCarousel();
            });

            const modal = document.getElementById('product-image-preview-modal');
            const imageFrame = document.getElementById('product-image-preview-frame');
            const modalImage = document.getElementById('product-image-preview-image');
            const trademark = document.getElementById('product-image-preview-trademark');
            const closeButton = document.getElementById('product-image-preview-close');
            const prevButton = document.getElementById('product-image-preview-prev');
            const nextButton = document.getElementById('product-image-preview-next');
            const countLabel = document.getElementById('product-image-preview-count');
            let activeIndex = 0;

            if (!modal || !imageFrame || !modalImage || !trademark || !closeButton || !prevButton || !nextButton || !countLabel || !previewImages.length) {
                return;
            }

            const positionTrademark = () => {
                const naturalWidth = modalImage.naturalWidth;
                const naturalHeight = modalImage.naturalHeight;
                const frameWidth = imageFrame.clientWidth;
                const frameHeight = imageFrame.clientHeight;

                if (!naturalWidth || !naturalHeight || !frameWidth || !frameHeight) {
                    trademark.style.right = '1.5rem';
                    trademark.style.bottom = '1.5rem';
                    return;
                }

                const imageRatio = naturalWidth / naturalHeight;
                const frameRatio = frameWidth / frameHeight;

                let renderedWidth = frameWidth;
                let renderedHeight = frameHeight;

                if (imageRatio > frameRatio) {
                    renderedHeight = frameWidth / imageRatio;
                } else {
                    renderedWidth = frameHeight * imageRatio;
                }

                const offsetX = (frameWidth - renderedWidth) / 2;
                const offsetY = (frameHeight - renderedHeight) / 2;

                trademark.style.right = `${offsetX + 24}px`;
                trademark.style.bottom = `${offsetY + 24}px`;
            };

            const renderImage = () => {
                const currentImage = previewImages[activeIndex];

                if (!currentImage) {
                    return;
                }

                modalImage.src = currentImage.src;
                modalImage.alt = currentImage.alt;
                countLabel.textContent = `Image ${activeIndex + 1} of ${previewImages.length}`;
                modalImage.onload = () => {
                    positionTrademark();
                };
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modalImage.src = '';
                modalImage.alt = '';
            };

            const openModal = (index) => {
                activeIndex = Number.isInteger(index) && index >= 0 && index < previewImages.length ? index : 0;
                renderImage();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const showPrevious = () => {
                activeIndex = (activeIndex - 1 + previewImages.length) % previewImages.length;
                renderImage();
            };

            const showNext = () => {
                activeIndex = (activeIndex + 1) % previewImages.length;
                renderImage();
            };

            document.querySelectorAll('[data-image-preview-trigger]').forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    openModal(Number.parseInt(trigger.dataset.imagePreviewTrigger || '0', 10));
                });
            });

            closeButton.addEventListener('click', closeModal);
            prevButton.addEventListener('click', showPrevious);
            nextButton.addEventListener('click', showNext);

            modal.addEventListener('click', (event) => {
                if (event.target !== modal) {
                    return;
                }

                closeModal();
            });

            document.addEventListener('keydown', (event) => {
                if (modal.classList.contains('hidden')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeModal();
                } else if (event.key === 'ArrowLeft') {
                    showPrevious();
                } else if (event.key === 'ArrowRight') {
                    showNext();
                }
            });

            window.addEventListener('resize', () => {
                if (!modal.classList.contains('hidden')) {
                    positionTrademark();
                }
            });
        });
    </script>
</x-layouts.app>
