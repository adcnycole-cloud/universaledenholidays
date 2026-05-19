<x-layouts.app title="Admin Dashboard | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-10 lg:px-10">
        @include('admin.partials.overview-cards', ['stats' => $stats])

        <section class="mt-8 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Admin Overview</p>
            <h1 class="mt-2 text-3xl font-semibold text-stone-900">Manage the site by section</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-stone-600">Each admin area now lives on its own page so you can move directly to the section you want without scrolling through one long dashboard.</p>

            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('admin.profile') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Profile</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Admin Profile</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Review the signed-in admin details and core shortcuts.</p>
                </a>
                <a href="{{ route('admin.promos') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-rose-600">Promos</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Promotion Manager</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Upload posters, edit offers, and manage live promo visibility.</p>
                </a>
                <a href="{{ route('admin.transport') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Transport</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Fleet Listings</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Maintain the fixed transport entries used across the site.</p>
                </a>
                <a href="{{ route('admin.packages') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-600">Packages</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Package Listings</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Create and update travel package entries.</p>
                </a>
                <a href="{{ route('admin.tours') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Tours</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Tour Listings</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Add and manage standalone tour products.</p>
                </a>
                <a href="{{ route('admin.testimonials') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-600">Testimonials</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Customer Reviews</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Store and feature guest testimonials.</p>
                </a>
                <a href="{{ route('admin.bookings') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700">Bookings</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">Booking Queue</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Review incoming requests and update their status.</p>
                </a>
                <a href="{{ route('home') }}" class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5 transition hover:-translate-y-1 hover:bg-white hover:shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-stone-600">Live Site</p>
                    <p class="mt-3 text-xl font-semibold text-stone-900">View Homepage</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Open the public-facing site in its current state.</p>
                </a>
            </div>
        </section>
    </main>
</x-layouts.app>
