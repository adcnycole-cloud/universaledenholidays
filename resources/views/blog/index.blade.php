<x-layouts.app title="Blog | Universal Eden Holidays">
    <main class="mx-auto min-h-[calc(100vh-var(--app-header-offset))] max-w-[1500px] px-6 py-10 lg:px-8">
        <section class="rounded-[2rem] border border-stone-200 bg-white px-5 py-6 shadow-sm md:px-8 md:py-8">
            <div class="text-center">
                <p class="font-['Oswald'] text-sm font-semibold uppercase tracking-[0.28em] text-[#315fbd]">Travel Channel</p>
                <h1 class="mt-2 font-['Oswald'] text-4xl font-bold uppercase tracking-[0.22em] text-stone-900 md:text-5xl lg:text-6xl">
                    Latest Blog
                </h1>
                <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-stone-500 md:text-base">
                    Browse travel updates, trip highlights, and fresh stories from Universal Eden Holidays.
                </p>
            </div>

            <div class="mt-8">
                @if ($blogPosts->isNotEmpty())
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($blogPosts as $post)
                            <a href="{{ route('blog.show', $post) }}" class="group overflow-hidden rounded-[1.6rem] border border-stone-200 bg-white shadow-[0_10px_22px_rgba(15,23,42,0.08)] transition hover:-translate-y-1 hover:shadow-[0_18px_32px_rgba(15,23,42,0.14)]">
                                <div class="relative aspect-video overflow-hidden bg-stone-200">
                                    @if ($post->cover_image_url)
                                        <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.04]">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] px-8 text-center">
                                            <span class="font-['Prata'] text-2xl leading-tight text-white">{{ $post->title }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-3 right-3 rounded-md bg-stone-950/85 px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-white">
                                        Blog
                                    </div>
                                </div>
                                <div class="px-5 py-4">
                                    <div class="min-w-0">
                                        <h2 class="line-clamp-2 text-base font-semibold leading-6 text-stone-900 md:text-lg">{{ $post->title }}</h2>
                                        <p class="mt-2 text-sm font-medium text-stone-500">Universal Eden Holidays</p>
                                        <p class="mt-1 text-sm text-stone-400">{{ $post->published_at?->format('d M Y') ?? 'Latest post' }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-[1.6rem] border border-dashed border-stone-300 bg-white px-6 py-10 text-center text-sm leading-7 text-stone-600">
                        No travel updates are available yet.
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="mt-auto border-t border-stone-200/80 bg-stone-950 text-stone-200">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 lg:grid-cols-[1.2fr_0.8fr_0.8fr_1fr] lg:px-10">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10">
                    <div>
                        <p class="font-['Prata'] text-xl text-white">Universal Eden Holidays</p>
                    </div>
                </div>
                <p class="mt-5 max-w-md text-sm leading-7 text-stone-400">
                    Travel planning for Sabah made easier with transport services, holiday packages, and practical booking support in one place.
                </p>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Explore</p>
                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-400">
                    <a href="{{ route('home') }}#promos" class="transition hover:text-white">Promos</a>
                    <a href="{{ route('home') }}#transport" class="transition hover:text-white">Transport</a>
                    <a href="{{ route('home') }}#packages-showcase" class="transition hover:text-white">Packages</a>
                    <a href="{{ route('home') }}#testimonials" class="transition hover:text-white">Testimonials</a>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Company</p>
                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-400">
                    <a href="{{ route('home') }}#about-us" class="transition hover:text-white">About Us</a>
                    <a href="{{ route('home') }}#popular-picks" class="transition hover:text-white">Popular Picks</a>
                    <a href="{{ route('bookings.track.form') }}" class="transition hover:text-white">Track Your Bookings</a>
                    @auth
                        <a href="{{ route('profile.show') }}" class="transition hover:text-white">My Profile</a>
                    @else
                        <a href="{{ route('login') }}" class="transition hover:text-white">Login</a>
                    @endauth
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Contact</p>
                <div class="mt-5 space-y-4 text-sm text-stone-400">
                    <p>Email: <a href="mailto:info@universaledenholiday.com" class="transition hover:text-white">info@universaledenholiday.com</a></p>
                    <p>Phone: <a href="tel:+6088212345" class="transition hover:text-white">+60 88 212 345</a></p>
                    <p>Kota Kinabalu, Sabah, Malaysia</p>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl items-center justify-center px-6 py-5 text-center text-xs uppercase tracking-[0.22em] text-stone-500 lg:px-10">
                <p>Adcey &copy; Universal Eden Holidays - {{ now()->year }}</p>
            </div>
        </div>
    </footer>
</x-layouts.app>
