<x-layouts.app :title="$blogPost->title.' | Universal Eden Holidays'">
    <main class="mx-auto min-h-[calc(100vh-var(--app-header-offset))] max-w-[1360px] px-6 py-10 lg:px-8">
        <style>
            .blog-watch-layout {
                display: block;
            }

            .blog-fixed-panel {
                width: min(100%, 980px);
                margin-left: auto;
                margin-right: auto;
            }

            .blog-copy-text {
                overflow-wrap: anywhere;
                word-break: break-word;
            }

            .blog-prose {
                color: #44403c;
                font-size: 1rem;
                line-height: 1.95rem;
            }

            .blog-prose > :first-child {
                margin-top: 0;
            }

            .blog-prose > :last-child {
                margin-bottom: 0;
            }

            .blog-prose p,
            .blog-prose ul,
            .blog-prose ol,
            .blog-prose blockquote {
                margin: 0 0 1.35rem;
            }

            .blog-prose h2,
            .blog-prose h3 {
                margin: 2rem 0 1rem;
                color: #111827;
                font-family: 'Prata', serif;
                line-height: 1.2;
            }

            .blog-prose h2 {
                font-size: 2.15rem;
            }

            .blog-prose h3 {
                font-size: 1.6rem;
            }

            .blog-prose ul,
            .blog-prose ol {
                padding-left: 1.4rem;
            }

            .blog-prose li + li {
                margin-top: 0.7rem;
            }

            .blog-prose strong,
            .blog-prose b {
                color: #1f2937;
                font-weight: 700;
            }

            .blog-prose em,
            .blog-prose i {
                font-style: italic;
            }

            .blog-section-image {
                display: block;
                width: 100%;
                border-radius: 18px;
                border: 1px solid #e7e5e4;
                background: #000;
                object-fit: contain;
            }

            .blog-media-frame {
                width: min(100%, 980px);
                height: 560px;
                background: #fff;
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
            }

            .blog-media-frame video {
                background: #fff;
                object-fit: contain;
            }

            .blog-media-frame img {
                width: 100%;
                height: 100%;
                background: #fff;
                object-fit: cover;
            }

            @media (max-width: 900px) {
                .blog-media-frame {
                    height: min(700px, 70vw);
                }
            }
            @media (min-width: 1100px) {
                .blog-watch-layout {
                    display: grid;
                    grid-template-columns: minmax(0, 980px) 300px;
                    gap: 24px;
                    justify-content: center;
                    align-items: start;
                }
            }
        </style>

        <div class="blog-watch-layout">
            <section style="min-width: 0;">
                <div class="blog-fixed-panel" style="margin-top: 18px; border: 1px solid #e7e5e4; border-radius: 20px; background: #fff; padding: 1.4rem 1.5rem; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);">
                    <div id="blog-media-player" style="position: relative; overflow: hidden; border: 1px solid #e7e5e4; border-radius: 20px; background: #fff; box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08); max-height: 700px;">
                        @if ($blogPost->hasEmbeddableVideo())
                            <div class="blog-media-fill blog-media-frame" style="background: #fff;">
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
                            <video class="blog-media-fill blog-media-frame" controls preload="metadata" style="background: #fff;">
                                <source src="{{ $blogPost->video_url }}">
                                Your browser does not support the video tag.
                            </video>
                        @elseif ($blogPost->cover_image_url)
                            <div class="blog-media-fill blog-media-frame" style="background: #fff;">
                                <img src="{{ $blogPost->cover_image_url }}" alt="{{ $blogPost->title }}">
                            </div>
                        @else
                            <div class="blog-media-fill blog-media-frame" style="display: flex; align-items: center; justify-content: center; background: #fff; padding: 2rem; text-align: center;">
                                <span style="font-family: 'Prata', serif; font-size: 2rem; line-height: 1.25; color: #111827;">{{ $blogPost->title }}</span>
                            </div>
                        @endif
                    </div>

                    <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; gap: 1rem; border-radius: 14px; background: #fff; padding: 0.95rem 0.15rem 0 0.15rem; color: #111827;">
                        <h1 style="margin: 0; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #111827; font-family: 'Prata', serif; font-size: 2.1rem; line-height: 1.15;">
                            {{ $blogPost->title }}
                        </h1>
                    </div>

                    <div>
                        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.9rem 1.25rem; border-bottom: 1px solid #f0ece7; padding-bottom: 1rem;">
                            <div style="display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; color: #78716c;">
                                @if ($blogPost->destination)
                                    <p style="margin: 0; font-size: 0.92rem; font-weight: 600;">
                                        Destination: <span style="color: #1f2937;">{{ $blogPost->destination }}</span>
                                    </p>
                                @endif
                                <p style="margin: 0; font-size: 0.92rem; font-weight: 600;">
                                    {{ $blogPost->published_at?->format('d M Y, h:i A') ?? 'Latest post' }}
                                </p>
                                @if ($blogPost->author_name)
                                    <p style="margin: 0; font-size: 0.92rem; font-weight: 600;">
                                        By <span style="color: #1f2937;">{{ $blogPost->author_name }}</span>
                                    </p>
                                @endif
                            </div>
                            <button
                                type="button"
                                data-blog-share-button
                                data-share-url="{{ route('blog.show', $blogPost) }}"
                                style="display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d6d3d1; border-radius: 999px; background: #fff; padding: 0.6rem 0.95rem; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: #1f2937; cursor: pointer;"
                            >
                                Share Post
                            </button>
                        </div>

                        @if ($blogPost->description)
                            <div class="blog-copy-text blog-prose" style="margin-top: 1.4rem;">
                                {!! $blogPost->description !!}
                            </div>
                        @endif

                        @if ($blogPost->section_items)
                            <div style="margin-top: 1.6rem; display: grid; gap: 2rem;">
                                @foreach ($blogPost->section_items as $section)
                                    <section style="display: grid; gap: 1rem;">
                                        @if ($section['image_url'])
                                            <img src="{{ $section['image_url'] }}" alt="{{ $section['title'] ?: $blogPost->title }}" class="blog-section-image">
                                        @endif

                                        @if ($section['title'])
                                            <h2 style="margin: 0; color: #111827; font-family: 'Prata', serif; font-size: 2.1rem; line-height: 1.15;">
                                                {{ $section['title'] }}
                                            </h2>
                                        @endif

                                        @if ($section['description'])
                                            <div class="blog-copy-text blog-prose">
                                                {!! $section['description'] !!}
                                            </div>
                                        @endif
                                    </section>
                                @endforeach
                            </div>
                        @endif

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
                <aside style="display: grid; gap: 18px; margin-top: 18px;">
                    <div style="border: 1px solid #e7e5e4; border-radius: 20px; background: #fff; padding: 0.75rem; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);">
                        @foreach ($latestBlogPosts as $post)
                            <a href="{{ route('blog.show', $post) }}" style="display: flex; gap: 0.75rem; border-radius: 14px; padding: 0.5rem; text-decoration: none; color: #111827;">
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
                                    <h2 style="margin: 0; display: -webkit-box; overflow: hidden; -webkit-line-clamp: 2; -webkit-box-orient: vertical; font-size: 0.82rem; font-weight: 700; line-height: 1.15rem; color: #111827;">
                                        {{ $post->title }}
                                    </h2>
                                    <p style="margin: 0.35rem 0 0; font-size: 0.72rem; color: #57534e;">Universal Eden Holidays</p>
                                    <p style="margin: 0.25rem 0 0; font-size: 0.7rem; color: #78716c;">
                                        {{ $post->published_at?->format('d M Y') ?? 'Latest post' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div style="border: 1px solid #e7e5e4; border-radius: 20px; background: #fff; padding: 1.25rem; box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);">
                        <p style="margin: 0; font-size: 1.9rem; font-weight: 700; color: #111827;">Follow our socials:</p>
                        <div style="margin-top: 1.2rem; display: grid; gap: 1rem;">
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
                </aside>
            @endif
        </div>
    </main>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-blog-share-button]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const shareUrl = button.dataset.shareUrl;

                    if (!shareUrl) {
                        return;
                    }

                    try {
                        if (navigator.share) {
                            await navigator.share({
                                title: @js($blogPost->title),
                                url: shareUrl,
                            });
                        } else if (navigator.clipboard) {
                            await navigator.clipboard.writeText(shareUrl);
                            const originalLabel = button.textContent;
                            button.textContent = 'Copied';

                            setTimeout(() => {
                                button.textContent = originalLabel;
                            }, 1800);
                        }
                    } catch (error) {
                    }
                });
            });
        });
    </script>
</x-layouts.app>
