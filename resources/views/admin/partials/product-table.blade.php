@php($editable = $editable ?? false)
@php($wrapperId = $wrapperId ?? null)
@php($itemAttribute = $itemAttribute ?? null)
@php($gridColumns = $gridColumns ?? 1)

<div
    @if($wrapperId) id="{{ $wrapperId }}" @endif
    class="mt-6 {{ $gridColumns > 1 ? 'grid gap-4' : 'space-y-4' }}"
    @if($gridColumns > 1) style="display: grid; grid-template-columns: repeat({{ $gridColumns }}, minmax(0, 1fr)); gap: 1rem; align-items: start;" @endif
>
    @forelse ($products as $product)
        @php($cardImage = $product->image_url ?: (collect($product->gallery_images ?? [])->filter()->first()))
        @php($isPackage = $product->category === 'package')

        <article @if($itemAttribute) {{ $itemAttribute }}="true" @endif class="relative rounded-3xl border border-stone-200 bg-stone-50 p-5" @if($isPackage) data-package-inline-row @endif>
            <div
                class="grid gap-4 items-start {{ $isPackage ? '' : 'xl:grid-cols-[110px_minmax(0,1fr)_auto]' }}"
                @if ($isPackage) style="grid-template-columns: 180px minmax(0, 1fr);" @endif
            >
                <div class="overflow-hidden border border-stone-200 bg-white {{ $isPackage ? 'rounded-md' : 'rounded-[1rem]' }}" @if ($isPackage) style="width: 180px; height: 180px;" @else style="width: 72px; height: 72px;" @endif>
                    @if ($cardImage)
                        <img
                            src="{{ $cardImage }}"
                            alt="{{ $product->name }}"
                            class="w-full object-cover {{ $isPackage ? '' : 'h-24' }}"
                            @if ($isPackage) style="width: 180px; height: 180px;" @else style="width: 72px; height: 72px;" @endif
                        >
                    @else
                        <div
                            class="flex items-center justify-center bg-stone-100 text-center font-semibold uppercase text-stone-400 {{ $isPackage ? 'px-1 text-[8px] tracking-[0.15em]' : 'h-24 px-2 text-[10px] tracking-[0.2em]' }}"
                            @if ($isPackage) style="width: 180px; height: 180px;" @else style="width: 72px; height: 72px;" @endif
                        >
                            No image
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    @if ($isPackage && $editable)
                        @php($inlineFormId = 'package-inline-form-'.$product->id)
                        <form id="{{ $inlineFormId }}" method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-3" data-package-inline-form>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="image_url" value="{{ $product->image_url }}">
                            @php($inlineGalleryImages = collect($product->gallery_images ?? [])->filter()->values())
                            @foreach ($inlineGalleryImages as $galleryImage)
                                <input type="hidden" name="existing_gallery_images[]" value="{{ $galleryImage }}">
                            @endforeach

                            <div class="package-inline-view space-y-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-xl font-semibold text-stone-900">{{ $product->name }}</h4>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                        {{ $product->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </div>
                                <p class="text-sm text-stone-500">{{ $product->location }} | {{ $product->duration }}</p>
                                <p class="text-sm leading-6 text-stone-600">{{ $product->summary }}</p>
                                <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em]">
                                    <span class="rounded-full bg-white px-3 py-1 text-stone-600">RM {{ number_format((float) $product->malaysia_adult_price_myr, 2) }}</span>
                                    @if ($product->capacity)
                                        <span class="rounded-full bg-white px-3 py-1 text-stone-600">Capacity {{ $product->capacity }}</span>
                                    @endif
                                    <span class="rounded-full bg-white px-3 py-1 text-stone-600">{{ $product->is_top_choice ? 'Top choice' : ($product->is_featured ? 'Featured' : 'Standard') }}</span>
                                </div>
                            </div>

                            <div class="package-inline-edit hidden fixed inset-x-0 bottom-0 z-[280] items-start justify-center overflow-y-auto bg-stone-950/55 px-8 py-6">
                                <div class="w-full max-w-[1390px] overflow-y-auto rounded-[2rem] border border-stone-200 bg-stone-100 p-4 shadow-[0_24px_60px_rgba(15,23,42,0.24)]" data-package-inline-panel>
                                <div class="grid gap-6 items-start" style="grid-template-columns: 560px minmax(0, 1fr);">
                                    <div>
                                        <div class="grid gap-4" style="grid-template-columns: 180px 180px 170px;">
                                            <div class="min-w-0">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Main image</p>
                                                @if ($product->image_url)
                                                    <button type="button" class="package-inline-main-image-open block overflow-hidden rounded-lg shadow-sm" data-inline-main-image-target="package-inline-main-image-{{ $product->id }}" aria-label="Open main image preview">
                                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-lg object-cover" style="width: 180px; height: 180px;">
                                                    </button>

                                                    <div id="package-inline-main-image-{{ $product->id }}" class="package-inline-main-image-modal fixed inset-0 z-[260] hidden items-center justify-center bg-stone-950/70 px-4 py-6">
                                                        <div class="w-full max-w-5xl rounded-[1.4rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]">
                                                            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                                                                <div>
                                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-400">Main image</p>
                                                                    <p class="mt-1 text-sm font-semibold text-stone-800">{{ $product->name }}</p>
                                                                </div>
                                                                <button type="button" class="package-inline-main-image-close inline-flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close main image preview">&times;</button>
                                                            </div>
                                                            <div class="flex max-h-[78vh] items-center justify-center overflow-auto p-4">
                                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-[70vh] w-auto max-w-full rounded-xl object-contain">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center rounded-lg border border-dashed border-stone-300 bg-stone-50 text-center text-[10px] font-medium uppercase tracking-[0.14em] text-stone-400" style="width: 180px; height: 180px;">
                                                        No main image
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Gallery folder</p>
                                                @if ($inlineGalleryImages->isNotEmpty())
                                                    <button type="button" class="package-inline-gallery-open relative flex h-[180px] w-[180px] flex-col justify-end overflow-hidden rounded-[1.6rem] border border-amber-200 bg-gradient-to-b from-amber-100 via-amber-50 to-white px-6 py-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" data-inline-gallery-target="package-inline-gallery-{{ $product->id }}">
                                                        <span class="absolute left-5 top-0 h-8 w-20 rounded-b-[1rem] rounded-t-[0.75rem] bg-amber-200/90"></span>
                                                        <span class="absolute left-0 right-0 top-7 h-px bg-amber-200/80"></span>
                                                        <p class="text-4xl font-semibold leading-none text-amber-950">{{ $inlineGalleryImages->count() }}</p>
                                                        <p class="mt-3 text-lg font-semibold uppercase tracking-[0.06em] text-amber-900">Image{{ $inlineGalleryImages->count() === 1 ? '' : 's' }}</p>
                                                        <p class="mt-4 text-sm uppercase tracking-[0.18em] text-amber-700/70">Open folder</p>
                                                    </button>

                                                    <div id="package-inline-gallery-{{ $product->id }}" class="package-inline-gallery-modal fixed inset-0 z-[250] hidden items-center justify-center bg-stone-950/60 px-4 py-6">
                                                        <div class="w-full max-w-3xl rounded-[1.4rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]">
                                                            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                                                                <div>
                                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-400">Gallery images</p>
                                                                    <p class="mt-1 text-sm font-semibold text-stone-800">{{ $product->name }}</p>
                                                                </div>
                                                                <button type="button" class="package-inline-gallery-close inline-flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close gallery">&times;</button>
                                                            </div>
                                                            <div class="grid max-h-[70vh] grid-cols-2 gap-3 overflow-y-auto p-4 md:grid-cols-3">
                                                                @foreach ($inlineGalleryImages as $galleryImage)
                                                                    <div class="package-inline-gallery-item relative overflow-hidden rounded-xl border border-stone-200 bg-stone-50" data-gallery-image="{{ $galleryImage }}">
                                                                        <button type="button" class="package-inline-gallery-remove absolute right-2 top-2 z-10 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold leading-none text-white shadow-sm transition hover:bg-rose-700" aria-label="Remove gallery image">-</button>
                                                                        <a href="{{ $galleryImage }}" target="_blank" rel="noopener" class="block">
                                                                            <img src="{{ $galleryImage }}" alt="{{ $product->name }} gallery image" class="h-32 w-full object-cover transition hover:scale-[1.03]">
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="relative flex h-[180px] w-[180px] flex-col justify-end overflow-hidden rounded-[1.6rem] border border-amber-200 bg-gradient-to-b from-amber-100 via-amber-50 to-white px-6 py-5 text-left shadow-sm">
                                                        <span class="absolute left-5 top-0 h-8 w-20 rounded-b-[1rem] rounded-t-[0.75rem] bg-amber-200/90"></span>
                                                        <span class="absolute left-0 right-0 top-7 h-px bg-amber-200/80"></span>
                                                        <p class="text-4xl font-semibold leading-none text-amber-950">0</p>
                                                        <p class="mt-3 text-lg font-semibold uppercase tracking-[0.06em] text-amber-900">Images</p>
                                                        <p class="mt-4 text-sm uppercase tracking-[0.18em] text-amber-700/70">Empty folder</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">Upload images</p>
                                                <div class="flex h-[180px] flex-col justify-between gap-2">
                                                    <label class="block">
                                                        <span class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-500">Main image</span>
                                                        <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-3 py-2 text-xs text-stone-700">
                                                    </label>
                                                    <label class="block">
                                                        <span class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-500">Gallery folder</span>
                                                        <input name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-3 py-2 text-xs text-stone-700">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid gap-3">
                                <div
                                    class="grid gap-3 {{ $product->category !== 'package' ? 'hidden' : '' }}"
                                    style="grid-template-columns: repeat(4, minmax(0, 1fr)); align-content: start;"
                                >
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Name</label>
                                        <input name="name" type="text" value="{{ $product->name }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Location</label>
                                        <input name="location" type="text" value="{{ $product->location }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Duration</label>
                                        <input name="duration" type="text" value="{{ $product->duration }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Capacity</label>
                                        <input name="capacity" type="number" value="{{ $product->capacity }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Malaysian adult price (MYR)</label>
                                        <input name="malaysia_adult_price_myr" type="number" step="0.01" value="{{ $product->malaysia_adult_price_myr }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">International adult price (MYR)</label>
                                        <input name="international_adult_price_myr" type="number" step="0.01" value="{{ $product->international_adult_price_myr }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Malaysian child price (MYR)</label>
                                        <input name="malaysia_child_price_myr" type="number" step="0.01" value="{{ $product->malaysia_child_price_myr }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">International child price (MYR)</label>
                                        <input name="international_child_price_myr" type="number" step="0.01" value="{{ $product->international_child_price_myr }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div style="grid-column: span 2 / span 2;">
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Summary</label>
                                        <textarea name="summary" rows="6" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">{{ $product->summary }}</textarea>
                                    </div>
                                    <div style="grid-column: span 2 / span 2;">
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Description</label>
                                        <textarea name="description" rows="6" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_featured" value="1" @checked($product->is_featured) class="rounded border-stone-300">
                                        Featured
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_top_choice" value="1" @checked($product->is_top_choice) class="rounded border-stone-300">
                                        Top choice
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_active" value="1" @checked($product->is_active) class="rounded border-stone-300">
                                        Active
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_discounted" value="1" @checked($product->is_discounted) class="rounded border-stone-300">
                                        Discount
                                    </label>
                                    <div class="flex items-center gap-2 text-sm text-stone-600">
                                        <label for="package-discount-{{ $product->id }}">%</label>
                                        <input id="package-discount-{{ $product->id }}" name="discount_percentage" type="number" step="0.01" min="0" max="100" value="{{ $product->discount_percentage }}" class="w-24 rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800">
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end gap-3">
                                    <button type="submit" form="{{ $inlineFormId }}" class="rounded-full bg-sky-600 px-6 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-white transition hover:bg-sky-700">
                                        Save
                                    </button>
                                    <button type="button" class="rounded-full border border-stone-300 bg-white px-6 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-stone-700 transition hover:bg-stone-100" data-package-inline-cancel>
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            </div>
                        </form>
                    @else
                        @if ($isPackage)
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-xl font-semibold text-stone-900">{{ $product->name }}</h4>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-stone-500">{{ $product->location }} | {{ $product->duration }}</p>
                            <p class="mt-3 text-sm leading-6 text-stone-600">{{ $product->summary }}</p>
                            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em]">
                                <span class="rounded-full bg-white px-3 py-1 text-stone-600">RM {{ number_format((float) $product->malaysia_adult_price_myr, 2) }}</span>
                                @if ($product->capacity)
                                    <span class="rounded-full bg-white px-3 py-1 text-stone-600">Capacity {{ $product->capacity }}</span>
                                @endif
                                <span class="rounded-full bg-white px-3 py-1 text-stone-600">{{ $product->is_top_choice ? 'Top choice' : ($product->is_featured ? 'Featured' : 'Standard') }}</span>
                            </div>
                        @else
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-xl font-semibold text-stone-900">{{ $product->name }}</h4>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </div>
                            @if ($product->capacity)
                                <p class="mt-2 text-sm font-medium text-stone-500">Capacity: {{ $product->capacity }}</p>
                            @endif
                            <p class="mt-3 text-sm leading-6 text-stone-600">{{ $product->summary }}</p>
                            <p class="mt-3 text-sm leading-7 text-stone-500">{{ $product->description }}</p>
                        @endif
                    @endif
                </div>

                @if ($editable)
                    <div class="flex flex-wrap gap-2 xl:w-[10rem] xl:flex-col xl:items-stretch" @if ($isPackage) style="grid-column: 1 / -1;" @endif>
                        @if ($isPackage)
                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 package-inline-edit-trigger" data-package-inline-edit>
                                Edit
                            </button>
                            <button type="submit" form="{{ $inlineFormId }}" class="hidden rounded-full bg-sky-600 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700 package-inline-save">
                                Save
                            </button>
                            <button type="button" class="hidden rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 package-inline-cancel" data-package-inline-cancel>
                                Cancel
                            </button>
                        @else
                            <details class="group inline-block">
                                <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 inline-flex min-w-[6.75rem] items-center justify-center">
                                    <span class="group-open:hidden">Edit</span>
                                    <span class="hidden group-open:inline">Close</span>
                                </summary>
                                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="absolute left-0 right-0 top-0 z-20 mt-0 w-full min-w-[320px] space-y-3 rounded-2xl border border-stone-200 bg-white p-4 shadow-[0_18px_40px_rgba(15,23,42,0.16)]">
                                @csrf
                                @method('PATCH')
                                <div class="grid gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Name</label>
                                        <input name="name" type="text" value="{{ $product->name }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Location</label>
                                        <input name="location" type="text" value="{{ $product->location }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                </div>
                                @if ($product->category === 'package')
                                    <div class="grid gap-4 md:items-start" style="grid-template-columns: 92px minmax(0, 1fr);">
                                        <div class="rounded-[0.9rem] border border-stone-200 bg-stone-50 p-2" style="width: 92px;">
                                            <p class="mb-3 text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Current images</p>
                                            <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Main image</p>
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-[0.6rem] object-cover shadow-sm" style="width: 88px; height: 40px;">
                                            @else
                                                <div class="flex items-center justify-center rounded-[0.6rem] border border-dashed border-stone-300 bg-white px-1 text-center text-[8px] font-medium uppercase tracking-[0.14em] text-stone-400" style="width: 88px; height: 40px;">
                                                    No main image
                                                </div>
                                            @endif

                                            @php($packageGalleryImages = collect($product->gallery_images ?? [])->filter()->values())

                                            <div class="mt-4">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Gallery images</p>
                                                @if ($packageGalleryImages->isNotEmpty())
                                                    <div class="grid grid-cols-2 gap-1">
                                                        @foreach ($packageGalleryImages as $galleryImage)
                                                            <div class="package-gallery-item relative overflow-hidden rounded-[0.55rem] border border-stone-200 bg-white shadow-sm" style="width: 42px;">
                                                                <input type="hidden" name="existing_gallery_images[]" value="{{ $galleryImage }}">
                                                                <button type="button" class="package-gallery-remove absolute right-0.5 top-0.5 z-10 inline-flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-[9px] font-bold leading-none text-white shadow-sm transition hover:bg-rose-700" aria-label="Remove gallery image">-</button>
                                                                <a href="{{ $galleryImage }}" target="_blank" rel="noopener" class="block">
                                                                    <img src="{{ $galleryImage }}" alt="{{ $product->name }} gallery image" class="w-full object-cover transition hover:scale-[1.03]" style="width: 42px; height: 32px;">
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="flex min-h-[5.5rem] items-center justify-center rounded-[0.95rem] border border-dashed border-stone-300 bg-white px-3 text-center text-[11px] font-medium uppercase tracking-[0.16em] text-stone-400">
                                                        No gallery images
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="grid gap-3">
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Summary</label>
                                                <input name="summary" type="text" value="{{ $product->summary }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                            <div class="grid gap-3 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Replace main image</label>
                                                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                                                </div>
                                                <div>
                                                    <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Upload gallery images</label>
                                                    <input name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @php($transportGalleryImages = collect($product->gallery_images ?? [])->filter()->values())
                                    <input type="hidden" name="image_url" value="{{ $product->image_url }}">
                                    <input type="hidden" name="location" value="{{ $product->location }}">
                                    <input type="hidden" name="duration" value="{{ $product->duration }}">
                                    <input type="hidden" name="malaysia_adult_price_myr" value="{{ $product->malaysia_adult_price_myr }}">
                                    <input type="hidden" name="malaysia_child_price_myr" value="{{ $product->malaysia_child_price_myr }}">
                                    <input type="hidden" name="international_adult_price_myr" value="{{ $product->international_adult_price_myr }}">
                                    <input type="hidden" name="international_child_price_myr" value="{{ $product->international_child_price_myr }}">
                                    @foreach ($transportGalleryImages as $galleryImage)
                                        <input type="hidden" name="existing_gallery_images[]" value="{{ $galleryImage }}">
                                    @endforeach
                                    <div class="grid gap-6 lg:grid-cols-[210px_210px_minmax(0,1fr)] lg:items-start">
                                        <div>
                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Main image</label>
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-2xl object-cover shadow-sm" style="width: 80px; height: 80px;">
                                            @else
                                                <div class="flex items-center justify-center rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-2 text-center text-[10px] font-medium uppercase tracking-[0.16em] text-stone-400" style="width: 80px; height: 80px;">
                                                    No main image
                                                </div>
                                            @endif
                                        </div>

                                        <div>
                                            <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Upload images</label>
                                            <div class="flex flex-col justify-start gap-4" style="min-height: 80px;">
                                                <div>
                                                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400">Main image</label>
                                                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-3 py-3 text-xs text-stone-700">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid gap-4 md:grid-cols-2">
                                            <div>
                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Name</label>
                                                <input name="name" type="text" value="{{ $product->name }}" class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Capacity</label>
                                                <input name="capacity" type="number" value="{{ $product->capacity }}" class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Summary</label>
                                                <textarea name="summary" rows="3" class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $product->summary }}</textarea>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Description</label>
                                                <textarea name="description" rows="3" class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $product->description }}</textarea>
                                            </div>
                                            <div class="md:col-span-2 flex flex-wrap gap-5 pt-1">
                                                <label class="flex items-center gap-2 text-sm text-stone-600">
                                                    <input type="checkbox" name="is_active" value="1" @checked($product->is_active) class="rounded border-stone-300">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="grid gap-3 md:grid-cols-3 {{ $product->category !== 'package' ? 'hidden' : '' }}">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Duration</label>
                                        <input name="duration" type="text" value="{{ $product->duration }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Capacity</label>
                                        <input name="capacity" type="number" value="{{ $product->capacity }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                </div>
                                <div class="grid gap-3 md:grid-cols-2 {{ $product->category !== 'package' ? 'hidden' : '' }}">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Malaysia adult price</label>
                                        <input name="malaysia_adult_price_myr" type="number" step="0.01" value="{{ $product->malaysia_adult_price_myr }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Malaysia child price</label>
                                        <input name="malaysia_child_price_myr" type="number" step="0.01" value="{{ $product->malaysia_child_price_myr }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">International adult price</label>
                                        <input name="international_adult_price_myr" type="number" step="0.01" value="{{ $product->international_adult_price_myr }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">International child price</label>
                                        <input name="international_child_price_myr" type="number" step="0.01" value="{{ $product->international_child_price_myr }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                </div>
                                <div class="grid gap-3 md:grid-cols-[1fr_1.2fr] {{ $product->category !== 'package' ? 'hidden' : '' }}">
                                    @if ($product->category !== 'package')
                                        <div>
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery image URLs</label>
                                            <textarea name="gallery_images" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">@if(is_array($product->gallery_images)){{ implode("\n", $product->gallery_images) }}@endif</textarea>
                                        </div>
                                    @endif
                                    <div class="{{ $product->category === 'package' ? 'md:col-span-2' : '' }}">
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Description</label>
                                        <textarea name="description" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-5 pt-1 {{ $product->category !== 'package' ? 'hidden' : '' }}">
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_featured" value="1" @checked($product->is_featured) class="rounded border-stone-300">
                                        Featured product
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_top_choice" value="1" @checked($product->is_top_choice) class="rounded border-stone-300">
                                        Top choice
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-stone-600">
                                        <input type="checkbox" name="is_active" value="1" @checked($product->is_active) class="rounded border-stone-300">
                                        Active
                                    </label>
                                </div>
                                <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700 {{ $product->category !== 'package' ? 'md:ml-auto md:w-auto md:min-w-[9rem]' : '' }}">
                                    Update Product
                                </button>
                                </form>
                            </details>
                        @endif

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50 {{ $isPackage ? '' : 'min-w-[6.75rem]' }}">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </article>
    @empty
        <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 p-6 text-sm text-stone-600">
            No entries in this section yet.
        </div>
    @endforelse
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-package-inline-row]').forEach((row) => {
            const form = row.querySelector('[data-package-inline-form]');
            const viewSection = row.querySelector('.package-inline-view');
            const editSection = row.querySelector('.package-inline-edit');
            const editPanel = row.querySelector('[data-package-inline-panel]');
            const editButton = row.querySelector('[data-package-inline-edit]');
            const cancelButton = row.querySelector('[data-package-inline-cancel]');
            const saveButton = row.querySelector('.package-inline-save');

            if (!form || !viewSection || !editSection || !editButton || !cancelButton || !saveButton) {
                return;
            }

            const resetForm = () => {
                form.reset();
            };

            const updateEditPosition = () => {
                if (!editSection || !editPanel) {
                    return;
                }

                const managementStack = row.closest('[data-product-management-stack]');
                const createPanel = managementStack?.querySelector('[data-product-create-panel]');
                const headerOffset = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--app-header-offset')) || 0;
                const createPanelBottom = createPanel?.getBoundingClientRect().bottom;
                const topOffset = Math.max(headerOffset + 16, (createPanelBottom ?? headerOffset) + 16);
                const availableHeight = Math.max(320, window.innerHeight - topOffset - 24);
                const panelHeight = Math.min(520, availableHeight);

                editSection.style.top = `${topOffset}px`;
                editPanel.style.height = `${panelHeight}px`;
                editPanel.style.maxHeight = `${panelHeight}px`;
                editPanel.style.width = '';
                editPanel.style.marginLeft = '';
                editSection.style.justifyContent = '';
            };

            editButton.addEventListener('click', () => {
                updateEditPosition();
                viewSection.classList.add('hidden');
                editSection.classList.remove('hidden');
                editSection.classList.add('flex');
                editButton.classList.add('hidden');
            });

            cancelButton.addEventListener('click', () => {
                resetForm();
                viewSection.classList.remove('hidden');
                editSection.classList.add('hidden');
                editSection.classList.remove('flex');
                editButton.classList.remove('hidden');
            });

            editSection.addEventListener('click', (event) => {
                if (event.target !== editSection) {
                    return;
                }

                cancelButton.click();
            });

            window.addEventListener('resize', () => {
                if (!editSection.classList.contains('hidden')) {
                    updateEditPosition();
                }
            });

            window.addEventListener('scroll', () => {
                if (!editSection.classList.contains('hidden')) {
                    updateEditPosition();
                }
            }, { passive: true });
        });

        document.querySelectorAll('.package-gallery-remove').forEach((button) => {
            button.addEventListener('click', () => {
                button.closest('.package-gallery-item')?.remove();
            });
        });

        document.querySelectorAll('.package-inline-gallery-open').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.inlineGalleryTarget);
                modal?.classList.remove('hidden');
                modal?.classList.add('flex');
            });
        });

        document.querySelectorAll('.package-inline-gallery-close').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = button.closest('.package-inline-gallery-modal');
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            });
        });

        document.querySelectorAll('.package-inline-gallery-modal').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target !== modal) {
                    return;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });

        document.querySelectorAll('.package-inline-gallery-remove').forEach((button) => {
            button.addEventListener('click', () => {
                const item = button.closest('.package-inline-gallery-item');
                const row = button.closest('[data-package-inline-row]');
                const imageUrl = item?.dataset.galleryImage;

                if (!item || !row || !imageUrl) {
                    return;
                }

                row.querySelectorAll('input[name="existing_gallery_images[]"]').forEach((input) => {
                    if (input.value === imageUrl) {
                        input.remove();
                    }
                });

                item.remove();
            });
        });

        document.querySelectorAll('.package-inline-main-image-open').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.inlineMainImageTarget);
                modal?.classList.remove('hidden');
                modal?.classList.add('flex');
            });
        });

        document.querySelectorAll('.package-inline-main-image-close').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = button.closest('.package-inline-main-image-modal');
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            });
        });

        document.querySelectorAll('.package-inline-main-image-modal').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target !== modal) {
                    return;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });
    });
</script>
