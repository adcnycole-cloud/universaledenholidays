<x-layouts.app :title="$product->name.' | Universal Eden Holidays'">
    <main class="mx-auto max-w-[120rem] px-6 py-10 lg:px-10">
        <div class="mb-6 text-sm text-stone-500">
            <a href="{{ route('home') }}" class="hover:text-sky-700">Home</a>
            <span class="mx-2">›</span>
            <span class="capitalize">{{ $product->category }}</span>
            <span class="mx-2">›</span>
            <span class="text-stone-700">{{ $product->name }}</span>
        </div>

        @php
            $isTransport = $product->category === 'transport';
            $galleryImages = $product->gallery_urls;
            $primaryImage = $galleryImages[0] ?? null;
            $thumbnailImages = collect($galleryImages)->slice(1, 4)->values();
            $remainingGalleryCount = max(count($galleryImages) - 5, 0);
            $startingPrice = (float) $product->discounted_malaysia_adult_price_myr;
            $originalStartingPrice = (float) $product->malaysia_adult_price_myr;
            $previewImages = collect($galleryImages)
                ->map(fn ($image, $index) => [
                    'src' => $image,
                    'alt' => $index === 0
                        ? $product->name
                        : $product->name.' gallery image '.($index + 1),
                ])
                ->values();
            $serviceInclusions = is_array($product->service_inclusions) ? $product->service_inclusions : [];
            $structuredServiceInclusions = collect($serviceInclusions)
                ->filter(fn ($item) => is_array($item) && array_key_exists('value', $item))
                ->values();
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
        @endphp

        @if ($isTransport)
        <section class="grid gap-8 lg:grid-cols-5 lg:items-stretch">
            <div class="h-full lg:col-span-3">
                <div>
                    <div class="flex h-full flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                        @if ($primaryImage)
                            <button
                                type="button"
                                class="relative block w-full overflow-hidden text-left"
                                data-image-preview-trigger="0"
                                aria-label="Open main image preview"
                            >
                                <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-[26rem] w-full object-cover">
                                <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-14 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                            </button>
                        @else
                            <div class="flex h-[26rem] items-center justify-center bg-[linear-gradient(135deg,_#dbeafe,_#fff7ed_55%,_#ecfeff)] px-8 text-center">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                                    <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                                </div>
                            </div>
                        @endif
                        @if ($thumbnailImages->isNotEmpty())
                            <div class="grid grid-cols-2 gap-3 border-t border-stone-200 bg-stone-50 p-4 md:grid-cols-4">
                                @foreach ($thumbnailImages as $index => $image)
                                    <button
                                        type="button"
                                        class="relative overflow-hidden rounded-[1.25rem] text-left"
                                        data-image-preview-trigger="{{ $index + 1 }}"
                                        aria-label="Open gallery image {{ $index + 2 }}"
                                    >
                                        <img src="{{ $image }}" alt="{{ $product->name }} gallery image {{ $index + 2 }}" class="h-28 w-full object-cover">
                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-6 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.35rem, -0.35rem);">
                                        @if ($remainingGalleryCount > 0 && $loop->last)
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/45 text-2xl font-semibold text-white">
                                                +{{ $remainingGalleryCount }}
                                            </div>
                                @endif
                                    </button>
                                @endforeach
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
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'mode' => 'enquiry']) }}" class="rounded-full border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-600 transition hover:bg-amber-50">Send Enquiry</a>
                    </div>
                </section>
            </aside>
        </section>
        @else
        <section class="grid gap-8 lg:grid-cols-2">
            <div>
                <div>
                    <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                        @if ($primaryImage)
                            <button
                                type="button"
                                class="relative block w-full overflow-hidden text-left"
                                data-image-preview-trigger="0"
                                aria-label="Open main image preview"
                            >
                                <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-[26rem] w-full object-cover">
                                <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-14 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                            </button>
                        @else
                            <div class="flex h-[26rem] items-center justify-center bg-[linear-gradient(135deg,_#dbeafe,_#fff7ed_55%,_#ecfeff)] px-8 text-center">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                                    <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                                </div>
                            </div>
                        @endif
                        @if ($thumbnailImages->isNotEmpty())
                            <div class="grid grid-cols-2 gap-3 border-t border-stone-200 bg-stone-50 p-4 md:grid-cols-4">
                                @foreach ($thumbnailImages as $index => $image)
                                    <button
                                        type="button"
                                        class="relative overflow-hidden rounded-[1.25rem] text-left"
                                        data-image-preview-trigger="{{ $index + 1 }}"
                                        aria-label="Open gallery image {{ $index + 2 }}"
                                    >
                                        <img src="{{ $image }}" alt="{{ $product->name }} gallery image {{ $index + 2 }}" class="h-28 w-full object-cover">
                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-6 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.35rem, -0.35rem);">
                                        @if ($remainingGalleryCount > 0 && $loop->last)
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/45 text-2xl font-semibold text-white">
                                                +{{ $remainingGalleryCount }}
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        <div class="p-8">
                            <p class="text-sm uppercase tracking-[0.3em] text-sky-700">{{ ucfirst($product->category) }}</p>
                            <h1 class="mt-3 font-['Prata'] text-4xl text-stone-900 md:text-5xl">{{ $product->name }}</h1>
                            <p class="mt-4 max-w-3xl text-base leading-8 text-stone-600">{{ $product->description }}</p>
                            <div class="mt-8 grid gap-4 md:grid-cols-3">
                                <div class="rounded-3xl bg-white/80 p-5">
                                    <p class="text-sm text-stone-500">Location</p>
                                    <p class="mt-2 text-lg font-semibold text-stone-900">{{ $product->location }}</p>
                                </div>
                                <div class="rounded-3xl bg-white/80 p-5">
                                    <p class="text-sm text-stone-500">Duration</p>
                                    <p class="mt-2 text-lg font-semibold text-stone-900">{{ $product->duration }}</p>
                                </div>
                                <div class="rounded-3xl bg-white/80 p-5">
                                    <p class="text-sm text-stone-500">Capacity</p>
                                    <p class="mt-2 text-lg font-semibold text-stone-900">{{ $product->capacity ?? 'Flexible' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <section class="rounded-[2rem] border border-emerald-500/30 bg-white p-6 shadow-sm">
                    <div class="mt-6 space-y-4 text-sm text-stone-700">
                        <div class="flex justify-between gap-4"><span class="font-semibold">Description</span><span>{{ $product->name }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Region</span><span>{{ $product->location }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Duration</span><span>{{ $product->duration }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Minimum</span><span>1 Pax</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Availability</span><span>Daily / Subject to booking</span></div>
                    </div>

                    <div class="mt-6 flex items-center justify-between gap-4 rounded-3xl bg-emerald-50 p-5">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-700">Starting From</p>
                            @if ($product->has_active_discount)
                                <p class="mt-2 text-sm font-medium text-stone-500 line-through">RM {{ number_format($originalStartingPrice, 2) }}</p>
                            @endif
                            <p class="mt-2 text-4xl font-bold text-emerald-700">RM {{ number_format($startingPrice, 2) }}</p>
                            <p class="mt-2 text-sm text-stone-500">Malaysia adult rate</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'mode' => 'enquiry']) }}" class="rounded-full border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-600 transition hover:bg-amber-50">Send Enquiry</a>
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'action' => 'reserve']) }}" class="rounded-full border border-emerald-600 px-5 py-3 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50">Reserve Now</a>
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'action' => 'instant_book']) }}" class="rounded-full bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">Instant Book</a>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-stone-900">Service Inclusion</h2>
                    <div class="mt-6 overflow-hidden rounded-3xl border border-stone-200">
                        <table class="min-w-full text-left text-sm">
                            <tbody class="bg-white text-stone-700">
                                @if ($structuredServiceInclusions->isNotEmpty())
                                    @foreach ($structuredServiceInclusions as $item)
                                        <tr class="{{ $loop->last ? '' : 'border-b border-stone-200' }}">
                                            <th class="w-48 bg-stone-50 px-5 py-4 font-semibold">{{ $item['label'] ?: 'Item '.$loop->iteration }}</th>
                                            <td class="px-5 py-4">{{ $item['value'] ?: '--' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b border-stone-200">
                                        <th class="w-48 bg-stone-50 px-5 py-4 font-semibold">Meals</th>
                                        <td class="px-5 py-4">{{ ($serviceInclusions['meals'] ?? '') ?: 'Subject to package or service arrangement.' }}</td>
                                    </tr>
                                    <tr class="border-b border-stone-200">
                                        <th class="bg-stone-50 px-5 py-4 font-semibold">Inclusion</th>
                                        <td class="px-5 py-4">{{ ($serviceInclusions['inclusion'] ?? '') ?: 'Core service delivery, support coordination, and supplier-side arrangements as stated.' }}</td>
                                    </tr>
                                    <tr class="border-b border-stone-200">
                                        <th class="bg-stone-50 px-5 py-4 font-semibold">Accommodation</th>
                                        <td class="px-5 py-4">{{ ($serviceInclusions['accommodation'] ?? '') ?: 'Included where relevant for package and overnight products.' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-stone-50 px-5 py-4 font-semibold">Exclusion</th>
                                        <td class="px-5 py-4">{{ ($serviceInclusions['exclusion'] ?? '') ?: 'Flights, personal travel insurance, personal spending, and unlisted add-ons.' }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
            </aside>
        </section>
        @endif

        @if ($product->category !== 'transport')
            @if ($itineraryItems->isNotEmpty())
                <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <h2 class="text-3xl font-semibold text-stone-900">Package Itinerary</h2>
                    @if ($structuredItineraryItems->isNotEmpty())
                        <div class="mt-6 space-y-4">
                            @foreach ($groupedItineraryDays as $day)
                                <section class="overflow-hidden rounded-[1.5rem] border border-stone-200 bg-stone-50">
                                    <div class="flex items-center justify-between gap-4 border-b border-stone-200 bg-white px-4 py-3">
                                        <div>
                                            <h3 class="text-xl font-semibold text-stone-900">{{ $day['label'] }}</h3>
                                        </div>
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                                            {{ $day['items']->count() }} stop{{ $day['items']->count() === 1 ? '' : 's' }}
                                        </span>
                                    </div>
                                    <div class="overflow-hidden">
                                        <table class="w-full table-fixed text-sm">
                                            <colgroup>
                                                <col style="width: 180px;">
                                                <col style="width: 44%;">
                                                <col style="width: auto;">
                                            </colgroup>
                                            <thead class="bg-stone-100/80 text-stone-600">
                                                <tr>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">Time</th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">Activity</th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white text-stone-700">
                                                @foreach ($day['items'] as $item)
                                                    <tr class="{{ $loop->last ? '' : 'border-b border-stone-200' }}">
                                                        <td class="px-4 py-3 text-center align-top font-semibold text-sky-700 whitespace-nowrap">
                                                            {{ filled($item['time'] ?? null) ? $item['time'] : 'Flexible time' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-justify leading-6">
                                                            {{ $item['activity'] ?? '' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-justify leading-6 text-stone-600">
                                                            {{ filled($item['notes'] ?? null) ? $item['notes'] : '--' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-6 grid gap-4">
                            @foreach ($itineraryItems as $item)
                                <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50 px-5 py-4 text-sm leading-7 text-stone-700">
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
            <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-3xl font-semibold text-stone-900">Market Pricing</h2>
                        <p class="mt-2 text-sm leading-6 text-stone-600">Malaysia and international price lists are shown side by side for easier comparison.</p>
                    </div>
                </div>
                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="overflow-hidden rounded-3xl border border-blue-200">
                        <div class="bg-blue-50 px-5 py-4 text-center text-lg font-semibold text-blue-700">Malaysia Market Pricing</div>
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
                                            <div class="font-semibold text-sky-700">Adult / Child</div>
                                            <div class="mt-1 text-stone-500">{{ $tier['label'] }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if ($tier['enquire'])
                                                <div class="font-semibold text-stone-900">Please Enquire</div>
                                            @else
                                                <div class="font-semibold text-stone-900">MYR {{ number_format($tier['adult_price'], 2) }}</div>
                                                @if (($tier['original_adult_price'] ?? null) !== null && $tier['original_adult_price'] > $tier['adult_price'])
                                                    <div class="mt-1 text-xs text-stone-400 line-through">MYR {{ number_format($tier['original_adult_price'], 2) }}</div>
                                                @endif
                                                <div class="mt-1 font-semibold text-rose-600">{{ number_format($tier['child_price'], 2) }}</div>
                                                @if (($tier['original_child_price'] ?? null) !== null && $tier['original_child_price'] > $tier['child_price'])
                                                    <div class="mt-1 text-xs text-stone-400 line-through">MYR {{ number_format($tier['original_child_price'], 2) }}</div>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="overflow-hidden rounded-3xl border border-amber-200">
                        <div class="bg-amber-50 px-5 py-4 text-center text-lg font-semibold text-amber-700">International Market Pricing</div>
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-stone-100 text-stone-700">
                                <tr>
                                    <th class="px-5 py-3">Group Size</th>
                                    <th class="px-5 py-3">Price Per Person</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white text-stone-700">
                                @foreach ($internationalPricingTiers as $tier)
                                    <tr class="border-t border-stone-200">
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-sky-700">Adult / Child</div>
                                            <div class="mt-1 text-stone-500">{{ $tier['label'] }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            @if ($tier['enquire'])
                                                <div class="font-semibold text-stone-900">Please Enquire</div>
                                            @else
                                                <div class="font-semibold text-stone-900">MYR {{ number_format($tier['adult_price'], 2) }}</div>
                                                @if (($tier['original_adult_price'] ?? null) !== null && $tier['original_adult_price'] > $tier['adult_price'])
                                                    <div class="mt-1 text-xs text-stone-400 line-through">MYR {{ number_format($tier['original_adult_price'], 2) }}</div>
                                                @endif
                                                <div class="mt-1 font-semibold text-rose-600">{{ number_format($tier['child_price'], 2) }}</div>
                                                @if (($tier['original_child_price'] ?? null) !== null && $tier['original_child_price'] > $tier['child_price'])
                                                    <div class="mt-1 text-xs text-stone-400 line-through">MYR {{ number_format($tier['original_child_price'], 2) }}</div>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        @endif

        @if ($product->category === 'package')
            <section id="reviews" class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <h2 class="text-2xl font-semibold text-stone-900">Reviews</h2>
                    @if (($googleReviewData['reviews_count'] ?? 0) > 0 && !is_null($googleReviewData['rating'] ?? null))
                        <a
                            href="{{ $googleReviewData['place_url'] }}"
                            target="_blank"
                            rel="noreferrer"
                            class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                        >
                            Google {{ number_format((float) $googleReviewData['rating'], 1) }}/5 from {{ $googleReviewData['reviews_count'] }} reviews
                        </a>
                    @endif
                </div>
                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        @if (!empty($googleReviewData['landscape_photo_url']))
                            <article class="sm:col-span-2 xl:col-span-3 overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
                                <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="block">
                                    <img
                                        src="{{ $googleReviewData['landscape_photo_url'] }}"
                                        alt="{{ $googleReviewData['place_name'] ?: 'Universal Eden Holidays' }}"
                                        class="h-56 w-full object-cover"
                                    >
                                </a>
                                <div class="flex flex-wrap items-center justify-between gap-3 p-4">
                                    <div>
                                        <p class="text-sm font-semibold text-stone-900">{{ $googleReviewData['place_name'] ?: 'Universal Eden Holidays' }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-500">Google business photo</p>
                                    </div>
                                    <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="text-sm font-semibold text-sky-700 transition hover:text-sky-800">
                                        Open on Google
                                    </a>
                                </div>
                                @if (!empty($googleReviewData['landscape_photo_attribution']))
                                    <div class="border-t border-stone-200 px-4 py-3 text-xs text-stone-500">
                                        Photo:
                                        @foreach ($googleReviewData['landscape_photo_attribution'] as $author)
                                            @if (!empty($author['uri']))
                                                <a href="{{ $author['uri'] }}" target="_blank" rel="noreferrer" class="font-medium text-stone-700 hover:text-sky-700">{{ $author['name'] }}</a>@if (! $loop->last), @endif
                                            @else
                                                <span class="font-medium text-stone-700">{{ $author['name'] }}</span>@if (! $loop->last), @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </article>
                        @endif

                        @forelse ($reviews as $review)
                            @include('partials.public-review-card', ['review' => $review])
                        @empty
                            <div class="md:col-span-3 rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">
                                No package or Google reviews are available yet.
                            </div>
                        @endforelse
                    </div>

                    <section class="rounded-[1.75rem] border border-stone-200 bg-stone-50/80 p-5 shadow-sm">
                        <p class="text-sm uppercase tracking-[0.28em] text-amber-600">Leave a Review</p>
                        <h3 class="mt-2 font-['Prata'] text-2xl text-stone-900">Share your {{ $product->name }} experience</h3>
                        <p class="mt-3 text-sm leading-6 text-stone-600">If you joined this package before, you can leave a short review here. We will check it before showing it on this package page.</p>

                        <form method="POST" action="{{ route('products.testimonials.store', $product) }}" enctype="multipart/form-data" class="mt-5 space-y-4" data-form-persist="product-testimonial-review-{{ $product->id }}">
                            @csrf
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="product_testimonial_name" class="mb-2 block text-sm font-medium text-stone-700">Your name</label>
                                    <input id="product_testimonial_name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                </div>
                                <div>
                                    <label for="product_testimonial_email" class="mb-2 block text-sm font-medium text-stone-700">Gmail / Email</label>
                                    <input id="product_testimonial_email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="yourname@gmail.com" autocomplete="email" inputmode="email" spellcheck="false" required>
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="product_testimonial_location" class="mb-2 block text-sm font-medium text-stone-700">Your location</label>
                                    <input id="product_testimonial_location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                </div>
                                <div>
                                    <label for="product_testimonial_profile_photo" class="mb-2 block text-sm font-medium text-stone-700">Upload image</label>
                                    <input id="product_testimonial_profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-stone-700">
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-[1fr_9rem]">
                                <div>
                                    <label for="product_testimonial_trip_name" class="mb-2 block text-sm font-medium text-stone-700">Trip or package name</label>
                                    <input id="product_testimonial_trip_name" name="trip_name" type="text" value="{{ old('trip_name', $product->name) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                </div>
                                <div>
                                    <label for="product_testimonial_rating" class="mb-2 block text-sm font-medium text-stone-700">Rating</label>
                                    <select id="product_testimonial_rating" name="rating" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                                        @foreach ([5, 4, 3, 2, 1] as $rating)
                                            <option value="{{ $rating }}" @selected((int) old('rating', 5) === $rating)>{{ $rating }}/5</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="product_testimonial_quote" class="mb-2 block text-sm font-medium text-stone-700">Your review</label>
                                <textarea id="product_testimonial_quote" name="quote" rows="5" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Tell future travelers what stood out about this package." required>{{ old('quote') }}</textarea>
                            </div>
                            <button type="submit" class="w-full rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.22em] text-white transition hover:bg-amber-700">
                                Submit Review
                            </button>
                        </form>
                    </section>
                </div>
            </section>
        @endif

        @if ($product->category !== 'transport')
            <section class="mt-8 grid gap-8 lg:grid-cols-[1fr_1fr]">
                <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-stone-900">Recommended Packages</h2>
                    <div class="mt-6 grid gap-5 md:grid-cols-3">
                        @foreach ($recommendedProducts as $recommended)
                            <a href="{{ route('products.show', $recommended) }}" class="block overflow-hidden rounded-3xl border border-stone-200 bg-stone-50 transition duration-300 hover:-translate-y-1.5 hover:shadow-lg">
                                @if ($recommended->image_url)
                                    <div class="relative">
                                        <img src="{{ $recommended->image_url }}" alt="{{ $recommended->name }}" class="h-44 w-full object-cover">
                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-10 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.65rem, -0.65rem);">
                                    </div>
                                @else
                                    <div class="flex h-44 items-center justify-center bg-[linear-gradient(135deg,_#dbeafe,_#fff7ed_55%,_#ecfeff)] px-6 text-center text-xl font-semibold text-stone-700">
                                        {{ $recommended->name }}
                                    </div>
                                @endif
                                <div class="p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">{{ ucfirst($recommended->category) }}</p>
                                <h3 class="mt-3 text-xl font-semibold text-stone-900">{{ $recommended->name }}</h3>
                                <p class="mt-3 text-sm text-stone-500">{{ $recommended->location }}</p>
                                <p class="mt-4 text-sm leading-6 text-stone-600">{{ $recommended->summary }}</p>
                                @if ($recommended->has_active_discount)
                                    <div class="mt-5 text-xs text-stone-400 line-through">From MYR {{ number_format((float) $recommended->malaysia_adult_price_myr, 2) }}</div>
                                @endif
                                <div class="mt-1 text-lg font-semibold text-stone-900">From MYR {{ number_format((float) $recommended->discounted_malaysia_adult_price_myr, 2) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-stone-900">Related {{ ucfirst($product->category) }}s</h2>
                    <div class="mt-6 grid gap-5 md:grid-cols-3">
                        @foreach ($relatedProducts as $related)
                            <a href="{{ route('products.show', $related) }}" class="block overflow-hidden rounded-3xl border border-stone-200 bg-stone-50 transition duration-300 hover:-translate-y-1.5 hover:shadow-lg">
                                @if ($related->image_url)
                                    <div class="relative">
                                        <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="h-44 w-full object-cover">
                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-10 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.65rem, -0.65rem);">
                                    </div>
                                @else
                                    <div class="flex h-44 items-center justify-center bg-[linear-gradient(135deg,_#dbeafe,_#fff7ed_55%,_#ecfeff)] px-6 text-center text-xl font-semibold text-stone-700">
                                        {{ $related->name }}
                                    </div>
                                @endif
                                <div class="p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">{{ ucfirst($related->category) }}</p>
                                <h3 class="mt-3 text-xl font-semibold text-stone-900">{{ $related->name }}</h3>
                                <p class="mt-3 text-sm text-stone-500">{{ $related->location }}</p>
                                <p class="mt-4 text-sm leading-6 text-stone-600">{{ $related->summary }}</p>
                                @if ($related->has_active_discount)
                                    <div class="mt-5 text-xs text-stone-400 line-through">From MYR {{ number_format((float) $related->malaysia_adult_price_myr, 2) }}</div>
                                @endif
                                <div class="mt-1 text-lg font-semibold text-stone-900">From MYR {{ number_format((float) $related->discounted_malaysia_adult_price_myr, 2) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            </section>
        @endif

        <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="rounded-[1.75rem] bg-[linear-gradient(135deg,_#e0f2fe,_#fef3c7_60%,_#ecfccb)] p-8">
                    <p class="text-sm uppercase tracking-[0.35em] text-stone-600">Why Travel With</p>
                    <h2 class="mt-3 font-['Prata'] text-4xl text-stone-900">Universal Eden Holidays</h2>
                    <p class="mt-4 text-sm leading-7 text-stone-700">Trusted coordination, practical pricing visibility, and a simpler booking path for Sabah experiences.</p>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <h3 class="text-xl font-semibold text-stone-900">Licensed and Regulated</h3>
                        <p class="mt-2 text-sm leading-7 text-stone-600">Local partnerships and operational support designed for dependable Sabah travel planning.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-stone-900">Trusted Team</h3>
                        <p class="mt-2 text-sm leading-7 text-stone-600">Customer support for product questions, booking changes, and reservation follow-up.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-stone-900">Flexible Arrangements</h3>
                        <p class="mt-2 text-sm leading-7 text-stone-600">Useful for transport requests, custom departures, and last-minute changes.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-stone-900">Value for Money</h3>
                        <p class="mt-2 text-sm leading-7 text-stone-600">Malaysia and international adult/child rates are clearly shown before the customer books.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="h-12"></div>

    <footer class="border-t border-stone-200/80 bg-stone-950 text-stone-200">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 lg:grid-cols-[1.2fr_0.8fr_0.8fr_1fr] lg:px-10">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10">
                    <div>
                        <p class="font-['Prata'] text-xl text-white">Universal Eden Holidays</p>
                    </div>
                </div>
                <p class="mt-5 max-w-md text-sm leading-7 text-stone-400">
                    Travel planning for Sabah made easier with transport services, holiday packages, and practical booking support in one place.
                </p>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Explore</p>
                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-400">
                    <a href="{{ route('home') }}#promos" class="transition hover:text-white">Promos</a>
                    <a href="{{ route('home') }}#transport" class="transition hover:text-white">Transport</a>
                    <a href="{{ route('home') }}#packages-showcase" class="transition hover:text-white">Packages</a>
                    <a href="{{ route('home') }}#testimonials" class="transition hover:text-white">Testimonials</a>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Company</p>
                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-400">
                    <a href="{{ route('home') }}#about-us" class="transition hover:text-white">About Us</a>
                    <a href="{{ route('home') }}#popular-picks" class="transition hover:text-white">Popular Picks</a>
                    <a href="{{ route('bookings.track.form') }}" class="transition hover:text-white">Track Your Bookings</a>
                    @auth
                        <a href="{{ route('profile.show') }}" class="transition hover:text-white">My Profile</a>
                    @else
                        <a href="{{ route('login') }}" class="transition hover:text-white">Login</a>
                    @endauth
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Contact</p>
                <div class="mt-5 space-y-4 text-sm text-stone-400">
                    <p>Email: <a href="mailto:info@universaledenholiday.com" class="transition hover:text-white">info@universaledenholiday.com</a></p>
                    <p>Phone: <a href="tel:+6088212345" class="transition hover:text-white">+60 88 212 345</a></p>
                    <p>Kota Kinabalu, Sabah, Malaysia</p>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl items-center justify-center px-6 py-5 text-center text-xs uppercase tracking-[0.22em] text-stone-500 lg:px-10">
                <p>Adcey &copy; Universal Eden Holidays - {{ now()->year }}</p>
            </div>
        </div>
    </footer>

    <div id="product-image-preview-modal" class="fixed inset-0 z-[260] hidden items-center justify-center bg-stone-950/80 px-2 py-6">
        <div class="w-full rounded-[1.75rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]" style="max-width: min(1100px, calc(100vw - 1rem));">
            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                <div>
                    <p class="text-sm font-semibold text-stone-800">Image preview</p>
                    <p id="product-image-preview-count" class="mt-1 text-xs uppercase tracking-[0.18em] text-stone-500"></p>
                </div>
                <button type="button" id="product-image-preview-close" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close image preview">&times;</button>
            </div>
            <div class="relative flex max-h-[88vh] items-center justify-center overflow-auto p-2">
                <button type="button" id="product-image-preview-prev" class="absolute left-4 top-1/2 z-20 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-2xl leading-none text-stone-700 shadow-lg transition hover:bg-white" aria-label="Previous image">&#8249;</button>
                <div id="product-image-preview-frame" class="relative inline-flex items-center justify-center" style="width: min(56vw, 980px); height: 68vh;">
                    <img id="product-image-preview-image" src="" alt="" class="rounded-[1.25rem] object-contain" style="width: min(56vw, 980px); height: 68vh;">
                    <img id="product-image-preview-trademark" src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-14 w-auto opacity-90" style="right: 1.5rem; bottom: 1.5rem;">
                </div>
                <button type="button" id="product-image-preview-next" class="absolute right-4 top-1/2 z-20 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-2xl leading-none text-stone-700 shadow-lg transition hover:bg-white" aria-label="Next image">&#8250;</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const previewImages = @json($previewImages);
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
