<section class="mt-5 space-y-8">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Testimonials</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Customer review moderation</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">
                    Reviews come from customers. Use this page to decide whether each review should appear on the landing page or a specific product package page.
                </p>
            </div>
            <div class="grid gap-3 md:grid-cols-[minmax(0,20rem)_12rem]">
                <label class="relative block">
                    <span class="sr-only">Search reviews</span>
                    <input id="admin-testimonial-search" type="search" placeholder="Search by customer, trip, quote, or package" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white">
                </label>
                <label class="relative block">
                    <span class="sr-only">Filter by rating</span>
                    <select id="admin-testimonial-rating-filter" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white">
                        <option value="">All ratings</option>
                        <option value="5">5 stars</option>
                        <option value="4">4 stars</option>
                        <option value="3">3 stars</option>
                        <option value="2">2 stars</option>
                        <option value="1">1 star</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="mt-5 flex items-center justify-between gap-3">
            <p id="admin-testimonial-results" class="text-sm text-stone-500" aria-live="polite">Showing {{ $testimonials->count() }} customer reviews</p>
        </div>

        <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-stone-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
                    <thead class="bg-stone-100 text-stone-700">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Trip</th>
                            <th class="px-5 py-4 font-semibold">Rating</th>
                            <th class="px-5 py-4 font-semibold">Display Target</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-testimonial-list" class="divide-y divide-stone-200 bg-white">
                        @forelse ($testimonials as $testimonial)
                            <tr
                                data-admin-testimonial-item="true"
                                data-rating="{{ $testimonial->rating }}"
                                data-search="{{ strtolower(trim($testimonial->name.' '.$testimonial->location.' '.$testimonial->trip_name.' '.$testimonial->quote.' '.($testimonial->product?->name ?? '').' '.$testimonial->display_location)) }}"
                            >
                                <td class="px-5 py-4 align-top">
                                    <div class="flex items-start gap-3">
                                        <img src="{{ $testimonial->profile_photo_url }}" alt="{{ $testimonial->name }}" class="h-12 w-12 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-white" style="aspect-ratio: 1 / 1; border-radius: 9999px;">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-stone-900">{{ $testimonial->name }}</div>
                                            @if ($testimonial->email)
                                                <div class="mt-1 text-xs text-stone-500">{{ $testimonial->email }}</div>
                                            @endif
                                            <div class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-500">{{ $testimonial->location }}</div>
                                            <p class="mt-2 max-w-md text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit($testimonial->quote, 120) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="font-semibold text-stone-900">{{ $testimonial->trip_name }}</div>
                                    @if ($testimonial->product)
                                        <div class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-500">{{ $testimonial->product->name }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="font-semibold text-amber-500">{{ str_repeat('*', $testimonial->rating) }}</div>
                                    <div class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-500">{{ $testimonial->rating }} star{{ $testimonial->rating === 1 ? '' : 's' }}</div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $testimonial->display_location === 'landing' ? 'text-emerald-700' : 'text-sky-700' }}">
                                        {{ $testimonial->display_location === 'landing' ? 'Landing page' : 'Package page' }}
                                    </span>
                                    @if ($testimonial->display_location === 'package' && $testimonial->product)
                                        <div class="mt-2 text-sm text-stone-600">{{ $testimonial->product->name }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $testimonial->is_featured ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $testimonial->is_featured ? 'Showing' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-wrap gap-2">
                                        <details class="group min-w-[16rem]">
                                            <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                                <span class="group-open:hidden">Review Settings</span>
                                                <span class="hidden group-open:inline">Close</span>
                                            </summary>
                                            <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="mt-4 space-y-3 rounded-2xl border border-stone-200 bg-stone-50 p-4" data-form-persist="admin-testimonials-update-{{ $testimonial->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="grid gap-3 md:grid-cols-2">
                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Customer name</label>
                                                        <input name="name" type="text" value="{{ $testimonial->name }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Email</label>
                                                        <input name="email" type="email" value="{{ $testimonial->email }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                    </div>
                                                </div>
                                                <div class="grid gap-3 md:grid-cols-2">
                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Location</label>
                                                        <input name="location" type="text" value="{{ $testimonial->location }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                    </div>
                                                    <div>
                                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Profile picture</label>
                                                        <input name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
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
                                                <label class="flex items-center gap-2 text-sm text-stone-700">
                                                    <input type="checkbox" name="is_featured" value="1" @checked($testimonial->is_featured) class="rounded border-stone-300">
                                                    Show this review on the public site
                                                </label>
                                                <div>
                                                    <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Quote</label>
                                                    <textarea name="quote" rows="4" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $testimonial->quote }}</textarea>
                                                </div>
                                                <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">Save Review Settings</button>
                                            </form>
                                        </details>
                                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-sm text-stone-600">No customer reviews saved yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

        const searchInput = document.getElementById('admin-testimonial-search');
        const ratingFilter = document.getElementById('admin-testimonial-rating-filter');
        const rows = Array.from(document.querySelectorAll('[data-admin-testimonial-item]'));
        const resultsLabel = document.getElementById('admin-testimonial-results');

        if (!searchInput || !ratingFilter || !rows.length || !resultsLabel) {
            return;
        }

        const updateTable = () => {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const selectedRating = ratingFilter.value;
            let visibleCount = 0;

            rows.forEach((row) => {
                const searchHaystack = row.dataset.search || '';
                const rowRating = row.dataset.rating || '';
                const matchesSearch = searchTerm === '' || searchHaystack.includes(searchTerm);
                const matchesRating = selectedRating === '' || rowRating === selectedRating;
                const shouldShow = matchesSearch && matchesRating;

                row.classList.toggle('hidden', !shouldShow);

                if (shouldShow) {
                    visibleCount += 1;
                }
            });

            resultsLabel.textContent = `Showing ${visibleCount} customer review${visibleCount === 1 ? '' : 's'}`;
        };

        searchInput.addEventListener('input', updateTable);
        ratingFilter.addEventListener('change', updateTable);
        updateTable();
    });
</script>
