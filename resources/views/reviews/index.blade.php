<x-layouts.app title="Customer Reviews | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))] px-4 py-8 md:px-6 md:py-10" style="background: linear-gradient(180deg, #f5efe3 0%, #e7edf8 100%);">
        <div class="mx-auto" style="max-width: 1320px;">
            <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-[0_18px_48px_rgba(15,23,42,0.1)]">
                <div class="px-5 py-8 text-center md:px-10 md:py-12" style="background: linear-gradient(135deg, #244c9a 0%, #315fbd 48%, #7ea9e9 100%);">
                    <p class="font-['Oswald'] text-sm font-semibold uppercase tracking-[0.28em] text-white/80">Guest Feedback</p>
                    <h1 class="mt-3 font-['Oswald'] text-4xl font-bold uppercase tracking-[0.2em] text-white md:text-5xl">Customer Reviews</h1>
                    <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-white/90 md:text-base">
                        Browse approved website reviews together with the latest Google feedback from travellers who booked with Universal Eden Holidays.
                    </p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        @if (($websiteReviewStats['reviews_count'] ?? 0) > 0 && !is_null($websiteReviewStats['average_rating'] ?? null))
                            <div class="rounded-full bg-white/14 px-4 py-2 text-sm font-semibold text-white" style="backdrop-filter: blur(6px);">
                                Website reviews: {{ number_format((float) $websiteReviewStats['average_rating'], 1) }}/5 from {{ $websiteReviewStats['reviews_count'] }}
                            </div>
                        @endif
                        @if (($googleReviewData['reviews_count'] ?? 0) > 0 && !is_null($googleReviewData['rating'] ?? null))
                            <div class="rounded-full bg-white/14 px-4 py-2 text-sm font-semibold text-white" style="backdrop-filter: blur(6px);">
                                Google rating: {{ number_format((float) $googleReviewData['rating'], 1) }}/5 from {{ $googleReviewData['reviews_count'] }}
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        @if (!empty($googleReviewData['place_url']))
                            <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#244c9a] transition hover:bg-stone-100">
                                Go to Google Reviews
                            </a>
                        @endif
                        <a href="{{ route('home') }}#testimonials" class="inline-flex items-center justify-center rounded-full border border-white/55 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Back to homepage
                        </a>
                    </div>
                </div>

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
            </section>
        </div>
    </main>

    @include('partials.footer')
</x-layouts.app>
