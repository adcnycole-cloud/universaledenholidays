<section id="admin-blog-listings" class="mt-5 space-y-8">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-emerald-600">Blog</p>
                <h1 class="mt-2 text-2xl font-semibold text-stone-900">Publish daily posts to your website</h1>
            </div>
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100"
                data-blog-create-toggle
                aria-expanded="false"
                aria-controls="admin-blog-create-panel-body"
            >
                New Post
            </button>
        </div>
        <div id="admin-blog-create-panel-body" class="mt-6 hidden" data-blog-create-body>
            <form method="POST" action="{{ route('admin.blog-posts.store') }}" enctype="multipart/form-data" class="space-y-4" data-form-persist="admin-blogs-create">
                @csrf
                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label for="blog_title" class="mb-2 block text-sm font-medium text-stone-700">Title</label>
                        <input id="blog_title" name="title" type="text" value="{{ old('title') }}" placeholder="Best places to visit in Sabah today" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="blog_cover_image" class="mb-2 block text-sm font-medium text-stone-700">Cover image</label>
                        <input id="blog_cover_image" name="cover_image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-dashed border-stone-300 px-3 py-1.5 text-xs text-stone-700 file:mr-2 file:rounded-full file:border-0 file:bg-stone-100 file:px-2.5 file:py-1 file:text-[11px] file:font-semibold file:text-stone-700 hover:file:bg-stone-200">
                    </div>
                </div>
                <div>
                    <label for="blog_description" class="mb-2 block text-sm font-medium text-stone-700">Description</label>
                    <textarea id="blog_description" name="description" rows="8" placeholder="Write the main blog description here." class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="blog_credits" class="mb-2 block text-sm font-medium text-stone-700">Credits (optional)</label>
                    <textarea id="blog_credits" name="credits" rows="3" placeholder="Photo credit, collaborator, source, or other attribution." class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">{{ old('credits') }}</textarea>
                </div>
                <div>
                    <label for="blog_social_media_url" class="mb-2 block text-sm font-medium text-stone-700">Social media URL</label>
                    <input id="blog_social_media_url" name="social_media_url" type="url" value="{{ old('social_media_url') }}" placeholder="https://www.instagram.com/p/..." class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div>
                    <label for="blog_video_url" class="mb-2 block text-sm font-medium text-stone-700">Video URL</label>
                    <input id="blog_video_url" name="video_url" type="url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=... or direct .mp4 link" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                </div>
                <div class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div>
                        <label for="blog_published_at" class="mb-2 block text-sm font-medium text-stone-700">Publish date</label>
                        <input id="blog_published_at" name="published_at" type="datetime-local" value="{{ old('published_at') ? \Illuminate\Support\Carbon::parse(old('published_at'))->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-stone-600 lg:pb-3">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true)) class="rounded border-stone-300">
                        Publish this post on the website
                    </label>
                </div>
                <button type="submit" class="w-full rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-emerald-700">Save Blog Post</button>
            </form>
        </div>
    </section>

    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-2xl font-semibold text-stone-900">Blog library</h2>
                <label class="relative block w-full lg:max-w-sm">
                    <span class="sr-only">Search blog posts</span>
                    <input id="admin-blog-search" type="search" placeholder="Search posts by title, description, or credits" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-emerald-400 focus:bg-white">
                </label>
            </div>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <p id="admin-blog-results" class="text-sm text-stone-500" aria-live="polite">Showing {{ $blogPosts->count() }} blog posts</p>
                <div class="flex items-center gap-2">
                    <button id="admin-blog-prev" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-lg font-semibold leading-none text-stone-700 transition hover:bg-stone-100" aria-label="Previous page">&larr;</button>
                    <button id="admin-blog-next" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-lg font-semibold leading-none text-stone-700 transition hover:bg-stone-100" aria-label="Next page">&rarr;</button>
                </div>
            </div>
        </div>
        <div id="admin-blog-list" class="mt-6 space-y-4">
            @forelse ($blogPosts as $blogPost)
                @php
                    $isScheduled = $blogPost->published_at && $blogPost->published_at->isFuture();
                    $statusLabel = ! $blogPost->is_published ? 'Draft' : ($isScheduled ? 'Scheduled' : 'Published');
                    $statusClasses = ! $blogPost->is_published
                        ? 'text-stone-500'
                        : ($isScheduled ? 'text-amber-700' : 'text-emerald-700');
                @endphp
                <article data-admin-blog-item="true" class="relative rounded-3xl border border-stone-200 bg-stone-50 p-5">
                    <div class="grid gap-4 items-start" style="grid-template-columns: 96px minmax(0, 1fr);">
                        <div class="overflow-hidden rounded-md border border-stone-200 bg-white" style="width: 96px; height: 96px;">
                            @if ($blogPost->cover_image_url)
                                <img src="{{ $blogPost->cover_image_url }}" alt="{{ $blogPost->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-100 via-white to-stone-100 px-1 text-center text-[8px] font-semibold uppercase tracking-[0.15em] text-emerald-700">
                                    No cover image
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-xl font-semibold text-stone-900">{{ $blogPost->title }}</h4>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-stone-500">
                                {{ $blogPost->published_at ? $blogPost->published_at->format('d M Y h:i A') : 'No publish date set' }}
                            </p>
                            <p class="mt-3 text-sm leading-6 text-stone-600">
                                {{ \Illuminate\Support\Str::limit($blogPost->description, 180) }}
                            </p>
                            @if ($blogPost->credits)
                                <p class="mt-2 text-xs font-medium uppercase tracking-[0.18em] text-stone-400">
                                    Credits: {{ $blogPost->credits }}
                                </p>
                            @endif
                            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em]">
                                <span class="rounded-full bg-white px-3 py-1 text-stone-600">Slug {{ $blogPost->slug }}</span>
                                @if ($blogPost->social_media_url)
                                    <a href="{{ $blogPost->social_media_url }}" target="_blank" rel="noopener noreferrer" class="rounded-full bg-white px-3 py-1 text-stone-600 transition hover:text-emerald-700">Social Link</a>
                                @endif
                                @if ($blogPost->video_url)
                                    <span class="rounded-full bg-white px-3 py-1 text-sky-700">Video Attached</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2" style="grid-column: 1 / -1;">
                            @if ($blogPost->is_published && (! $blogPost->published_at || $blogPost->published_at->lte(now())))
                                <a href="{{ route('blog.show', $blogPost) }}" target="_blank" rel="noopener noreferrer" class="min-w-[8.75rem] rounded-full border border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                    Preview
                                </a>
                            @else
                                <span class="min-w-[8.75rem] rounded-full border border-dashed border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-400">
                                    Not Public Yet
                                </span>
                            @endif
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100" data-blog-edit-open>
                                Edit
                            </button>

                            <div class="hidden items-center justify-center overflow-y-auto bg-stone-950/55 px-8 py-6" data-blog-edit-modal style="position: fixed; inset: 0; z-index: 5000;">
                                <form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data" class="w-full max-w-4xl space-y-4 rounded-2xl border border-stone-200 bg-white p-5 shadow-[0_18px_40px_rgba(15,23,42,0.16)]" data-form-persist="admin-blogs-update-{{ $blogPost->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center justify-between gap-3">
                                        <h4 class="text-lg font-semibold text-stone-900">Edit Blog Post</h4>
                                        <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-stone-300 bg-white text-lg leading-none text-stone-700 transition hover:bg-stone-100" data-blog-edit-close aria-label="Close edit modal">&times;</button>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Title</label>
                                            <input name="title" type="text" value="{{ $blogPost->title }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Cover image</label>
                                            <input name="cover_image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-dashed border-stone-300 px-3 py-1.5 text-xs text-stone-700 file:mr-2 file:rounded-full file:border-0 file:bg-stone-100 file:px-2.5 file:py-1 file:text-[11px] file:font-semibold file:text-stone-700 hover:file:bg-stone-200">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Description</label>
                                        <textarea name="description" rows="8" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $blogPost->description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Credits (optional)</label>
                                        <textarea name="credits" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $blogPost->credits }}</textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Social media URL</label>
                                        <input name="social_media_url" type="url" value="{{ $blogPost->social_media_url }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Video URL</label>
                                        <input name="video_url" type="url" value="{{ $blogPost->video_url }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Publish date</label>
                                            <input name="published_at" type="datetime-local" value="{{ $blogPost->published_at?->format('Y-m-d\TH:i') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                        <label class="flex items-center gap-2 text-sm text-stone-600 md:pb-3">
                                            <input type="checkbox" name="is_published" value="1" @checked($blogPost->is_published) class="rounded border-stone-300">
                                            Publish this post
                                        </label>
                                    </div>
                                    <div class="flex flex-wrap justify-end gap-3">
                                        <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100" data-blog-edit-close>Cancel</button>
                                        <button type="submit" class="rounded-full bg-emerald-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-emerald-700">Update Post</button>
                                    </div>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('admin.blog-posts.destroy', $blogPost) }}" onsubmit="return confirm('Delete this blog post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="min-w-[8.75rem] rounded-full border border-rose-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">No blog posts yet.</div>
            @endforelse
        </div>
    </section>
</section>
