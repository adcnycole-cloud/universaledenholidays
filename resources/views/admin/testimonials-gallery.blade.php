<x-layouts.app title="Customer Gallery Management | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="mx-auto max-w-[1600px] space-y-8">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <details>
                    <summary class="list-none">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.3em] text-amber-600">Customer Gallery</p>
                                <h1 class="mt-2 text-2xl font-semibold text-stone-900">Customer gallery management</h1>
                                <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">
                                    Create and manage standalone customer gallery cards with their own image, title, and description.
                                </p>
                            </div>
                            <div class="flex flex-col items-stretch gap-3 lg:items-end">
                                <span class="inline-flex cursor-pointer items-center justify-center rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                    Add Gallery Item
                                </span>
                                <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600">
                                    {{ ($customerGalleryItems ?? collect())->where('is_active', true)->count() }} item{{ ($customerGalleryItems ?? collect())->where('is_active', true)->count() === 1 ? '' : 's' }} currently showing in gallery
                                </div>
                            </div>
                        </div>
                    </summary>

                    <div class="mt-8 rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                    <form method="POST" action="{{ route('admin.customer-gallery-items.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        <label class="flex items-center gap-2 text-sm text-stone-700">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-stone-300">
                            Show in customer gallery
                        </label>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery title</label>
                                <input name="title" type="text" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery image</label>
                                <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700" required>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery description</label>
                            <textarea name="description" rows="4" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">
                            Save Gallery Item
                        </button>
                    </form>
                    </div>
                </details>

                <div class="mt-8 grid items-start gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @forelse (($customerGalleryItems ?? collect()) as $galleryItem)
                        <article class="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-stone-50 shadow-sm">
                            <div class="flex min-h-[8rem] items-start gap-4 border-b border-stone-200 bg-white px-5 py-5">
                                <div class="shrink-0 overflow-hidden rounded-[1.25rem] shadow-sm" style="width: 80px; height: 80px;">
                                    <img src="{{ $galleryItem->image_url }}" alt="{{ $galleryItem->title }}" class="block h-full w-full object-cover" style="width: 80px; height: 80px; object-fit: cover; object-position: center;">
                                </div>
                                <div class="flex min-w-0 flex-1 flex-col justify-center">
                                    <div class="flex items-start gap-2">
                                        <h2 class="min-h-[3.5rem] flex-1 line-clamp-2 text-lg font-semibold leading-7 text-stone-900">{{ $galleryItem->title }}</h2>
                                        <span class="shrink-0 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $galleryItem->is_active ? 'bg-sky-100 text-sky-700' : 'bg-stone-200 text-stone-600' }}">
                                            {{ $galleryItem->is_active ? 'Showing' : 'Hidden' }}
                                        </span>
                                    </div>
                                    <p class="mt-1 min-h-[3rem] line-clamp-2 text-sm leading-6 text-stone-600">
                                        {{ \Illuminate\Support\Str::limit($galleryItem->description, 120) }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 py-5">
                                <details class="min-w-0">
                                    <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                        Edit Gallery Item
                                    </summary>
                                    <div class="mt-4 space-y-4 rounded-[1.5rem] border border-stone-200 bg-white p-4">
                                    <form method="POST" action="{{ route('admin.customer-gallery-items.update', $galleryItem) }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        @method('PATCH')

                                        <label class="flex items-center gap-2 text-sm text-stone-700">
                                            <input type="checkbox" name="is_active" value="1" @checked($galleryItem->is_active) class="rounded border-stone-300">
                                            Show in customer gallery
                                        </label>

                                        <div class="grid gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery title</label>
                                                <input name="title" type="text" value="{{ $galleryItem->title }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery image</label>
                                                <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery description</label>
                                            <textarea name="description" rows="4" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>{{ $galleryItem->description }}</textarea>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <button type="submit" class="flex-1 rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">
                                                Save
                                            </button>
                                    </form>
                                            <form method="POST" action="{{ route('admin.customer-gallery-items.destroy', $galleryItem) }}" data-gallery-delete-form>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50" data-gallery-delete-trigger data-gallery-delete-label="{{ $galleryItem->title }}">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </details>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 px-6 py-10 text-center text-sm leading-7 text-stone-600 lg:col-span-2">
                            No customer gallery items saved yet.
                        </div>
                    @endforelse
                </div>
            </section>
        </section>
    </main>

    <div id="gallery-delete-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-stone-950/50 px-4">
        <div class="w-full max-w-md rounded-[1.75rem] bg-white p-6 shadow-[0_30px_80px_rgba(15,23,42,0.24)]">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-500">Delete Gallery Item</p>
            <h2 class="mt-3 text-2xl font-semibold text-stone-900">Confirm deletion</h2>
            <p id="gallery-delete-message" class="mt-3 text-sm leading-7 text-stone-600">
                Are you sure you want to delete this gallery item?
            </p>
            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <button type="button" id="gallery-delete-cancel" class="rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
                    Cancel
                </button>
                <button type="button" id="gallery-delete-confirm" class="rounded-full bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Delete Item
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('gallery-delete-modal');
            const message = document.getElementById('gallery-delete-message');
            const cancelButton = document.getElementById('gallery-delete-cancel');
            const confirmButton = document.getElementById('gallery-delete-confirm');

            if (!modal || !message || !cancelButton || !confirmButton) {
                return;
            }

            let activeForm = null;

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                activeForm = null;
            };

            document.querySelectorAll('[data-gallery-delete-trigger]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();

                    const form = button.closest('[data-gallery-delete-form]');
                    const label = button.dataset.galleryDeleteLabel || 'this gallery item';

                    if (!form) {
                        return;
                    }

                    activeForm = form;
                    message.textContent = `Are you sure you want to delete "${label}"? This action cannot be undone.`;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            cancelButton.addEventListener('click', closeModal);

            confirmButton.addEventListener('click', () => {
                if (activeForm) {
                    activeForm.submit();
                }
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</x-layouts.app>
