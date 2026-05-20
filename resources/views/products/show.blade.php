<x-layouts.app :title="$product->name.' | Universal Eden Holidays'">
    <main class="mx-auto max-w-[96rem] px-6 py-10 lg:px-10">
        <div class="mb-6 text-sm text-stone-500">
            <a href="{{ route('home') }}" class="hover:text-sky-700">Home</a>
            <span class="mx-2">›</span>
            <span class="capitalize">{{ $product->category }}</span>
            <span class="mx-2">›</span>
            <span class="text-stone-700">{{ $product->name }}</span>
        </div>

        @php
            $galleryImages = $product->gallery_urls;
            $primaryImage = $galleryImages[0] ?? null;
            $thumbnailImages = collect($galleryImages)->slice(1, 4)->values();
            $remainingGalleryCount = max(count($galleryImages) - 5, 0);
            $previewImages = collect($galleryImages)
                ->map(fn ($image, $index) => [
                    'src' => $image,
                    'alt' => $index === 0
                        ? $product->name
                        : $product->name.' gallery image '.($index + 1),
                ])
                ->values();
        @endphp

        <section class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
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

                <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <h2 class="text-3xl font-semibold text-stone-900">Product Highlights</h2>
                    <div class="mt-5 grid gap-6 md:grid-cols-[1.1fr_0.9fr]">
                        <div class="rounded-3xl bg-stone-100 p-6">
                            @if ($primaryImage)
                                <button
                                    type="button"
                                    class="relative block w-full overflow-hidden rounded-[1.5rem] text-left"
                                    data-image-preview-trigger="0"
                                    aria-label="Open product highlight image preview"
                                >
                                    <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-full min-h-64 w-full object-cover">
                                    <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-12 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                                </button>
                            @else
                                <div class="flex h-full min-h-64 items-center justify-center rounded-[1.5rem] bg-[linear-gradient(135deg,_#bae6fd,_#fde68a_55%,_#dcfce7)] px-8 text-center">
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.35em] text-stone-600">Sabah Experience</p>
                                        <p class="mt-3 font-['Prata'] text-3xl text-stone-900">{{ $product->name }}</p>
                                        <p class="mt-4 text-sm leading-7 text-stone-700">{{ $product->summary }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div>
                            <ul class="space-y-4 text-base leading-8 text-stone-600">
                                <li>Carefully arranged {{ strtolower($product->category) }} experience in {{ $product->location }}.</li>
                                <li>Suitable for Malaysian and international guests with transparent market pricing.</li>
                                <li>Booking supports adult and child counts separately for both markets.</li>
                                <li>Works for direct reservation, quote requests, and admin-side follow-up.</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-[2rem] border border-emerald-500/30 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4 rounded-3xl bg-emerald-50 p-5">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-700">Starting From</p>
                            <p class="mt-2 text-4xl font-bold text-emerald-700">RM {{ number_format((float) $product->malaysia_adult_price_myr, 2) }}</p>
                            <p class="mt-2 text-sm text-stone-500">Malaysia adult rate</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm text-stone-700">
                        <div class="flex justify-between gap-4"><span class="font-semibold">Description</span><span>{{ $product->name }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Region</span><span>{{ $product->location }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Duration</span><span>{{ $product->duration }}</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Minimum</span><span>1 Pax</span></div>
                        <div class="flex justify-between gap-4"><span class="font-semibold">Availability</span><span>Daily / Subject to booking</span></div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'mode' => 'enquiry']) }}" class="rounded-full border border-amber-400 px-5 py-3 text-sm font-semibold text-amber-600 transition hover:bg-amber-50">Send Enquiry</a>
                        @if ($product->category !== 'transport')
                            <a href="{{ route('booking.create', ['product_id' => $product->id, 'action' => 'reserve']) }}" class="rounded-full border border-emerald-600 px-5 py-3 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50">Reserve Now</a>
                            <a href="{{ route('booking.create', ['product_id' => $product->id, 'action' => 'instant_book']) }}" class="rounded-full bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">Instant Book</a>
                        @endif
                    </div>
                </section>

                <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-stone-900">Malaysia Market</h2>
                    <div class="mt-4 overflow-hidden rounded-3xl border border-blue-200">
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
                                                <div class="mt-1 font-semibold text-rose-600">{{ number_format($tier['child_price'], 2) }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 overflow-hidden rounded-3xl border border-amber-200">
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
                                                <div class="mt-1 font-semibold text-rose-600">{{ number_format($tier['child_price'], 2) }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </aside>
        </section>

        <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-3xl font-semibold text-stone-900">{{ $product->name }}</h2>
            <div class="mt-5 rounded-3xl bg-stone-50 p-5 text-sm leading-8 text-stone-600">
                <ul class="space-y-2">
                    <li>The prices shown are quoted in Ringgit Malaysia (MYR).</li>
                    <li>Malaysia and international rates are shown separately for adult and child guests.</li>
                    <li>Booking totals will be calculated automatically based on the guest mix entered in the booking form.</li>
                    <li>Rates may change based on seasonality, park fees, or supplier availability.</li>
                    <li>Our team will reconfirm all reservations before final fulfillment.</li>
                </ul>
            </div>
        </section>

        <section class="mt-8 grid gap-8 lg:grid-cols-[1fr_1fr]">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-stone-900">Sample Itinerary</h2>
                    <span class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-700">Flexible</span>
                </div>
                <div class="mt-6 space-y-4">
                    <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-700">Day 1</p>
                        <p class="mt-2 text-lg font-semibold text-stone-900">Arrival, registration, and guided experience</p>
                        <p class="mt-3 text-sm leading-7 text-stone-600">Begin in {{ $product->location }} with coordination, transport support, and the core {{ strtolower($product->category) }} program.</p>
                    </div>
                    <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-700">Day 2</p>
                        <p class="mt-2 text-lg font-semibold text-stone-900">Highlights, wrap-up, and return</p>
                        <p class="mt-3 text-sm leading-7 text-stone-600">Continue key activities, scenic stops, and return arrangements according to the selected package duration.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-stone-900">Service Inclusion</h2>
                <div class="mt-6 overflow-hidden rounded-3xl border border-stone-200">
                    <table class="min-w-full text-left text-sm">
                        <tbody class="bg-white text-stone-700">
                            <tr class="border-b border-stone-200">
                                <th class="w-48 bg-stone-50 px-5 py-4 font-semibold">Meals</th>
                                <td class="px-5 py-4">Subject to package or service arrangement.</td>
                            </tr>
                            <tr class="border-b border-stone-200">
                                <th class="bg-stone-50 px-5 py-4 font-semibold">Inclusion</th>
                                <td class="px-5 py-4">Core service delivery, support coordination, and supplier-side arrangements as stated.</td>
                            </tr>
                            <tr class="border-b border-stone-200">
                                <th class="bg-stone-50 px-5 py-4 font-semibold">Accommodation</th>
                                <td class="px-5 py-4">Included where relevant for package and overnight products.</td>
                            </tr>
                            <tr>
                                <th class="bg-stone-50 px-5 py-4 font-semibold">Exclusion</th>
                                <td class="px-5 py-4">Flights, personal travel insurance, personal spending, and unlisted add-ons.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>

        <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-semibold text-stone-900">Reviews</h2>
            <div class="mt-6 grid gap-5 md:grid-cols-3">
                @forelse ($testimonials as $testimonial)
                    <article class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ $testimonial->profile_photo_url }}" alt="{{ $testimonial->name }}" class="h-12 w-12 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-white" style="aspect-ratio: 1 / 1; border-radius: 9999px;">
                                <div>
                                    <h3 class="text-lg font-semibold text-stone-900">{{ $testimonial->name }}</h3>
                                    <p class="text-sm text-stone-500">{{ $testimonial->location }}</p>
                                </div>
                            </div>
                            <div class="text-amber-500">{{ str_repeat('*', $testimonial->rating) }}</div>
                        </div>
                        <p class="mt-4 text-sm leading-7 text-stone-600">{{ $testimonial->quote }}</p>
                    </article>
                @empty
                    <div class="md:col-span-3 rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">
                        No package reviews have been assigned here yet.
                    </div>
                @endforelse
            </div>
        </section>

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
                            <div class="mt-5 text-lg font-semibold text-stone-900">From MYR {{ number_format((float) $recommended->malaysia_adult_price_myr, 2) }}</div>
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
                            <div class="mt-5 text-lg font-semibold text-stone-900">From MYR {{ number_format((float) $related->malaysia_adult_price_myr, 2) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        </section>

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

    <div id="product-image-preview-modal" class="fixed inset-0 z-[260] hidden items-center justify-center bg-stone-950/80 px-4 py-6">
        <div class="w-full max-w-6xl rounded-[1.75rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]">
            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                <div>
                    <p class="text-sm font-semibold text-stone-800">Image preview</p>
                    <p id="product-image-preview-count" class="mt-1 text-xs uppercase tracking-[0.18em] text-stone-500"></p>
                </div>
                <button type="button" id="product-image-preview-close" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close image preview">&times;</button>
            </div>
            <div class="relative flex max-h-[80vh] items-center justify-center overflow-auto p-4">
                <button type="button" id="product-image-preview-prev" class="absolute left-4 top-1/2 z-20 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-2xl leading-none text-stone-700 shadow-lg transition hover:bg-white" aria-label="Previous image">&#8249;</button>
                <img id="product-image-preview-image" src="" alt="" class="max-h-[72vh] w-auto max-w-full rounded-[1.25rem] object-contain">
                <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-14 w-auto opacity-90" style="right: 1.5rem; bottom: 1.5rem;">
                <button type="button" id="product-image-preview-next" class="absolute right-4 top-1/2 z-20 inline-flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-2xl leading-none text-stone-700 shadow-lg transition hover:bg-white" aria-label="Next image">&#8250;</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const previewImages = @json($previewImages);
            const modal = document.getElementById('product-image-preview-modal');
            const modalImage = document.getElementById('product-image-preview-image');
            const closeButton = document.getElementById('product-image-preview-close');
            const prevButton = document.getElementById('product-image-preview-prev');
            const nextButton = document.getElementById('product-image-preview-next');
            const countLabel = document.getElementById('product-image-preview-count');
            let activeIndex = 0;

            if (!modal || !modalImage || !closeButton || !prevButton || !nextButton || !countLabel || !previewImages.length) {
                return;
            }

            const renderImage = () => {
                const currentImage = previewImages[activeIndex];

                if (!currentImage) {
                    return;
                }

                modalImage.src = currentImage.src;
                modalImage.alt = currentImage.alt;
                countLabel.textContent = `Image ${activeIndex + 1} of ${previewImages.length}`;
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
        });
    </script>
</x-layouts.app>
