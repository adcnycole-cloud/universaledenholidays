<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
    @csrf
    <input type="hidden" name="category" value="{{ $category }}">
    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label for="{{ $category }}_name" class="mb-2 block text-sm font-medium text-stone-700">{{ $title }} name</label>
            <input id="{{ $category }}_name" name="name" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_location" class="mb-2 block text-sm font-medium text-stone-700">Location</label>
            <input id="{{ $category }}_location" name="location" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
    </div>
    @if ($category === 'package')
        <div class="grid gap-4 lg:grid-cols-[1.2fr_1fr]">
            <div>
                <label for="{{ $category }}_summary" class="mb-2 block text-sm font-medium text-stone-700">Summary</label>
                <input id="{{ $category }}_summary" name="summary" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
            <div>
                <label for="{{ $category }}_image" class="mb-2 block text-sm font-medium text-stone-700">Upload main image</label>
                <input id="{{ $category }}_image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-stone-700">
            </div>
        </div>
    @else
        <div class="grid gap-4 lg:grid-cols-[1.3fr_1fr]">
            <div>
                <label for="{{ $category }}_summary" class="mb-2 block text-sm font-medium text-stone-700">Summary</label>
                <input id="{{ $category }}_summary" name="summary" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
            <div>
                <label for="{{ $category }}_image_url" class="mb-2 block text-sm font-medium text-stone-700">Image URL</label>
                <input id="{{ $category }}_image_url" name="image_url" type="url" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
        </div>
    @endif
    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label for="{{ $category }}_duration" class="mb-2 block text-sm font-medium text-stone-700">Duration</label>
            <input id="{{ $category }}_duration" name="duration" type="text" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_capacity" class="mb-2 block text-sm font-medium text-stone-700">Capacity</label>
            <input id="{{ $category }}_capacity" name="capacity" type="number" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
    </div>
    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label for="{{ $category }}_malaysia_adult_price_myr" class="mb-2 block text-sm font-medium text-stone-700">Malaysia adult price</label>
            <input id="{{ $category }}_malaysia_adult_price_myr" name="malaysia_adult_price_myr" type="number" step="0.01" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_malaysia_child_price_myr" class="mb-2 block text-sm font-medium text-stone-700">Malaysia child price</label>
            <input id="{{ $category }}_malaysia_child_price_myr" name="malaysia_child_price_myr" type="number" step="0.01" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_international_adult_price_myr" class="mb-2 block text-sm font-medium text-stone-700">International adult price</label>
            <input id="{{ $category }}_international_adult_price_myr" name="international_adult_price_myr" type="number" step="0.01" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_international_child_price_myr" class="mb-2 block text-sm font-medium text-stone-700">International child price</label>
            <input id="{{ $category }}_international_child_price_myr" name="international_child_price_myr" type="number" step="0.01" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
    </div>
    <div class="grid gap-4 lg:grid-cols-[1fr_1.2fr]">
        <div>
            @if ($category === 'package')
                <label for="{{ $category }}_gallery_image_files" class="mb-2 block text-sm font-medium text-stone-700">Upload gallery images</label>
                <input id="{{ $category }}_gallery_image_files" name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-stone-700">
            @else
                <label for="{{ $category }}_gallery_images" class="mb-2 block text-sm font-medium text-stone-700">Gallery image URLs</label>
                <textarea id="{{ $category }}_gallery_images" name="gallery_images" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" placeholder="One image URL per line"></textarea>
            @endif
        </div>
        <div>
            <label for="{{ $category }}_description" class="mb-2 block text-sm font-medium text-stone-700">Description</label>
            <textarea id="{{ $category }}_description" name="description" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800"></textarea>
        </div>
    </div>
    <div class="flex flex-wrap gap-6 pt-1">
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="is_featured" value="1" class="rounded border-stone-300">
            Featured product
        </label>
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="is_top_choice" value="1" class="rounded border-stone-300">
            Top choice
        </label>
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="is_discounted" value="1" class="rounded border-stone-300">
            Discount
        </label>
        <div class="flex items-center gap-2 text-sm text-stone-600">
            <label for="{{ $category }}_discount_percentage">Discount %</label>
            <input id="{{ $category }}_discount_percentage" name="discount_percentage" type="number" step="0.01" min="0" max="100" class="w-24 rounded-2xl border border-stone-300 px-3 py-2 text-stone-800">
        </div>
    </div>
    <button type="submit" class="w-full rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">Save {{ $title }}</button>
</form>
