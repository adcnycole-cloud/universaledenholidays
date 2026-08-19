<section class="space-y-8">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-sky-600">Landing Page</p>
                <h1 class="mt-2 text-2xl font-semibold text-stone-900">Manage homepage hero slider images</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-stone-600">Upload up to 5 background images for the top homepage section. Reorder them, hide them, replace them, and manage the homepage card heading and description on each slide.</p>
            </div>
            <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600">
                <span class="font-semibold text-stone-900">{{ $homeHeroSlides->count() }}</span> / 5 slides used
            </div>
        </div>

        @if ($errors->has('home_hero_slides'))
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first('home_hero_slides') }}
            </div>
        @endif

        <div class="mt-6">
            <div class="grid items-start gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                @forelse ($homeHeroSlides as $slide)
                    <article class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-4">
                        <div class="space-y-4">
                            <div class="overflow-hidden rounded-[1.25rem] border border-stone-200 bg-white p-2">
                                <img src="{{ $slide->image_url }}" alt="Homepage hero slide {{ $slide->display_order }}" class="h-[110px] w-full rounded-[0.9rem] object-cover">
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">Slide {{ $slide->display_order }}</span>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $slide->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                        {{ $slide->is_active ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                                <div class="mt-3 rounded-2xl border border-stone-200 bg-white px-4 py-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-stone-500">Homepage card preview</p>
                                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.24em] text-stone-500">
                                        {{ $slide->card_heading ?: 'Sabah Escape' }}
                                    </p>
                                    <p class="mt-1 text-sm font-medium text-stone-700">
                                        {{ $slide->card_description ?: 'Slide '.$slide->display_order }}
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <details class="group">
                                        <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                            Edit Slide
                                        </summary>

                                        <div class="mt-4 rounded-[1.5rem] border border-stone-200 bg-white p-4 group-open:block">
                                            <form method="POST" action="{{ route('admin.home-hero-slides.update', $slide) }}" enctype="multipart/form-data" class="space-y-4">
                                                @csrf
                                                @method('PATCH')
                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="mb-2 block text-sm font-medium text-stone-700">Replace image</label>
                                                        <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                                                    </div>
                                                    <div>
                                                        <label class="mb-2 block text-sm font-medium text-stone-700">Slider position</label>
                                                        <select name="display_order" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                            @for ($position = 1; $position <= $homeHeroSlides->count(); $position++)
                                                                <option value="{{ $position }}" @selected($slide->display_order === $position)>Position {{ $position }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="mb-2 block text-sm font-medium text-stone-700">Card heading</label>
                                                        <input
                                                            name="card_heading"
                                                            type="text"
                                                            value="{{ old('card_heading', $slide->card_heading) }}"
                                                            placeholder="Example: Sabah Escape"
                                                            class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800"
                                                        >
                                                        <p class="mt-2 text-xs leading-5 text-stone-500">
                                                            This is the small heading shown at the top of the homepage slide card.
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="mb-2 block text-sm font-medium text-stone-700">Card description</label>
                                                        <input
                                                            name="card_description"
                                                            type="text"
                                                            value="{{ old('card_description', $slide->card_description) }}"
                                                            placeholder="Example: Slide 03, Pulau Sapi, or Mount Kinabalu"
                                                            class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800"
                                                        >
                                                        <p class="mt-2 text-xs leading-5 text-stone-500">
                                                            This is the bigger text shown under the card heading on the homepage slide card.
                                                        </p>
                                                    </div>
                                                </div>
                                                <label class="flex items-center gap-2 text-sm text-stone-600">
                                                    <input type="checkbox" name="is_active" value="1" @checked($slide->is_active) class="rounded border-stone-300">
                                                    Show this image in the homepage slider
                                                </label>
                                                <div class="flex flex-wrap gap-3">
                                                    <button type="submit" class="flex-1 rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">
                                                        Save Slide
                                                    </button>
                                            </form>
                                                    <form method="POST" action="{{ route('admin.home-hero-slides.destroy', $slide) }}" onsubmit="return confirm('Delete this homepage hero image?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete Slide</button>
                                                    </form>
                                                </div>
                                            </div>
                                    </details>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600 xl:col-span-5">No homepage hero images yet. Add your first slide to replace the hardcoded hero background.</div>
                @endforelse
            </div>
        </div>
    </section>
</section>
