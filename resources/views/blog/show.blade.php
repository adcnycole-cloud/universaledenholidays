<x-layouts.app :title="$blogPost->title.' | Universal Eden Holidays'">
    <main class="mx-auto min-h-[calc(100vh-var(--app-header-offset))] max-w-[1680px] px-6 py-10 lg:px-8">
        <style>
            .blog-watch-layout {
                display: block;
            }

            .blog-fixed-panel {
                width: min(100%, 1300px);
                margin-left: auto;
                margin-right: auto;
            }

            .blog-copy-text {
                overflow-wrap: anywhere;
                word-break: break-word;
            }

            .blog-media-frame {
                width: min(100%, 1300px);
                height: 700px;
                background: #000;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .blog-media-frame iframe {
                width: 100%;
                height: 100%;
                display: block;
            }

            .blog-media-frame video,
            .blog-media-frame img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .blog-media-frame video {
                background: #000;
            }

            .blog-media-frame img {
                width: 100%;
                height: 100%;
                background: #000;
            }

            @media (max-width: 900px) {
                .blog-media-frame {
                    height: min(700px, 70vw);
                }
            }

            #blog-media-player:fullscreen,
            #blog-media-player:-webkit-full-screen {
                width: 100vw;
                height: 100vh;
                max-height: none !important;
                border: 0 !important;
                border-radius: 0 !important;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #000;
            }

            #blog-media-player:fullscreen .blog-media-fill,
            #blog-media-player:-webkit-full-screen .blog-media-fill {
                width: 100vw !important;
                height: 100vh !important;
                max-width: none !important;
                max-height: none !important;
            }

            #blog-media-player:fullscreen iframe,
            #blog-media-player:-webkit-full-screen iframe,
            #blog-media-player:fullscreen video,
            #blog-media-player:-webkit-full-screen video,
            #blog-media-player:fullscreen img,
            #blog-media-player:-webkit-full-screen img {
                width: 100vw !important;
                height: 100vh !important;
                max-width: none !important;
                max-height: none !important;
            }

            #blog-media-player:fullscreen img,
            #blog-media-player:-webkit-full-screen img {
                object-fit: contain;
            }

            @media (min-width: 1100px) {
                .blog-watch-layout {
                    display: grid;
                    grid-template-columns: minmax(0, 1300px) 320px;
                    gap: 24px;
                    justify-content: center;
                    align-items: start;
                }
            }
        </style>

        <div class="blog-watch-layout">
            <section style="min-width: 0;">
                <div id="blog-media-player" class="blog-fixed-panel" style="position: relative; overflow: hidden; border: 1px solid #e7e5e4; border-radius: 20px; background: #000; box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18); max-height: 700px;">
                    <button
                        type="button"
                        onclick="(function(){const el=document.getElementById('blog-media-player'); if(!el) return; if(document.fullscreenElement){document.exitFullscreen?.();} else {el.requestFullscreen?.();}})()"
                        aria-label="Toggle fullscreen"
                        title="Toggle fullscreen"
                        style="position: absolute; right: 12px; top: 12px; z-index: 20; display: inline-flex; height: 44px; width: 44px; align-items: center; justify-content: center; border: 0; border-radius: 999px; background: rgba(0,0,0,0.72); padding: 0; color: #fff; cursor: pointer;"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true" style="height: 20px; width: 20px; fill: currentColor;">
                            <path d="M4 9V4h5v2H6v3H4Zm14 0V6h-3V4h5v5h-2ZM4 20v-5h2v3h3v2H4Zm14-2v-3h2v5h-5v-2h3Z"/>
                        </svg>
                    </button>
                    @if ($blogPost->hasEmbeddableVideo())
                        <div class="blog-media-fill blog-media-frame">
                            <iframe
                                src="{{ $blogPost->video_embed_url }}"
                                title="{{ $blogPost->title }} video"
                                style="border: 0;"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen
                            ></iframe>
                        </div>
                    @elseif ($blogPost->hasDirectVideoFile())
                        <video class="blog-media-fill blog-media-frame" controls preload="metadata">
                            <source src="{{ $blogPost->video_url }}">
                            Your browser does not support the video tag.
                        </video>
                    @elseif ($blogPost->cover_image_url)
                        <div class="blog-media-fill blog-media-frame">
                            <img src="{{ $blogPost->cover_image_url }}" alt="{{ $blogPost->title }}">
                        </div>
                    @else
                        <div class="blog-media-fill blog-media-frame" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(145deg, #1f2937 0%, #111827 100%); padding: 2rem; text-align: center;">
                            <span style="font-family: 'Prata', serif; font-size: 2rem; line-height: 1.25; color: #fff;">{{ $blogPost->title }}</span>
                        </div>
                    @endif
                </div>

                <div class="blog-fixed-panel" style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; gap: 1rem; border-radius: 10px; background: #171717; padding: 0.75rem 1rem; color: #fff;">
                    <h1 style="margin: 0; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.95rem; font-weight: 700; text-transform: uppercase;">
                        {{ $blogPost->title }}
                    </h1>
                    <p style="margin: 0; flex-shrink: 0; font-size: 0.85rem; font-weight: 700; color: rgba(255, 255, 255, 0.88);">
                        {{ $blogPost->published_at?->diffForHumans() ?? 'Latest post' }}
                    </p>
                </div>

                <div class="blog-fixed-panel" style="margin-top: 18px; border: 1px solid #e7e5e4; border-radius: 20px; background: #fff; padding: 1.4rem 1.5rem; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);">
                    <div>
                        @if ($blogPost->description)
                            <p class="blog-copy-text" style="margin: 1.2rem 0 0; font-size: 1rem; line-height: 1.9rem; color: #57534e;">
                                {{ $blogPost->description }}
                            </p>
                        @endif

                        <div style="margin-top: 1.6rem;">
                            <p style="margin: 0; font-size: 1.9rem; font-weight: 700; color: #111827;">Follow our socials:</p>
                            <div style="margin-top: 1.2rem; display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(0, 1fr));">
                                <a href="https://www.facebook.com/universal.edenholidays" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; gap: 0.75rem; border: 1px solid #e7e5e4; border-radius: 16px; background: #fafaf9; padding: 0.8rem 1rem; text-decoration: none;">
                                    <span style="display: inline-flex; height: 42px; width: 42px; align-items: center; justify-content: center; border-radius: 999px; background: #1877f2; color: #fff; font-size: 1.35rem; font-weight: 700;">f</span>
                                    <span style="min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.92rem; font-weight: 700; color: #1f2937;">Universal Eden Holidays</span>
                                </a>

                                <a href="{{ $blogPost->social_media_url ?: 'https://www.instagram.com/ue.holidays/' }}" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; gap: 0.75rem; border: 1px solid #e7e5e4; border-radius: 16px; background: #fafaf9; padding: 0.8rem 1rem; text-decoration: none;">
                                    <span style="display: inline-flex; height: 42px; width: 42px; align-items: center; justify-content: center; border-radius: 999px; background: linear-gradient(135deg, #f58529, #dd2a7b, #8134af, #515bd4); color: #fff;">
                                        <svg viewBox="0 0 24 24" aria-hidden="true" style="height: 20px; width: 20px; fill: currentColor;">
                                            <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm5.25-2a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z"/>
                                        </svg>
                                    </span>
                                    <span style="min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.92rem; font-weight: 700; color: #1f2937;">@ue.holidays</span>
                                </a>

                                <a href="{{ $blogPost->video_url ?: 'https://www.tiktok.com/@ue.holidays' }}" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; gap: 0.75rem; border: 1px solid #e7e5e4; border-radius: 16px; background: #fafaf9; padding: 0.8rem 1rem; text-decoration: none;">
                                    <span style="display: inline-flex; height: 42px; width: 42px; align-items: center; justify-content: center; border-radius: 999px; background: #000; color: #fff;">
                                        <svg viewBox="0 0 24 24" aria-hidden="true" style="height: 20px; width: 20px; fill: currentColor;">
                                            <path d="M19.59 6.69A4.83 4.83 0 0 1 16 5.13V15.5a6.5 6.5 0 1 1-6.5-6.5c.18 0 .36.01.54.03v3.2a3.3 3.3 0 0 0-.54-.05 3.32 3.32 0 1 0 3.32 3.32V2h3.06a4.84 4.84 0 0 0 4.71 4.09v3.1c-.34 0-.67-.03-1-.1Z"/>
                                        </svg>
                                    </span>
                                    <span style="min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.92rem; font-weight: 700; color: #1f2937;">@ue.holidays</span>
                                </a>
                            </div>
                        </div>

                        @if ($blogPost->credits)
                            <p style="margin: 1.6rem 0 0; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #78716c;">
                                Credits
                            </p>
                            <p class="blog-copy-text" style="margin: 0.45rem 0 0; font-size: 0.98rem; line-height: 1.8rem; color: #57534e;">
                                {{ $blogPost->credits }}
                            </p>
                        @endif
                    </div>
                </div>
            </section>

            @if ($latestBlogPosts->isNotEmpty())
                <aside style="border-radius: 20px; background: #111111; padding: 0.75rem; box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);">
                    @foreach ($latestBlogPosts as $post)
                        <a href="{{ route('blog.show', $post) }}" style="display: flex; gap: 0.75rem; border-radius: 14px; padding: 0.5rem; text-decoration: none; color: #fff;">
                            <div style="position: relative; height: 88px; width: 148px; flex-shrink: 0; overflow: hidden; border-radius: 10px; background: #292524;">
                                @if ($post->cover_image_url)
                                    <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" style="display: block; height: 100%; width: 100%; object-fit: cover;">
                                @else
                                    <div style="display: flex; height: 100%; width: 100%; align-items: center; justify-content: center; background: linear-gradient(145deg, #374151 0%, #111827 100%); padding: 0.6rem; text-align: center;">
                                        <span style="font-size: 0.72rem; font-weight: 700; line-height: 1.1rem; color: #fff;">{{ $post->title }}</span>
                                    </div>
                                @endif
                                <span style="position: absolute; right: 0.25rem; bottom: 0.25rem; border-radius: 4px; background: rgba(0, 0, 0, 0.82); padding: 0.15rem 0.35rem; font-size: 0.62rem; font-weight: 700; color: #fff;">
                                    {{ $post->published_at?->format('H:i') ?? 'Blog' }}
                                </span>
                            </div>
                            <div style="min-width: 0;">
                                <h2 style="margin: 0; display: -webkit-box; overflow: hidden; -webkit-line-clamp: 2; -webkit-box-orient: vertical; font-size: 0.82rem; font-weight: 700; line-height: 1.15rem; color: #fff;">
                                    {{ $post->title }}
                                </h2>
                                <p style="margin: 0.35rem 0 0; font-size: 0.72rem; color: #d6d3d1;">Universal Eden Holidays</p>
                                <p style="margin: 0.25rem 0 0; font-size: 0.7rem; color: #a8a29e;">
                                    {{ $post->published_at?->format('d M Y') ?? 'Latest post' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </aside>
            @endif
        </div>
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
