@php($editable = $editable ?? false)
@php($wrapperId = $wrapperId ?? null)
@php($itemAttribute = $itemAttribute ?? null)

<div @if($wrapperId) id="{{ $wrapperId }}" @endif class="mt-6 space-y-4">
    @forelse ($products as $product)
        <article @if($itemAttribute) {{ $itemAttribute }}="true" @endif class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h4 class="text-xl font-semibold text-stone-900">{{ $product->name }}</h4>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}">
                            {{ $product->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-stone-500">{{ $product->location }} · {{ $product->duration }}</p>
                    <p class="mt-3 text-sm leading-6 text-stone-600">{{ $product->summary }}</p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em]">
                        <span class="rounded-full bg-white px-3 py-1 text-stone-600">RM {{ number_format((float) $product->malaysia_adult_price_myr, 2) }}</span>
                        @if ($product->capacity)
                            <span class="rounded-full bg-white px-3 py-1 text-stone-600">Capacity {{ $product->capacity }}</span>
                        @endif
                        <span class="rounded-full bg-white px-3 py-1 text-stone-600">{{ $product->is_top_choice ? 'Top choice' : ($product->is_featured ? 'Featured' : 'Standard') }}</span>
                    </div>
                </div>

                @if ($editable)
                    <div class="flex flex-wrap gap-2">
                        <details class="group">
                            <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                <span class="group-open:hidden">Edit</span>
                                <span class="hidden group-open:inline">Close</span>
                            </summary>
                            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="mt-4 w-full min-w-[320px] space-y-3 rounded-2xl border border-stone-200 bg-white p-4 lg:min-w-[620px]">
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
                                <div class="grid gap-3 md:grid-cols-[1.2fr_1fr]">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Summary</label>
                                        <input name="summary" type="text" value="{{ $product->summary }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        @if ($product->category === 'tour')
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Tour image upload</label>
                                            <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-700">
                                            @if ($product->image_url)
                                                <p class="mt-2 text-xs text-stone-500">Current image is already saved. Upload a new file to replace it.</p>
                                            @endif
                                        @else
                                            <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Image URL</label>
                                            <input name="image_url" type="url" value="{{ $product->image_url }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                        @endif
                                    </div>
                                </div>
                                <div class="grid gap-3 md:grid-cols-3">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Duration</label>
                                        <input name="duration" type="text" value="{{ $product->duration }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Price (MYR)</label>
                                        <input name="price_myr" type="number" step="0.01" value="{{ $product->price_myr }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Capacity</label>
                                        <input name="capacity" type="number" value="{{ $product->capacity }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">
                                    </div>
                                </div>
                                <div class="grid gap-3 md:grid-cols-2">
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
                                <div class="grid gap-3 md:grid-cols-[1fr_1.2fr]">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Gallery image URLs</label>
                                        <textarea name="gallery_images" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">@if(is_array($product->gallery_images)){{ implode("\n", $product->gallery_images) }}@endif</textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Description</label>
                                        <textarea name="description" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm text-stone-800">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-5 pt-1">
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
                                <button type="submit" class="w-full rounded-full bg-sky-600 px-4 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700">
                                    Update Product
                                </button>
                            </form>
                        </details>

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">
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
