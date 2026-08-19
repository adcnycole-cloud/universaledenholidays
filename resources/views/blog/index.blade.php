<x-layouts.app title="Blog | Universal Eden Holidays">
    <style>
        .page-banner {
            position: relative;
            overflow: hidden;
            margin-top: -3.1rem;
        }

        .page-banner-wave {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            min-width: 100vw;
            max-width: none;
            height: auto;
            display: block;
            filter: brightness(0) invert(1);
            pointer-events: none;
            z-index: 1;
        }

        .page-banner-image {
            position: relative;
            z-index: 0;
        }

        .page-banner-overlay {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            width: 100%;
        }

        @media (max-width: 767px) {
            .page-banner {
                margin-top: 0;
            }
        }
    </style>

    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))]" style="background: linear-gradient(180deg, #f5efe3 0%, #e7edf8 100%);">
        <section class="page-banner w-full bg-white">
            <img
                src="{{ asset('images/travel_blog.png') }}"
                alt="Travel Blog"
                class="page-banner-image block w-full h-auto"
            >
            <img
                src="{{ asset('images/wave.png') }}"
                alt=""
                class="page-banner-wave"
                aria-hidden="true"
            >
        </section>

        <section class="w-full bg-white">
            <div class="mx-auto max-w-[1500px] px-5 py-6 md:px-8 md:py-8">
                    <div class="text-center">
                        <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Travel Channel</span>
                        <h1 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">
                            Travel Channel
                        </h1>
                        <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-stone-600 md:text-base">
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
            </div>
        </section>
    </main>

    @include('partials.footer')
</x-layouts.app>
