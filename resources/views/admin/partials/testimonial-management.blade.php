<section class="mt-5 space-y-8">
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
            <div class="grid gap-4 lg:grid-cols-3 lg:items-end">
                <div>
                    <label for="rating" class="mb-2 block text-sm font-medium text-stone-700">Rating</label>
                    <select id="rating" name="rating" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                        @foreach ([5, 4, 3, 2, 1] as $rating)
                            <option value="{{ $rating }}">{{ $rating }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="testimonial_display_location" class="mb-2 block text-sm font-medium text-stone-700">Show review at</label>
                    <select id="testimonial_display_location" name="display_location" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" data-testimonial-location-select>
                        <option value="landing">Landing page</option>
                        <option value="package">Specific product package</option>
                    </select>
                </div>
                <div data-testimonial-package-field class="hidden">
                    <label for="testimonial_product_id" class="mb-2 block text-sm font-medium text-stone-700">Package</label>
                    <select id="testimonial_product_id" name="product_id" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                        <option value="">Select a package</option>
                        @foreach (($packageProducts ?? collect()) as $packageProduct)
                            <option value="{{ $packageProduct->id }}">{{ $packageProduct->name }}</option>
                        @endforeach
                    </select>
                </div>
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
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] {{ $testimonial->display_location === 'landing' ? 'text-emerald-700' : 'text-sky-700' }}">
                                {{ $testimonial->display_location === 'landing' ? 'Landing page' : 'Package page' }}
                            </span>
                            @if ($testimonial->display_location === 'package' && $testimonial->product)
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-stone-500">
                                    {{ $testimonial->product->name }}
                                </span>
                            @endif
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
                                    <div class="grid gap-3 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Show review at</label>
                                            <select name="display_location" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800" data-testimonial-location-select>
                                                <option value="landing" @selected($testimonial->display_location === 'landing')>Landing page</option>
                                                <option value="package" @selected($testimonial->display_location === 'package')>Specific product package</option>
                                            </select>
                                        </div>
                                        <div data-testimonial-package-field class="{{ $testimonial->display_location === 'package' ? '' : 'hidden' }}">
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Package</label>
                                            <select name="product_id" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                <option value="">Select a package</option>
                                                @foreach (($packageProducts ?? collect()) as $packageProduct)
                                                    <option value="{{ $packageProduct->id }}" @selected($testimonial->product_id === $packageProduct->id)>{{ $packageProduct->name }}</option>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const syncTestimonialPlacement = (scope) => {
            const select = scope.querySelector('[data-testimonial-location-select]');
            const packageField = scope.querySelector('[data-testimonial-package-field]');

            if (!select || !packageField) {
                return;
            }

            const update = () => {
                packageField.classList.toggle('hidden', select.value !== 'package');
            };

            update();
            select.addEventListener('change', update);
        };

        document.querySelectorAll('form').forEach((form) => {
            if (form.querySelector('[data-testimonial-location-select]')) {
                syncTestimonialPlacement(form);
            }
        });
    });
</script>
