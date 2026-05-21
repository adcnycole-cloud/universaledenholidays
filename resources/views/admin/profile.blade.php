<x-layouts.app title="Admin Profile | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm lg:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Admin Profile</p>
            <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_1fr]">
                <div class="rounded-xl bg-gradient-to-br from-emerald-50 via-white to-sky-50 p-6 border border-stone-200">
                    <h1 class="text-2xl font-bold text-stone-900">{{ $adminUser->name }}</h1>
                    <p class="mt-2 text-sm text-stone-600">{{ $adminUser->email }}</p>
                    <div class="mt-6 space-y-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Role</p>
                            <p class="mt-2 text-lg font-semibold text-stone-900">Administrator</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Access Level</p>
                            <p class="mt-2 text-sm font-medium text-emerald-700">Full Admin Access</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border border-stone-200 bg-stone-50 p-6">
                    <h2 class="text-xl font-semibold text-stone-900">Quick Actions</h2>
                    <p class="mt-3 text-sm leading-6 text-stone-600">Navigate to key sections of your admin dashboard or view your live site.</p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-xs font-semibold text-stone-700 transition hover:border-stone-400 hover:bg-stone-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                            View Site
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
