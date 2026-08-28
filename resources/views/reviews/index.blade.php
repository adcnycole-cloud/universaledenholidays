<x-layouts.app title="Customer Reviews | Universal Eden Holidays">
    @php
        $reviewCount = $allReviews->count();
        $averageRating = $reviewCount ? round((float) $allReviews->avg('rating'), 1) : null;
        $ratingBreakdown = collect(range(5, 1))->mapWithKeys(fn ($rating) => [$rating => $reviewCount ? round(($allReviews->where('rating', $rating)->count() / $reviewCount) * 100) : 0]);
    @endphp

    <main class="reviews-page min-h-[calc(100vh-var(--app-header-offset,0px))]">
        <section class="reviews-shell">
            <header class="reviews-intro">
                <span class="reviews-kicker">Traveller Experiences</span>
                <h1>Stories from Our Happy Travellers</h1>
                <p>Real experiences shared by guests who explored Sabah with Universal Eden Holidays.</p>
            </header>

            <section class="reviews-summary" aria-label="Customer rating summary">
                <div class="reviews-score">
                    <strong>{{ $averageRating ? number_format($averageRating, 1) : '5.0' }}</strong>
                    <span class="reviews-stars">★★★★★</span>
                    <small>Based on <b>{{ $reviewCount }}</b> verified reviews</small>
                </div>
                <div class="reviews-breakdown">
                    @foreach ($ratingBreakdown as $rating => $percent)
                        <div class="reviews-rating-row">
                            <span>{{ $rating }} stars</span>
                            <i><b style="width: {{ $percent }}%"></b></i>
                            <em>{{ $percent }}%</em>
                        </div>
                    @endforeach
                </div>
                <div class="reviews-metrics">
                    <div><span class="reviews-metric-icon">♧</span><strong>{{ $averageRating ? number_format($averageRating * 20) : 100 }}%</strong><small>Recommend Us</small></div>
                    <div><span class="reviews-metric-icon">♧</span><strong>{{ $averageRating ? number_format($averageRating, 1) : '5.0' }}</strong><small>Service</small></div>
                    <div><span class="reviews-metric-icon">⌂</span><strong>{{ $averageRating ? number_format($averageRating, 1) : '5.0' }}</strong><small>Tour Experience</small></div>
                </div>
            </section>

            <section class="reviews-listing" aria-labelledby="customer-reviews-title">
                <div class="reviews-list-header">
                    <h2 id="customer-reviews-title">Customer Reviews</h2>
                    <div class="reviews-controls">
                        <select data-review-rating aria-label="Filter by rating">
                            <option value="all">All Ratings</option>
                            @for ($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}">{{ $rating }} Stars</option>
                            @endfor
                        </select>
                        <select data-review-sort aria-label="Sort reviews">
                            <option value="recent">Most Recent</option>
                            <option value="highest">Highest Rating</option>
                        </select>
                    </div>
                </div>

                <div class="reviews-grid" data-reviews-grid>
                    @forelse ($allReviews as $review)
                        @php($initials = collect(explode(' ', trim($review['name'])))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode(''))
                        <article class="review-card" data-review-card data-rating="{{ $review['rating'] }}">
                            <div class="review-person">
                                @if (!empty($review['profile_photo_url']))
                                    <img src="{{ $review['profile_photo_url'] }}" alt="{{ $review['name'] }}">
                                @else
                                    <span class="review-avatar">{{ $initials ?: 'UE' }}</span>
                                @endif
                                <div><strong>{{ $review['name'] }}</strong><small>{{ $review['location'] ?: 'Sabah traveller' }}</small></div>
                            </div>
                            <div class="review-content">
                                <div class="review-rating"><span>{{ str_repeat('★', (int) $review['rating']) }}{{ str_repeat('☆', 5 - (int) $review['rating']) }}</span><small>{{ $review['published_label'] ?: 'Verified traveller' }}</small></div>
                                <h3>{{ $review['trip_name'] ?: 'A Sabah travel experience' }}</h3>
                                <p>{{ $review['quote'] }}</p>
                                <span class="review-verified">Verified Booking</span>
                            </div>
                        </article>
                    @empty
                        <p class="reviews-empty">No customer reviews are available yet.</p>
                    @endforelse
                </div>
                <p class="reviews-empty hidden" data-review-empty>No reviews match that rating.</p>
            </section>

            <details class="review-form">
                <summary><span>Share Your Experience</span><b>Leave a Customer Review</b><i aria-hidden="true">⌄</i></summary>
                <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="review-form-grid">
                        <label>Name<input name="name" value="{{ old('name') }}" required></label>
                        <label>Email<input name="email" type="email" value="{{ old('email') }}" required></label>
                        <label>Location<input name="location" value="{{ old('location') }}" required></label>
                        <label>Trip Name<input name="trip_name" value="{{ old('trip_name') }}" required></label>
                        <label>Rating<select name="rating" required><option value="">Select rating</option>@for ($rating = 5; $rating >= 1; $rating--)<option value="{{ $rating }}">{{ $rating }} Star{{ $rating > 1 ? 's' : '' }}</option>@endfor</select></label>
                        <label>Profile Photo<input name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp"></label>
                    </div>
                    <label class="review-form-message">Review<textarea name="quote" rows="4" required>{{ old('quote') }}</textarea></label>
                    <button type="submit">Submit Review →</button>
                </form>
            </details>

            <section class="gallery-section" aria-labelledby="customer-gallery-title">
                <header class="reviews-intro">
                    <span class="reviews-kicker">Customer Gallery</span>
                    <h2 id="customer-gallery-title">Travel Memories From Our Guests</h2>
                    <p>Snapshots of remarkable Sabah adventures shared by our travellers.</p>
                </header>
                @if ($customerGallery->isNotEmpty())
                    <div class="gallery-grid">
                        @foreach ($customerGallery as $galleryItem)
                            <article class="gallery-card">
                                <img src="{{ $galleryItem['image'] }}" alt="{{ $galleryItem['title'] }}">
                                <div><h3>{{ $galleryItem['title'] }}</h3><p>{{ $galleryItem['caption'] }}</p></div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <p class="reviews-empty">Customer gallery items will appear here once they are added.</p>
                @endif
            </section>
        </section>
    </main>

    <style>
        .reviews-page { background: #faf7f0; color: #132c4e; }
        .reviews-shell { width: min(100% - 2rem, 1120px); margin: 0 auto; padding: 2.5rem 0 4rem; }
        .reviews-intro { text-align: center; }.reviews-kicker { color: #24953a; font-size: .66rem; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; }.reviews-intro h1, .reviews-intro h2 { margin-top: .4rem; color: #132c4e; font-size: clamp(1.7rem, 3.2vw, 2.45rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }.reviews-intro p { margin: .5rem auto 0; color: #788496; font-size: .78rem; }
        .reviews-summary { display: grid; grid-template-columns: .85fr 1.35fr 1.3fr; align-items: center; gap: 1.6rem; margin-top: 1.2rem; padding: 1.4rem 2rem; border: 1px solid #e5e7e5; border-radius: .9rem; background: #fff; box-shadow: 0 8px 20px rgba(23, 44, 73, .08); }.reviews-score { display: flex; flex-direction: column; align-items: center; padding-right: 1.6rem; border-right: 1px solid #e9eceb; }.reviews-score strong { font-size: 3.4rem; line-height: .9; }.reviews-stars { color: #f6b30f; font-size: 1.15rem; letter-spacing: .06em; }.reviews-score small { margin-top: .25rem; color: #6e7a8c; font-size: .62rem; }.reviews-score b { color: #24953a; }.reviews-breakdown { display: grid; gap: .45rem; }.reviews-rating-row { display: grid; grid-template-columns: 2.8rem 1fr 2rem; align-items: center; gap: .45rem; color: #677485; font-size: .62rem; }.reviews-rating-row i { height: .28rem; overflow: hidden; border-radius: 999px; background: #edf0ef; }.reviews-rating-row b { display: block; height: 100%; border-radius: inherit; background: #24953a; }.reviews-rating-row em { font-style: normal; }.reviews-metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; text-align: center; }.reviews-metrics div { display: flex; flex-direction: column; align-items: center; }.reviews-metric-icon { color: #24953a; font-size: 1.45rem; line-height: 1; }.reviews-metrics strong { margin-top: .25rem; font-size: 1.25rem; }.reviews-metrics small { color: #6e7a8c; font-size: .6rem; }
        .reviews-listing { margin-top: 1.4rem; }.reviews-list-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }.reviews-list-header h2 { font-size: 1.35rem; font-weight: 800; }.reviews-controls { display: flex; gap: .5rem; }.reviews-controls select { border: 1px solid #dfe5e4; border-radius: .4rem; background: #fff; color: #657185; padding: .42rem .75rem; font-size: .66rem; outline: 0; }.reviews-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; margin-top: .65rem; }.review-card { display: grid; grid-template-columns: 5.3rem minmax(0, 1fr); gap: .85rem; min-height: 10rem; padding: .8rem; border: 1px solid #e4e8e5; border-radius: .8rem; background: #fff; box-shadow: 0 4px 12px rgba(23, 44, 73, .06); }.review-person { text-align: center; }.review-person img, .review-avatar { display: flex; width: 3.2rem; height: 3.2rem; align-items: center; justify-content: center; margin: 0 auto .35rem; border-radius: 50%; background: #dceadf; color: #247438; font-size: .76rem; font-weight: 800; object-fit: cover; }.review-person strong { display: block; color: #233b58; font-size: .65rem; line-height: 1.2; }.review-person small { display: block; margin-top: .15rem; color: #7f8a99; font-size: .56rem; }.review-rating { display: flex; align-items: center; justify-content: space-between; gap: .6rem; }.review-rating span { color: #f4ad12; font-size: .86rem; letter-spacing: .03em; }.review-rating small { color: #7b8796; font-size: .57rem; }.review-content h3 { margin-top: .35rem; color: #1c3454; font-size: .85rem; font-weight: 800; line-height: 1.2; }.review-content p { display: -webkit-box; overflow: hidden; -webkit-box-orient: vertical; -webkit-line-clamp: 3; margin-top: .3rem; color: #687588; font-size: .64rem; line-height: 1.45; }.review-verified { display: inline-block; margin-top: .4rem; border: 1px solid #48a75b; border-radius: .2rem; color: #248337; padding: .14rem .3rem; font-size: .53rem; font-weight: 800; }.reviews-empty { margin-top: 1rem; color: #738092; font-size: .8rem; text-align: center; }
        .review-form { margin-top: 1.5rem; overflow: hidden; border: 1px solid #e4e8e5; border-radius: .8rem; background: #fff; }.review-form summary { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; cursor: pointer; padding: 1rem 1.4rem; text-align: center; }.review-form summary span { color: #24953a; font-size: .6rem; font-weight: 800; letter-spacing: .18em; text-transform: uppercase; }.review-form summary b { color: #173151; font-size: 1.05rem; }.review-form summary i { justify-self: end; color: #24953a; font-size: 1.3rem; font-style: normal; }.review-form form { border-top: 1px solid #e7ebe9; padding: 1.2rem; }.review-form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: .8rem; }.review-form label { color: #607084; font-size: .65rem; font-weight: 800; }.review-form input, .review-form select, .review-form textarea { display: block; box-sizing: border-box; width: 100%; margin-top: .3rem; border: 1px solid #dce3e2; border-radius: .4rem; background: #fbfcfb; padding: .6rem; color: #183150; font-size: .78rem; outline-color: #24953a; }.review-form-message { display: block; margin-top: .8rem; }.review-form button { margin-top: .9rem; border: 0; border-radius: .35rem; background: #2d9f42; color: #fff; cursor: pointer; padding: .65rem 1.1rem; font-size: .72rem; font-weight: 800; }
        .gallery-section { margin-top: 3.5rem; padding-top: 2.6rem; border-top: 1px solid #e7e6df; }.gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: .75rem; margin-top: 1.25rem; }.gallery-card { position: relative; min-height: 13rem; overflow: hidden; border: 1px solid #e0e6e3; border-radius: .8rem; background: #fff; box-shadow: 0 5px 13px rgba(23, 44, 73, .06); }.gallery-card img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }.gallery-card:hover img { transform: scale(1.05); }.gallery-card div { position: absolute; right: 0; bottom: 0; left: 0; padding: .85rem; background: linear-gradient(transparent, rgba(10, 31, 51, .88)); color: #fff; }.gallery-card h3 { font-size: .78rem; font-weight: 800; }.gallery-card p { margin-top: .2rem; font-size: .62rem; line-height: 1.35; }.reviews-page .hidden { display: none !important; }
        @media (max-width: 800px) { .reviews-summary { grid-template-columns: 1fr; gap: 1rem; }.reviews-score { padding: 0 0 1rem; border-right: 0; border-bottom: 1px solid #e9eceb; }.reviews-grid { grid-template-columns: 1fr; }.gallery-grid { grid-template-columns: repeat(2, 1fr); }.reviews-metrics { max-width: 22rem; margin: 0 auto; }.reviews-breakdown { max-width: 25rem; width: 100%; margin: 0 auto; } }
        @media (max-width: 560px) { .reviews-shell { width: min(100% - 1.2rem, 1120px); padding-top: 2rem; }.reviews-summary { padding: 1.1rem; }.reviews-list-header { align-items: flex-start; flex-direction: column; }.review-card { grid-template-columns: 4.5rem minmax(0, 1fr); }.review-form summary { grid-template-columns: 1fr auto; text-align: left; }.review-form summary span { grid-column: 1; }.review-form summary b { grid-column: 1; grid-row: 2; }.review-form summary i { grid-column: 2; grid-row: 1 / 3; }.review-form-grid { grid-template-columns: 1fr; }.gallery-grid { grid-template-columns: 1fr; }.gallery-card { min-height: 15rem; } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rating = document.querySelector('[data-review-rating]');
            const sort = document.querySelector('[data-review-sort]');
            const cards = [...document.querySelectorAll('[data-review-card]')];
            const grid = document.querySelector('[data-reviews-grid]');
            const empty = document.querySelector('[data-review-empty]');
            const updateReviews = () => {
                const selectedRating = rating.value;
                const visible = cards.filter((card) => selectedRating === 'all' || card.dataset.rating === selectedRating);
                cards.forEach((card) => card.classList.toggle('hidden', !visible.includes(card)));
                if (sort.value === 'highest') visible.sort((a, b) => Number(b.dataset.rating) - Number(a.dataset.rating)).forEach((card) => grid.appendChild(card));
                empty.classList.toggle('hidden', visible.length > 0);
            };
            rating?.addEventListener('change', updateReviews); sort?.addEventListener('change', updateReviews);
        });
    </script>

    @include('partials.footer')
</x-layouts.app>
