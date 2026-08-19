<article class="flex h-full min-h-[20rem] flex-col rounded-[2rem] border border-white/80 bg-white px-6 py-6 shadow-[0_20px_45px_rgba(148,163,184,0.18)]">
    <div class="text-center">
        <img
            src="{{ $review['profile_photo_url'] }}"
            alt="{{ $review['name'] }}"
            class="mx-auto h-16 w-16 rounded-full object-cover shadow-sm ring-4 ring-white"
            style="aspect-ratio: 1 / 1; border-radius: 9999px;"
        >
        <h3 class="mt-4 text-lg font-semibold text-stone-900">{{ $review['name'] }}</h3>
        <p class="mt-1 text-sm text-stone-500">
            {{ $review['location'] }}
            @if (!empty($review['trip_name']))
                &middot; {{ $review['trip_name'] }}
            @endif
        </p>
        @if (!empty($review['published_label']))
            <p class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-400">{{ $review['published_label'] }}</p>
        @endif
        <div class="mt-3 flex items-center justify-center gap-1 text-amber-500">
            @for ($star = 0; $star < (int) $review['rating']; $star++)
                <span class="text-lg leading-none">&#9733;</span>
            @endfor
        </div>
    </div>

    <div class="mt-6 text-7xl leading-none text-stone-200">&ldquo;</div>

    <p class="mt-2 flex-1 text-sm leading-7 text-stone-600">"{{ $review['quote'] }}"</p>

    <div class="mt-5 flex justify-end text-7xl leading-none text-stone-200">&rdquo;</div>

    @if (!empty($review['review_url']))
        <div class="mt-4">
            <a
                href="{{ $review['review_url'] }}"
                target="_blank"
                rel="noreferrer"
                class="text-sm font-semibold text-sky-700 transition hover:text-sky-800"
            >
                {{ $review['source'] === 'google' ? 'View on Google' : 'Read review' }}
            </a>
        </div>
    @endif
</article>
