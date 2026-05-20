<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Universal Eden Holidays</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|prata:400" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-stone-950 text-stone-100">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.18),_transparent_30%),linear-gradient(135deg,_#120d08,_#1f2937_55%,_#0a0a0a)]"></div>
            <div class="absolute -left-20 top-24 h-64 w-64 rounded-full bg-amber-400/20 blur-3xl"></div>
            <div class="absolute right-0 top-0 h-72 w-72 rounded-full bg-sky-400/15 blur-3xl"></div>

            <main class="relative mx-auto flex min-h-screen max-w-7xl flex-col gap-12 px-6 py-8 lg:px-10">
                <header class="flex flex-col gap-6 rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-amber-300">Universal Eden Holidays</p>
                        <h1 class="mt-3 font-['Prata'] text-4xl text-white md:text-6xl">Simple holiday booking for your next escape.</h1>
                    </div>
                    <div class="max-w-sm text-sm leading-6 text-stone-300">
                        Book beach weekends, city breaks, and cool-weather retreats in one quick form. Perfect for a small agency demo or starter booking website.
                    </div>
                </header>

                <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="space-y-8">
                        <div class="grid gap-5 md:grid-cols-3">
                            @foreach ($packages as $package)
                                <article class="rounded-[1.75rem] border border-white/10 bg-white/8 p-5 shadow-xl backdrop-blur">
                                    <p class="text-xs uppercase tracking-[0.3em] text-amber-300">{{ $package['location'] }}</p>
                                    <h2 class="mt-3 text-2xl font-semibold text-white">{{ $package['name'] }}</h2>
                                    <p class="mt-3 text-sm leading-6 text-stone-300">{{ $package['description'] }}</p>
                                    <div class="mt-6 flex items-center justify-between">
                                        <span class="text-sm text-stone-400">Starting from</span>
                                        <span class="text-xl font-semibold text-amber-200">{{ $package['price'] }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="rounded-[2rem] border border-white/10 bg-black/25 p-6 shadow-xl">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-sky-300">Recent Requests</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-white">Latest bookings</h2>
                                </div>
                                <span class="rounded-full border border-emerald-400/40 bg-emerald-400/10 px-4 py-2 text-xs uppercase tracking-[0.25em] text-emerald-200">Live</span>
                            </div>

                            <div class="mt-6 space-y-4">
                                @forelse ($bookings as $booking)
                                    <div class="flex flex-col gap-2 rounded-2xl border border-white/8 bg-white/5 p-4 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <p class="text-lg font-semibold text-white">{{ $booking->full_name }}</p>
                                            <p class="text-sm text-stone-300">{{ $booking->package_name }} in {{ $booking->destination }}</p>
                                        </div>
                                        <div class="text-sm text-stone-300">
                                            {{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }} · {{ $booking->guest_count }} guests
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-6 text-sm leading-6 text-stone-300">
                                        No bookings yet. Use the form to create the first reservation request.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <section class="rounded-[2rem] border border-white/10 bg-white/8 p-6 shadow-2xl backdrop-blur">
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Book Now</p>
                        <h2 class="mt-2 text-3xl font-semibold text-white">Reserve your trip</h2>

                        @if (session('success'))
                            <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mt-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 px-4 py-3 text-sm text-rose-100">
                                Please check the form and try again.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('booking.store') }}" class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <label for="full_name" class="mb-2 block text-sm text-stone-200">Full name</label>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" required>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-sm text-stone-200">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" autocomplete="email" inputmode="email" spellcheck="false" required>
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-sm text-stone-200">Phone</label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" autocomplete="tel" inputmode="tel" pattern="^\+[0-9\s\-()]{8,25}$" maxlength="25" placeholder="+60 12-345 6789" required>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="destination" class="mb-2 block text-sm text-stone-200">Destination</label>
                                    <input id="destination" name="destination" type="text" value="{{ old('destination') }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" placeholder="Langkawi" required>
                                </div>
                                <div>
                                    <label for="package_name" class="mb-2 block text-sm text-stone-200">Package</label>
                                    <select id="package_name" name="package_name" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" required>
                                        <option value="">Select a package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package['name'] }}" @selected(old('package_name') === $package['name'])>{{ $package['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label for="guest_count" class="mb-2 block text-sm text-stone-200">Guests</label>
                                    <input id="guest_count" name="guest_count" type="number" min="1" max="20" value="{{ old('guest_count', 2) }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" required>
                                </div>
                                <div>
                                    <label for="check_in_date" class="mb-2 block text-sm text-stone-200">Check in</label>
                                    <input id="check_in_date" name="check_in_date" type="date" value="{{ old('check_in_date') }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" required>
                                </div>
                                <div>
                                    <label for="check_out_date" class="mb-2 block text-sm text-stone-200">Check out</label>
                                    <input id="check_out_date" name="check_out_date" type="date" value="{{ old('check_out_date') }}" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" required>
                                </div>
                            </div>

                            <div>
                                <label for="special_requests" class="mb-2 block text-sm text-stone-200">Special requests</label>
                                <textarea id="special_requests" name="special_requests" rows="4" class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-amber-300" placeholder="Airport transfer, late check-in, room preference...">{{ old('special_requests') }}</textarea>
                            </div>

                            <button type="submit" class="w-full rounded-full bg-amber-300 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-stone-950 transition hover:bg-amber-200">
                                Submit booking
                            </button>
                        </form>
                    </section>
                </section>
            </main>
        </div>
    </body>
</html>
