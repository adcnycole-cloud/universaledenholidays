@props(['currentRoute' => null])

<aside class="fixed inset-y-0 left-0 z-40 hidden border-r border-stone-200 bg-white md:block" style="width: var(--admin-sidebar-width);">
    <div class="flex h-full flex-col">
        <div class="shrink-0 border-b border-stone-200 px-4 py-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl bg-stone-50 px-3 py-3 transition hover:bg-stone-100">
                <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-11 w-11 rounded-2xl object-cover shadow-sm">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-stone-900">Eden Holidays</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
        <a href="{{ route('admin.dashboard') }}"
            class="mt-4 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Overview</span>
        </a>

        <a href="{{ route('admin.profile') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.profile') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Profile</span>
        </a>

        <a href="{{ route('admin.promos') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.promos') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M7 12h10M12 7v10"></path>
            </svg>
            <span>Promos</span>
        </a>

        <a href="{{ route('admin.transport') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.transport') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="18" cy="18" r="4"></circle>
                <circle cx="6" cy="18" r="4"></circle>
                <path d="M6 12H4m16 0h2M9 9h6V4H9z"></path>
            </svg>
            <span>Transport</span>
        </a>

        <a href="{{ route('admin.packages') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.packages') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span>Packages</span>
        </a>

        <a href="{{ route('admin.testimonials') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.testimonials') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Testimonials</span>
        </a>

        <a href="{{ route('admin.bookings') }}"
            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.bookings', 'admin.bookings.export') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Bookings</span>
        </a>

            <a href="{{ route('admin.enquiries') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.enquiries') ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-stone-700 hover:border-stone-200 hover:bg-stone-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Enquiries</span>
            </a>
        </nav>

        <div class="mt-auto shrink-0 border-t border-stone-200 bg-white px-4 py-4">
            <a href="{{ route('home') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-stone-700 transition hover:border-stone-200 hover:bg-stone-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                <span>View Site</span>
            </a>

            @auth
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2.5 text-sm font-medium text-rose-700 transition hover:border-rose-300 hover:bg-rose-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</aside>
