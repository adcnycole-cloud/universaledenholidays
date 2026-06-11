<section class="space-y-8">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-sky-600">Landing Page</p>
                <h1 class="mt-2 text-2xl font-semibold text-stone-900">Manage homepage hero slider images</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-stone-600">Upload up to 5 background images for the top homepage section. Reorder them, hide them, or replace them anytime from here.</p>
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

        <div class="mt-6 grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
            <div class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                <h2 class="text-lg font-semibold text-stone-900">Add hero image</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">New slides will appear in the homepage image rotation. Accepted formats: JPG, PNG, and WebP up to 20 MB each.</p>

                @if ($homeHeroSlides->count() >= 5)
                    <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                        Maximum reached. Delete one of the current slides before adding another.
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.home-hero-slides.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label for="hero_image" class="mb-2 block text-sm font-medium text-stone-700">Hero image</label>
                            <input id="hero_image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                        </div>
                        <div>
                            <label for="hero_display_order" class="mb-2 block text-sm font-medium text-stone-700">Slider position</label>
                            <select id="hero_display_order" name="display_order" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                @for ($position = 1; $position <= min($homeHeroSlides->count() + 1, 5); $position++)
                                    <option value="{{ $position }}" @selected((int) old('display_order', $homeHeroSlides->count() + 1) === $position)>Position {{ $position }}</option>
                                @endfor
                            </select>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-stone-600">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-stone-300">
                            Show this image in the homepage slider
                        </label>
                        <button type="submit" class="w-full rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">Add Hero Image</button>
                    </form>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($homeHeroSlides as $slide)
                    <article class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-4 md:p-5">
                        <div class="grid gap-4 lg:grid-cols-[280px_minmax(0,1fr)]">
                            <div class="overflow-hidden rounded-[1.25rem] border border-stone-200 bg-white p-2">
                                <img src="{{ $slide->image_url }}" alt="Homepage hero slide {{ $slide->display_order }}" class="h-[180px] w-full rounded-[0.9rem] object-cover">
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-sky-700">Slide {{ $slide->display_order }}</span>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $slide->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                        {{ $slide->is_active ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>

                                <form method="POST" action="{{ route('admin.home-hero-slides.update', $slide) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <div class="grid gap-4 md:grid-cols-2">
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
                                    </div>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_active" value="1" @checked($slide->is_active) class="rounded border-stone-300">
                                        Show this image in the homepage slider
                                    </label>
                                    <div class="flex flex-wrap justify-end gap-3">
                                        <button type="submit" class="rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">
                                            Save Slide
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.home-hero-slides.destroy', $slide) }}" class="mt-3" onsubmit="return confirm('Delete this homepage hero image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete Slide</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">No homepage hero images yet. Add your first slide to replace the hardcoded hero background.</div>
                @endforelse
            </div>
        </div>
    </section>
</section>
