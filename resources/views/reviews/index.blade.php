<x-layouts.app title="Customer Reviews | Universal Eden Holidays">
    <style>
        .page-banner {
            position: relative;
            overflow: hidden;
        }

        .page-banner-wave {
            position: absolute;
            bottom: 0;
            left: -2px;
            right: -2px;
            width: calc(100% + 4px);
            min-width: calc(100% + 4px);
            max-width: none;
            height: auto;
            display: block;
            filter: brightness(0) invert(1);
            pointer-events: none;
            z-index: 1;
        }

        .page-banner-image {
            position: relative;
            z-index: 0;
        }

        .page-banner-overlay {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            width: 100%;
        }

        .reviews-page-marquee {
            position: relative;
            overflow: hidden;
            width: 100vw;
            margin-left: calc(50% - 50vw);
            padding: 3.25rem 0 3.5rem;
            z-index: 1;
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
            background: linear-gradient(90deg, rgba(248, 251, 255, 0.98), rgba(248, 251, 255, 0));
        }

        .reviews-page-marquee::after {
            right: 0;
            background: linear-gradient(270deg, rgba(238, 245, 255, 0.98), rgba(238, 245, 255, 0));
        }

        .reviews-page-showcase {
            position: relative;
            overflow: visible;
            padding: 1rem 0 1.25rem;
        }

        .reviews-page-showcase-frame {
            position: absolute;
            inset: 0 auto 0 50%;
            width: min(100%, 68rem);
            transform: translateX(-50%);
            border: 1px solid rgba(219, 234, 254, 0.95);
            border-radius: 2.75rem;
            background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
            box-shadow: 0 30px 70px rgba(148, 163, 184, 0.18);
        }

        .reviews-page-track {
            display: flex;
            width: max-content;
            align-items: stretch;
            gap: 3.5rem;
            padding: 0 3.5rem;
            will-change: transform;
        }

        .reviews-page-marquee:hover .reviews-page-track {
            cursor: grab;
        }

        .reviews-page-slide {
            flex: 0 0 min(20rem, calc(100vw - 5rem));
            transform: scale(0.82);
            transform-origin: center center;
            will-change: transform;
        }

        .reviews-page-slide.is-pulsing {
            animation: reviews-slide-pulse 3s ease-in-out 1;
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

        .customer-gallery-grid {
            display: grid;
            grid-template-columns: minmax(0, 21rem);
            justify-content: center;
            gap: 1.25rem;
        }

        @media (min-width: 768px) {
            .customer-gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 21rem));
            }
        }

        @media (min-width: 1280px) {
            .customer-gallery-grid {
                grid-template-columns: repeat(4, minmax(0, 21rem));
            }
        }

        .customer-gallery-card {
            position: relative;
            height: 19.5rem;
            overflow: hidden;
        }

        .customer-gallery-card img {
            transition: transform 0.35s ease;
        }

        .customer-gallery-card:hover img {
            transform: scale(1.06);
        }

        .customer-gallery-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            text-align: center;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.74) 100%);
            opacity: 0;
            transition: opacity 0.28s ease;
        }

        .customer-gallery-card:hover .customer-gallery-overlay {
            opacity: 1;
        }

        @keyframes reviews-slide-pulse {
            0% {
                transform: scale(0.82);
            }

            45% {
                transform: scale(1.22);
            }

            55% {
                transform: scale(1.22);
            }

            100% {
                transform: scale(0.82);
            }
        }

    </style>

    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))]" style="background: linear-gradient(180deg, #f5efe3 0%, #e7edf8 100%);">
        <section class="page-banner w-full bg-white">
            <img
                src="{{ asset('images/customer_reviews.png') }}"
                alt="Customer Reviews"
                class="page-banner-image block w-full h-auto"
            >
            <img
                src="{{ asset('images/wave.png') }}"
                alt=""
                class="page-banner-wave"
                aria-hidden="true"
            >
        </section>

        <section class="w-full bg-white">
            <div class="mx-auto" style="max-width: 1600px;">
                <div class="px-5 pt-8 text-center md:px-10 md:pt-10">
                    <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">What They Say</span>
                    <h1 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">
                        Customer Reviews
                    </h1>
                </div>

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
                        @php($loopCopies = 4)
                        @php($loopingReviews = collect(range(1, $loopCopies))->flatMap(fn () => $allReviews))
                        <div class="reviews-page-showcase">
                            <div class="reviews-page-showcase-frame" aria-hidden="true"></div>
                            <div class="reviews-page-marquee" data-review-marquee>
                                <div class="reviews-page-track" data-review-track data-review-loop-copies="{{ $loopCopies }}">
                                    @foreach ($loopingReviews as $review)
                                        <div class="reviews-page-slide" data-review-slide @if ($loop->index >= $allReviews->count()) aria-hidden="true" @endif>
                                            @include('partials.public-review-card', ['review' => $review])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 px-6 py-10 text-center text-sm leading-7 text-stone-600">
                            No customer reviews are available yet.
                        </div>
                    @endif
                </div>

                <div class="px-5 pb-10 md:px-8 md:pb-14">
                    <details class="reviews-form-details mx-auto w-full overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm" style="max-width: min(100%, 68rem);">
                        <summary class="grid cursor-pointer grid-cols-[1fr_auto_1fr] items-center gap-4 px-6 py-5 md:px-8">
                            <span aria-hidden="true" class="block h-11 w-11 justify-self-start"></span>
                            <div class="text-center">
                                <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Share Your Experience</span>
                                <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">Leave a Customer Review</h2>
                                <p class="mx-auto mt-2 max-w-2xl text-sm leading-7 text-stone-600">Open this form if you would like to submit your travel experience for review.</p>
                            </div>
                            <span class="inline-flex h-11 w-11 items-center justify-center justify-self-end rounded-full bg-sky-50 text-sky-700 transition duration-200" data-reviews-form-chevron>
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

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-sky-200 bg-sky-50 px-6 py-2.5 text-base font-semibold text-sky-700 shadow-[0_12px_24px_rgba(14,116,144,0.14)] transition hover:-translate-y-0.5 hover:bg-sky-100 hover:border-sky-300">
                                        Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>
                    </details>
                </div>

            </div>
            <div class="h-16 md:h-24"></div>
            <div class="pb-14 md:pb-18">
                <section class="mx-auto px-3 md:px-4 xl:px-6" style="width: min(96vw, 150rem);">
                    <div class="text-center">
                        <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Customer Gallery</span>
                        <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">Travel Memories From Our Guests</h2>
                    </div>

                    @if (($customerGallery ?? collect())->isNotEmpty())
                        <div class="customer-gallery-grid mt-8 auto-rows-fr">
                            @foreach ($customerGallery as $galleryItem)
                                <article class="customer-gallery-card w-full rounded-[2rem] border border-stone-200 bg-white shadow-[0_20px_45px_rgba(148,163,184,0.14)]">
                                    <img
                                        src="{{ $galleryItem['image'] }}"
                                        alt="{{ $galleryItem['title'] }}"
                                        class="h-full w-full object-cover"
                                    >
                                    <div class="customer-gallery-overlay">
                                        <h3 class="text-base font-semibold text-black">{{ $galleryItem['title'] }}</h3>
                                        <p class="mt-2 max-w-[14rem] text-sm leading-6 text-black">{{ $galleryItem['caption'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="mx-auto mt-8 max-w-3xl rounded-[2rem] border border-dashed border-stone-300 bg-white px-6 py-10 text-center text-sm leading-7 text-stone-600">
                            Customer gallery items will appear here once they are added from the admin testimonials page.
                        </div>
                    @endif
                </section>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const marquee = document.querySelector('[data-review-marquee]');
            const track = document.querySelector('[data-review-track]');

            if (!marquee || !track) {
                return;
            }

            const slides = Array.from(track.querySelectorAll('[data-review-slide]'));

            if (!slides.length) {
                return;
            }

            let offset = 0;
            let lastTimestamp = null;
            let animationFrameId = null;
            let isPaused = false;
            let activeSlide = null;
            let suppressNextPulse = false;
            const loopCopies = Number(track.dataset.reviewLoopCopies || 1);
            const getLoopStartShift = () => Math.min(320, window.innerWidth * 0.24);
            const getInitialCenterNudge = () => Math.min(180, window.innerWidth * 0.13);

            const getLoopWidth = () => track.scrollWidth / loopCopies;

            const updateSlideScale = () => {
                const viewportCenter = window.innerWidth / 2;
                const centerThreshold = Math.min(90, window.innerWidth * 0.08);
                let closestSlide = null;
                let closestDistance = Number.POSITIVE_INFINITY;

                slides.forEach((slide) => {
                    const rect = slide.getBoundingClientRect();
                    const slideCenter = rect.left + (rect.width / 2);
                    const distance = Math.abs(viewportCenter - slideCenter);

                    if (distance < closestDistance) {
                        closestDistance = distance;
                        closestSlide = slide;
                    }
                });

                if (!closestSlide || closestDistance > centerThreshold) {
                    activeSlide = null;
                    return;
                }

                if (closestSlide === activeSlide) {
                    return;
                }

                if (suppressNextPulse) {
                    activeSlide = closestSlide;
                    suppressNextPulse = false;
                    return;
                }

                if (closestSlide) {
                    closestSlide.classList.remove('is-pulsing');
                    void closestSlide.offsetWidth;
                    closestSlide.classList.add('is-pulsing');
                }

                activeSlide = closestSlide;
            };

            const step = (timestamp) => {
                if (lastTimestamp === null) {
                    lastTimestamp = timestamp;
                }

                const delta = timestamp - lastTimestamp;
                lastTimestamp = timestamp;

                if (!isPaused) {
                    offset -= delta * 0.045;

                    const loopWidth = getLoopWidth();
                    const loopStartShift = getLoopStartShift();

                    if (loopWidth > 0 && offset <= (-2 * loopWidth) + loopStartShift) {
                        offset += loopWidth;
                        slides.forEach((slide) => slide.classList.remove('is-pulsing'));
                        activeSlide = null;
                        suppressNextPulse = true;
                    }

                    track.style.transform = `translateX(${offset}px)`;
                }

                updateSlideScale();
                animationFrameId = window.requestAnimationFrame(step);
            };

            marquee.addEventListener('mouseenter', () => {
                isPaused = true;
            });

            marquee.addEventListener('mouseleave', () => {
                isPaused = false;
            });

            window.addEventListener('resize', () => {
                const loopWidth = getLoopWidth();
                const loopStartShift = getLoopStartShift();

                if (loopWidth > 0) {
                    while (offset > (-1 * loopWidth) + loopStartShift) {
                        offset -= loopWidth;
                    }

                    while (offset <= (-2 * loopWidth) + loopStartShift) {
                        offset += loopWidth;
                    }

                    track.style.transform = `translateX(${offset}px)`;
                }

                updateSlideScale();
            });

            const initialLoopWidth = getLoopWidth();

            if (initialLoopWidth > 0) {
                offset = (-1 * initialLoopWidth) + getLoopStartShift() + getInitialCenterNudge();
                track.style.transform = `translateX(${offset}px)`;
                updateSlideScale();
            }

            animationFrameId = window.requestAnimationFrame(step);

            window.addEventListener('beforeunload', () => {
                if (animationFrameId) {
                    window.cancelAnimationFrame(animationFrameId);
                }
            });
        });
    </script>
</x-layouts.app>
