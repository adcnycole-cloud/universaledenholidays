<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4" data-form-persist="admin-products-create-{{ $category }}">
    @csrf
    <input type="hidden" name="category" value="{{ $category }}">
    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label for="{{ $category }}_name" class="mb-2 block text-sm font-medium text-stone-700">{{ $title }} name</label>
            <input id="{{ $category }}_name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        @if ($category === 'package')
            <div>
                <label for="{{ $category }}_package_type" class="mb-2 block text-sm font-medium text-stone-700">Package Type</label>
                <select id="{{ $category }}_package_type" name="package_type" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" data-package-type-select>
                    <option value="">Select package type</option>
                    <option value="Day Trip" @selected(old('package_type') === 'Day Trip')>Day Trip</option>
                    <option value="2D1N" @selected(old('package_type') === '2D1N')>2D1N</option>
                    <option value="3D2N" @selected(old('package_type') === '3D2N')>3D2N</option>
                    <option value="4D3N" @selected(old('package_type') === '4D3N')>4D3N</option>
                </select>
            </div>
        @else
            <div>
                <label for="{{ $category }}_location" class="mb-2 block text-sm font-medium text-stone-700">Location</label>
                <input id="{{ $category }}_location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
        @endif
    </div>
    @if ($category === 'package')
        <div class="grid gap-4 lg:grid-cols-[1.2fr_1fr]">
            <div>
                <label for="{{ $category }}_summary" class="mb-2 block text-sm font-medium text-stone-700">Summary</label>
                <input id="{{ $category }}_summary" name="summary" type="text" value="{{ old('summary') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
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
                <input id="{{ $category }}_summary" name="summary" type="text" value="{{ old('summary') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
            <div>
                <label for="{{ $category }}_image" class="mb-2 block text-sm font-medium text-stone-700">Upload main image</label>
                <input id="{{ $category }}_image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-stone-700">
            </div>
        </div>
    @endif
    @if ($category === 'package')
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 xl:items-start">
            <div>
                <label for="{{ $category }}_location" class="mb-2 block text-sm font-medium text-stone-700">Location</label>
                <input id="{{ $category }}_location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
            <div>
                <label for="{{ $category }}_tour_code" class="mb-2 block text-sm font-medium text-stone-700">Tour Code</label>
                <input id="{{ $category }}_tour_code" name="tour_code" type="text" value="{{ old('tour_code') }}" placeholder="{{ old('package_type') === 'Day Trip' ? 'DT-UEH01' : 'OT-UEH01' }}" class="w-full rounded-2xl border border-stone-300 px-3 py-2.5 text-sm text-stone-800" data-tour-code-input>
                <p class="mt-2 text-xs text-stone-500">Saved as `DT-UEH...` for day trips and `OT-UEH...` for overnight trips.</p>
            </div>
            <div>
                <label for="{{ $category }}_duration_detail" class="mb-2 block text-sm font-medium text-stone-700">Duration</label>
                <input id="{{ $category }}_duration_detail" name="duration_detail" type="text" value="{{ old('duration_detail') }}" placeholder="Example: 6 hours" class="w-full rounded-2xl border border-stone-300 px-3 py-2.5 text-sm text-stone-800" data-package-duration-row @disabled(old('package_type') !== 'Day Trip')>
                <p class="mt-2 text-xs text-stone-500">Day trip only.</p>
            </div>
            <div>
                <label for="{{ $category }}_capacity" class="mb-2 block text-sm font-medium text-stone-700">Capacity</label>
                <input id="{{ $category }}_capacity" name="capacity" type="number" value="{{ old('capacity') }}" class="w-full rounded-2xl border border-stone-300 px-3 py-2.5 text-sm text-stone-800">
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:max-w-[32rem] xl:items-start">
            <div>
                <label for="{{ $category }}_minimum_age_mode" class="mb-2 block text-sm font-medium text-stone-700">Minimum Age</label>
                <select id="{{ $category }}_minimum_age_mode" name="minimum_age_mode" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800" data-minimum-age-mode>
                    <option value="no_limit" @selected(old('minimum_age_mode', 'no_limit') === 'no_limit')>No limit</option>
                    <option value="above_age" @selected(old('minimum_age_mode') === 'above_age')>Above age</option>
                </select>
            </div>
            <div>
                <label for="{{ $category }}_minimum_age_years" class="mb-2 block text-sm font-medium text-stone-700">Years Old</label>
                <input id="{{ $category }}_minimum_age_years" name="minimum_age_years" type="number" min="1" max="120" value="{{ old('minimum_age_years') }}" class="w-full rounded-2xl border border-stone-300 px-3 py-2.5 text-sm text-stone-800" placeholder="12" data-minimum-age-years @disabled(old('minimum_age_mode', 'no_limit') !== 'above_age')>
                <p class="mt-2 text-xs text-stone-500">Used only when minimum age is above a certain age.</p>
            </div>
        </div>
    @else
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label for="{{ $category }}_location" class="mb-2 block text-sm font-medium text-stone-700">Location</label>
                <input id="{{ $category }}_location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
            <div>
                <label for="{{ $category }}_duration" class="mb-2 block text-sm font-medium text-stone-700">Duration</label>
                <input id="{{ $category }}_duration" name="duration" type="text" value="{{ old('duration') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
            </div>
        </div>
    @endif
    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label for="{{ $category }}_malaysia_adult_price_myr" class="mb-2 block text-sm font-medium text-stone-700">Malaysia adult price</label>
            <input id="{{ $category }}_malaysia_adult_price_myr" name="malaysia_adult_price_myr" type="number" step="0.01" value="{{ old('malaysia_adult_price_myr') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_malaysia_child_price_myr" class="mb-2 block text-sm font-medium text-stone-700">Malaysia child price</label>
            <input id="{{ $category }}_malaysia_child_price_myr" name="malaysia_child_price_myr" type="number" step="0.01" value="{{ old('malaysia_child_price_myr') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_international_adult_price_myr" class="mb-2 block text-sm font-medium text-stone-700">International adult price</label>
            <input id="{{ $category }}_international_adult_price_myr" name="international_adult_price_myr" type="number" step="0.01" value="{{ old('international_adult_price_myr') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
        <div>
            <label for="{{ $category }}_international_child_price_myr" class="mb-2 block text-sm font-medium text-stone-700">International child price</label>
            <input id="{{ $category }}_international_child_price_myr" name="international_child_price_myr" type="number" step="0.01" value="{{ old('international_child_price_myr') }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">
        </div>
    </div>
    <div class="grid gap-4 lg:grid-cols-[1fr_1.2fr]">
        <div>
            @if ($category === 'package')
                <label for="{{ $category }}_gallery_image_files" class="mb-2 block text-sm font-medium text-stone-700">Upload gallery images</label>
                <input id="{{ $category }}_gallery_image_files" name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 px-4 py-3 text-stone-700">
            @endif
        </div>
        <div>
            <label for="{{ $category }}_description" class="mb-2 block text-sm font-medium text-stone-700">Description</label>
            <textarea id="{{ $category }}_description" name="description" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-stone-800">{{ old('description') }}</textarea>
        </div>
    </div>
    <div class="flex flex-wrap gap-6 pt-1">
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="rounded border-stone-300">
            Featured product
        </label>
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="is_top_choice" value="1" @checked(old('is_top_choice')) class="rounded border-stone-300">
            Top choice
        </label>
        <label class="flex items-center gap-2 text-sm text-stone-600">
            <input type="checkbox" name="is_discounted" value="1" @checked(old('is_discounted')) class="rounded border-stone-300">
            Discount
        </label>
        <div class="flex items-center gap-2 text-sm text-stone-600">
            <label for="{{ $category }}_discount_percentage">Discount %</label>
            <input id="{{ $category }}_discount_percentage" name="discount_percentage" type="number" step="0.01" min="0" max="100" value="{{ old('discount_percentage') }}" class="w-24 rounded-2xl border border-stone-300 px-3 py-2 text-stone-800">
        </div>
    </div>
    <button type="submit" class="w-full rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.25em] text-white transition hover:bg-sky-700">Save {{ $title }}</button>
</form>

@if ($category === 'package')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const packageTypeSelect = document.querySelector('[data-package-type-select]');
            const durationInput = document.getElementById('{{ $category }}_duration_detail');
            const tourCodeInput = document.querySelector('[data-tour-code-input]');
            const minimumAgeMode = document.querySelector('[data-minimum-age-mode]');
            const minimumAgeYears = document.querySelector('[data-minimum-age-years]');

            if (!packageTypeSelect || !durationInput || !tourCodeInput || !minimumAgeMode || !minimumAgeYears) {
                return;
            }

            const normalizeTourCodeValue = (value, isDayTrip) => {
                const desiredPrefix = isDayTrip ? 'DT-UEH' : 'OT-UEH';
                const cleanedValue = String(value || '')
                    .toUpperCase()
                    .replace(/\s+/g, '')
                    .replace(/_/g, '-')
                    .replace(/\./g, '-')
                    .replace(/^(DT|OT)-?UEH/i, '')
                    .replace(/^UEH/i, '')
                    .replace(/[^A-Z0-9-]/g, '');

                return desiredPrefix + cleanedValue.replace(/-/g, '');
            };

            const syncMinimumAgeFields = () => {
                const requiresAge = minimumAgeMode.value === 'above_age';
                minimumAgeYears.disabled = !requiresAge;
                minimumAgeYears.required = requiresAge;

                if (!requiresAge) {
                    minimumAgeYears.value = '';
                }
            };

            const syncDurationField = () => {
                const isDayTrip = packageTypeSelect.value === 'Day Trip';
                durationInput.required = isDayTrip;
                durationInput.disabled = !isDayTrip;
                tourCodeInput.placeholder = isDayTrip ? 'DT-UEH01' : 'OT-UEH01';
                tourCodeInput.value = normalizeTourCodeValue(tourCodeInput.value, isDayTrip);

                if (!isDayTrip) {
                    durationInput.value = '';
                }
            };

            tourCodeInput.addEventListener('input', () => {
                const isDayTrip = packageTypeSelect.value === 'Day Trip';
                const normalizedValue = normalizeTourCodeValue(tourCodeInput.value, isDayTrip);

                if (tourCodeInput.value !== normalizedValue) {
                    tourCodeInput.value = normalizedValue;
                }
            });

            packageTypeSelect.addEventListener('change', syncDurationField);
            minimumAgeMode.addEventListener('change', syncMinimumAgeFields);
            syncDurationField();
            syncMinimumAgeFields();
        });
    </script>
@endif
