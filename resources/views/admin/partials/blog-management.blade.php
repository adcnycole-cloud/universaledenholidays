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
                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label for="blog_destination" class="mb-2 block text-sm font-medium text-stone-700">Destination</label>
                        <input id="blog_destination" name="destination" type="text" value="{{ old('destination') }}" placeholder="Sabah, Malaysia" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="blog_author_name" class="mb-2 block text-sm font-medium text-stone-700">Author name</label>
                        <input id="blog_author_name" name="author_name" type="text" value="{{ old('author_name') }}" placeholder="Nicolie" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                    <div>
                        <label for="blog_published_at" class="mb-2 block text-sm font-medium text-stone-700">Timestamp post</label>
                        <input id="blog_published_at" name="published_at" type="datetime-local" value="{{ old('published_at') ? \Illuminate\Support\Carbon::parse(old('published_at'))->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
                    </div>
                </div>
                <div>
                    <label for="blog_description" class="mb-2 block text-sm font-medium text-stone-700">Description</label>
                    <div class="mb-2 flex flex-wrap gap-2">
                        <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-xs font-semibold text-stone-700" data-rich-toggle data-target="blog_description" data-open="<strong>" data-close="</strong>">Bold</button>
                        <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-xs font-semibold text-stone-700" data-rich-toggle data-target="blog_description" data-open="<em>" data-close="</em>">Italic</button>
                    </div>
                    <textarea id="blog_description" name="description" rows="8" placeholder="Write the main blog description here. You can use the buttons above for bold or italic text." class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="blog_credits" class="mb-2 block text-sm font-medium text-stone-700">Credits (optional)</label>
                    <textarea id="blog_credits" name="credits" rows="3" placeholder="Photo credit, collaborator, source, or other attribution." class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">{{ old('credits') }}</textarea>
                </div>
                <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-stone-700">Add-On Sections</p>
                            <p class="mt-1 text-sm text-stone-500">Add more images, image titles, and extra descriptions to build a tall article.</p>
                        </div>
                        <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700" data-blog-section-add data-target="blog-create-sections">
                            Add Section
                        </button>
                    </div>
                    <div id="blog-create-sections" class="mt-4 space-y-4">
                        @foreach (old('sections', []) as $index => $section)
                            <div class="rounded-2xl border border-stone-200 bg-white p-4" data-blog-section-item>
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500" data-blog-section-label>Section {{ $index + 1 }}</p>
                                    <button type="button" class="rounded-full border border-rose-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-rose-700" data-blog-section-remove>Remove</button>
                                </div>
                                <input type="hidden" data-blog-existing-image-input value="{{ $section['existing_image_path'] ?? '' }}">
                                <div class="mt-3 grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image</label>
                                        <input name="sections[{{ $index }}][image]" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-dashed border-stone-300 px-3 py-2 text-xs text-stone-700" data-blog-section-image>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image title</label>
                                        <input name="sections[{{ $index }}][title]" type="text" value="{{ $section['title'] ?? '' }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800" data-blog-section-title>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="mb-2 flex flex-wrap gap-2">
                                        <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-blog-section-bold>Bold</button>
                                        <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-blog-section-italic>Italic</button>
                                    </div>
                                    <textarea rows="5" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800" data-blog-section-description>{{ $section['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                    <div></div>
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
                            <div class="mt-2 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-stone-500">
                                @if ($blogPost->destination)
                                    <span>{{ $blogPost->destination }}</span>
                                @endif
                                @if ($blogPost->author_name)
                                    <span>{{ $blogPost->author_name }}</span>
                                @endif
                            </div>
                            <p class="mt-3 text-sm leading-6 text-stone-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($blogPost->description), 180) }}
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

                            <div class="hidden items-start justify-center overflow-y-auto bg-stone-950/55 px-8 py-8" data-blog-edit-modal style="position: fixed; inset: 0; z-index: 5000;">
                                <form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data" class="my-auto w-full max-w-4xl space-y-4 rounded-2xl border border-stone-200 bg-white p-5 shadow-[0_18px_40px_rgba(15,23,42,0.16)]" style="max-height: calc(100vh - 4rem); overflow-y: auto;" data-form-persist="admin-blogs-update-{{ $blogPost->id }}">
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
                                    <div class="grid gap-4 md:grid-cols-3">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Destination</label>
                                            <input name="destination" type="text" value="{{ $blogPost->destination }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Author name</label>
                                            <input name="author_name" type="text" value="{{ $blogPost->author_name }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Timestamp post</label>
                                            <input name="published_at" type="datetime-local" value="{{ $blogPost->published_at?->format('Y-m-d\TH:i') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Description</label>
                                        <div class="mb-2 flex flex-wrap gap-2">
                                            <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-rich-toggle data-target="edit_blog_description_{{ $blogPost->id }}" data-open="<strong>" data-close="</strong>">Bold</button>
                                            <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-rich-toggle data-target="edit_blog_description_{{ $blogPost->id }}" data-open="<em>" data-close="</em>">Italic</button>
                                        </div>
                                        <textarea id="edit_blog_description_{{ $blogPost->id }}" name="description" rows="8" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $blogPost->description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Credits (optional)</label>
                                        <textarea name="credits" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $blogPost->credits }}</textarea>
                                    </div>
                                    <div class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-700">Add-On Sections</p>
                                                <p class="mt-1 text-sm text-stone-500">Add more images, image titles, and extra descriptions.</p>
                                            </div>
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-700" data-blog-section-add data-target="blog-edit-sections-{{ $blogPost->id }}">
                                                Add Section
                                            </button>
                                        </div>
                                        <div id="blog-edit-sections-{{ $blogPost->id }}" class="mt-4 space-y-4">
                                            @foreach ($blogPost->section_items as $index => $section)
                                                <div class="rounded-2xl border border-stone-200 bg-white p-4" data-blog-section-item>
                                                    <div class="flex items-center justify-between gap-3">
                                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">Section {{ $index + 1 }}</p>
                                                        <button type="button" class="rounded-full border border-rose-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-rose-700" data-blog-section-remove>Remove</button>
                                                    </div>
                                                    <input type="hidden" name="sections[{{ $index }}][existing_image_path]" value="{{ $section['image_path'] }}">
                                                    <div class="mt-3 grid gap-4 md:grid-cols-2">
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image</label>
                                                            <input name="sections[{{ $index }}][image]" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-dashed border-stone-300 px-3 py-2 text-xs text-stone-700">
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image title</label>
                                                            <input name="sections[{{ $index }}][title]" type="text" value="{{ $section['title'] }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="mb-2 flex flex-wrap gap-2">
                                                            <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-rich-toggle data-target="blog_edit_section_{{ $blogPost->id }}_{{ $index }}" data-open="<strong>" data-close="</strong>">Bold</button>
                                                            <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-rich-toggle data-target="blog_edit_section_{{ $blogPost->id }}_{{ $index }}" data-open="<em>" data-close="</em>">Italic</button>
                                                        </div>
                                                        <textarea id="blog_edit_section_{{ $blogPost->id }}_{{ $index }}" name="sections[{{ $index }}][description]" rows="5" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $section['description'] }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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
                                        <div></div>
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

<template id="blog-section-template">
    <div class="rounded-2xl border border-stone-200 bg-white p-4" data-blog-section-item>
        <div class="flex items-center justify-between gap-3">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500" data-blog-section-label>Section</p>
            <button type="button" class="rounded-full border border-rose-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-rose-700" data-blog-section-remove>Remove</button>
        </div>
        <input type="hidden" data-blog-existing-image-input>
        <div class="mt-3 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image</label>
                <input type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-dashed border-stone-300 px-3 py-2 text-xs text-stone-700" data-blog-section-image>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image title</label>
                <input type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800" data-blog-section-title>
            </div>
        </div>
        <div class="mt-3">
            <div class="mb-2 flex flex-wrap gap-2">
                <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-blog-section-bold>Bold</button>
                <button type="button" class="rounded-full border border-stone-300 px-3 py-1 text-[11px] font-semibold text-stone-700" data-blog-section-italic>Italic</button>
            </div>
            <textarea rows="5" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800" data-blog-section-description></textarea>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapSelection = (field, openTag, closeTag) => {
            if (!field) {
                return;
            }

            const start = field.selectionStart ?? field.value.length;
            const end = field.selectionEnd ?? field.value.length;
            const selectedText = field.value.slice(start, end);
            const replacement = `${openTag}${selectedText}${closeTag}`;

            field.setRangeText(replacement, start, end, 'end');
            field.focus();
        };

        document.querySelectorAll('[data-rich-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = document.getElementById(button.dataset.target);
                wrapSelection(target, button.dataset.open ?? '', button.dataset.close ?? '');
            });
        });

        document.querySelectorAll('[data-share-url]').forEach((button) => {
            button.addEventListener('click', async () => {
                const shareUrl = button.dataset.shareUrl;

                if (!shareUrl) {
                    return;
                }

                try {
                    if (navigator.share) {
                        await navigator.share({ url: shareUrl });
                    } else if (navigator.clipboard) {
                        await navigator.clipboard.writeText(shareUrl);
                        button.textContent = 'Copied';
                        setTimeout(() => {
                            button.textContent = 'Share';
                        }, 1800);
                    }
                } catch (error) {
                }
            });
        });

        const template = document.getElementById('blog-section-template');

        const syncSectionNames = (container) => {
            Array.from(container.querySelectorAll('[data-blog-section-item]')).forEach((item, index) => {
                item.querySelector('[data-blog-section-label]').textContent = `Section ${index + 1}`;
                item.querySelector('[data-blog-existing-image-input]').name = `sections[${index}][existing_image_path]`;
                item.querySelector('[data-blog-section-image]').name = `sections[${index}][image]`;
                item.querySelector('[data-blog-section-title]').name = `sections[${index}][title]`;
                item.querySelector('[data-blog-section-description]').name = `sections[${index}][description]`;

                const textarea = item.querySelector('[data-blog-section-description]');
                const textareaId = `${container.id}_section_${index}`;
                textarea.id = textareaId;

                item.querySelector('[data-blog-section-bold]').onclick = () => wrapSelection(textarea, '<strong>', '</strong>');
                item.querySelector('[data-blog-section-italic]').onclick = () => wrapSelection(textarea, '<em>', '</em>');
                item.querySelector('[data-blog-section-remove]').onclick = () => {
                    item.remove();
                    syncSectionNames(container);
                };
            });
        };

        document.querySelectorAll('[data-blog-section-add]').forEach((button) => {
            button.addEventListener('click', () => {
                const container = document.getElementById(button.dataset.target);

                if (!template || !container) {
                    return;
                }

                const fragment = template.content.cloneNode(true);
                container.appendChild(fragment);
                syncSectionNames(container);
            });
        });

        document.querySelectorAll('[id^="blog-edit-sections-"], #blog-create-sections').forEach((container) => {
            syncSectionNames(container);
        });
    });
</script>
