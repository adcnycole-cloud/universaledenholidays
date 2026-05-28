<article class="rounded-3xl border border-stone-200 bg-stone-50 p-5 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-4">
            <img
                src="{{ $review['profile_photo_url'] }}"
                alt="{{ $review['name'] }}"
                class="h-12 w-12 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-white"
                style="aspect-ratio: 1 / 1; border-radius: 9999px;"
            >
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-lg font-semibold text-stone-900">{{ $review['name'] }}</h3>
                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] {{ $review['source'] === 'google' ? 'bg-rose-100 text-rose-700' : 'bg-sky-100 text-sky-700' }}">
                        {{ $review['source_label'] }}
                    </span>
                </div>
                <p class="text-sm text-stone-500">
                    {{ $review['location'] }}
                    @if (!empty($review['trip_name']))
                        &middot; {{ $review['trip_name'] }}
                    @endif
                </p>
                @if (!empty($review['published_label']))
                    <p class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-400">{{ $review['published_label'] }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-1 text-amber-500">
            @for ($star = 0; $star < (int) $review['rating']; $star++)
                <span class="text-lg leading-none">&#9733;</span>
            @endfor
        </div>
    </div>

    <p class="mt-4 text-sm leading-6 text-stone-600">"{{ $review['quote'] }}"</p>

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
