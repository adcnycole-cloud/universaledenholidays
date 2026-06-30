<x-layouts.app title="Customer Reviews | Universal Eden Holidays">
    <style>
        .reviews-page-marquee {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(191, 219, 254, 0.9);
            border-radius: 2rem;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(246, 248, 252, 0.98));
            padding: 1rem 0;
        }

        .reviews-page-marquee::before,
        .reviews-page-marquee::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: clamp(2rem, 7vw, 5rem);
            z-index: 2;
            pointer-events: none;
        }

        .reviews-page-marquee::before {
            left: 0;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0));
        }

        .reviews-page-marquee::after {
            right: 0;
            background: linear-gradient(270deg, rgba(246, 248, 252, 0.98), rgba(246, 248, 252, 0));
        }

        .reviews-page-track {
            display: flex;
            width: max-content;
            align-items: stretch;
            gap: 1.25rem;
            padding: 0 1.25rem;
            animation: reviews-page-scroll 40s linear infinite;
        }

        .reviews-page-marquee:hover .reviews-page-track {
            animation-play-state: paused;
        }

        .reviews-page-slide {
            flex: 0 0 min(24rem, calc(100vw - 4.5rem));
        }

        .reviews-form-details > summary {
            list-style: none;
        }

        .reviews-form-details > summary::-webkit-details-marker {
            display: none;
        }

        .reviews-form-details[open] [data-reviews-form-chevron] {
            transform: rotate(180deg);
        }

        @keyframes reviews-page-scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(calc(-50% - 0.625rem));
            }
        }
    </style>

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
                        @php($loopingReviews = $allReviews->concat($allReviews))
                        <div class="reviews-page-marquee">
                            <div class="reviews-page-track">
                                @foreach ($loopingReviews as $review)
                                    <div class="reviews-page-slide" @if ($loop->index >= $allReviews->count()) aria-hidden="true" @endif>
                                        @include('partials.public-review-card', ['review' => $review])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 px-6 py-10 text-center text-sm leading-7 text-stone-600">
                            No customer reviews are available yet.
                        </div>
                    @endif
                </div>

                <div class="px-5 pb-10 md:px-8 md:pb-14">
                    <details class="reviews-form-details overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 px-6 py-5 md:px-8">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700">Share Your Experience</p>
                                <h2 class="mt-2 text-2xl font-semibold text-stone-900">Leave a Customer Review</h2>
                                <p class="mt-2 max-w-2xl text-sm leading-7 text-stone-600">Open this form if you would like to submit your travel experience for review.</p>
                            </div>
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-sky-50 text-sky-700 transition duration-200" data-reviews-form-chevron>
                                <svg viewBox="0 0 20 20" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.18l3.71-3.95a.75.75 0 1 1 1.1 1.02l-4.25 4.52a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
                            </span>
                        </summary>

                        <div class="border-t border-stone-200 px-6 py-6 md:px-8">
                            <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data" class="space-y-5">
                                @csrf

                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Name</label>
                                        <input name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Email</label>
                                        <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Location</label>
                                        <input name="location" type="text" value="{{ old('location') }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Trip Name</label>
                                        <input name="trip_name" type="text" value="{{ old('trip_name') }}" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white" required>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Rating</label>
                                        <select name="rating" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white" required>
                                            <option value="">Select rating</option>
                                            @for ($rating = 5; $rating >= 1; $rating--)
                                                <option value="{{ $rating }}" @selected((string) old('rating') === (string) $rating)>{{ $rating }} Star{{ $rating === 1 ? '' : 's' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Profile Photo</label>
                                        <input name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-700 outline-none transition focus:border-sky-400 focus:bg-white">
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Review</label>
                                    <textarea name="quote" rows="5" class="w-full rounded-[1.5rem] border border-stone-300 bg-stone-50 px-4 py-3 text-sm leading-7 text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white" required>{{ old('quote') }}</textarea>
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <p class="text-sm leading-6 text-stone-500">Your review will be submitted for approval before it appears publicly.</p>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#244c9a] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#1d3d7d]">
                                        Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
</x-layouts.app>
