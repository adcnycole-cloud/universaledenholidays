<x-layouts.app :title="$product->name.' | Universal Eden Holidays'">
    <main class="mx-auto max-w-[120rem] px-6 py-10 lg:px-10" style="background-color: #ffffff;">
        <div class="mb-6 text-stone-500" style="font-size: 1.2rem; line-height: 1.25;">
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
            $serviceInfoSections = collect([
                'inclusion' => ['title' => 'Inclusion', 'items' => []],
                'exclusion' => ['title' => 'Exclusion', 'items' => []],
                'things_to_bring' => ['title' => 'Things to Bring', 'items' => []],
                'important_notes' => ['title' => 'Important Notes', 'items' => []],
            ]);
            $pushServiceSectionItem = function (string $key, string $value) use (&$serviceInfoSections) {
                $trimmedValue = trim($value);

                if ($trimmedValue === '') {
                    return;
                }

                $section = $serviceInfoSections->get($key);
                $section['items'][] = $trimmedValue;
                $serviceInfoSections->put($key, $section);
            };

            if ($structuredServiceInclusions->isNotEmpty()) {
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
                    <div class="overflow-hidden" style="background: #ffffff;">
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
                            <div class="grid grid-cols-2 gap-3 border-t border-stone-200 bg-white p-4 md:grid-cols-4">
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
                            <div class="mt-8 grid gap-0 border-y border-stone-200 md:grid-cols-3">
                                <div class="p-5 md:border-r md:border-stone-200">
                                    <p class="text-sm text-stone-500">Location</p>
                                    <p class="mt-2 text-lg font-semibold text-stone-900">{{ $product->location }}</p>
                                </div>
                                <div class="border-t border-stone-200 p-5 md:border-t-0 md:border-r md:border-stone-200">
                                    <p class="text-sm text-stone-500">Duration</p>
                                    <p class="mt-2 text-lg font-semibold text-stone-900">{{ $product->duration }}</p>
                                </div>
                                <div class="border-t border-stone-200 p-5 md:border-t-0">
                                    <p class="text-sm text-stone-500">Capacity</p>
                                    <p class="mt-2 text-lg font-semibold text-stone-900">{{ $product->capacity ?? 'Flexible' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <section class="p-0" style="background: #ffffff;">
                    <div class="space-y-0 text-sm text-stone-700">
                        <div class="flex justify-between gap-4 border-b border-stone-200 py-4"><span class="font-semibold">Description</span><span class="text-right">{{ $product->name }}</span></div>
                        <div class="flex justify-between gap-4 border-b border-stone-200 py-4"><span class="font-semibold">Region</span><span class="text-right">{{ $product->location }}</span></div>
                        <div class="flex justify-between gap-4 border-b border-stone-200 py-4"><span class="font-semibold">Duration</span><span class="text-right">{{ $product->duration }}</span></div>
                        <div class="flex justify-between gap-4 border-b border-stone-200 py-4"><span class="font-semibold">Minimum</span><span class="text-right">1 Pax</span></div>
                        <div class="flex justify-between gap-4 border-b border-stone-200 py-4"><span class="font-semibold">Availability</span><span class="text-right">Daily / Subject to booking</span></div>
                    </div>

                    <div class="mt-6 border-b border-stone-200 pb-6">
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

                <section class="border-t border-stone-200 pt-8" data-package-details style="background: #ffffff;">
                    <h2 class="font-['Oswald'] text-3xl font-bold uppercase tracking-[0.08em] text-stone-900">Package Details</h2>
                    <div class="mt-6 grid gap-0 border-y border-stone-200 md:grid-cols-4" style="background-color: #455499;">
                        @foreach ($serviceInfoSections as $key => $section)
                            <button
                                type="button"
                                class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-[0.22em] transition"
                                style="background-color: #455499; color: #111111; border-right: 1px solid rgba(255,255,255,0.35);"
                                data-package-detail-tab="{{ $key }}"
                                aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                            >
                                {{ $key === 'inclusion' ? 'Includes' : ($key === 'exclusion' ? 'Excludes' : $section['title']) }}
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-10 border-t border-stone-200 pt-6">
                        @foreach ($serviceInfoSections as $key => $section)
                            <div @class(['hidden' => !$loop->first]) data-package-detail-panel="{{ $key }}">
                                @if (!empty($section['items']))
                                    <ul class="space-y-3 text-sm leading-8 text-stone-600">
                                        @foreach ($section['items'] as $entry)
                                            <li class="flex items-center gap-3">
                                                <span class="text-base font-bold leading-none text-stone-500">{{ $key === 'exclusion' ? '•' : '✓' }}</span>
                                                <span>{{ $entry }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm leading-7 text-stone-500">Details will be confirmed with this package.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </section>
        @endif

        @if ($product->category !== 'transport')
            @if ($itineraryItems->isNotEmpty())
                <section class="mt-8 w-full border-t border-stone-200 pt-8">
                        <h2 class="text-3xl font-semibold text-stone-900">Package Itinerary</h2>
                        @if ($structuredItineraryItems->isNotEmpty())
                            <div class="mt-6 w-full border-y border-stone-200">
                                @foreach ($groupedItineraryDays as $day)
                                    <section class="min-w-0 {{ $loop->last ? '' : 'border-b border-stone-200' }}" data-itinerary-day>
                                        <button
                                            type="button"
                                            class="flex w-full items-center justify-between gap-4 px-4 py-4 text-left transition hover:bg-stone-50"
                                            data-itinerary-toggle
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        >
                                            <h3 class="text-xl font-semibold text-stone-900">{{ $day['label'] }}</h3>
                                            <div class="flex items-center gap-3">
                                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                                                    {{ $day['items']->count() }} stop{{ $day['items']->count() === 1 ? '' : 's' }}
                                                </span>
                                                <span class="text-lg leading-none text-stone-500" data-itinerary-icon>{{ $loop->first ? '−' : '+' }}</span>
                                            </div>
                                        </button>
                                        <div class="{{ $loop->first ? '' : 'hidden' }}" data-itinerary-panel>
                                            <div class="border-t border-stone-200 bg-white">
                                                <div class="max-w-[58rem] overflow-x-auto">
                                                    <table class="w-full min-w-0 table-fixed text-sm">
                                                    <colgroup>
                                                        <col style="width: 120px;">
                                                        <col style="width: auto;">
                                                    </colgroup>
                                                    <thead class="bg-stone-100/80 text-stone-600">
                                                        <tr>
                                                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">Time</th>
                                                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">Activity</th>
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
                        @else
                            <div class="mt-6 w-full border-y border-stone-200">
                                @foreach ($itineraryItems as $item)
                                    <div class="{{ $loop->last ? '' : 'border-b border-stone-200' }} px-5 py-4 text-sm leading-7 text-stone-700">
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

    </main>

    <div class="h-12"></div>

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

    @include('/partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
                        tab.style.color = '#111111';
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

            document.querySelectorAll('[data-itinerary-day]').forEach((dayBlock) => {
                const toggle = dayBlock.querySelector('[data-itinerary-toggle]');
                const panel = dayBlock.querySelector('[data-itinerary-panel]');
                const icon = dayBlock.querySelector('[data-itinerary-icon]');

                if (!toggle || !panel || !icon) {
                    return;
                }

                toggle.addEventListener('click', () => {
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                    panel.classList.toggle('hidden', isExpanded);
                    icon.textContent = isExpanded ? '+' : '−';
                });
            });

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
