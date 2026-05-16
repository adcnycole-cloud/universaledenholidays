<x-layouts.app title="My Profile | Universal Eden Holidays">
    <main class="mx-auto max-w-7xl px-6 py-10 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Profile Interface</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">{{ $user->name }}</h1>
                <p class="mt-2 text-sm text-stone-500">{{ $user->email }}</p>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-stone-700">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-medium text-stone-700">Phone</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="preferred_currency" class="mb-2 block text-sm font-medium text-stone-700">Preferred currency</label>
                        <select id="preferred_currency" name="preferred_currency" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                            @foreach ($currencies as $code => $label)
                                <option value="{{ $code }}" @selected(old('preferred_currency', $user->preferred_currency) === $code)>{{ $code }} - {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">Save Profile</button>
                </form>
            </section>

            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">My Bookings</p>
                <h2 class="mt-2 text-3xl font-semibold text-stone-900">Customer booking history</h2>
                <div class="mt-6 flex flex-col gap-4">
                    <div class="rounded-3xl border border-stone-200 bg-stone-50 p-6">
                        <p class="text-sm text-stone-600">
                            You have <span class="font-semibold text-stone-900">{{ $user->bookings->count() }}</span> {{ $user->bookings->count() === 1 ? 'booking' : 'bookings' }} in your history.
                        </p>
                        <a href="{{ route('profile.bookings') }}" class="mt-4 inline-block rounded-full bg-sky-600 px-6 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">
                            View All Bookings
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
