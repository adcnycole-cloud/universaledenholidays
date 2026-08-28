<x-layouts.app title="Travel Stories & Local Guides | Universal Eden Holidays">
    @php
        $featureStory = $blogPosts->first();
        $latestStories = $blogPosts->slice(1)->values();
        $fallbackImages = [asset('images/mount kinabalu.jpg'), asset('images/mari mari.png'), asset('images/shun shun.png'), asset('images/dreamer island.png'), asset('images/mount kinabalu.png')];
        $storyCategory = function ($post) {
            $content = strtolower(trim($post->title.' '.$post->description.' '.$post->destination));
            return match (true) {
                str_contains($content, 'food') || str_contains($content, 'eat') || str_contains($content, 'restaurant') => 'Food & Drink',
                str_contains($content, 'culture') || str_contains($content, 'heritage') || str_contains($content, 'mari mari') => 'Culture',
                str_contains($content, 'island') || str_contains($content, 'destination') || str_contains($content, 'beach') => 'Destinations',
                default => 'Travel Tips',
            };
        };
    @endphp

    <main class="blog-page min-h-[calc(100vh-var(--app-header-offset,0px))]">
        <section class="blog-shell">
            <header class="blog-intro">
                <span class="blog-kicker">Discover Sabah</span>
                <h1>Travel Stories &amp; Local Guides</h1>
                <p>Inspiration, practical tips and local stories to help you experience the best of Sabah.</p>
            </header>

            <div class="blog-toolbar">
                <div class="blog-filters" role="group" aria-label="Filter stories by category">
                    @foreach (['All Stories', 'Destinations', 'Travel Tips', 'Culture', 'Food & Drink'] as $category)
                        <button type="button" class="blog-filter {{ $loop->first ? 'is-active' : '' }}" data-blog-filter="{{ $category }}">{{ $category }}</button>
                    @endforeach
                </div>
                <label class="blog-search">
                    <span class="sr-only">Search articles</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 21-4.35-4.35m2.35-5.15a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" /></svg>
                    <input type="search" placeholder="Search articles" data-blog-search>
                </label>
            </div>

            @if ($featureStory)
                @php($featureCategory = $storyCategory($featureStory))
                <a href="{{ route('blog.show', $featureStory) }}" class="blog-feature group" data-featured-story>
                    <div class="blog-feature-media"><img src="{{ $featureStory->cover_image_url ?: $fallbackImages[0] }}" alt="{{ $featureStory->title }}"></div>
                    <div class="blog-feature-content">
                        <span class="blog-category-label">Featured</span>
                        <span class="blog-date">{{ $featureStory->published_at?->format('d M Y') ?? 'Latest Story' }}</span>
                        <h2>{{ $featureStory->title }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($featureStory->excerpt ?: $featureStory->description), 180) }}</p>
                        <span class="blog-read-link">Read Article <span aria-hidden="true">&#8594;</span></span>
                    </div>
                </a>
            @endif

            <section class="blog-latest" aria-labelledby="latest-stories-title">
                <h2 id="latest-stories-title">Latest Stories</h2>
                <div class="blog-story-grid" data-blog-grid>
                    @forelse ($latestStories as $post)
                        @php($category = $storyCategory($post))
                        <a href="{{ route('blog.show', $post) }}" class="blog-story-card group" data-blog-story data-category="{{ $category }}" data-search="{{ strtolower($post->title.' '.$post->description.' '.$category) }}">
                            <div class="blog-story-media">
                                <img src="{{ $post->cover_image_url ?: $fallbackImages[$loop->index % count($fallbackImages)] }}" alt="{{ $post->title }}">
                                <span class="blog-category-label">{{ $category }}</span>
                            </div>
                            <div class="blog-story-content">
                                <span class="blog-date">{{ $post->published_at?->format('d M Y') ?? 'Latest Story' }}</span>
                                <h3>{{ $post->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->description), 105) }}</p>
                                <span class="blog-read-link">Read Article <span aria-hidden="true">&#8594;</span></span>
                            </div>
                        </a>
                    @empty
                        <p class="blog-empty">More Sabah stories are on their way. Please check back soon.</p>
                    @endforelse
                </div>
                <p class="blog-empty hidden" data-blog-no-results>No stories match your search.</p>
                <nav class="blog-pagination hidden" aria-label="Story pages" data-blog-pagination></nav>
            </section>
        </section>
    </main>

    <style>
        .blog-page { background: #f7f4ec; color: #152d4e; }
        .blog-shell { width: min(100% - 2rem, 1260px); margin: 0 auto; padding: 3rem 0 4rem; }
        .blog-intro { text-align: center; }
        .blog-kicker { color: #2fa542; font-size: .68rem; font-weight: 800; letter-spacing: .24em; text-transform: uppercase; }
        .blog-intro h1 { margin-top: .4rem; color: #132d50; font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 800; letter-spacing: -.035em; line-height: 1.08; }
        .blog-intro p { margin: .65rem auto 0; max-width: 43rem; color: #697789; font-size: .92rem; line-height: 1.6; }
        .blog-toolbar { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .8rem; margin-top: 1.8rem; }
        .blog-filters { display: flex; flex-wrap: wrap; gap: .55rem; }
        .blog-filter { border: 1px solid #dce2e9; border-radius: 999px; background: #fff; color: #30425b; cursor: pointer; padding: .48rem 1rem; font-size: .75rem; font-weight: 700; transition: .2s ease; }
        .blog-filter:hover, .blog-filter.is-active { border-color: #32a744; background: #32a744; color: #fff; }
        .blog-search { display: flex; align-items: center; width: min(100%, 16.5rem); border: 1px solid #dce2e9; border-radius: 999px; background: #fff; padding: 0 .85rem; }
        .blog-search svg { width: .9rem; height: .9rem; fill: none; stroke: #91a0b1; stroke-linecap: round; stroke-width: 1.8; }
        .blog-search input { width: 100%; border: 0; background: transparent; outline: 0; padding: .52rem .55rem; color: #1d314c; font-size: .76rem; }
        .blog-feature { display: grid; grid-template-columns: 1.05fr 1fr; overflow: hidden; margin-top: 1rem; border: 1px solid #e1e4e7; border-radius: 1rem; background: #fff; box-shadow: 0 8px 20px rgba(28, 44, 66, .07); text-decoration: none; }
        .blog-feature-media { min-height: 17rem; overflow: hidden; background: #dce8e2; }
        .blog-feature img, .blog-story-media img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
        .group:hover img { transform: scale(1.035); }
        .blog-feature-content { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding: 2rem 2.2rem; }
        .blog-category-label { display: inline-block; border-radius: .25rem; background: #35a846; color: #fff; padding: .22rem .45rem; font-size: .58rem; font-weight: 800; letter-spacing: .08em; line-height: 1; text-transform: uppercase; }
        .blog-date { display: block; margin-top: .65rem; color: #788697; font-size: .7rem; }
        .blog-feature h2 { margin-top: .55rem; color: #172c4d; font-size: clamp(1.25rem, 2vw, 1.7rem); font-weight: 800; line-height: 1.16; }
        .blog-feature p, .blog-story-content p { margin-top: .45rem; color: #6d798a; font-size: .78rem; line-height: 1.5; }
        .blog-read-link { display: inline-flex; align-items: center; gap: .35rem; margin-top: .8rem; color: #259638; font-size: .72rem; font-weight: 800; }
        .blog-latest { margin-top: 1.35rem; }
        .blog-latest > h2 { color: #1a3152; font-size: 1rem; font-weight: 800; }
        .blog-story-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .75rem; margin-top: .6rem; }
        .blog-story-card { overflow: hidden; border: 1px solid #e1e4e7; border-radius: .8rem; background: #fff; box-shadow: 0 5px 13px rgba(28, 44, 66, .06); text-decoration: none; }
        .blog-story-media { position: relative; height: 8rem; overflow: hidden; background: #dce8e2; }
        .blog-story-media .blog-category-label { position: absolute; top: .55rem; left: .55rem; }
        .blog-story-content { padding: .7rem .8rem .75rem; }
        .blog-story-content .blog-date { margin-top: 0; }
        .blog-story-content h3 { margin-top: .3rem; color: #1b3151; font-size: .88rem; font-weight: 800; line-height: 1.25; }
        .blog-story-content p { display: -webkit-box; overflow: hidden; -webkit-box-orient: vertical; -webkit-line-clamp: 2; margin-top: .25rem; font-size: .69rem; line-height: 1.42; }
        .blog-story-content .blog-read-link { margin-top: .4rem; font-size: .67rem; }
        .blog-empty { margin-top: 1rem; color: #6d798a; font-size: .85rem; }
        .blog-pagination { display: flex; justify-content: center; gap: .45rem; margin-top: 1.2rem; }
        .blog-page-button { display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; border: 1px solid #dce2e9; border-radius: .35rem; background: #fff; color: #647386; cursor: pointer; font-size: .72rem; font-weight: 800; }
        .blog-page-button.is-active { border-color: #32a744; background: #32a744; color: #fff; }
        @media (max-width: 767px) { .blog-shell { width: min(100% - 1.4rem, 1260px); padding: 2.2rem 0 3rem; } .blog-toolbar { justify-content: center; } .blog-filters { justify-content: center; } .blog-search { width: 100%; } .blog-feature { grid-template-columns: 1fr; } .blog-feature-media { min-height: 13rem; } .blog-feature-content { padding: 1.2rem; } .blog-story-grid { grid-template-columns: 1fr; } .blog-story-media { height: 10rem; } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filters = [...document.querySelectorAll('[data-blog-filter]')];
            const search = document.querySelector('[data-blog-search]');
            const cards = [...document.querySelectorAll('[data-blog-story]')];
            const featured = document.querySelector('[data-featured-story]');
            const empty = document.querySelector('[data-blog-no-results]');
            const pagination = document.querySelector('[data-blog-pagination]');
            const perPage = 6;
            let category = 'All Stories'; let page = 1;
            const render = () => {
                const query = search.value.trim().toLowerCase();
                const matches = cards.filter((card) => (category === 'All Stories' || card.dataset.category === category) && card.dataset.search.includes(query));
                const pages = Math.max(1, Math.ceil(matches.length / perPage)); page = Math.min(page, pages);
                cards.forEach((card) => card.classList.add('hidden'));
                matches.slice((page - 1) * perPage, page * perPage).forEach((card) => card.classList.remove('hidden'));
                empty.classList.toggle('hidden', matches.length > 0);
                if (featured) featured.classList.toggle('hidden', Boolean(query) || category !== 'All Stories');
                pagination.innerHTML = ''; pagination.classList.toggle('hidden', pages <= 1);
                for (let index = 1; index <= pages; index++) {
                    const button = document.createElement('button'); button.type = 'button'; button.textContent = index;
                    button.className = `blog-page-button${index === page ? ' is-active' : ''}`;
                    button.addEventListener('click', () => { page = index; render(); }); pagination.appendChild(button);
                }
            };
            filters.forEach((filter) => filter.addEventListener('click', () => { category = filter.dataset.blogFilter; page = 1; filters.forEach((button) => button.classList.toggle('is-active', button === filter)); render(); }));
            search.addEventListener('input', () => { page = 1; render(); }); render();
        });
    </script>

    @include('partials.footer')
</x-layouts.app>
