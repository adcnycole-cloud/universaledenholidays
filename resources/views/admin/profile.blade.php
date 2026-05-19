<x-layouts.app title="Admin Profile | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-6 lg:px-10">
        <section class="mt-5 rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Admin Profile</p>
            <div class="mt-4 grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
                <div class="rounded-[1.75rem] bg-[linear-gradient(135deg,_#e0f2fe,_#f8fafc_55%,_#ecfccb)] p-6">
                    <h1 class="text-3xl font-semibold text-stone-900">{{ $adminUser->name }}</h1>
                    <p class="mt-2 text-sm text-stone-600">{{ $adminUser->email }}</p>
                    <p class="mt-6 text-sm font-semibold uppercase tracking-[0.22em] text-stone-500">Role</p>
                    <p class="mt-2 text-lg font-semibold text-stone-900">Administrator</p>
                </div>
                <div class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-6">
                    <h2 class="text-xl font-semibold text-stone-900">Profile actions</h2>
                    <p class="mt-3 text-sm leading-7 text-stone-600">This admin area is separate from the customer side. Use these shortcuts to manage the dashboard and review live site content.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('home') }}" class="rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">View Homepage</a>
                        <a href="{{ route('admin.bookings') }}" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-white">View Booking Queue</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
