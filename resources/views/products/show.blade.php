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
        @endphp

        <section class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
            <div>
                <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                    @if ($primaryImage)
                        <div class="relative overflow-hidden">
                            <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-[26rem] w-full object-cover">
                            <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-14 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                        </div>
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
                                <div class="relative overflow-hidden rounded-[1.25rem]">
                                    <img src="{{ $image }}" alt="{{ $product->name }} gallery image {{ $index + 2 }}" class="h-28 w-full object-cover">
                                    <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-8 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.45rem, -0.45rem);">
                                    @if ($remainingGalleryCount > 0 && $loop->last)
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/45 text-2xl font-semibold text-white">
                                            +{{ $remainingGalleryCount }}
                                        </div>
                                    @endif
                                </div>
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
                    <h2 class="text-3xl font-semibold text-stone-900">Tour Highlights</h2>
                    <div class="mt-5 grid gap-6 md:grid-cols-[1.1fr_0.9fr]">
                        <div class="rounded-3xl bg-stone-100 p-6">
                            @if ($primaryImage)
                                <div class="relative overflow-hidden rounded-[1.5rem]">
                                    <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="h-full min-h-64 w-full object-cover">
                                    <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-12 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.75rem, -0.75rem);">
                                </div>
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
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'action' => 'reserve']) }}" class="rounded-full border border-emerald-600 px-5 py-3 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50">Reserve Now</a>
                        <a href="{{ route('booking.create', ['product_id' => $product->id, 'action' => 'instant_book']) }}" class="rounded-full bg-rose-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-600">Instant Book</a>
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
                <h2 class="text-2xl font-semibold text-stone-900">Tour Inclusion</h2>
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
                                <td class="px-5 py-4">Included where relevant for package and overnight tour products.</td>
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
                @foreach ($testimonials as $testimonial)
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
                @endforeach
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
</x-layouts.app>
