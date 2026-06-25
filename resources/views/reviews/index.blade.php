<x-layouts.app title="Customer Reviews | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))]" style="background: linear-gradient(180deg, #f5efe3 0%, #e7edf8 100%);">
        <section class="relative w-full bg-white">
            <img
                src="{{ asset('images/customer_reviews.png') }}"
                alt="Customer Reviews"
                class="block w-full h-auto"
            >
            <div class="absolute inset-0 flex items-center justify-center px-6 text-center">
                <h1 class="rounded-full bg-white/85 px-6 py-3 font-['Oswald'] text-4xl font-bold uppercase tracking-[0.2em] text-stone-900 shadow-[0_10px_24px_rgba(255,255,255,0.28)] md:text-5xl">
                    Customer Reviews
                </h1>
            </div>
        </section>

        <section class="w-full bg-white">
            <div class="mx-auto" style="max-width: 1320px;">
                @if ((($googleReviewData['reviews_count'] ?? 0) > 0 && !is_null($googleReviewData['rating'] ?? null)) || !empty($googleReviewData['place_url']))
                    <div class="px-5 py-8 text-center md:px-10 md:py-12" style="background: linear-gradient(180deg, #ffffff 0%, #f6f8fc 100%);">
                        <div class="flex flex-wrap justify-center gap-3">
                            @if (($googleReviewData['reviews_count'] ?? 0) > 0 && !is_null($googleReviewData['rating'] ?? null))
                                <div class="rounded-full bg-[#315fbd] px-4 py-2 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(49,95,189,0.18)]">
                                    Google rating: {{ number_format((float) $googleReviewData['rating'], 1) }}/5 from {{ $googleReviewData['reviews_count'] }}
                                </div>
                            @endif
                        </div>
                        <div class="mt-6 flex flex-wrap justify-center gap-3">
                            @if (!empty($googleReviewData['place_url']))
                                <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-full bg-[#244c9a] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#1d3d7d]">
                                    Go to Google Reviews
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="px-5 py-6 md:px-8 md:py-8">
                    @if ($allReviews->isNotEmpty())
                        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($allReviews as $review)
                                @include('partials.public-review-card', ['review' => $review])
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 px-6 py-10 text-center text-sm leading-7 text-stone-600">
                            No customer reviews are available yet.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
</x-layouts.app>
