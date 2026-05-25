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
                        @php($itineraryFormId = 'package-itinerary-form-'.$product->id)
                        @php($serviceInclusionFormId = 'package-service-inclusion-form-'.$product->id)
                        @php($packageItineraryItems = collect($product->itinerary_items ?? [])->filter()->values())
                        @php($packageDurationDays = preg_match('/(\d+)\s*day/i', $product->duration ?? '', $packageDurationMatches) ? max(1, (int) $packageDurationMatches[1]) : 1)
                        @php($packageItineraryRows = collect(range(0, $packageDurationDays - 1))->map(function ($index) use ($packageItineraryItems) {
                            $existingRow = $packageItineraryItems->get($index);
                            if (is_array($existingRow) && array_key_exists('activity', $existingRow)) {
                                return [
                                    'day_number' => $existingRow['day_number'] ?? 'Day '.($index + 1),
                                    'time' => $existingRow['time'] ?? '',
                                    'activity' => $existingRow['activity'] ?? '',
                                ];
                            }

                            return [
                                'day_number' => 'Day '.($index + 1),
                                'time' => '',
                                'activity' => is_string($existingRow) ? $existingRow : '',
                            ];
                        }))
                        @php($packageItineraryGroups = $packageItineraryRows->groupBy(fn ($row) => $row['day_number'] ?: 'Day 1')->values())
                        @php($itineraryTimeOptions = collect(range(0, 47))->map(fn ($index) => \Carbon\Carbon::createFromTime(0, 0)->addMinutes($index * 30)->format('h:i A'))->all())
                        @php($rawPackageServiceInclusions = collect(is_array($product->service_inclusions) ? $product->service_inclusions : [])->filter()->values())
                        @php($packageServiceInclusionRows = $rawPackageServiceInclusions->isNotEmpty()
                            ? $rawPackageServiceInclusions->map(function ($row, $index) {
                                if (is_array($row) && array_key_exists('value', $row)) {
                                    return [
                                        'label' => $row['label'] ?? '',
                                        'value' => $row['value'] ?? '',
                                    ];
                                }

                                $legacyMap = [
                                    'meals' => 'Meals',
                                    'inclusion' => 'Inclusion',
                                    'accommodation' => 'Accommodation',
                                    'exclusion' => 'Exclusion',
                                ];

                                if (is_array($row) && count($row) === 1) {
                                    $legacyKey = array_key_first($row);
                                    return [
                                        'label' => $legacyMap[$legacyKey] ?? (string) $legacyKey,
                                        'value' => (string) ($row[$legacyKey] ?? ''),
                                    ];
                                }

                                return [
                                    'label' => 'Row '.($index + 1),
                                    'value' => is_string($row) ? $row : '',
                                ];
                            })
                            : collect([
                                ['label' => 'Meals', 'value' => ''],
                                ['label' => 'Inclusion', 'value' => ''],
                            ]))
                        <form id="{{ $inlineFormId }}" method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-3" data-package-inline-form data-form-persist="admin-products-update-{{ $product->id }}">
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

                            <div class="package-inline-edit hidden fixed inset-0 z-[400] items-center justify-center overflow-y-auto bg-stone-950/55 px-8 py-6">
                                <div class="w-full max-w-[1390px] overflow-y-auto rounded-[2rem] border border-stone-200 bg-stone-100 p-4 shadow-[0_24px_60px_rgba(15,23,42,0.24)]" data-package-inline-panel>
                                <div class="grid gap-6 items-start" style="grid-template-columns: 560px minmax(0, 1fr);">
                                    <div>
                                        <div class="grid gap-4" style="grid-template-columns: 240px 180px;">
                                            <div class="min-w-0">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Main image</p>
                                                @if ($product->image_url)
                                                    <button type="button" class="package-inline-main-image-open block overflow-hidden rounded-lg shadow-sm" data-inline-main-image-target="package-inline-main-image-{{ $product->id }}" aria-label="Open main image preview">
                                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-lg object-cover" style="width: 240px; height: 240px;">
                                                    </button>

                                                    <div id="package-inline-main-image-{{ $product->id }}" class="package-inline-main-image-modal fixed inset-0 z-[260] hidden items-center justify-center bg-stone-950/70 px-4 py-4">
                                                        <div class="inline-flex flex-col rounded-[0.8rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]" data-package-main-image-panel style="width: fit-content; min-width: 0; max-width: calc(100vw - 8rem); height: calc(88vh - 50px);">
                                                            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                                                                <div>
                                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-400">Main image</p>
                                                                    <p class="mt-1 text-sm font-semibold text-stone-800">{{ $product->name }}</p>
                                                                </div>
                                                                <button type="button" class="package-inline-main-image-close inline-flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close main image preview">&times;</button>
                                                            </div>
                                                            <div class="flex flex-1 items-center justify-center overflow-hidden" style="width: fit-content; min-width: 0; max-width: 100%; padding: 30px; margin: 0 auto;">
                                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="block h-full w-auto rounded-[0.4rem] object-contain" data-package-main-image-preview style="max-width: none;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center rounded-lg border border-dashed border-stone-300 bg-stone-50 text-center text-[10px] font-medium uppercase tracking-[0.14em] text-stone-400" style="width: 240px; height: 240px;">
                                                        No main image
                                                    </div>
                                                @endif
                                                <label class="mt-3 block">
                                                    <span class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-500">Upload main image</span>
                                                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-3 py-2 text-xs text-stone-700">
                                                </label>
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

                                                    <div id="package-inline-gallery-{{ $product->id }}" class="package-inline-gallery-modal fixed inset-0 z-[250] hidden items-center justify-center bg-stone-950/60 px-4 py-4">
                                                        <div class="flex h-[88vh] w-full max-w-7xl flex-col rounded-[1.4rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]">
                                                            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                                                                <div>
                                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-400">Gallery images</p>
                                                                    <p class="mt-1 text-sm font-semibold text-stone-800">{{ $product->name }}</p>
                                                                </div>
                                                                <button type="button" class="package-inline-gallery-close inline-flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close gallery">&times;</button>
                                                            </div>
                                                            <div class="grid flex-1 grid-cols-2 gap-3 overflow-y-auto p-2 md:grid-cols-3 lg:grid-cols-4">
                                                                @foreach ($inlineGalleryImages as $galleryImage)
                                                                    <div class="package-inline-gallery-item relative overflow-hidden rounded-xl border border-stone-200 bg-stone-50" data-gallery-image="{{ $galleryImage }}">
                                                                        <button type="button" class="package-inline-gallery-remove absolute right-2 top-2 z-10 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold leading-none text-white shadow-sm transition hover:bg-rose-700" aria-label="Remove gallery image">-</button>
                                                                        <button type="button" class="package-inline-gallery-preview-open block w-full" data-gallery-preview-src="{{ $galleryImage }}" data-gallery-preview-name="{{ $product->name }}" data-gallery-preview-modal="package-inline-gallery-image-preview-{{ $product->id }}" aria-label="Open gallery image preview">
                                                                            <img src="{{ $galleryImage }}" alt="{{ $product->name }} gallery image" class="h-44 w-full object-cover transition hover:scale-[1.03]">
                                                                        </button>
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
                                                <label class="mt-3 block">
                                                    <span class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-500">Upload gallery images</span>
                                                    <input name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-3 py-2 text-xs text-stone-700">
                                                </label>
                                            </div>
                                            @if ($inlineGalleryImages->isNotEmpty())
                                                <div id="package-inline-gallery-image-preview-{{ $product->id }}" class="package-inline-gallery-image-modal fixed inset-0 hidden items-center justify-center bg-stone-950/70 px-4 py-4" style="z-index: 20000;">
                                                    <div class="inline-flex flex-col rounded-[0.8rem] bg-white shadow-[0_24px_60px_rgba(15,23,42,0.28)]" data-package-gallery-image-panel style="width: fit-content; min-width: 0; max-width: calc(100vw - 8rem); height: calc(88vh - 50px);">
                                                        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                                                            <div>
                                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-400">Gallery image</p>
                                                                <p class="mt-1 text-sm font-semibold text-stone-800" data-package-gallery-image-title>{{ $product->name }}</p>
                                                            </div>
                                                            <button type="button" class="package-inline-gallery-image-close inline-flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 bg-white text-lg leading-none text-stone-500 transition hover:bg-stone-100" aria-label="Close gallery image preview">&times;</button>
                                                        </div>
                                                        <div class="flex flex-1 items-center justify-center overflow-hidden" style="width: fit-content; min-width: 0; max-width: 100%; padding: 30px; margin: 0 auto;">
                                                            <img src="" alt="" class="block h-full w-auto rounded-[0.4rem] object-contain" data-package-gallery-image-preview style="max-width: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                                <span
                                    class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}"
                                    data-transport-status-badge
                                >
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
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
                    <div class="flex flex-wrap items-center gap-2" @if ($isPackage) style="grid-column: 1 / -1;" @endif>
                        @if ($isPackage)
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 package-inline-edit-trigger" data-package-inline-edit>
                                Edit
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-sky-300 bg-sky-50 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 transition hover:bg-sky-100" data-package-inline-open="itinerary">
                                Add Itinerary
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-emerald-300 bg-emerald-50 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 transition hover:bg-emerald-100" data-package-inline-open="service-inclusions">
                                Add Service Inclusion
                            </button>
                            <button type="submit" form="{{ $inlineFormId }}" class="hidden min-w-[8.75rem] rounded-full bg-sky-600 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700 package-inline-save">
                                Save
                            </button>
                            <button type="button" class="hidden min-w-[8.75rem] rounded-full border border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 package-inline-cancel" data-package-inline-cancel>
                                Cancel
                            </button>
                            <div class="package-itinerary-modal hidden fixed inset-0 z-[1200] items-center justify-center overflow-y-auto bg-stone-950/55 px-8 py-6" data-package-itinerary-modal>
                                <div class="w-full max-w-5xl rounded-[2rem] border border-stone-200 bg-stone-100 p-5 shadow-[0_24px_60px_rgba(15,23,42,0.24)]">
                                    <form id="{{ $itineraryFormId }}" method="POST" action="{{ route('admin.products.itinerary', $product) }}" class="space-y-4" data-form-persist="admin-products-itinerary-{{ $product->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Package Itinerary</p>
                                                <h3 class="mt-1 text-2xl font-semibold text-stone-900">{{ $product->name }}</h3>
                                                <p class="mt-2 text-sm text-stone-500">Template rows are prepared automatically from the saved package duration: <span class="font-semibold text-stone-700">{{ $product->duration }}</span>.</p>
                                            </div>
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-package-itinerary-close>
                                                Close
                                            </button>
                                        </div>
                                        <div class="overflow-hidden rounded-[1.25rem] border border-stone-200 bg-white">
                                            <table class="min-w-full text-left text-sm">
                                                <thead class="bg-stone-100 text-stone-700">
                                                    <tr>
                                                        <th class="w-40 px-4 py-3 font-semibold">Day Number</th>
                                                        <th class="w-48 px-4 py-3 font-semibold">Time</th>
                                                        <th class="px-4 py-3 font-semibold">Activity</th>
                                                        <th class="px-4 py-3 font-semibold">Notes</th>
                                                        <th class="w-24 px-4 py-3 font-semibold text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-stone-200" data-package-itinerary-table-body>
                                                    @foreach ($packageItineraryGroups as $groupIndex => $dayGroup)
                                                        @php($dayLabel = $dayGroup->first()['day_number'] ?? 'Day '.($groupIndex + 1))
                                                        @php($dayGroupId = 'day-group-'.$product->id.'-'.$groupIndex)
                                                        @foreach ($dayGroup as $slotIndex => $row)
                                                            <tr data-itinerary-slot-row data-itinerary-day-group="{{ $dayGroupId }}">
                                                                @if ($slotIndex === 0)
                                                                    <td rowspan="{{ max(1, $dayGroup->count()) }}" class="px-4 py-3 align-top" data-itinerary-day-cell>
                                                                        <input type="text" value="{{ $dayLabel }}" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" data-itinerary-day-label>
                                                                        <div class="mt-3 flex justify-start">
                                                                            <button type="button" class="inline-flex items-center justify-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-rose-700 transition hover:bg-rose-100" data-package-itinerary-remove-day aria-label="Remove day row">
                                                                                Delete Row
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                                <td class="px-4 py-3 align-top">
                                                                    <div class="flex h-[96px] min-w-[11rem] items-stretch rounded-xl border border-stone-200 bg-stone-50 p-2.5" data-itinerary-time-slot>
                                                                        <input type="hidden" name="itinerary_day_number[]" value="{{ $dayLabel }}" data-itinerary-day-hidden>
                                                                        <select name="itinerary_time[]" class="h-full w-full min-w-[9.5rem] rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800">
                                                                            <option value="">Select time</option>
                                                                            @foreach ($itineraryTimeOptions as $timeOption)
                                                                                <option value="{{ $timeOption }}" @selected($row['time'] === $timeOption)>{{ $timeOption }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 align-top">
                                                                    <div class="flex h-[96px] items-stretch gap-2 rounded-xl border border-stone-200 bg-stone-50 p-2.5" data-itinerary-activity-slot>
                                                                        <textarea name="itinerary_activity[]" rows="2" class="h-full flex-1 resize-none rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800" placeholder="Arrival, transfer, check-in, and evening city walk">{{ $row['activity'] }}</textarea>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 align-top">
                                                                    <div class="flex h-[96px] items-stretch gap-2 rounded-xl border border-stone-200 bg-stone-50 p-2.5">
                                                                        <textarea name="itinerary_notes[]" rows="2" class="h-full flex-1 resize-none rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800" placeholder="Meeting point, what to bring, extra detail">{{ $row['notes'] ?? '' }}</textarea>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 align-top text-center">
                                                                    <div class="flex h-[96px] flex-col items-center justify-center gap-2">
                                                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-stone-300 bg-white text-lg leading-none text-stone-600 transition hover:border-sky-300 hover:text-sky-700" data-package-itinerary-add-slot aria-label="Add slot">
                                                                            <span class="leading-none">+</span>
                                                                        </button>
                                                                        @if ($slotIndex > 0)
                                                                            <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100" data-package-itinerary-remove-slot aria-label="Remove slot">
                                                                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                                                                    <path fill-rule="evenodd" d="M8.5 2.5A1.5 1.5 0 0 0 7 4v.5H4.75a.75.75 0 0 0 0 1.5h.538l.813 9.21A2 2 0 0 0 8.094 17h3.812a2 2 0 0 0 1.993-1.79l.813-9.21h.538a.75.75 0 0 0 0-1.5H13V4a1.5 1.5 0 0 0-1.5-1.5h-3ZM11.5 4v.5h-3V4h3Z" clip-rule="evenodd" />
                                                                                </svg>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <template data-package-itinerary-day-template>
                                            <tr data-itinerary-slot-row>
                                                <td rowspan="1" class="px-4 py-3 align-top" data-itinerary-day-cell>
                                                    <input type="text" value="" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" data-itinerary-day-label>
                                                    <div class="mt-3 flex justify-start">
                                                        <button type="button" class="inline-flex items-center justify-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-rose-700 transition hover:bg-rose-100" data-package-itinerary-remove-day aria-label="Remove day row">
                                                            Delete Row
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 align-top">
                                                    <div class="flex h-[96px] min-w-[11rem] items-stretch rounded-xl border border-stone-200 bg-stone-50 p-2.5" data-itinerary-time-slot>
                                                        <input type="hidden" name="itinerary_day_number[]" value="" data-itinerary-day-hidden>
                                                        <select name="itinerary_time[]" class="h-full w-full min-w-[9.5rem] rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800">
                                                            <option value="">Select time</option>
                                                            @foreach ($itineraryTimeOptions as $timeOption)
                                                                <option value="{{ $timeOption }}">{{ $timeOption }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 align-top">
                                                    <div class="flex h-[96px] items-stretch gap-2 rounded-xl border border-stone-200 bg-stone-50 p-2.5" data-itinerary-activity-slot>
                                                        <textarea name="itinerary_activity[]" rows="2" class="h-full flex-1 resize-none rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800" placeholder="Arrival, transfer, check-in, and evening city walk"></textarea>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 align-top">
                                                    <div class="flex h-[96px] items-stretch gap-2 rounded-xl border border-stone-200 bg-stone-50 p-2.5">
                                                        <textarea name="itinerary_notes[]" rows="2" class="h-full flex-1 resize-none rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800" placeholder="Meeting point, what to bring, extra detail"></textarea>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 align-top text-center">
                                                    <div class="flex h-[96px] flex-col items-center justify-center gap-2">
                                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-stone-300 bg-white text-lg leading-none text-stone-600 transition hover:border-sky-300 hover:text-sky-700" data-package-itinerary-add-slot aria-label="Add slot">
                                                            <span class="leading-none">+</span>
                                                        </button>
                                                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100" data-package-itinerary-remove-slot aria-label="Remove slot">
                                                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M8.5 2.5A1.5 1.5 0 0 0 7 4v.5H4.75a.75.75 0 0 0 0 1.5h.538l.813 9.21A2 2 0 0 0 8.094 17h3.812a2 2 0 0 0 1.993-1.79l.813-9.21h.538a.75.75 0 0 0 0-1.5H13V4a1.5 1.5 0 0 0-1.5-1.5h-3ZM11.5 4v.5h-3V4h3Z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <div class="flex justify-between gap-3">
                                            <button type="button" class="rounded-full border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700 transition hover:bg-emerald-100" data-package-itinerary-add-day>
                                                + Day
                                            </button>
                                            <div class="flex justify-end gap-3">
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-stone-700 transition hover:bg-stone-100" data-package-itinerary-close>
                                                Cancel
                                            </button>
                                            <button type="submit" class="rounded-full bg-sky-600 px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white transition hover:bg-sky-700">
                                                Save Itinerary
                                            </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="package-service-inclusion-modal hidden fixed inset-0 z-[410] items-center justify-center overflow-y-auto bg-stone-950/55 px-8 py-6" data-package-service-inclusion-modal>
                                <div class="w-full max-w-4xl rounded-[2rem] border border-stone-200 bg-stone-100 p-5 shadow-[0_24px_60px_rgba(15,23,42,0.24)]">
                                    <form id="{{ $serviceInclusionFormId }}" method="POST" action="{{ route('admin.products.service-inclusions', $product) }}" class="space-y-4" data-form-persist="admin-products-service-inclusions-{{ $product->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Service Inclusion</p>
                                                <h3 class="mt-1 text-2xl font-semibold text-stone-900">{{ $product->name }}</h3>
                                                <p class="mt-2 text-sm text-stone-500">Add label/value rows for the package inclusion table.</p>
                                            </div>
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-package-service-inclusion-close>
                                                Close
                                            </button>
                                        </div>
                                        <div class="overflow-hidden rounded-[1.25rem] border border-stone-200 bg-white">
                                            <table class="min-w-full text-left text-sm">
                                                <thead class="bg-stone-100 text-stone-700">
                                                    <tr>
                                                        <th class="w-52 px-4 py-3 font-semibold">Label</th>
                                                        <th class="px-4 py-3 font-semibold">Description</th>
                                                        <th class="w-16 px-4 py-3 text-center font-semibold">Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody data-service-inclusion-table-body class="divide-y divide-stone-200">
                                                    @foreach ($packageServiceInclusionRows as $row)
                                                        <tr data-service-inclusion-row>
                                                            <td class="px-4 py-3 align-top">
                                                                <input name="service_inclusion_label[]" type="text" value="{{ $row['label'] }}" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="Meals">
                                                            </td>
                                                            <td class="px-4 py-3 align-top">
                                                                <textarea name="service_inclusion_value[]" rows="2" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="Breakfast included daily.">{{ $row['value'] }}</textarea>
                                                            </td>
                                                            <td class="px-4 py-3 align-top text-center">
                                                                <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100" data-service-inclusion-remove-row aria-label="Remove service inclusion row">
                                                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                                                        <path fill-rule="evenodd" d="M8.5 2.5A1.5 1.5 0 0 0 7 4v.5H4.75a.75.75 0 0 0 0 1.5h.538l.813 9.21A2 2 0 0 0 8.094 17h3.812a2 2 0 0 0 1.993-1.79l.813-9.21h.538a.75.75 0 0 0 0-1.5H13V4a1.5 1.5 0 0 0-1.5-1.5h-3ZM11.5 4v.5h-3V4h3Z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <template data-service-inclusion-row-template>
                                            <tr data-service-inclusion-row>
                                                <td class="px-4 py-3 align-top">
                                                    <input name="service_inclusion_label[]" type="text" value="" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="Label">
                                                </td>
                                                <td class="px-4 py-3 align-top">
                                                    <textarea name="service_inclusion_value[]" rows="2" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="Description"></textarea>
                                                </td>
                                                <td class="px-4 py-3 align-top text-center">
                                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100" data-service-inclusion-remove-row aria-label="Remove service inclusion row">
                                                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M8.5 2.5A1.5 1.5 0 0 0 7 4v.5H4.75a.75.75 0 0 0 0 1.5h.538l.813 9.21A2 2 0 0 0 8.094 17h3.812a2 2 0 0 0 1.993-1.79l.813-9.21h.538a.75.75 0 0 0 0-1.5H13V4a1.5 1.5 0 0 0-1.5-1.5h-3ZM11.5 4v.5h-3V4h3Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <div class="flex justify-between gap-3">
                                            <button type="button" class="rounded-full border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700 transition hover:bg-emerald-100" data-service-inclusion-add-row>
                                                Add Row
                                            </button>
                                            <div class="flex gap-3">
                                                <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-stone-700 transition hover:bg-stone-100" data-package-service-inclusion-close>
                                                    Cancel
                                                </button>
                                                <button type="submit" class="rounded-full bg-sky-600 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white transition hover:bg-sky-700">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.products.active', $product) }}" class="w-full" data-transport-active-form>
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}" data-transport-active-input>
                                <div class="flex items-center justify-between gap-2 rounded-2xl border border-stone-200 bg-white px-3 py-2">
                                    <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Active</span>
                                    <button
                                        type="submit"
                                        class="inline-flex min-w-[3.25rem] items-center justify-center rounded-full border border-sky-300 bg-sky-50 px-2 py-1.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-sky-700 transition hover:bg-sky-100"
                                        aria-label="{{ $product->is_active ? 'Deactivate transport listing' : 'Activate transport listing' }}"
                                        data-transport-active-button
                                    >
                                        <span data-transport-active-label>{{ $product->is_active ? 'On' : 'Off' }}</span>
                                    </button>
                                </div>
                            </form>
                            <button type="button" class="w-full rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100" data-transport-edit-open>
                                Edit
                            </button>
                            <div class="fixed inset-0 z-[410] hidden items-center justify-center overflow-y-auto bg-stone-950/55 px-8 py-6" data-transport-edit-modal>
                                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="w-full max-w-5xl space-y-3 rounded-2xl border border-stone-200 bg-white p-4 shadow-[0_18px_40px_rgba(15,23,42,0.16)]" data-form-persist="admin-products-floating-update-{{ $product->id }}">
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
                                            <div class="mt-2">
                                                <label class="mb-1 block text-[10px] font-medium uppercase tracking-[0.18em] text-stone-500">Replace main image</label>
                                                <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-2 py-2 text-[10px] text-stone-700">
                                            </div>

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
                                                <div class="mt-2">
                                                    <label class="mb-1 block text-[10px] font-medium uppercase tracking-[0.18em] text-stone-500">Upload gallery images</label>
                                                    <input name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 px-2 py-2 text-[10px] text-stone-700">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid gap-3">
                                            <div>
                                                <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Summary</label>
                                                <input name="summary" type="text" value="{{ $product->summary }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
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
                                    <input type="hidden" name="is_active" value="{{ $product->is_active ? 1 : 0 }}">
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
                                <div class="flex flex-wrap justify-end gap-3">
                                    @if ($product->category !== 'package')
                                        <button
                                            type="button"
                                            class="rounded-full border border-stone-300 bg-white px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100"
                                            data-transport-edit-close
                                        >
                                            Cancel
                                        </button>
                                    @endif
                                    <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700 {{ $product->category !== 'package' ? 'md:w-auto md:min-w-[9rem]' : '' }}">
                                        Update Product
                                    </button>
                                </div>
                                </form>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');" class="{{ $isPackage ? '' : 'w-full' }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="min-w-[8.75rem] rounded-full border border-rose-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50 {{ $isPackage ? '' : 'w-full' }}">
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
            const itineraryButton = row.querySelector('[data-package-inline-open="itinerary"]');
            const serviceInclusionButton = row.querySelector('[data-package-inline-open="service-inclusions"]');
            const cancelButton = row.querySelector('[data-package-inline-cancel]');
            const saveButton = row.querySelector('.package-inline-save');
            const itineraryModal = row.querySelector('[data-package-itinerary-modal]');
            const itineraryCloseButtons = row.querySelectorAll('[data-package-itinerary-close]');
            const itineraryTableBody = row.querySelector('[data-package-itinerary-table-body]');
            const itineraryDayTemplate = row.querySelector('[data-package-itinerary-day-template]');
            const itineraryAddDayButton = row.querySelector('[data-package-itinerary-add-day]');
            const serviceInclusionModal = row.querySelector('[data-package-service-inclusion-modal]');
            const serviceInclusionCloseButtons = row.querySelectorAll('[data-package-service-inclusion-close]');
            const serviceInclusionTableBody = row.querySelector('[data-service-inclusion-table-body]');
            const serviceInclusionRowTemplate = row.querySelector('[data-service-inclusion-row-template]');
            const serviceInclusionAddRowButton = row.querySelector('[data-service-inclusion-add-row]');

            if (!form || !viewSection || !editSection || !editButton || !cancelButton || !saveButton) {
                return;
            }

            const resetForm = () => {
                form.reset();
            };

            const setRowOverlayState = (isActive) => {
                row.style.zIndex = isActive ? '350' : '';
            };

            const syncRowOverlayState = () => {
                const isEditOpen = !editSection.classList.contains('hidden');
                const isItineraryOpen = itineraryModal && !itineraryModal.classList.contains('hidden');
                const isServiceInclusionOpen = serviceInclusionModal && !serviceInclusionModal.classList.contains('hidden');

                setRowOverlayState(isEditOpen || isItineraryOpen || isServiceInclusionOpen);
            };

            const updateEditPosition = () => {
                if (!editSection || !editPanel) {
                    return;
                }

                const headerOffset = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--app-header-offset')) || 0;
                const availableHeight = Math.max(420, window.innerHeight - headerOffset - 64);

                editPanel.style.maxHeight = `${availableHeight}px`;
            };

            editButton.addEventListener('click', () => {
                updateEditPosition();
                setRowOverlayState(true);
                editSection.style.zIndex = '9999';
                editPanel.style.position = 'relative';
                editPanel.style.zIndex = '10000';
                viewSection.classList.add('hidden');
                editSection.classList.remove('hidden');
                editSection.classList.add('flex');
                editButton.classList.add('hidden');
            });

            itineraryButton?.addEventListener('click', () => {
                setRowOverlayState(true);
                itineraryModal?.classList.remove('hidden');
                itineraryModal?.classList.add('flex');
            });

            itineraryCloseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    itineraryModal?.classList.add('hidden');
                    itineraryModal?.classList.remove('flex');
                    syncRowOverlayState();
                });
            });

            itineraryModal?.addEventListener('click', (event) => {
                if (event.target !== itineraryModal) {
                    return;
                }

                itineraryModal.classList.add('hidden');
                itineraryModal.classList.remove('flex');
                syncRowOverlayState();
            });

            serviceInclusionButton?.addEventListener('click', () => {
                setRowOverlayState(true);
                serviceInclusionModal?.classList.remove('hidden');
                serviceInclusionModal?.classList.add('flex');
            });

            serviceInclusionCloseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    serviceInclusionModal?.classList.add('hidden');
                    serviceInclusionModal?.classList.remove('flex');
                    syncRowOverlayState();
                });
            });

            serviceInclusionModal?.addEventListener('click', (event) => {
                if (event.target !== serviceInclusionModal) {
                    return;
                }

                serviceInclusionModal.classList.add('hidden');
                serviceInclusionModal.classList.remove('flex');
                syncRowOverlayState();
            });

            serviceInclusionAddRowButton?.addEventListener('click', () => {
                const templateContent = serviceInclusionRowTemplate?.content?.cloneNode(true);

                if (!templateContent || !serviceInclusionTableBody) {
                    return;
                }

                serviceInclusionTableBody.appendChild(templateContent);
            });

            serviceInclusionTableBody?.addEventListener('click', (event) => {
                const removeRowButton = event.target.closest('[data-service-inclusion-remove-row]');

                if (!removeRowButton) {
                    return;
                }

                removeRowButton.closest('[data-service-inclusion-row]')?.remove();
            });

            const syncItineraryDayValues = (slotRow) => {
                const dayGroupId = slotRow?.dataset.itineraryDayGroup;
                const dayLabelInput = itineraryTableBody?.querySelector(`[data-itinerary-day-group="${dayGroupId}"] [data-itinerary-day-label]`);
                const hiddenDayInputs = itineraryTableBody?.querySelectorAll(`[data-itinerary-day-group="${dayGroupId}"] [data-itinerary-day-hidden]`);

                hiddenDayInputs?.forEach((input) => {
                    input.value = dayLabelInput?.value ?? '';
                });
            };

            const createSlotRow = (dayLabel, dayGroupId, options = {}) => {
                const { includeDayCell = false, includeRemoveButton = true } = options;
                const templateContent = itineraryDayTemplate?.content?.cloneNode(true);
                const slotRow = templateContent?.querySelector('[data-itinerary-slot-row]');

                if (!slotRow) {
                    return null;
                }

                slotRow.dataset.itineraryDayGroup = dayGroupId;

                const dayCell = slotRow.querySelector('[data-itinerary-day-cell]');
                const dayLabelInput = slotRow.querySelector('[data-itinerary-day-label]');
                const hiddenDayInput = slotRow.querySelector('[data-itinerary-day-hidden]');
                const timeInput = slotRow.querySelector('select[name="itinerary_time[]"]');
                const activityInput = slotRow.querySelector('textarea[name="itinerary_activity[]"]');
                const removeButton = slotRow.querySelector('[data-package-itinerary-remove-slot]');

                if (!includeDayCell) {
                    dayCell?.remove();
                } else {
                    dayCell?.setAttribute('rowspan', '1');
                    if (dayLabelInput) {
                        dayLabelInput.value = dayLabel;
                    }
                }

                if (!includeRemoveButton) {
                    removeButton?.remove();
                }

                if (hiddenDayInput) {
                    hiddenDayInput.value = dayLabel;
                }

                if (timeInput) {
                    timeInput.value = '';
                }

                if (activityInput) {
                    activityInput.value = '';
                }

                return {
                    slotRow,
                    dayLabelInput,
                    timeInput,
                };
            };

            itineraryTableBody?.querySelectorAll('[data-itinerary-slot-row]').forEach((slotRow) => {
                if (slotRow.querySelector('[data-itinerary-day-label]')) {
                    syncItineraryDayValues(slotRow);
                }
            });

            itineraryAddDayButton?.addEventListener('click', () => {
                if (!itineraryTableBody) {
                    return;
                }

                const dayCells = itineraryTableBody.querySelectorAll('[data-itinerary-day-cell]');
                const nextDayNumber = dayCells.length + 1;
                const dayGroupId = `day-group-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
                const nextDayRow = createSlotRow(`Day ${nextDayNumber}`, dayGroupId, {
                    includeDayCell: true,
                    includeRemoveButton: false,
                });

                if (!nextDayRow) {
                    return;
                }

                itineraryTableBody.appendChild(nextDayRow.slotRow);
                nextDayRow.dayLabelInput?.focus();
            });

            itineraryTableBody?.addEventListener('input', (event) => {
                const dayLabelInput = event.target.closest('[data-itinerary-day-label]');

                if (!dayLabelInput) {
                    return;
                }

                const slotRow = dayLabelInput.closest('[data-itinerary-slot-row]');
                syncItineraryDayValues(slotRow);
            });

            itineraryTableBody?.addEventListener('click', (event) => {
                const addSlotButton = event.target.closest('[data-package-itinerary-add-slot]');
                const removeSlotButton = event.target.closest('[data-package-itinerary-remove-slot]');
                const removeDayButton = event.target.closest('[data-package-itinerary-remove-day]');

                if (addSlotButton) {
                    const currentRow = addSlotButton.closest('[data-itinerary-slot-row]');
                    const dayGroupId = currentRow?.dataset.itineraryDayGroup ?? '';
                    const dayLabel = itineraryTableBody?.querySelector(`[data-itinerary-day-group="${dayGroupId}"] [data-itinerary-day-label]`)?.value ?? '';
                    const groupRows = Array.from(itineraryTableBody?.querySelectorAll(`[data-itinerary-day-group="${dayGroupId}"]`) ?? []);
                    const lastRow = groupRows[groupRows.length - 1];
                    const dayCell = itineraryTableBody?.querySelector(`[data-itinerary-day-group="${dayGroupId}"] [data-itinerary-day-cell]`);
                    const newSlotRow = createSlotRow(dayLabel, dayGroupId, {
                        includeDayCell: false,
                        includeRemoveButton: true,
                    });

                    if (!currentRow || !lastRow || !dayCell || !newSlotRow) {
                        return;
                    }

                    lastRow.insertAdjacentElement('afterend', newSlotRow.slotRow);
                    const currentRowspan = parseInt(dayCell.getAttribute('rowspan') || '1', 10);
                    dayCell.setAttribute('rowspan', String(currentRowspan + 1));
                    newSlotRow.timeInput?.focus();
                    return;
                }

                if (removeSlotButton) {
                    const currentRow = removeSlotButton.closest('[data-itinerary-slot-row]');
                    const dayGroupId = currentRow?.dataset.itineraryDayGroup ?? '';
                    const groupRows = Array.from(itineraryTableBody?.querySelectorAll(`[data-itinerary-day-group="${dayGroupId}"]`) ?? []);
                    const dayCell = itineraryTableBody?.querySelector(`[data-itinerary-day-group="${dayGroupId}"] [data-itinerary-day-cell]`);

                    if (!currentRow || !groupRows.length || !dayCell) {
                        return;
                    }

                    const currentRowspan = parseInt(dayCell.getAttribute('rowspan') || '1', 10);
                    const isFirstRow = currentRow.contains(dayCell);

                    if (isFirstRow && groupRows.length > 1) {
                        groupRows[1].insertAdjacentElement('afterbegin', dayCell);
                    }

                    currentRow.remove();
                    dayCell.setAttribute('rowspan', String(Math.max(1, currentRowspan - 1)));
                    return;
                }

                if (removeDayButton) {
                    const currentRow = removeDayButton.closest('[data-itinerary-slot-row]');
                    const dayGroupId = currentRow?.dataset.itineraryDayGroup ?? '';
                    const groupRows = Array.from(itineraryTableBody?.querySelectorAll(`[data-itinerary-day-group="${dayGroupId}"]`) ?? []);

                    if (!groupRows.length) {
                        return;
                    }

                    groupRows.forEach((groupRow) => groupRow.remove());
                }
            });

            cancelButton.addEventListener('click', () => {
                resetForm();
                editSection.style.zIndex = '';
                editPanel.style.position = '';
                editPanel.style.zIndex = '';
                viewSection.classList.remove('hidden');
                editSection.classList.add('hidden');
                editSection.classList.remove('flex');
                editButton.classList.remove('hidden');
                syncRowOverlayState();
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

        document.addEventListener('codex:form-draft-restored', (event) => {
            const restoredFormId = event.detail?.formId;

            if (!restoredFormId) {
                return;
            }

            const restoredForm = document.getElementById(restoredFormId);

            if (!restoredForm?.matches('[data-package-inline-form]')) {
                return;
            }

            const row = restoredForm.closest('[data-package-inline-row]');
            const editButton = row?.querySelector('[data-package-inline-edit]');
            const editSection = row?.querySelector('.package-inline-edit');

            if (!row || !editButton || !editSection || !editSection.classList.contains('hidden')) {
                return;
            }

            editButton.click();
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

        document.querySelectorAll('.package-inline-gallery-preview-open').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                const row = button.closest('[data-package-inline-row]');
                const modal = document.getElementById(button.dataset.galleryPreviewModal || '');
                const galleryModal = button.closest('.package-inline-gallery-modal');
                const panel = modal?.querySelector('[data-package-gallery-image-panel]');
                const image = modal?.querySelector('[data-package-gallery-image-preview]');
                const title = modal?.querySelector('[data-package-gallery-image-title]');
                const imageSrc = button.dataset.galleryPreviewSrc;
                const imageTitle = button.dataset.galleryPreviewName;

                const syncGalleryImageModalWidth = () => {
                    if (!modal || !panel || !image || !image.naturalWidth || !image.naturalHeight) {
                        return;
                    }

                    panel.style.width = 'fit-content';

                    requestAnimationFrame(() => {
                        const header = panel.firstElementChild;
                        const panelHeight = panel.clientHeight;
                        const headerHeight = header instanceof HTMLElement ? header.offsetHeight : 0;
                        const bodyVerticalPadding = 60;
                        const bodyHorizontalPadding = 60;
                        const availableImageHeight = Math.max(120, panelHeight - headerHeight - bodyVerticalPadding);
                        const imageAspectRatio = image.naturalWidth / image.naturalHeight;
                        const targetImageWidth = availableImageHeight * imageAspectRatio;
                        const viewportWidthCap = window.innerWidth - 80;
                        const panelWidth = Math.min(targetImageWidth + bodyHorizontalPadding, viewportWidthCap);

                        panel.style.width = `${panelWidth}px`;
                    });
                };

                if (!row || !modal || !panel || !image || !imageSrc) {
                    return;
                }

                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                modal.style.zIndex = '20000';
                panel.style.zIndex = '20001';

                if (galleryModal instanceof HTMLElement) {
                    galleryModal.style.zIndex = '1200';
                }

                image.src = imageSrc;
                image.alt = imageTitle ? `${imageTitle} gallery image` : 'Gallery image';

                if (title && imageTitle) {
                    title.textContent = imageTitle;
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                if (image.complete) {
                    syncGalleryImageModalWidth();
                } else {
                    image.addEventListener('load', syncGalleryImageModalWidth, { once: true });
                }
            });
        });

        document.querySelectorAll('.package-inline-gallery-image-close').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = button.closest('.package-inline-gallery-image-modal');
                const galleryModal = document.querySelector('.package-inline-gallery-modal.flex');

                if (galleryModal instanceof HTMLElement) {
                    galleryModal.style.zIndex = '';
                }

                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            });
        });

        document.querySelectorAll('.package-inline-gallery-image-modal').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target !== modal) {
                    return;
                }

                const galleryModal = document.querySelector('.package-inline-gallery-modal.flex');

                if (galleryModal instanceof HTMLElement) {
                    galleryModal.style.zIndex = '';
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });

        document.querySelectorAll('.package-inline-main-image-open').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.inlineMainImageTarget);
                const panel = modal?.querySelector('[data-package-main-image-panel]');
                const image = modal?.querySelector('[data-package-main-image-preview]');

                const syncMainImageModalWidth = () => {
                    if (!modal || !panel || !image || !image.naturalWidth || !image.naturalHeight) {
                        return;
                    }

                    panel.style.width = 'fit-content';

                    requestAnimationFrame(() => {
                        const header = panel.firstElementChild;
                        const panelHeight = panel.clientHeight;
                        const headerHeight = header instanceof HTMLElement ? header.offsetHeight : 0;
                        const bodyVerticalPadding = 60;
                        const bodyHorizontalPadding = 60;
                        const availableImageHeight = Math.max(120, panelHeight - headerHeight - bodyVerticalPadding);
                        const imageAspectRatio = image.naturalWidth / image.naturalHeight;
                        const targetImageWidth = availableImageHeight * imageAspectRatio;
                        const viewportWidthCap = window.innerWidth - 80;
                        const panelWidth = Math.min(targetImageWidth + bodyHorizontalPadding, viewportWidthCap);

                        panel.style.width = `${panelWidth}px`;
                    });
                };

                modal?.classList.remove('hidden');
                modal?.classList.add('flex');

                if (image?.complete) {
                    syncMainImageModalWidth();
                } else {
                    image?.addEventListener('load', syncMainImageModalWidth, { once: true });
                }
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

        document.querySelectorAll('[data-transport-active-form]').forEach((form) => {
            const input = form.querySelector('[data-transport-active-input]');
            const button = form.querySelector('[data-transport-active-button]');
            const label = form.querySelector('[data-transport-active-label]');
            const statusBadge = form.closest('[data-admin-transport-item]')?.querySelector('[data-transport-status-badge]');

            if (!input || !button || !label) {
                return;
            }

            const applyState = (isActive) => {
                input.value = isActive ? '0' : '1';
                label.textContent = isActive ? 'On' : 'Off';
                button.setAttribute('aria-label', isActive ? 'Deactivate transport listing' : 'Activate transport listing');

                if (statusBadge) {
                    statusBadge.textContent = isActive ? 'Active' : 'Inactive';
                    statusBadge.classList.toggle('text-emerald-700', isActive);
                    statusBadge.classList.toggle('text-stone-500', !isActive);
                }
            };

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const nextValue = input.value === '1';
                const formData = new FormData(form);
                button.disabled = true;
                button.classList.add('opacity-70');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        throw new Error('Toggle request failed.');
                    }

                    const data = await response.json();
                    applyState(Boolean(data.is_active ?? nextValue));
                } catch (error) {
                    form.submit();
                    return;
                } finally {
                    button.disabled = false;
                    button.classList.remove('opacity-70');
                }
            });
        });

        document.querySelectorAll('[data-admin-transport-item]').forEach((item) => {
            const openButton = item.querySelector('[data-transport-edit-open]');
            const modal = item.querySelector('[data-transport-edit-modal]');
            const closeButtons = item.querySelectorAll('[data-transport-edit-close]');

            if (!openButton || !modal) {
                return;
            }

            openButton.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });

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
