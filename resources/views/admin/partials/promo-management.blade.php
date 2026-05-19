<section id="admin-promo-listings" class="mt-5 space-y-8">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <p class="text-sm uppercase tracking-[0.3em] text-rose-600">Promos</p>
        <h1 class="mt-2 text-3xl font-semibold text-stone-900">Upload posters for special offers</h1>
        <form method="POST" action="{{ route('admin.news-features.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div class="grid gap-4 lg:grid-cols-3">
                <div>
                    <label for="promo_label" class="mb-2 block text-sm font-medium text-stone-700">Promo label</label>
                    <input id="promo_label" name="promo_label" type="text" placeholder="Hari Raya Promo" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-stone-700">Title</label>
                    <input id="title" name="title" type="text" placeholder="Limited Days Offer" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="poster" class="mb-2 block text-sm font-medium text-stone-700">Poster image</label>
                    <input id="poster" name="poster" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-stone-700">
                </div>
            </div>
            <div>
                <label for="summary" class="mb-2 block text-sm font-medium text-stone-700">Summary</label>
                <textarea id="summary" name="summary" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800"></textarea>
            </div>
            <div class="grid gap-4 lg:grid-cols-[1fr_1fr_auto] lg:items-end">
                <div>
                    <label for="starts_at" class="mb-2 block text-sm font-medium text-stone-700">Start date</label>
                    <input id="starts_at" name="starts_at" type="date" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="ends_at" class="mb-2 block text-sm font-medium text-stone-700">End date</label>
                    <input id="ends_at" name="ends_at" type="date" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <label class="flex items-center gap-2 text-sm text-stone-600 lg:pb-3">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-stone-300">
                    Show this promo on the homepage
                </label>
            </div>
            <button type="submit" class="w-full rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-rose-700">Save Promo</button>
        </form>
    </section>

    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-2xl font-semibold text-stone-900">Live promo entries</h2>
                <label class="relative block w-full lg:max-w-sm">
                    <span class="sr-only">Search promos</span>
                    <input id="admin-promo-search" type="search" placeholder="Search promos by label, title, or summary" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white">
                </label>
            </div>
            <div class="flex items-center justify-end gap-3">
                <p id="admin-promo-results" class="sr-only">Showing promo entries</p>
                <div class="flex items-center gap-2">
                    <button
                        id="admin-promo-prev"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-stone-700 transition hover:bg-stone-100"
                        aria-label="Previous promos page"
                    >
                        <span class="text-lg leading-none">&#8249;</span>
                    </button>
                    <button
                        id="admin-promo-next"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-stone-700 transition hover:bg-stone-100"
                        aria-label="Next promos page"
                    >
                        <span class="text-lg leading-none">&#8250;</span>
                    </button>
                </div>
            </div>
        </div>
        <div id="admin-promo-list" class="mt-6 space-y-4">
            @forelse ($newsFeatures as $feature)
                @php($promoEnded = $feature->ends_at && $feature->ends_at->isPast())
                <article data-admin-promo-item="true" class="overflow-hidden rounded-3xl border border-stone-200 bg-stone-50">
                    <div style="display: grid; gap: 0; grid-template-columns: 226px minmax(0, 1fr);">
                        <div class="border-b border-stone-200 bg-white md:border-b-0 md:border-r" style="padding: 0.35rem;">
                            <a href="{{ $feature->poster_url }}" target="_blank" rel="noopener noreferrer" class="block">
                                <img src="{{ $feature->poster_url }}" alt="{{ $feature->title }}" style="display: block; width: 200px; max-width: 200px; height: 300px; margin: 0 auto; object-fit: contain; border-radius: 0.375rem; background: #fff; box-shadow: 0 1px 3px rgba(15,23,42,0.12);">
                            </a>
                        </div>
                        <div class="min-w-0 p-6 md:p-7" style="min-width: 0;">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-rose-700">{{ $feature->promo_label ?: 'Promo News' }}</span>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $promoEnded ? 'text-amber-700' : ($feature->is_active ? 'text-emerald-700' : 'text-stone-500') }}">
                                    {{ $promoEnded ? 'Promo ended' : ($feature->is_active ? 'Active' : 'Hidden') }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <details class="group">
                                    <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                        <span class="group-open:hidden">Edit</span>
                                        <span class="hidden group-open:inline">Close</span>
                                    </summary>
                                    <form method="POST" action="{{ route('admin.news-features.update', $feature) }}" enctype="multipart/form-data" class="mt-4 space-y-3 rounded-2xl border border-stone-200 bg-white p-4">
                                        @csrf
                                        @method('PATCH')
                                        <div class="grid gap-3 md:grid-cols-2">
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Promo label</label>
                                                <input name="promo_label" type="text" value="{{ $feature->promo_label }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Title</label>
                                                <input name="title" type="text" value="{{ $feature->title }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Summary</label>
                                            <textarea name="summary" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $feature->summary }}</textarea>
                                        </div>
                                        <div class="grid gap-3 md:grid-cols-3">
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Start date</label>
                                                <input name="starts_at" type="date" value="{{ $feature->starts_at?->format('Y-m-d') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">End date</label>
                                                <input name="ends_at" type="date" value="{{ $feature->ends_at?->format('Y-m-d') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Poster image</label>
                                                <input name="poster" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                                            </div>
                                        </div>
                                        <label class="flex items-center gap-2 text-sm text-stone-600">
                                            <input type="checkbox" name="is_active" value="1" @checked($feature->is_active) class="rounded border-stone-300">
                                            Show this promo on the homepage
                                        </label>
                                        <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">Update Promo</button>
                                    </form>
                                </details>

                                <form method="POST" action="{{ route('admin.news-features.destroy', $feature) }}" onsubmit="return confirm('Delete this promo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete</button>
                                </form>
                            </div>
                        </div>
                        <h3 class="mt-3 text-xl font-semibold text-stone-900">{{ $feature->title }}</h3>
                        @if ($feature->summary)
                            <p class="mt-2 text-sm leading-6 text-stone-600">{{ $feature->summary }}</p>
                        @endif
                        <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            @if ($feature->starts_at)
                                <span class="rounded-full bg-white px-3 py-1">Start {{ $feature->starts_at->format('d M Y') }}</span>
                            @endif
                            @if ($feature->ends_at)
                                <span class="rounded-full bg-white px-3 py-1">End {{ $feature->ends_at->format('d M Y') }}</span>
                            @endif
                        </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">No promo news yet.</div>
            @endforelse
        </div>
    </section>
</section>
