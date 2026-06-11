{{--
    Shared site footer partial.
    Include this in all public-facing pages:
        @include('partials.footer')
--}}

<footer class="mt-auto border-t border-white/10 bg-stone-950 text-stone-200">

    {{-- Main Footer --}}
    <div class="mx-auto max-w-7xl px-8 py-10 lg:px-12">
        <div class="flex flex-col gap-10 lg:flex-row lg:items-start lg:justify-between">

            {{-- Brand Column --}}
            <div class="max-w-sm">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/ue_logo.jpg') }}"
                        alt="Universal Eden Holidays Logo"
                        class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10"
                    >
                    <div>
                        <h3 class="font-['Prata'] text-xl text-white">
                            Universal Eden Holidays
                        </h3>
                    </div>
                </div>

                <p class="mt-5 text-sm leading-6 text-stone-400">
                    Travel planning for Sabah made easier with transport
                    services, holiday packages, and practical booking
                    support in one place.
                </p>
            </div>

            {{-- Right Side Columns --}}
            <div class="flex flex-col gap-10 sm:flex-row sm:gap-8">

                {{-- Explore --}}
                <div class="flex-1">
                    <h4 class="text-xs font-semibold uppercase tracking-[0.22em] text-white">
                        Explore
                    </h4>
                    <div class="mt-4 flex flex-col gap-3 text-sm text-stone-400">
                        <a href="{{ route('home') }}#promos" class="hover:text-white transition">Promos</a>
                        <a href="{{ route('home') }}#transport" class="hover:text-white transition">Transport</a>
                        <a href="{{ route('home') }}#packages-showcase" class="hover:text-white transition">Packages</a>
                        <a href="{{ route('home') }}#testimonials" class="hover:text-white transition">Testimonials</a>
                    </div>
                </div>

                {{-- Company --}}
                <div class="flex-1">
                    <h4 class="text-xs font-semibold uppercase tracking-[0.22em] text-white">
                        Company
                    </h4>
                    <div class="mt-4 flex flex-col gap-3 text-sm text-stone-400">
                        <a href="{{ route('home') }}#about-us" class="hover:text-white transition">About Us</a>
                        <a href="{{ route('home') }}#popular-picks" class="hover:text-white transition">Popular Picks</a>
                        <a href="{{ route('bookings.track.form') }}" class="hover:text-white transition">Track Your Bookings</a>
                        @auth
                            <a href="{{ route('profile.show') }}" class="hover:text-white transition">Profile</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-white transition">Login</a>
                        @endauth
                    </div>
                </div>

                {{-- Contact --}}
                <div class="flex-1">
                    <h4 class="text-xs font-semibold uppercase tracking-[0.22em] text-white">
                        Contact
                    </h4>
                    <div class="mt-4 flex flex-col gap-3 text-sm text-stone-400">
                        <a href="mailto:info@universaledenholiday.com" class="hover:text-white transition">Email: uniedenholidays@gmail.com</a>
                        <a href="tel:+60168122921" class="hover:text-white transition">Phone: +6016 812 2921</a>
                        <span>Kota Kinabalu, Sabah, Malaysia</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-8 py-5 lg:px-12">
            <p class="text-center text-xs uppercase tracking-[0.35em] text-stone-500">
                ADCEY &copy; UNIVERSAL EDEN HOLIDAYS - {{ now()->year }}
            </p>
        </div>
    </div>

</footer>