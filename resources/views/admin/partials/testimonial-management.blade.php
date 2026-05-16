<section class="mt-8 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Testimonials</p>
        <h1 class="mt-2 text-3xl font-semibold text-stone-900">Add customer reviews</h1>
        <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div class="grid gap-4 lg:grid-cols-4">
                <div>
                    <label for="testimonial_name" class="mb-2 block text-sm font-medium text-stone-700">Customer name</label>
                    <input id="testimonial_name" name="name" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="testimonial_location" class="mb-2 block text-sm font-medium text-stone-700">Location</label>
                    <input id="testimonial_location" name="location" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="trip_name" class="mb-2 block text-sm font-medium text-stone-700">Trip name</label>
                    <input id="trip_name" name="trip_name" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="testimonial_profile_photo" class="mb-2 block text-sm font-medium text-stone-700">Profile picture</label>
                    <input id="testimonial_profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-stone-700">
                </div>
            </div>
            <div>
                <label for="quote" class="mb-2 block text-sm font-medium text-stone-700">Quote</label>
                <textarea id="quote" name="quote" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800"></textarea>
            </div>
            <div class="grid gap-4 lg:grid-cols-[220px_auto] lg:items-end">
                <div>
                    <label for="rating" class="mb-2 block text-sm font-medium text-stone-700">Rating</label>
                    <select id="rating" name="rating" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                        @foreach ([5, 4, 3, 2, 1] as $rating)
                            <option value="{{ $rating }}">{{ $rating }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-stone-600 lg:pb-3">
                    <input type="checkbox" name="is_featured" value="1" checked class="rounded border-stone-300">
                    Feature on homepage
                </label>
            </div>
            <button type="submit" class="w-full rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-amber-700">Save Testimonial</button>
        </form>
    </section>
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-stone-900">Current testimonials</h2>
        <div class="mt-6 space-y-4">
            @forelse ($testimonials as $testimonial)
                <article class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $testimonial->profile_photo_url }}" alt="{{ $testimonial->name }}" class="h-14 w-14 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-white" style="aspect-ratio: 1 / 1; border-radius: 9999px;">
                            <div>
                                <h3 class="text-lg font-semibold text-stone-900">{{ $testimonial->name }}</h3>
                                <p class="text-sm text-stone-500">{{ $testimonial->location }} &middot; {{ $testimonial->trip_name }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $testimonial->is_featured ? 'text-emerald-700' : 'text-stone-500' }}">{{ $testimonial->is_featured ? 'Featured' : 'Stored' }}</span>
                            <details class="group">
                                <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                    <span class="group-open:hidden">Edit</span>
                                    <span class="hidden group-open:inline">Close</span>
                                </summary>
                                <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="mt-4 space-y-3 rounded-2xl border border-stone-200 bg-white p-4">
                                    @csrf
                                    @method('PATCH')
                                    <div class="grid gap-3 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Customer name</label>
                                            <input name="name" type="text" value="{{ $testimonial->name }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Location</label>
                                            <input name="location" type="text" value="{{ $testimonial->location }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                    </div>
                                    <div class="grid gap-3 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Trip name</label>
                                            <input name="trip_name" type="text" value="{{ $testimonial->trip_name }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Rating</label>
                                            <select name="rating" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                @foreach ([5, 4, 3, 2, 1] as $rating)
                                                    <option value="{{ $rating }}" @selected($testimonial->rating === $rating)>{{ $rating }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Quote</label>
                                        <textarea name="quote" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $testimonial->quote }}</textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Profile picture</label>
                                        <input name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                                    </div>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_featured" value="1" @checked($testimonial->is_featured) class="rounded border-stone-300">
                                        Feature on homepage
                                    </label>
                                    <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">Update Testimonial</button>
                                </form>
                            </details>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Delete this testimonial?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete</button>
                            </form>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-7 text-stone-600">{{ $testimonial->quote }}</p>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">No testimonials saved yet.</div>
            @endforelse
        </div>
    </section>
</section>
