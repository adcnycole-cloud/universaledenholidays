<x-layouts.app title="Admin Dashboard | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        @include('admin.partials.overview-cards', ['stats' => $stats])

        <section class="mt-8 rounded-2xl border border-stone-200 bg-white p-6 shadow-sm lg:p-8">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Dashboard</p>
                <h1 class="mt-2 text-4xl font-bold text-stone-900">Welcome to Admin</h1>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-stone-600">Select a section from the sidebar to manage that part of your site. Each area has been optimized for quick access and efficient updates.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <a href="{{ route('admin.profile') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-sky-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-700 group-hover:bg-sky-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Admin Profile</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Review admin details and core shortcuts.</p>
                </a>

                <a href="{{ route('admin.promos') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-rose-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 text-rose-700 group-hover:bg-rose-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M7 12h10M12 7v10"></path>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Promotion Manager</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Upload posters, edit offers, and manage live promos.</p>
                </a>

                <a href="{{ route('admin.transport') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-blue-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700 group-hover:bg-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="18" r="4"></circle>
                            <circle cx="6" cy="18" r="4"></circle>
                            <path d="M6 12H4m16 0h2M9 9h6V4H9z"></path>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Fleet Listings</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Maintain fixed transport entries across the site.</p>
                </a>

                <a href="{{ route('admin.packages') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-amber-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-700 group-hover:bg-amber-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Package Listings</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Create and update travel package entries.</p>
                </a>

                <a href="{{ route('admin.testimonials') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-purple-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-700 group-hover:bg-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Customer Reviews</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Store and feature guest testimonials.</p>
                </a>

                <a href="{{ route('admin.bookings') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-cyan-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-100 text-cyan-700 group-hover:bg-cyan-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Booking Queue</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Review requests and update their status.</p>
                </a>

                <a href="{{ route('admin.enquiries') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-green-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700 group-hover:bg-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">Enquiry Inbox</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">See customers who asked questions.</p>
                </a>

                <a href="{{ route('home') }}" class="group rounded-xl border border-stone-200 bg-gradient-to-br from-stone-50 to-white p-5 transition hover:border-indigo-200 hover:shadow-md">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 group-hover:bg-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                    </div>
                    <p class="mt-4 font-semibold text-stone-900">View Homepage</p>
                    <p class="mt-2 text-xs leading-5 text-stone-600">Open the public-facing site in its current state.</p>
                </a>
            </div>
        </section>
    </main>
</x-layouts.app>
