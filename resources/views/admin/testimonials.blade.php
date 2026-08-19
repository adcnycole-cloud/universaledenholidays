<x-layouts.app title="Admin Testimonials | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="mt-5 space-y-8">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Testimonials</p>
                        <h1 class="mt-2 text-2xl font-semibold text-stone-900">Testimonials management</h1>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">
                            Choose which testimonial content you want to manage first, then continue on the dedicated page for that section.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                            {{ ($testimonials ?? collect())->count() }} review{{ ($testimonials ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                        <div class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                            {{ ($customerGalleryItems ?? collect())->count() }} gallery item{{ ($customerGalleryItems ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 md:grid-cols-2">
                <a href="{{ route('admin.testimonials.reviews') }}" class="group rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-sky-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-stone-900">Customer reviews management</h2>
                            <p class="mt-3 max-w-xl text-sm leading-7 text-stone-600">
                                Create, edit, approve, and place customer reviews shown on the public site.
                            </p>
                        </div>
                        <div class="rounded-full bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700">
                            {{ ($testimonials ?? collect())->count() }} review{{ ($testimonials ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition group-hover:bg-sky-100">
                        Open customer reviews page
                        <span aria-hidden="true">&rarr;</span>
                    </div>
                </a>

                <a href="{{ route('admin.testimonials.gallery') }}" class="group rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-amber-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-stone-900">Customer gallery management</h2>
                            <p class="mt-3 max-w-xl text-sm leading-7 text-stone-600">
                                Manage standalone customer gallery cards with their own images, titles, and descriptions.
                            </p>
                        </div>
                        <div class="rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                            {{ ($customerGalleryItems ?? collect())->count() }} item{{ ($customerGalleryItems ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition group-hover:bg-amber-100">
                        Open customer gallery page
                        <span aria-hidden="true">&rarr;</span>
                    </div>
                </a>
            </section>
        </section>
    </main>
</x-layouts.app>
