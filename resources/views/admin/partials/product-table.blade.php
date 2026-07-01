@php
    $editable = $editable ?? false;
    $wrapperId = $wrapperId ?? null;
    $itemAttribute = $itemAttribute ?? null;
    $gridColumns = $gridColumns ?? 1;
@endphp

<div
    @if($wrapperId) id="{{ $wrapperId }}" @endif
    class="mt-6 {{ $gridColumns > 1 ? 'grid gap-4' : 'space-y-4' }}"
    @if($gridColumns > 1) style="display: grid; grid-template-columns: repeat({{ $gridColumns }}, minmax(0, 1fr)); gap: 1rem; align-items: start;" @endif
>
    @forelse ($products as $product)
        @php
            $cardImage = $product->image_url ?: collect($product->gallery_images ?? [])->filter()->first();
            $isPackage = $product->category === 'package';
            $packageDurationToken = strtolower(preg_replace('/\s+/', '', trim((string) $product->duration)) ?? '');
            $tourCodeToken = strtoupper(trim((string) ($product->tour_code ?? '')));
            $packageDurationFilter = match (true) {
                str_starts_with($tourCodeToken, 'DT-UEH') => 'day-trip',
                str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '2d1n'), str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '2days1night'), str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '2days1nights') => '2d1n',
                str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '3d2n'), str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '3days2night'), str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '3days2nights') => '3d2n',
                str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '4d3n'), str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '4days3night'), str_starts_with($tourCodeToken, 'OT-UEH') && str_contains($packageDurationToken, '4days3nights') => '4d3n',
                str_contains($packageDurationToken, 'daytrip'), str_contains($packageDurationToken, '1day') => 'day-trip',
                str_contains($packageDurationToken, '2d1n'), str_contains($packageDurationToken, '2days1night'), str_contains($packageDurationToken, '2days1nights') => '2d1n',
                str_contains($packageDurationToken, '3d2n'), str_contains($packageDurationToken, '3days2night'), str_contains($packageDurationToken, '3days2nights') => '3d2n',
                str_contains($packageDurationToken, '4d3n'), str_contains($packageDurationToken, '4days3night'), str_contains($packageDurationToken, '4days3nights') => '4d3n',
                default => 'other',
            };
            $packageCardCopy = $isPackage
                ? \Illuminate\Support\Str::limit(trim((string) ($product->summary ?: $product->description ?: '')), 210)
                : '';
        @endphp

        <article @if($itemAttribute) {{ $itemAttribute }}="true" @endif class="relative rounded-3xl border border-stone-200 bg-stone-50 {{ $isPackage ? 'p-4' : 'p-5' }}" @if($isPackage) data-package-inline-row data-list-filter-value="{{ $packageDurationFilter }}" @endif>
            <div
                class="grid gap-4 items-start {{ $isPackage ? '' : 'xl:grid-cols-[110px_minmax(0,1fr)_auto]' }}"
                @if ($isPackage) style="grid-template-columns: 132px minmax(0, 1fr);" @endif
            >
                <div class="overflow-hidden border border-stone-200 bg-white {{ $isPackage ? 'rounded-md' : 'rounded-[1rem]' }}" @if ($isPackage) style="width: 132px; height: 132px;" @else style="width: 72px; height: 72px;" @endif>
                    <img
                        src="{{ $cardImage }}"
                        alt="{{ $product->name }}"
                        class="w-full object-cover {{ $isPackage ? '' : 'h-24' }} {{ $cardImage ? 'block' : 'hidden' }}"
                        @if ($isPackage) style="width: 132px; height: 132px;" @else style="width: 72px; height: 72px;" @endif
                        @if ($isPackage) data-package-card-image @endif
                    >
                    <div
                        class="{{ $cardImage ? 'hidden' : 'flex' }} items-center justify-center bg-stone-100 text-center font-semibold uppercase text-stone-400 {{ $isPackage ? 'px-1 text-[8px] tracking-[0.15em]' : 'h-24 px-2 text-[10px] tracking-[0.2em]' }}"
                        @if ($isPackage) style="width: 132px; height: 132px;" @else style="width: 72px; height: 72px;" @endif
                        @if ($isPackage) data-package-card-empty @endif
                    >
                        No image
                    </div>
                </div>

                <div class="min-w-0">
                    @if ($isPackage && $editable)
                        @php
                            $inlineFormId = 'package-inline-form-'.$product->id;
                            $itineraryFormId = 'package-itinerary-form-'.$product->id;
                            $packageDetailsFormId = 'package-details-form-'.$product->id;
                            $packageContentFormId = 'package-content-form-'.$product->id;
                            $optionalActivitiesFormId = 'package-optional-activities-form-'.$product->id;
                            $normalizedPackageDuration = strtolower(trim((string) $product->duration));
                            $compactPackageDuration = str_replace(' ', '', $normalizedPackageDuration);
                            $currentPackageType = match (true) {
                                str_starts_with(strtoupper(trim((string) ($product->tour_code ?? ''))), 'DT-UEH') => 'Day Trip',
                                str_contains($normalizedPackageDuration, 'day trip') || str_contains($compactPackageDuration, '1day') => 'Day Trip',
                                str_contains($compactPackageDuration, '2d1n') || str_contains($compactPackageDuration, '2days1night') || str_contains($compactPackageDuration, '2days1nights') => '2D1N',
                                str_contains($compactPackageDuration, '3d2n') || str_contains($compactPackageDuration, '3days2night') || str_contains($compactPackageDuration, '3days2nights') => '3D2N',
                                str_contains($compactPackageDuration, '4d3n') || str_contains($compactPackageDuration, '4days3night') || str_contains($compactPackageDuration, '4days3nights') => '4D3N',
                                default => 'Day Trip',
                            };
                            $packageDurationDetail = $currentPackageType === 'Day Trip' ? $product->duration : '';
                            $minimumAgeLabel = trim((string) ($product->minimum_age ?? ''));
                            $minimumAgeMode = str_starts_with(strtolower($minimumAgeLabel), 'above ') ? 'above_age' : 'no_limit';
                            $minimumAgeYears = preg_match('/(\d+)/', $minimumAgeLabel, $minimumAgeMatches) ? (int) $minimumAgeMatches[1] : null;
                            $packagePricingTiers = collect($product->pricing_tiers ?? [])->filter(fn ($tier) => is_array($tier) && filled($tier['group_size_label'] ?? null))->values();
                            $packagePricingTiers = $packagePricingTiers->isNotEmpty() ? $packagePricingTiers : collect([[
                                'group_size_label' => $product->group_size_label ?? '',
                                'malaysia_adult_price_myr' => $product->malaysia_adult_price_myr,
                                'malaysia_child_price_myr' => $product->malaysia_child_price_myr,
                                'international_adult_price_myr' => $product->international_adult_price_myr,
                                'international_child_price_myr' => $product->international_child_price_myr,
                            ]]);
                            $packageItineraryItems = collect($product->itinerary_items ?? [])->filter()->values();
                            $packageDurationDays = preg_match('/(\d+)\s*day/i', $product->duration ?? '', $packageDurationMatches) ? max(1, (int) $packageDurationMatches[1]) : 1;
                            $normalizePackageItineraryRows = function ($existingRow, $fallbackDayLabel) use (&$normalizePackageItineraryRows) {
                                $normalizeStructuredRow = function (array $row, string $dayLabel) {
                                    return [[
                                        'day_number' => trim((string) ($row['day_number'] ?? '')) ?: $dayLabel,
                                        'time' => trim((string) ($row['time'] ?? '')),
                                        'activity' => trim((string) ($row['activity'] ?? '')),
                                    ]];
                                };

                                if (is_array($existingRow) && array_key_exists('activity', $existingRow)) {
                                    $activityValue = $existingRow['activity'] ?? '';

                                    if (is_string($activityValue)) {
                                        $decodedActivity = json_decode($activityValue, true);

                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedActivity)) {
                                            $decodedRows = array_is_list($decodedActivity) ? $decodedActivity : [$decodedActivity];

                                            return collect($decodedRows)
                                                ->flatMap(fn ($decodedRow, $decodedIndex) => $normalizePackageItineraryRows($decodedRow, trim((string) ($existingRow['day_number'] ?? '')) ?: ($fallbackDayLabel ?: 'Day '.($decodedIndex + 1))))
                                                ->values()
                                                ->all();
                                        }
                                    }

                                    return $normalizeStructuredRow($existingRow, $fallbackDayLabel);
                                }

                                if (is_array($existingRow)) {
                                    $rows = array_is_list($existingRow) ? $existingRow : [$existingRow];

                                    return collect($rows)
                                        ->flatMap(fn ($decodedRow, $decodedIndex) => $normalizePackageItineraryRows($decodedRow, $fallbackDayLabel ?: 'Day '.($decodedIndex + 1)))
                                        ->values()
                                        ->all();
                                }

                                if (is_string($existingRow)) {
                                    $decodedRow = json_decode($existingRow, true);

                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedRow)) {
                                        $decodedRows = array_is_list($decodedRow) ? $decodedRow : [$decodedRow];

                                        return collect($decodedRows)
                                            ->flatMap(fn ($nestedRow, $decodedIndex) => $normalizePackageItineraryRows($nestedRow, $fallbackDayLabel ?: 'Day '.($decodedIndex + 1)))
                                            ->values()
                                            ->all();
                                    }

                                    return [[
                                        'day_number' => $fallbackDayLabel,
                                        'time' => '',
                                        'activity' => trim($existingRow),
                                    ]];
                                }

                                return [[
                                    'day_number' => $fallbackDayLabel,
                                    'time' => '',
                                    'activity' => '',
                                ]];
                            };
                            $packageItineraryRows = $packageItineraryItems->isNotEmpty()
                                ? $packageItineraryItems->flatMap(function ($existingRow, $index) use ($normalizePackageItineraryRows) {
                                    return $normalizePackageItineraryRows($existingRow, 'Day '.($index + 1));
                                })->values()
                                : collect(range(0, $packageDurationDays - 1))->map(function ($index) {
                                    return [
                                        'day_number' => 'Day '.($index + 1),
                                        'time' => '',
                                        'activity' => '',
                                    ];
                                });
                            $packageItineraryGroups = $packageItineraryRows->groupBy(fn ($row) => $row['day_number'] ?: 'Day 1')->values();
                            $itineraryTimeOptions = collect(range(0, 47))->map(fn ($index) => \Carbon\Carbon::createFromTime(0, 0)->addMinutes($index * 30)->format('h:i A'))->all();
                            $itineraryTimeOptionsId = 'package-itinerary-time-options-'.$product->id;

                            $legacyServiceRows = collect(is_array($product->service_inclusions) ? $product->service_inclusions : [])->filter()->values();
                            $legacyPackageDetailSections = [
                                'includes' => [],
                                'excludes' => [],
                                'things_to_bring' => [],
                                'important_notes' => [],
                            ];

                            foreach ($legacyServiceRows as $legacyRow) {
                                $label = trim((string) ($legacyRow['label'] ?? ''));
                                $value = trim((string) ($legacyRow['value'] ?? ''));

                                if ($value === '') {
                                    continue;
                                }

                                $normalizedLabel = strtolower($label);
                                $targetKey = match (true) {
                                    str_contains($normalizedLabel, 'exclusion') => 'excludes',
                                    str_contains($normalizedLabel, 'bring') => 'things_to_bring',
                                    str_contains($normalizedLabel, 'important'), str_contains($normalizedLabel, 'note') => 'important_notes',
                                    default => 'includes',
                                };

                                $legacyPackageDetailSections[$targetKey][] = [
                                    'symbol' => match ($targetKey) {
                                        'excludes' => 'x',
                                        'things_to_bring', 'important_notes' => 'exclamation',
                                        default => 'tick',
                                    },
                                    'text' => $value,
                                ];
                            }

                            $normalizePackageDetailRows = function ($rows, $fallbackRows, $defaultSymbol, $allowedSymbols) {
                                $stripPackageDetailLeadingMarker = function ($text) {
                                    return trim((string) preg_replace('/^\s*(?:[â€¢â—â—‹â—¦â–ªâ–«âœ“âœ”âœ•âœ–âœ—âŒâš !]+|\d+[.)])\s*/u', '', (string) $text));
                                };

                                $normalizedRows = collect(is_array($rows) ? $rows : [])
                                    ->map(function ($row) use ($defaultSymbol, $allowedSymbols, $stripPackageDetailLeadingMarker) {
                                        if (is_array($row)) {
                                            $text = $stripPackageDetailLeadingMarker($row['text'] ?? $row['value'] ?? '');
                                            $symbol = trim((string) ($row['symbol'] ?? $defaultSymbol));

                                            if ($text === '') {
                                                return null;
                                            }

                                        return [
                                            'symbol' => in_array($symbol, $allowedSymbols, true) ? $symbol : $defaultSymbol,
                                            'text' => $text,
                                            'html' => trim((string) ($row['html'] ?? '')),
                                        ];
                                        }

                                        $text = $stripPackageDetailLeadingMarker($row);

                                        return $text === ''
                                            ? null
                                            : ['symbol' => $defaultSymbol, 'text' => $text, 'html' => ''];
                                    })
                                ->filter()
                                ->values();

                                if ($normalizedRows->isNotEmpty()) {
                                    return $normalizedRows;
                                }

                                $fallbackCollection = collect($fallbackRows)->filter(fn ($row) => filled($row['text'] ?? null))->values();

                                return $fallbackCollection->isNotEmpty()
                                    ? $fallbackCollection
                                    : collect([['symbol' => $defaultSymbol, 'text' => '']]);
                            };

                            $cleanPackageDetailHtml = function ($html) {
                                $cleaned = preg_replace(
                                    '/(<(?:p|div|li)[^>]*>\s*(?:<(?:strong|b|em|i|u)[^>]*>\s*)*)(?:[â€¢â—â—‹â—¦â–ªâ–«âœ“âœ”âœ•âœ–âœ—âŒâš !]+|\d+[.)])\s*/u',
                                    '$1',
                                    (string) $html
                                ) ?? (string) $html;

                                $cleaned = preg_replace('/<li[^>]*>\s*(?:&nbsp;|\s|<br\s*\/?>)*<\/li>/iu', '', $cleaned) ?? $cleaned;
                                $cleaned = preg_replace('/<(ul|ol)([^>]*)>\s*<\/\1>/iu', '', $cleaned) ?? $cleaned;

                                return $cleaned;
                            };

                            $buildPackageDetailSectionHtml = function ($rows) use ($cleanPackageDetailHtml) {
                                $segments = [];
                                $pendingLists = [];
                                $pointStyleMap = [
                                    'tick' => 'tick',
                                    'round' => 'round',
                                    'x' => 'x',
                                    'exclamation' => 'warning',
                                ];

                                $flushPendingLists = function () use (&$segments, &$pendingLists) {
                                    foreach ($pendingLists as $style => $items) {
                                        if ($items === []) {
                                            continue;
                                        }

                                        $segments[] = '<ul data-point-style="'.e($style).'">'.collect($items)
                                            ->map(fn ($item) => '<li>'.e($item).'</li>')
                                            ->implode('').'</ul>';
                                    }

                                    $pendingLists = [];
                                };

                                foreach (collect($rows) as $row) {
                                    if (! is_array($row)) {
                                        $text = trim((string) $row);

                                        if ($text !== '') {
                                            $pendingLists['round'][] = $text;
                                        }

                                        continue;
                                    }

                                    $html = trim($cleanPackageDetailHtml($row['html'] ?? ''));
                                    $text = trim((string) ($row['text'] ?? $row['value'] ?? ''));
                                    $symbol = trim((string) ($row['symbol'] ?? 'round'));
                                    $pointStyle = $pointStyleMap[$symbol] ?? 'round';
                                    $hasStructuredHtml = $html !== '' && preg_match('/<(?:ul|ol|li|p|div|br)\b/i', $html);

                                    if ($hasStructuredHtml) {
                                        $flushPendingLists();
                                        $segments[] = $html;

                                        continue;
                                    }

                                    if ($text !== '') {
                                        $pendingLists[$pointStyle][] = $text;
                                    }
                                }

                                $flushPendingLists();

                                return implode('', array_filter($segments));
                            };

                            $packageDetails = is_array($product->package_details) ? $product->package_details : [];

                            $packageIncludeRows = $normalizePackageDetailRows($packageDetails['includes'] ?? [], $legacyPackageDetailSections['includes'], 'tick', ['tick', 'round']);
                            $packageExcludeRows = $normalizePackageDetailRows($packageDetails['excludes'] ?? [], $legacyPackageDetailSections['excludes'], 'x', ['x', 'round']);
                            $packageBringRows = $normalizePackageDetailRows($packageDetails['things_to_bring'] ?? [], $legacyPackageDetailSections['things_to_bring'], 'exclamation', ['exclamation', 'round']);
                            $packageNoteRows = $normalizePackageDetailRows($packageDetails['important_notes'] ?? [], $legacyPackageDetailSections['important_notes'], 'exclamation', ['exclamation', 'round']);
                            $packageIncludeHtml = $buildPackageDetailSectionHtml($packageIncludeRows);
                            $packageExcludeHtml = $buildPackageDetailSectionHtml($packageExcludeRows);
                            $packageBringHtml = $buildPackageDetailSectionHtml($packageBringRows);
                            $packageNoteHtml = $buildPackageDetailSectionHtml($packageNoteRows);

                            $normalizeSimpleRows = function ($rows) {
                                $collection = collect(is_array($rows) ? $rows : [])
                                    ->map(fn ($item) => trim((string) $item))
                                    ->filter()
                                    ->values();

                                return $collection->isNotEmpty() ? $collection : collect(['']);
                            };

                            $buildPackageContentHtml = function ($rows) use ($cleanPackageDetailHtml) {
                                $segments = [];
                                $plainItems = [];

                                $flushPlainItems = function () use (&$segments, &$plainItems) {
                                    if ($plainItems === []) {
                                        return;
                                    }

                                    $segments[] = '<ul data-point-style="round">'.collect($plainItems)
                                        ->map(fn ($item) => '<li>'.e($item).'</li>')
                                        ->implode('').'</ul>';
                                    $plainItems = [];
                                };

                                foreach (collect(is_array($rows) ? $rows : []) as $row) {
                                    if (is_array($row)) {
                                        $html = trim($cleanPackageDetailHtml((string) ($row['html'] ?? '')));
                                        $text = trim((string) ($row['text'] ?? $row['value'] ?? ''));
                                        $hasStructuredHtml = $html !== '' && preg_match('/<(?:ul|ol|li|p|div|br)\b/i', $html);

                                        if ($hasStructuredHtml) {
                                            $flushPlainItems();
                                            $segments[] = $html;

                                            continue;
                                        }

                                        if ($text !== '') {
                                            $plainItems[] = $text;
                                        }

                                        continue;
                                    }

                                    $text = trim((string) $row);

                                    if ($text !== '') {
                                        $plainItems[] = $text;
                                    }
                                }

                                $flushPlainItems();

                                return implode('', array_filter($segments));
                            };

                            $packageTourHighlightsHtml = $buildPackageContentHtml($product->tour_highlights ?? []);
                            $packageRecommendedAttireHtml = $buildPackageContentHtml($product->recommended_attire ?? []);
                            $packageThingsToKnowHtml = $buildPackageContentHtml($product->things_to_know ?? []);
                            $packageTravelTipsHtml = $buildPackageContentHtml($product->travel_tips ?? []);
                            $packageOptionalActivitiesData = is_array($product->optional_activities) ? $product->optional_activities : [];
                            $packageOptionalActivitiesDescription = trim((string) ($packageOptionalActivitiesData['description'] ?? ''));
                            $packageOptionalActivitiesRows = collect(is_array($packageOptionalActivitiesData['rows'] ?? null) ? $packageOptionalActivitiesData['rows'] : [])
                                ->map(function ($row) {
                                    if (! is_array($row)) {
                                        return null;
                                    }

                                    $name = trim((string) ($row['name'] ?? ''));
                                    $rate = trim((string) ($row['rate'] ?? ($row['details'] ?? '')));

                                    if ($name === '' && $rate === '') {
                                        return null;
                                    }

                                    return [
                                        'name' => $name,
                                        'rate' => $rate,
                                    ];
                                })
                                ->filter()
                                ->values();

                            if ($packageOptionalActivitiesRows->isEmpty()) {
                                $packageOptionalActivitiesRows = collect(is_array($packageOptionalActivitiesData['items'] ?? null) ? $packageOptionalActivitiesData['items'] : [])
                                    ->map(function ($item) {
                                        $name = trim((string) $item);

                                        if ($name === '') {
                                            return null;
                                        }

                                        return [
                                            'name' => $name,
                                            'rate' => '',
                                        ];
                                    })
                                    ->filter()
                                    ->values();
                            }

                            if ($packageOptionalActivitiesRows->isEmpty()) {
                                $packageOptionalActivitiesRows = collect([[
                                    'name' => '',
                                    'rate' => '',
                                ]]);
                            }

                            $packageOptionalActivitiesEnabled = array_key_exists('enabled', $packageOptionalActivitiesData)
                                ? (bool) $packageOptionalActivitiesData['enabled']
                                : (
                                    filled($packageOptionalActivitiesDescription)
                                    || $packageOptionalActivitiesRows->contains(fn ($row) => filled($row['name'] ?? null) || filled($row['details'] ?? null))
                                );
                        @endphp
                        <form id="{{ $inlineFormId }}" method="POST" action="{{ route('admin.packages.update', $product) }}" enctype="multipart/form-data" class="space-y-3" data-package-inline-form data-form-persist="admin-products-update-{{ $product->id }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="image_url" value="{{ $product->image_url }}">
                            @php($inlineGalleryImages = collect($product->gallery_images ?? [])->filter()->values())
                            @foreach ($inlineGalleryImages as $galleryImage)
                                <input type="hidden" name="existing_gallery_images[]" value="{{ $galleryImage }}">
                            @endforeach

                            <div class="package-inline-view space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-xl font-semibold text-stone-900">{{ $product->name }}</h4>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}" data-package-status-badge>
                                        {{ $product->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                    @if ($product->tour_code)
                                        <span class="rounded-full bg-stone-900 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white">{{ $product->tour_code }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-stone-500">{{ $product->location }} | {{ $product->duration }}</p>
                                @if (filled($packageCardCopy))
                                    <p class="text-sm leading-6 text-stone-600">{{ $packageCardCopy }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em]">
                                    <span class="rounded-full bg-white px-3 py-1 text-stone-600">RM {{ number_format((float) $product->malaysia_adult_price_myr, 2) }}</span>
                                    @if ($product->group_size_label)
                                        <span class="rounded-full bg-white px-3 py-1 text-stone-600">{{ $product->group_size_label }}</span>
                                    @endif
                                    @if ($product->capacity !== null)
                                        <span class="rounded-full bg-white px-3 py-1 text-stone-600">Capacity {{ $product->capacity }}</span>
                                    @endif
                                    <span class="rounded-full bg-white px-3 py-1 text-stone-600">{{ $product->is_top_choice ? 'Top choice' : ($product->is_featured ? 'Featured' : 'Standard') }}</span>
                                </div>
                            </div>

                            <div class="package-inline-edit hidden fixed inset-0 z-[400] items-start justify-center overflow-hidden bg-stone-950/55 px-5 py-3 sm:px-6 sm:py-4">
                                <div class="w-full max-w-[1380px] overflow-y-auto rounded-[2rem] border border-stone-200 bg-stone-100 p-4 pr-6 shadow-[0_24px_60px_rgba(15,23,42,0.24)]" data-package-inline-panel style="max-height: calc(100vh - 1.5rem); scrollbar-gutter: stable; padding-right: 1.75rem;">
                                <div class="grid gap-6 items-start" style="grid-template-columns: 560px minmax(0, 1fr);">
                                    <div>
                                        <div class="grid gap-4" style="grid-template-columns: 240px 180px;">
                                            <div class="min-w-0">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Main image</p>
                                                <button
                                                    type="button"
                                                    class="package-inline-main-image-open {{ $product->image_url ? 'block' : 'hidden' }} overflow-hidden rounded-lg shadow-sm"
                                                    data-inline-main-image-target="package-inline-main-image-{{ $product->id }}"
                                                    data-package-main-image-button
                                                    aria-label="Open main image preview"
                                                >
                                                    <img
                                                        src="{{ $product->image_url }}"
                                                        alt="{{ $product->name }}"
                                                        class="rounded-lg object-cover"
                                                        data-package-main-image-card
                                                        style="width: 240px; height: 240px;"
                                                    >
                                                </button>
                                                <div
                                                    class="{{ $product->image_url ? 'hidden' : 'flex' }} items-center justify-center rounded-lg border border-dashed border-stone-300 bg-stone-50 text-center text-[10px] font-medium uppercase tracking-[0.14em] text-stone-400"
                                                    data-package-main-image-empty
                                                    style="width: 240px; height: 240px;"
                                                >
                                                    No main image
                                                </div>

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
                                                <label class="mt-3 block">
                                                    <span class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-500">Upload main image</span>
                                                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-3 py-2 text-xs text-stone-700" data-package-main-image-input>
                                                </label>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-400">Gallery folder</p>
                                                <button type="button" class="package-inline-gallery-open relative flex h-[180px] w-[180px] flex-col justify-end overflow-hidden rounded-[1.6rem] border border-amber-200 bg-gradient-to-b from-amber-100 via-amber-50 to-white px-6 py-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" data-inline-gallery-target="package-inline-gallery-{{ $product->id }}" data-package-gallery-open>
                                                    <span class="absolute left-5 top-0 h-8 w-20 rounded-b-[1rem] rounded-t-[0.75rem] bg-amber-200/90"></span>
                                                    <span class="absolute left-0 right-0 top-7 h-px bg-amber-200/80"></span>
                                                    <p class="text-4xl font-semibold leading-none text-amber-950" data-package-gallery-count>{{ $inlineGalleryImages->count() }}</p>
                                                    <p class="mt-3 text-lg font-semibold uppercase tracking-[0.06em] text-amber-900" data-package-gallery-label>Image{{ $inlineGalleryImages->count() === 1 ? '' : 's' }}</p>
                                                    <p class="mt-4 text-sm uppercase tracking-[0.18em] text-amber-700/70" data-package-gallery-status>{{ $inlineGalleryImages->isNotEmpty() ? 'Open folder' : 'Empty folder' }}</p>
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
                                                        <div class="grid flex-1 grid-cols-2 gap-3 overflow-y-auto p-2 md:grid-cols-3 lg:grid-cols-4" data-package-gallery-grid>
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
                                                <label class="mt-3 block">
                                                    <span class="mb-1 block text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-500">Upload gallery images</span>
                                                    <input name="gallery_image_files[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-3 py-2 text-xs text-stone-700" data-package-gallery-input>
                                                </label>
                                            </div>
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
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Pickup Location</label>
                                        <input name="pickup_location" type="text" value="{{ $product->pickup_location }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Dropoff Location</label>
                                        <input name="dropoff_location" type="text" value="{{ $product->dropoff_location }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Package Type</label>
                                        <select name="package_type" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800" data-package-edit-type>
                                            <option value="Day Trip" @selected($currentPackageType === 'Day Trip')>Day Trip</option>
                                            <option value="2D1N" @selected($currentPackageType === '2D1N')>2D1N</option>
                                            <option value="3D2N" @selected($currentPackageType === '3D2N')>3D2N</option>
                                            <option value="4D3N" @selected($currentPackageType === '4D3N')>4D3N</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Tour Code</label>
                                        <input name="tour_code" type="text" value="{{ $product->tour_code }}" placeholder="{{ $currentPackageType === 'Day Trip' ? 'DT-UEH01' : 'OT-UEH01' }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800" data-package-edit-tour-code>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Duration</label>
                                        <input name="duration_detail" type="text" value="{{ $packageDurationDetail }}" placeholder="Example: 6 hours" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800" data-package-edit-duration @disabled($currentPackageType !== 'Day Trip')>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Capacity</label>
                                        <input name="capacity" type="number" value="{{ $product->capacity }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Departure Time</label>
                                        <input name="departure_time" type="text" value="{{ $product->departure_time }}" placeholder="Example: 7:30 AM" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Minimum Age</label>
                                        <select name="minimum_age_mode" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800" data-package-edit-minimum-age-mode>
                                            <option value="no_limit" @selected($minimumAgeMode === 'no_limit')>No limit</option>
                                            <option value="above_age" @selected($minimumAgeMode === 'above_age')>Above age</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Years Old</label>
                                        <input name="minimum_age_years" type="number" min="1" max="120" value="{{ $minimumAgeYears }}" placeholder="12" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800" data-package-edit-minimum-age-years @disabled($minimumAgeMode !== 'above_age')>
                                    </div>
                                    <div style="grid-column: 1 / -1;">
                                        <div class="rounded-[1.25rem] border border-stone-200 bg-stone-50/70 p-4">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Group Pricing</label>
                                                    <p class="text-xs text-stone-500">Add one row for each pax range and its matching prices.</p>
                                                </div>
                                                <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-stone-700 transition hover:bg-stone-100" data-package-edit-pricing-add>
                                                    Add Group Size
                                                </button>
                                            </div>
                                            <div class="mt-4 overflow-x-auto">
                                                <div class="space-y-3" data-package-edit-pricing-rows>
                                                    @foreach ($packagePricingTiers as $pricingTier)
                                                        <div class="rounded-[1.1rem] border border-stone-200 bg-white p-4" data-package-edit-pricing-row>
                                                            <div class="flex flex-col gap-3">
                                                                <div class="grid gap-3 md:grid-cols-[12rem_minmax(0,1fr)_auto] md:items-center">
                                                                    <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">Group Size / No. Pax</span>
                                                                    <input name="pricing_group_size_label[]" type="text" value="{{ $pricingTier['group_size_label'] ?? '' }}" placeholder="Example: 4 - 6 Pax" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                                    <button type="button" class="rounded-full border border-rose-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-rose-600 transition hover:bg-rose-50" data-package-edit-pricing-remove>
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                                <div class="grid gap-3 md:grid-cols-2">
                                                                    <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                        <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">MY Adult</span>
                                                                        <input name="pricing_malaysia_adult_price_myr[]" type="number" step="0.01" value="{{ $pricingTier['malaysia_adult_price_myr'] ?? '' }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                                    </div>
                                                                    <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                        <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">MY Child</span>
                                                                        <input name="pricing_malaysia_child_price_myr[]" type="number" step="0.01" value="{{ $pricingTier['malaysia_child_price_myr'] ?? '' }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                                    </div>
                                                                </div>
                                                                <div class="grid gap-3 md:grid-cols-2">
                                                                    <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                        <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">INT Adult</span>
                                                                        <input name="pricing_international_adult_price_myr[]" type="number" step="0.01" value="{{ $pricingTier['international_adult_price_myr'] ?? '' }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                                    </div>
                                                                    <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                        <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">INT Child</span>
                                                                        <input name="pricing_international_child_price_myr[]" type="number" step="0.01" value="{{ $pricingTier['international_child_price_myr'] ?? '' }}" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <template data-package-edit-pricing-template>
                                                <div class="rounded-[1.1rem] border border-stone-200 bg-white p-4" data-package-edit-pricing-row>
                                                    <div class="flex flex-col gap-3">
                                                        <div class="grid gap-3 md:grid-cols-[12rem_minmax(0,1fr)_auto] md:items-center">
                                                            <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">Group Size / No. Pax</span>
                                                            <input name="pricing_group_size_label[]" type="text" placeholder="Example: 4 - 6 Pax" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                            <button type="button" class="rounded-full border border-rose-200 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-rose-600 transition hover:bg-rose-50" data-package-edit-pricing-remove>
                                                                Remove
                                                            </button>
                                                        </div>
                                                        <div class="grid gap-3 md:grid-cols-2">
                                                            <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">MY Adult</span>
                                                                <input name="pricing_malaysia_adult_price_myr[]" type="number" step="0.01" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                            </div>
                                                            <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">MY Child</span>
                                                                <input name="pricing_malaysia_child_price_myr[]" type="number" step="0.01" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                            </div>
                                                        </div>
                                                        <div class="grid gap-3 md:grid-cols-2">
                                                            <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">INT Adult</span>
                                                                <input name="pricing_international_adult_price_myr[]" type="number" step="0.01" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                            </div>
                                                            <div class="grid gap-3 md:grid-cols-[8rem_minmax(0,1fr)] md:items-center">
                                                                <span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-stone-500">INT Child</span>
                                                                <input name="pricing_international_child_price_myr[]" type="number" step="0.01" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-800">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div style="grid-column: 1 / -1;">
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Description</label>
                                        <textarea name="description" rows="6" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">{{ $product->description }}</textarea>
                                    </div>
                                    <div style="grid-column: 1 / -1;">
                                        <label class="mb-1 block text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Summary</label>
                                        <textarea name="summary" rows="6" class="w-full rounded-lg border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">{{ $product->summary }}</textarea>
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
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $product->is_active ? 'text-emerald-700' : 'text-stone-500' }}" data-package-status-badge>
                                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-stone-500">{{ $product->location }} | {{ $product->duration }}</p>
                            @if (filled($packageCardCopy))
                                <p class="mt-3 text-sm leading-6 text-stone-600">{{ $packageCardCopy }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.2em]">
                                <span class="rounded-full bg-white px-3 py-1 text-stone-600">RM {{ number_format((float) $product->malaysia_adult_price_myr, 2) }}</span>
                                @if ($product->capacity !== null)
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
                            @if ($product->capacity !== null)
                                <p class="mt-2 text-sm font-medium text-stone-500">Capacity: {{ $product->capacity }}</p>
                            @endif
                            <div class="mt-3 space-y-2">
                                <p class="text-sm leading-6 text-stone-600">{{ $product->summary }}</p>
                                @if (filled($product->description))
                                    <p class="text-sm leading-6 text-stone-500">{{ $product->description }}</p>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>

                @if ($editable)
                    <div class="flex flex-wrap items-center gap-2" @if ($isPackage) style="grid-column: 1 / -1;" @endif>
                        @if ($isPackage)
                            <button
                                type="button"
                                class="min-w-[8.75rem] rounded-full border px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] transition {{ $product->is_active ? 'border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'border-stone-300 bg-white text-stone-700 hover:bg-stone-100' }}"
                                aria-label="{{ $product->is_active ? 'Hide package listing' : 'Show package listing' }}"
                                data-package-active-button
                                data-package-active-url="{{ route('admin.packages.active', $product) }}"
                                data-package-active-value="{{ $product->is_active ? 0 : 1 }}"
                                data-package-active-token="{{ csrf_token() }}"
                            >
                                <span data-package-active-label>{{ $product->is_active ? 'Active' : 'Hidden' }}</span>
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 package-inline-edit-trigger" data-package-inline-edit>
                                Edit
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-sky-300 bg-sky-50 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 transition hover:bg-sky-100" data-package-inline-open="itinerary">
                                Add Itinerary
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-emerald-300 bg-emerald-50 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 transition hover:bg-emerald-100" data-package-inline-open="package-details">
                                Package Details
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-amber-300 bg-amber-50 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-amber-700 transition hover:bg-amber-100" data-package-inline-open="package-content">
                                Other Content
                            </button>
                            <button type="button" class="min-w-[8.75rem] rounded-full border border-violet-300 bg-violet-50 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-violet-700 transition hover:bg-violet-100" data-package-inline-open="optional-activities">
                                Optional Activities
                            </button>
                            <button type="submit" form="{{ $inlineFormId }}" class="hidden min-w-[8.75rem] rounded-full bg-sky-600 px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-sky-700 package-inline-save">
                                Save
                            </button>
                            <button type="button" class="hidden min-w-[8.75rem] rounded-full border border-stone-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100 package-inline-cancel" data-package-inline-cancel>
                                Cancel
                            </button>
                            <div class="package-itinerary-modal hidden fixed inset-0 z-[1200] items-start justify-center overflow-y-auto bg-stone-950/55 px-6 py-6 md:px-8" data-package-itinerary-modal>
                                <div class="flex max-h-[calc(100vh-3rem)] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-stone-100 p-5 shadow-[0_24px_60px_rgba(15,23,42,0.24)]">
                                    <form id="{{ $itineraryFormId }}" method="POST" action="{{ route('admin.packages.itinerary', $product) }}" class="flex min-h-0 flex-1 flex-col gap-4" data-form-persist="admin-products-itinerary-{{ $product->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <datalist id="{{ $itineraryTimeOptionsId }}">
                                            @foreach ($itineraryTimeOptions as $timeOption)
                                                <option value="{{ $timeOption }}"></option>
                                            @endforeach
                                        </datalist>
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
                                        <div class="min-h-0 flex-1 overflow-y-auto pr-2" data-package-itinerary-scroll>
                                        <div class="overflow-x-auto rounded-[1.25rem] border border-stone-200 bg-white">
                                            <table class="min-w-[860px] w-full text-left text-sm">
                                                <thead class="bg-stone-100 text-stone-700">
                                                    <tr>
                                                        <th class="w-32 px-3 py-3 font-semibold">Day Number</th>
                                                        <th class="w-36 px-3 py-3 font-semibold">Time</th>
                                                        <th class="px-4 py-3 font-semibold">Activity</th>
                                                        <th class="w-20 px-3 py-3 font-semibold text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-stone-200" data-package-itinerary-table-body>
                                                    @foreach ($packageItineraryGroups as $groupIndex => $dayGroup)
                                                        @php($dayLabel = $dayGroup->first()['day_number'] ?? 'Day '.($groupIndex + 1))
                                                        @php($dayGroupId = 'day-group-'.$product->id.'-'.$groupIndex)
                                                        @foreach ($dayGroup as $slotIndex => $row)
                                                            <tr data-itinerary-slot-row data-itinerary-day-group="{{ $dayGroupId }}">
                                                                @if ($slotIndex === 0)
                                                                    <td rowspan="{{ max(1, $dayGroup->count()) }}" class="px-3 py-3 align-top" data-itinerary-day-cell>
                                                                        <input type="text" value="{{ $dayLabel }}" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" data-itinerary-day-label>
                                                                        <div class="mt-3 flex justify-start">
                                                                            <button type="button" class="inline-flex items-center justify-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-rose-700 transition hover:bg-rose-100" data-package-itinerary-remove-day aria-label="Remove day row">
                                                                                Delete Row
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                                <td class="px-3 py-3 align-top">
                                                                    <div class="flex h-[96px] min-w-[9rem] items-stretch rounded-xl border border-stone-200 bg-stone-50 p-2" data-itinerary-time-slot>
                                                                        <input type="hidden" name="itinerary_day_number[]" value="{{ $dayLabel }}" data-itinerary-day-hidden>
                                                                        <div class="flex w-full flex-col gap-1">
                                                                            <input name="itinerary_time[]" type="text" value="{{ $row['time'] }}" list="{{ $itineraryTimeOptionsId }}" class="h-full w-full min-w-[7.75rem] rounded-lg border border-stone-300 bg-white px-2 py-1.5 text-sm text-stone-800" placeholder="7:30 AM">
                                                                            <p class="text-[11px] text-stone-500">Choose a preset or type any hour/minute.</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-3 py-3 align-top">
                                                                    <div class="flex h-[96px] items-stretch gap-2 rounded-xl border border-stone-200 bg-stone-50 p-2" data-itinerary-activity-slot>
                                                                        <textarea name="itinerary_activity[]" rows="2" class="h-full flex-1 resize-none rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800" placeholder="Arrival, transfer, check-in, and evening city walk">{{ $row['activity'] }}</textarea>
                                                                    </div>
                                                                </td>
                                                                <td class="px-3 py-3 align-top text-center">
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
                                        </div>
                                        <template data-package-itinerary-day-template>
                                            <tr data-itinerary-slot-row>
                                                <td rowspan="1" class="px-3 py-3 align-top" data-itinerary-day-cell>
                                                    <input type="text" value="" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" data-itinerary-day-label>
                                                    <div class="mt-3 flex justify-start">
                                                        <button type="button" class="inline-flex items-center justify-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-rose-700 transition hover:bg-rose-100" data-package-itinerary-remove-day aria-label="Remove day row">
                                                            Delete Row
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-3 align-top">
                                                    <div class="flex h-[96px] min-w-[9rem] items-stretch rounded-xl border border-stone-200 bg-stone-50 p-2" data-itinerary-time-slot>
                                                        <input type="hidden" name="itinerary_day_number[]" value="" data-itinerary-day-hidden>
                                                        <div class="flex w-full flex-col gap-1">
                                                            <input name="itinerary_time[]" type="text" value="" list="{{ $itineraryTimeOptionsId }}" class="h-full w-full min-w-[7.75rem] rounded-lg border border-stone-300 bg-white px-2 py-1.5 text-sm text-stone-800" placeholder="7:30 AM">
                                                            <p class="text-[11px] text-stone-500">Choose a preset or type any hour/minute.</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-3 align-top">
                                                    <div class="flex h-[96px] items-stretch gap-2 rounded-xl border border-stone-200 bg-stone-50 p-2" data-itinerary-activity-slot>
                                                        <textarea name="itinerary_activity[]" rows="2" class="h-full flex-1 resize-none rounded-lg border border-stone-300 bg-white px-2.5 py-1.5 text-sm text-stone-800" placeholder="Arrival, transfer, check-in, and evening city walk"></textarea>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-3 align-top text-center">
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
                                        <div class="flex flex-wrap justify-between gap-3 border-t border-stone-200 bg-stone-100 pt-4">
                                            <button type="button" class="rounded-full border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700 transition hover:bg-emerald-100" data-package-itinerary-add-day>
                                                + Day
                                            </button>
                                            <div class="flex flex-wrap justify-end gap-3">
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
                            <div class="package-service-inclusion-modal hidden fixed inset-0 z-[410] items-start justify-center overflow-y-auto bg-stone-950/55 px-6 py-6 md:px-8" data-package-service-inclusion-modal>
                                <div class="flex max-h-[calc(100vh-3rem)] w-full max-w-[96rem] flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-stone-100 p-5 shadow-[0_24px_60px_rgba(15,23,42,0.24)]">
                                    <form id="{{ $packageDetailsFormId }}" method="POST" action="{{ route('admin.packages.package-details', $product) }}" class="flex min-h-0 flex-1 flex-col gap-5" data-form-persist="admin-products-package-details-{{ $product->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Package Details</p>
                                                <h3 class="mt-1 text-2xl font-semibold text-stone-900">{{ $product->name }}</h3>
                                                <p class="mt-2 text-sm text-stone-500">Manage includes, excludes, things to bring, and important notes here.</p>
                                            </div>
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-package-service-inclusion-close>
                                                Close
                                            </button>
                                        </div>
                                        <div class="min-h-0 flex-1 overflow-y-auto pr-2" data-package-content-scroll>
                                        <div class="grid gap-4" data-package-content-sections>
                                            <section class="rounded-[1.25rem] border border-stone-200 bg-white p-4">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <h4 class="text-base font-semibold text-stone-900">Package Details</h4>
                                                        <p class="mt-1 text-xs text-stone-500">Use the symbol button for the row type, then highlight text inside the editor to format it.</p>
                                                    </div>
                                                </div>
                                                <div class="mt-4 grid gap-4 md:grid-cols-2 items-start">
                                                    <section class="rounded-[1rem] border border-stone-200 bg-stone-50 p-4">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <h5 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-700">Includes</h5>
                                                        </div>
                                                        <div class="mt-3">
                                                            <input type="hidden" name="package_detail_include_symbol[]" value="tick">
                                                            <div class="min-w-0" data-package-rich-editor-wrapper>
                                                                <input type="hidden" name="package_detail_include_value[]" value="{{ $packageIncludeHtml }}" data-package-rich-editor-input>
                                                                <div
                                                                    contenteditable="true"
                                                                    class="min-h-[5rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                                                    data-package-rich-editor
                                                                    data-placeholder="Hotel accommodation, guide, and transfers included."
                                                                >{!! $packageIncludeHtml !!}</div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <section class="rounded-[1rem] border border-stone-200 bg-stone-50 p-4">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <h5 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-700">Excludes</h5>
                                                        </div>
                                                        <div class="mt-3">
                                                            <input type="hidden" name="package_detail_exclude_symbol[]" value="x">
                                                            <div class="min-w-0" data-package-rich-editor-wrapper>
                                                                <input type="hidden" name="package_detail_exclude_value[]" value="{{ $packageExcludeHtml }}" data-package-rich-editor-input>
                                                                <div
                                                                    contenteditable="true"
                                                                    class="min-h-[5rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                                                    data-package-rich-editor
                                                                    data-placeholder="Flights, insurance, and personal expenses."
                                                                >{!! $packageExcludeHtml !!}</div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <section class="rounded-[1rem] border border-stone-200 bg-stone-50 p-4">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <h5 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-700">Things To Bring</h5>
                                                        </div>
                                                        <div class="mt-3">
                                                            <input type="hidden" name="package_detail_bring_symbol[]" value="exclamation">
                                                            <div class="min-w-0" data-package-rich-editor-wrapper>
                                                                <input type="hidden" name="package_detail_bring_value[]" value="{{ $packageBringHtml }}" data-package-rich-editor-input>
                                                                <div
                                                                    contenteditable="true"
                                                                    class="min-h-[5rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                                                    data-package-rich-editor
                                                                    data-placeholder="Water bottle, sunblock, and a change of clothes."
                                                                >{!! $packageBringHtml !!}</div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <section class="rounded-[1rem] border border-stone-200 bg-stone-50 p-4">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <h5 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-700">Important Notes</h5>
                                                        </div>
                                                        <div class="mt-3">
                                                            <input type="hidden" name="package_detail_note_symbol[]" value="exclamation">
                                                            <div class="min-w-0" data-package-rich-editor-wrapper>
                                                                <input type="hidden" name="package_detail_note_value[]" value="{{ $packageNoteHtml }}" data-package-rich-editor-input>
                                                                <div
                                                                    contenteditable="true"
                                                                    class="min-h-[5rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                                                    data-package-rich-editor
                                                                    data-placeholder="Timing may change due to weather or traffic conditions."
                                                                >{!! $packageNoteHtml !!}</div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </section>
                                        </div>
                                        </div>
                                        <div class="fixed z-[460] hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-[0_18px_45px_rgba(15,23,42,0.18)]" data-package-rich-toolbar data-package-rich-toolbar-mode="floating">
                                            <div class="flex flex-wrap items-center gap-1">
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100" data-package-rich-action="bold">B</button>
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm italic text-stone-700 transition hover:bg-stone-100" data-package-rich-action="italic">I</button>
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm underline text-stone-700 transition hover:bg-stone-100" data-package-rich-action="underline">U</button>
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="tick">&#10003;</button>
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="round">&#9679;</button>
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="x">&#10006;</button>
                                                <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="warning">&#9888;</button>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap justify-end gap-3 border-t border-stone-200 bg-stone-100 pt-4">
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-stone-700 transition hover:bg-stone-100" data-package-service-inclusion-close>
                                                Cancel
                                            </button>
                                            <button type="submit" class="rounded-full bg-sky-600 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white transition hover:bg-sky-700">
                                                Save Details
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="package-content-modal hidden fixed inset-0 z-[411] items-start justify-center overflow-y-auto bg-stone-950/55 px-6 py-6 md:px-8" data-package-content-modal>
                                <div class="flex max-h-[calc(100vh-3rem)] w-full max-w-[96rem] flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-stone-100 p-5 shadow-[0_24px_60px_rgba(15,23,42,0.24)]">
                                    <form id="{{ $packageContentFormId }}" method="POST" action="{{ route('admin.packages.package-content', $product) }}" class="flex min-h-0 flex-1 flex-col gap-5" data-form-persist="admin-products-package-content-{{ $product->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Other Package Content</p>
                                                <h3 class="mt-1 text-2xl font-semibold text-stone-900">{{ $product->name }}</h3>
                                                <p class="mt-2 text-sm text-stone-500">Manage highlights, attire, things you should know, and travel tips here.</p>
                                            </div>
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-package-content-close>
                                                Close
                                            </button>
                                        </div>
                                        <div class="min-h-0 flex-1 overflow-y-auto pr-2" data-package-other-content-scroll>
                                        <div class="grid gap-4 md:grid-cols-2 items-start" data-package-other-content-sections>
                                            <section class="rounded-[1.25rem] border border-stone-200 bg-white p-4">
                                                <div>
                                                    <h4 class="text-base font-semibold text-stone-900">Tour Highlights</h4>
                                                    <p class="mt-1 text-xs text-stone-500">One description box. Use the toolbar for points and symbols.</p>
                                                </div>
                                                <div class="mt-4 min-w-0" data-package-rich-editor-wrapper>
                                                    <input type="hidden" name="tour_highlights" value="{{ $packageTourHighlightsHtml }}" data-package-rich-editor-input>
                                                    <div contenteditable="true" class="min-h-[8rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" data-package-rich-editor data-placeholder="Scenic coastal drive, snorkelling spots, and sunset beach moments.">{!! $packageTourHighlightsHtml !!}</div>
                                                </div>
                                            </section>
                                            <section class="rounded-[1.25rem] border border-stone-200 bg-white p-4">
                                                <div>
                                                    <h4 class="text-base font-semibold text-stone-900">Recommended Attire</h4>
                                                    <p class="mt-1 text-xs text-stone-500">One description box. Use the toolbar for points and symbols.</p>
                                                </div>
                                                <div class="mt-4 min-w-0" data-package-rich-editor-wrapper>
                                                    <input type="hidden" name="recommended_attire" value="{{ $packageRecommendedAttireHtml }}" data-package-rich-editor-input>
                                                    <div contenteditable="true" class="min-h-[8rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" data-package-rich-editor data-placeholder="Wear light clothing, comfortable footwear, and bring sun protection.">{!! $packageRecommendedAttireHtml !!}</div>
                                                </div>
                                            </section>
                                            <section class="rounded-[1.25rem] border border-stone-200 bg-white p-4">
                                                <div>
                                                    <h4 class="text-base font-semibold text-stone-900">Things You Should Know</h4>
                                                    <p class="mt-1 text-xs text-stone-500">One description box. Use the toolbar for points and symbols.</p>
                                                </div>
                                                <div class="mt-4 min-w-0" data-package-rich-editor-wrapper>
                                                    <input type="hidden" name="things_to_know" value="{{ $packageThingsToKnowHtml }}" data-package-rich-editor-input>
                                                    <div contenteditable="true" class="min-h-[8rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" data-package-rich-editor data-placeholder="Weather, timing, and operator arrangements may affect the final flow.">{!! $packageThingsToKnowHtml !!}</div>
                                                </div>
                                            </section>
                                            <section class="rounded-[1.25rem] border border-stone-200 bg-white p-4">
                                                <div>
                                                    <h4 class="text-base font-semibold text-stone-900">Useful Travel Tips</h4>
                                                    <p class="mt-1 text-xs text-stone-500">One description box. Use the toolbar for points and symbols.</p>
                                                </div>
                                                <div class="mt-4 min-w-0" data-package-rich-editor-wrapper>
                                                    <input type="hidden" name="travel_tips" value="{{ $packageTravelTipsHtml }}" data-package-rich-editor-input>
                                                    <div contenteditable="true" class="min-h-[8rem] rounded-xl border border-sky-200 bg-white px-4 py-3 text-sm leading-6 text-stone-800 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" data-package-rich-editor data-placeholder="Bring water, a charged phone, and any personal essentials.">{!! $packageTravelTipsHtml !!}</div>
                                                </div>
                                            </section>
                                        </div>
                                        </div>
                                        <div class="flex flex-wrap justify-between gap-3 border-t border-stone-200 bg-stone-100 pt-4">
                                            <div></div>
                                            <div class="flex gap-3">
                                                <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-stone-700 transition hover:bg-stone-100" data-package-content-close>
                                                    Cancel
                                                </button>
                                                <button type="submit" class="rounded-full bg-sky-600 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white transition hover:bg-sky-700">
                                                    Save Content
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="fixed z-[460] hidden rounded-2xl border border-stone-200 bg-white p-2 shadow-[0_18px_45px_rgba(15,23,42,0.18)]" data-package-rich-toolbar data-package-rich-toolbar-mode="floating">
                                        <div class="flex flex-wrap items-center gap-1">
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100" data-package-rich-action="bold">B</button>
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm italic text-stone-700 transition hover:bg-stone-100" data-package-rich-action="italic">I</button>
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm underline text-stone-700 transition hover:bg-stone-100" data-package-rich-action="underline">U</button>
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="tick">&#10003;</button>
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="round">&#9679;</button>
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="x">&#10006;</button>
                                            <button type="button" class="inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-stone-200 px-2 text-sm text-stone-700 transition hover:bg-stone-100" data-package-rich-action="warning">&#9888;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="package-optional-activities-modal hidden fixed inset-0 z-[412] items-start justify-center overflow-y-auto bg-stone-950/55 px-6 py-6 md:px-8" data-package-optional-activities-modal>
                                <div class="flex max-h-[calc(100vh-3rem)] w-full max-w-4xl flex-col overflow-hidden rounded-[2rem] border border-stone-200 bg-stone-100 p-5 shadow-[0_24px_60px_rgba(15,23,42,0.24)]">
                                    <form id="{{ $optionalActivitiesFormId }}" method="POST" action="{{ route('admin.packages.optional-activities', $product) }}" class="flex min-h-0 flex-1 flex-col gap-5" data-form-persist="admin-products-optional-activities-{{ $product->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-medium uppercase tracking-[0.02em] text-stone-500">Optional Activities</p>
                                                <h3 class="mt-1 text-2xl font-semibold text-stone-900">{{ $product->name }}</h3>
                                                <p class="mt-2 text-sm text-stone-500">Choose whether this section appears on the user package page, then list any add-on activities.</p>
                                            </div>
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-package-optional-activities-close>
                                                Close
                                            </button>
                                        </div>
                                        <div class="min-h-0 flex-1 overflow-y-auto pr-2" data-package-optional-activities-sections>
                                            <section class="rounded-[1.25rem] border border-stone-200 bg-white p-5">
                                                <div>
                                                    <h4 class="text-base font-semibold text-stone-900">Section Description</h4>
                                                    <p class="mt-1 text-xs text-stone-500">Add a short intro above the extra activities table on the user package page.</p>
                                                </div>
                                                <textarea name="optional_activities_description" rows="4" class="mt-4 w-full rounded-lg border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" placeholder="Optional activities below can be arranged with extra charges depending on availability and weather conditions.">{{ $packageOptionalActivitiesDescription }}</textarea>
                                            </section>
                                            <section class="mt-4 rounded-[1.25rem] border border-stone-200 bg-white p-5">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div>
                                                        <h4 class="text-base font-semibold text-stone-900">Extra Activities Table</h4>
                                                        <p class="mt-1 text-xs text-stone-500">Keep at least 2 columns. I used Activity and Rate / Price so each add-on shows its charge clearly.</p>
                                                    </div>
                                                    <button type="button" class="rounded-full border border-violet-300 bg-violet-50 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.14em] text-violet-700 transition hover:bg-violet-100" data-package-content-add-row="optional-activities">Add Row</button>
                                                </div>
                                                <div class="mt-4 overflow-x-auto rounded-[1rem] border border-stone-200">
                                                    <table class="min-w-full text-left text-sm">
                                                        <thead class="bg-stone-100 text-stone-700">
                                                            <tr>
                                                                <th class="px-4 py-3 font-semibold">Activity</th>
                                                                <th class="px-4 py-3 font-semibold">Rate / Price</th>
                                                                <th class="w-20 px-4 py-3 font-semibold text-center">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-stone-200 bg-white" data-package-content-body="optional-activities">
                                                            @foreach ($packageOptionalActivitiesRows as $activity)
                                                                <tr data-package-content-row>
                                                                    <td class="px-4 py-3 align-top">
                                                                        <input name="optional_activity_name[]" type="text" value="{{ $activity['name'] }}" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="ATV Ride">
                                                                    </td>
                                                                    <td class="px-4 py-3 align-top">
                                                                        <input name="optional_activity_rate[]" type="text" value="{{ $activity['rate'] }}" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="RM 120 per person">
                                                                    </td>
                                                                    <td class="px-4 py-3 text-center align-top">
                                                                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100" data-package-content-remove-row aria-label="Remove row"><span class="text-lg leading-none">&times;</span></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
                                        </div>
                                        <template data-package-content-template="optional-activities">
                                            <tr data-package-content-row>
                                                <td class="px-4 py-3 align-top">
                                                    <input name="optional_activity_name[]" type="text" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="ATV Ride">
                                                </td>
                                                <td class="px-4 py-3 align-top">
                                                    <input name="optional_activity_rate[]" type="text" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800" placeholder="RM 120 per person">
                                                </td>
                                                <td class="px-4 py-3 text-center align-top">
                                                    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100" data-package-content-remove-row aria-label="Remove row"><span class="text-lg leading-none">&times;</span></button>
                                                </td>
                                            </tr>
                                        </template>
                                        <div class="flex flex-wrap justify-end gap-3 border-t border-stone-200 bg-stone-100 pt-4">
                                            <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-stone-700 transition hover:bg-stone-100" data-package-optional-activities-close>
                                                Cancel
                                            </button>
                                            <button type="submit" class="rounded-full bg-sky-600 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white transition hover:bg-sky-700">
                                                Save Optional Activities
                                            </button>
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

                        <form method="POST" action="{{ $isPackage ? route('admin.packages.destroy', $product) : route('admin.products.destroy', $product) }}" @if (! $isPackage) onsubmit="return confirm('Delete this product?');" @endif class="{{ $isPackage ? '' : 'w-full' }}" @if ($isPackage) data-package-delete-form @endif>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="min-w-[8.75rem] rounded-full border border-rose-300 bg-white px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50 {{ $isPackage ? '' : 'w-full' }}" @if ($isPackage) data-package-delete-button @endif>
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

<style>
    [data-package-rich-editor] {
        white-space: normal;
        word-break: break-word;
    }

    [data-package-rich-editor]:empty::before {
        content: attr(data-placeholder);
        color: rgb(168 162 158);
    }

    [data-package-rich-editor] p {
        margin: 0 0 0.45rem;
    }

    [data-package-rich-editor] p:last-child {
        margin-bottom: 0;
    }

    [data-package-rich-editor] ul,
    [data-package-rich-editor] ol {
        margin: 0.45rem 0;
        padding-left: 1.4rem;
        list-style: none;
    }

    [data-package-rich-editor] ul[data-point-style="tick"],
    [data-package-rich-editor] ul[data-point-style="round"],
    [data-package-rich-editor] ul[data-point-style="x"],
    [data-package-rich-editor] ul[data-point-style="warning"] {
        list-style: none;
        padding-left: 0;
    }

    [data-package-rich-editor] ul[data-point-style="tick"] li,
    [data-package-rich-editor] ul[data-point-style="round"] li,
    [data-package-rich-editor] ul[data-point-style="x"] li,
    [data-package-rich-editor] ul[data-point-style="warning"] li {
        position: relative;
        padding-left: 1.6rem;
    }

    [data-package-rich-editor] ul[data-point-style="tick"] li::before,
    [data-package-rich-editor] ul[data-point-style="round"] li::before,
    [data-package-rich-editor] ul[data-point-style="x"] li::before,
    [data-package-rich-editor] ul[data-point-style="warning"] li::before {
        position: absolute;
        left: 0;
        top: 0;
        font-weight: 700;
    }

    [data-package-rich-editor] ul[data-point-style="tick"] li::before {
        content: "\2713";
        color: rgb(21 128 61);
    }

    [data-package-rich-editor] ul[data-point-style="round"] li::before {
        content: "\2022";
        color: rgb(87 83 78);
    }

    [data-package-rich-editor] ul[data-point-style="x"] li::before {
        content: "\2716";
        color: rgb(185 28 28);
    }

    [data-package-rich-editor] ul[data-point-style="warning"] li::before {
        content: "\26A0";
        color: rgb(180 83 9);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-package-inline-row]').forEach((row) => {
            const form = row.querySelector('[data-package-inline-form]');
            const viewSection = row.querySelector('.package-inline-view');
            const editSection = row.querySelector('.package-inline-edit');
            const editPanel = row.querySelector('[data-package-inline-panel]');
            const editButton = row.querySelector('[data-package-inline-edit]');
            const packageTypeSelect = row.querySelector('[data-package-edit-type]');
            const durationInput = row.querySelector('[data-package-edit-duration]');
            const tourCodeInput = row.querySelector('[data-package-edit-tour-code]');
            const minimumAgeMode = row.querySelector('[data-package-edit-minimum-age-mode]');
            const minimumAgeYears = row.querySelector('[data-package-edit-minimum-age-years]');
            const pricingRows = row.querySelector('[data-package-edit-pricing-rows]');
            const pricingTemplate = row.querySelector('[data-package-edit-pricing-template]');
            const pricingAddButton = row.querySelector('[data-package-edit-pricing-add]');
            const itineraryButton = row.querySelector('[data-package-inline-open="itinerary"]');
            const packageDetailsButton = row.querySelector('[data-package-inline-open="package-details"]');
            const packageContentButton = row.querySelector('[data-package-inline-open="package-content"]');
            const optionalActivitiesButton = row.querySelector('[data-package-inline-open="optional-activities"]');
            const cancelButton = row.querySelector('[data-package-inline-cancel]');
            const saveButton = row.querySelector('.package-inline-save');
            const itineraryModal = row.querySelector('[data-package-itinerary-modal]');
            const itineraryForm = itineraryModal?.querySelector('form');
            const itineraryCloseButtons = row.querySelectorAll('[data-package-itinerary-close]');
            const itineraryTableBody = row.querySelector('[data-package-itinerary-table-body]');
            const itineraryDayTemplate = row.querySelector('[data-package-itinerary-day-template]');
            const itineraryAddDayButton = row.querySelector('[data-package-itinerary-add-day]');
            const serviceInclusionModal = row.querySelector('[data-package-service-inclusion-modal]');
            const serviceInclusionCloseButtons = row.querySelectorAll('[data-package-service-inclusion-close]');
            const packageDetailsForm = serviceInclusionModal?.querySelector('form');
            const packageContentModal = row.querySelector('[data-package-content-modal]');
            const packageContentForm = packageContentModal?.querySelector('form');
            const packageContentCloseButtons = row.querySelectorAll('[data-package-content-close]');
            const optionalActivitiesModal = row.querySelector('[data-package-optional-activities-modal]');
            const optionalActivitiesCloseButtons = row.querySelectorAll('[data-package-optional-activities-close]');
            const packageRichToolbars = Array.from(row.querySelectorAll('[data-package-rich-toolbar]'));
            const packageDeleteForm = row.querySelector('[data-package-delete-form]');
            const packageDeleteButton = row.querySelector('[data-package-delete-button]');
            let activeRichEditor = null;
            let savedRichSelection = null;

            if (!form || !viewSection || !editSection || !editButton || !cancelButton || !saveButton) {
                return;
            }

            const findClosestParent = (node, tagName) => {
                let currentNode = node instanceof Node ? node : null;

                while (currentNode) {
                    if (currentNode.nodeType === Node.ELEMENT_NODE && currentNode.nodeName === tagName) {
                        return currentNode;
                    }

                    currentNode = currentNode.parentNode;
                }

                return null;
            };

            const isRichEditorEmpty = (editor) => {
                if (!editor) {
                    return true;
                }

                const content = (editor.textContent || '').replace(/\u00A0/g, ' ').trim();

                return content === '' && !editor.querySelector('li');
            };

            const normalizeRichEditorHtml = (editor) => {
                if (!editor) {
                    return '';
                }

                if (isRichEditorEmpty(editor)) {
                    return '';
                }

                return editor.innerHTML.trim();
            };

            const syncRichEditorInput = (editor) => {
                const wrapper = editor?.closest('[data-package-rich-editor-wrapper]');
                const input = wrapper?.querySelector('[data-package-rich-editor-input]');

                if (!input) {
                    return;
                }

                input.value = normalizeRichEditorHtml(editor);
            };

            const getFloatingRichToolbars = () => {
                return packageRichToolbars.filter((toolbar) => toolbar.dataset.packageRichToolbarMode === 'floating');
            };

            const getRichToolbarForEditor = (editor) => {
                const editorModal = editor?.closest('[data-package-content-modal], [data-package-service-inclusion-modal]');

                return editorModal?.querySelector('[data-package-rich-toolbar][data-package-rich-toolbar-mode="floating"]')
                    ?? getFloatingRichToolbars()[0]
                    ?? null;
            };

            const hideRichToolbar = (clearActiveEditor = false) => {
                getFloatingRichToolbars().forEach((toolbar) => {
                    toolbar.classList.add('hidden');
                });

                if (clearActiveEditor) {
                    activeRichEditor = null;
                }
            };

            const selectionBelongsToEditor = (selection, editor) => {
                if (!selection || !editor || selection.rangeCount === 0) {
                    return false;
                }

                const range = selection.getRangeAt(0);

                return editor.contains(range.commonAncestorContainer);
            };

            const saveRichSelection = () => {
                const selection = window.getSelection();

                if (!selection || selection.rangeCount === 0 || !activeRichEditor || !selectionBelongsToEditor(selection, activeRichEditor)) {
                    savedRichSelection = null;

                    return;
                }

                savedRichSelection = selection.getRangeAt(0).cloneRange();
            };

            const restoreRichSelection = () => {
                if (!savedRichSelection) {
                    return false;
                }

                const selection = window.getSelection();

                if (!selection) {
                    return false;
                }

                selection.removeAllRanges();
                selection.addRange(savedRichSelection);

                return true;
            };

            const updateRichToolbarPosition = () => {
                const selection = window.getSelection();
                const packageRichToolbar = getRichToolbarForEditor(activeRichEditor);

                if (!packageRichToolbar || !selection || selection.rangeCount === 0 || !activeRichEditor || selection.isCollapsed || !selectionBelongsToEditor(selection, activeRichEditor)) {
                    hideRichToolbar();

                    return;
                }

                if (packageRichToolbar.dataset.packageRichToolbarMode !== 'floating') {
                    return;
                }

                const rect = selection.getRangeAt(0).getBoundingClientRect();

                if (!rect || (!rect.width && !rect.height)) {
                    hideRichToolbar();

                    return;
                }

                packageRichToolbar.classList.remove('hidden');
                const toolbarHeight = packageRichToolbar.offsetHeight || 48;
                const toolbarWidth = packageRichToolbar.offsetWidth || 280;
                const left = Math.min(Math.max(rect.left + (rect.width / 2) - (toolbarWidth / 2), 16), window.innerWidth - toolbarWidth - 16);
                const top = Math.max(rect.top - toolbarHeight - 12, 16);

                packageRichToolbar.style.left = `${left}px`;
                packageRichToolbar.style.top = `${top}px`;
            };

            const focusRichEditor = (editor) => {
                if (!editor) {
                    return;
                }

                activeRichEditor = editor;
                editor.focus();
            };

            const setSelectionToNodeContents = (node) => {
                const selection = window.getSelection();

                if (!selection || !node) {
                    return;
                }

                const range = document.createRange();
                range.selectNodeContents(node);
                selection.removeAllRanges();
                selection.addRange(range);
                savedRichSelection = range.cloneRange();
            };

            const normalizeListItemText = (value) => {
                return String(value || '')
                    .replace(/^\s*(?:[â€¢â—â—‹â—¦â–ªâ–«âœ“âœ”âœ•âœ–âœ—âŒâš !]+|\d+[.)])\s*/u, '')
                    .trim();
            };

            const buildListItemsFromSelection = (range) => {
                const container = document.createElement('div');
                container.appendChild(range.cloneContents());

                return (container.innerText || container.textContent || '')
                    .split(/\r?\n+/)
                    .map((line) => normalizeListItemText(line))
                    .filter((line) => line !== '');
            };

            const wrapSelectionWithTag = (tagName) => {
                const selection = window.getSelection();

                if (!selection || selection.rangeCount === 0) {
                    return;
                }

                const range = selection.getRangeAt(0);

                if (range.collapsed) {
                    return;
                }

                const wrapper = document.createElement(tagName);
                const fragment = range.extractContents();

                wrapper.appendChild(fragment);
                range.insertNode(wrapper);
                setSelectionToNodeContents(wrapper);
                syncRichEditorInput(activeRichEditor);
                updateRichToolbarPosition();
            };

            const unwrapListElement = (list) => {
                if (!list || !list.parentNode) {
                    return;
                }

                const fragment = document.createDocumentFragment();

                Array.from(list.children).forEach((listItem) => {
                    const paragraph = document.createElement('p');
                    paragraph.textContent = normalizeListItemText(listItem.textContent || '');
                    fragment.appendChild(paragraph);
                });

                const parent = list.parentNode;
                parent.insertBefore(fragment, list);
                parent.removeChild(list);
            };

            const applyListStyleToSelection = (style) => {
                if (!activeRichEditor) {
                    return;
                }

                focusRichEditor(activeRichEditor);
                restoreRichSelection();
                const selection = window.getSelection();

                if (!selection || selection.rangeCount === 0) {
                    return;
                }

                const range = selection.getRangeAt(0);
                const existingList = findClosestParent(range.commonAncestorContainer, 'UL')
                    ?? findClosestParent(range.commonAncestorContainer, 'OL');

                if (existingList instanceof HTMLElement) {
                    const existingStyle = existingList.getAttribute('data-point-style') || (existingList.tagName === 'OL' ? 'ordered' : 'unordered');

                    Array.from(existingList.querySelectorAll('li')).forEach((listItem) => {
                        listItem.textContent = normalizeListItemText(listItem.textContent || '');
                    });

                    if (existingStyle === style) {
                        unwrapListElement(existingList);
                        syncRichEditorInput(activeRichEditor);
                        hideRichToolbar();

                        return;
                    }

                    if (style === 'ordered') {
                        if (existingList.tagName !== 'OL') {
                            const orderedList = document.createElement('ol');

                            while (existingList.firstChild) {
                                orderedList.appendChild(existingList.firstChild);
                            }

                            existingList.parentNode?.replaceChild(orderedList, existingList);
                            setSelectionToNodeContents(orderedList);
                            syncRichEditorInput(activeRichEditor);
                            updateRichToolbarPosition();

                            return;
                        }

                        existingList.removeAttribute('data-point-style');
                        setSelectionToNodeContents(existingList);
                        syncRichEditorInput(activeRichEditor);
                        updateRichToolbarPosition();

                        return;
                    }

                    if (existingList.tagName === 'OL') {
                        const unorderedList = document.createElement('ul');

                        while (existingList.firstChild) {
                            unorderedList.appendChild(existingList.firstChild);
                        }

                        existingList.parentNode?.replaceChild(unorderedList, existingList);
                        unorderedList.setAttribute('data-point-style', style === 'unordered' ? '' : style);

                        if (style === 'unordered') {
                            unorderedList.removeAttribute('data-point-style');
                        }

                        setSelectionToNodeContents(unorderedList);
                        syncRichEditorInput(activeRichEditor);
                        updateRichToolbarPosition();

                        return;
                    }

                    if (style === 'unordered') {
                        existingList.removeAttribute('data-point-style');
                    } else {
                        existingList.setAttribute('data-point-style', style);
                    }

                    setSelectionToNodeContents(existingList);
                    syncRichEditorInput(activeRichEditor);
                    updateRichToolbarPosition();

                    return;
                }

                const items = buildListItemsFromSelection(range);

                if (items.length === 0) {
                    return;
                }

                const list = document.createElement(style === 'ordered' ? 'ol' : 'ul');

                if (!['unordered', 'ordered'].includes(style)) {
                    list.setAttribute('data-point-style', style);
                }

                items.forEach((item) => {
                    const listItem = document.createElement('li');
                    listItem.textContent = item;
                    list.appendChild(listItem);
                });

                range.deleteContents();
                range.insertNode(list);
                setSelectionToNodeContents(list);
                syncRichEditorInput(activeRichEditor);
                updateRichToolbarPosition();
            };

            const initializeRichEditor = (editor) => {
                if (!editor || editor.dataset.richReady === 'true') {
                    return;
                }

                editor.dataset.richReady = 'true';
                editor.setAttribute('spellcheck', 'true');

                if (isRichEditorEmpty(editor)) {
                    editor.innerHTML = '';
                }

                editor.addEventListener('focus', () => {
                    activeRichEditor = editor;
                });

                editor.addEventListener('input', () => {
                    syncRichEditorInput(editor);
                });

                editor.addEventListener('mouseup', () => {
                    activeRichEditor = editor;
                    window.setTimeout(() => {
                        saveRichSelection();
                        updateRichToolbarPosition();
                    }, 0);
                });

                editor.addEventListener('keyup', () => {
                    activeRichEditor = editor;
                    window.setTimeout(() => {
                        saveRichSelection();
                        updateRichToolbarPosition();
                    }, 0);
                });

                editor.addEventListener('blur', () => {
                    syncRichEditorInput(editor);

                    window.setTimeout(() => {
                        const selection = window.getSelection();

                        if (!selectionBelongsToEditor(selection, editor)) {
                            hideRichToolbar(true);
                        }
                    }, 120);
                });

                editor.addEventListener('keydown', (event) => {
                    if (event.key !== 'Enter' || event.shiftKey) {
                        return;
                    }

                    if (findClosestParent(window.getSelection()?.anchorNode, 'LI')) {
                        return;
                    }

                    event.preventDefault();
                    document.execCommand('insertParagraph');
                    syncRichEditorInput(editor);
                });

                syncRichEditorInput(editor);
            };

            row.querySelectorAll('[data-package-rich-editor]').forEach(initializeRichEditor);

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

            const syncPackageFields = () => {
                if (!packageTypeSelect || !durationInput || !tourCodeInput) {
                    return;
                }

                const isDayTrip = packageTypeSelect.value === 'Day Trip';
                durationInput.disabled = !isDayTrip;
                durationInput.required = isDayTrip;
                tourCodeInput.placeholder = isDayTrip ? 'DT-UEH01' : 'OT-UEH01';
                tourCodeInput.value = normalizeTourCodeValue(tourCodeInput.value, isDayTrip);

                if (!isDayTrip) {
                    durationInput.value = '';
                }
            };

            const syncMinimumAgeFields = () => {
                if (!minimumAgeMode || !minimumAgeYears) {
                    return;
                }

                const requiresAge = minimumAgeMode.value === 'above_age';
                minimumAgeYears.disabled = !requiresAge;
                minimumAgeYears.required = requiresAge;

                if (!requiresAge) {
                    minimumAgeYears.value = '';
                }
            };

            packageTypeSelect?.addEventListener('change', syncPackageFields);
            minimumAgeMode?.addEventListener('change', syncMinimumAgeFields);
            pricingAddButton?.addEventListener('click', () => {
                const templateContent = pricingTemplate?.content?.cloneNode(true);

                if (!templateContent || !pricingRows) {
                    return;
                }

                pricingRows.appendChild(templateContent);
            });
            pricingRows?.addEventListener('click', (event) => {
                const removeButton = event.target.closest('[data-package-edit-pricing-remove]');

                if (!removeButton) {
                    return;
                }

                const currentRow = removeButton.closest('[data-package-edit-pricing-row]');

                if (!currentRow || pricingRows.querySelectorAll('[data-package-edit-pricing-row]').length <= 1) {
                    currentRow?.querySelectorAll('input').forEach((input) => {
                        input.value = '';
                    });
                    return;
                }

                currentRow.remove();
            });
            tourCodeInput?.addEventListener('input', () => {
                if (!packageTypeSelect) {
                    return;
                }

                const normalizedValue = normalizeTourCodeValue(tourCodeInput.value, packageTypeSelect.value === 'Day Trip');

                if (tourCodeInput.value !== normalizedValue) {
                    tourCodeInput.value = normalizedValue;
                }
            });

            syncPackageFields();
            syncMinimumAgeFields();

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
                const isPackageContentOpen = packageContentModal && !packageContentModal.classList.contains('hidden');

                setRowOverlayState(isEditOpen || isItineraryOpen || isServiceInclusionOpen || isPackageContentOpen);
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

            const closeItineraryModal = () => {
                itineraryModal?.classList.add('hidden');
                itineraryModal?.classList.remove('flex');
                syncRowOverlayState();
            };

            itineraryCloseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    closeItineraryModal();
                });
            });

            itineraryModal?.addEventListener('click', (event) => {
                if (event.target !== itineraryModal) {
                    return;
                }

                closeItineraryModal();
            });

            packageDetailsButton?.addEventListener('click', () => {
                setRowOverlayState(true);
                serviceInclusionModal?.classList.remove('hidden');
                serviceInclusionModal?.classList.add('flex');
            });

            packageContentButton?.addEventListener('click', () => {
                setRowOverlayState(true);
                packageContentModal?.classList.remove('hidden');
                packageContentModal?.classList.add('flex');
            });

            optionalActivitiesButton?.addEventListener('click', () => {
                setRowOverlayState(true);
                optionalActivitiesModal?.classList.remove('hidden');
                optionalActivitiesModal?.classList.add('flex');
            });

            serviceInclusionCloseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    serviceInclusionModal?.classList.add('hidden');
                    serviceInclusionModal?.classList.remove('flex');
                    hideRichToolbar(true);
                    syncRowOverlayState();
                });
            });

            serviceInclusionModal?.addEventListener('click', (event) => {
                if (event.target !== serviceInclusionModal) {
                    return;
                }

                serviceInclusionModal.classList.add('hidden');
                serviceInclusionModal.classList.remove('flex');
                hideRichToolbar(true);
                syncRowOverlayState();
            });

            packageContentCloseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    packageContentModal?.classList.add('hidden');
                    packageContentModal?.classList.remove('flex');
                    syncRowOverlayState();
                });
            });

            packageContentModal?.addEventListener('click', (event) => {
                if (event.target !== packageContentModal) {
                    return;
                }

                packageContentModal.classList.add('hidden');
                packageContentModal.classList.remove('flex');
                syncRowOverlayState();
            });

            optionalActivitiesCloseButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    optionalActivitiesModal?.classList.add('hidden');
                    optionalActivitiesModal?.classList.remove('flex');
                    syncRowOverlayState();
                });
            });

            optionalActivitiesModal?.addEventListener('click', (event) => {
                if (event.target !== optionalActivitiesModal) {
                    return;
                }

                optionalActivitiesModal.classList.add('hidden');
                optionalActivitiesModal.classList.remove('flex');
                syncRowOverlayState();
            });

            const closeSymbolPickers = (scope = row) => {
                scope?.querySelectorAll('[data-symbol-picker-menu]').forEach((menu) => {
                    menu.classList.add('hidden');
                });

                scope?.querySelectorAll('[data-symbol-picker-trigger]').forEach((trigger) => {
                    trigger.setAttribute('aria-expanded', 'false');
                });
            };

            const handlePackageContentSectionClick = (event) => {
                const addButton = event.target.closest('[data-package-content-add-row]');
                const removeRowButton = event.target.closest('[data-package-content-remove-row]');
                const symbolOptionButton = event.target.closest('[data-symbol-option]');
                const symbolTriggerButton = event.target.closest('[data-symbol-picker-trigger]');

                if (symbolOptionButton) {
                    const picker = symbolOptionButton.closest('[data-symbol-picker]');
                    const input = picker?.querySelector('[data-symbol-picker-input]');
                    const display = picker?.querySelector('[data-symbol-picker-display]');

                    if (!picker || !input || !display) {
                        return;
                    }

                    input.value = symbolOptionButton.getAttribute('data-symbol-option') ?? input.value;
                    display.textContent = symbolOptionButton.getAttribute('data-symbol-display') ?? display.textContent;
                    closeSymbolPickers(row);
                    return;
                }

                if (symbolTriggerButton) {
                    const picker = symbolTriggerButton.closest('[data-symbol-picker]');
                    const menu = picker?.querySelector('[data-symbol-picker-menu]');
                    const willOpen = menu?.classList.contains('hidden');

                    closeSymbolPickers(row);

                    if (menu && willOpen) {
                        menu.classList.remove('hidden');
                        symbolTriggerButton.setAttribute('aria-expanded', 'true');
                    }

                    return;
                }

                closeSymbolPickers(row);

                if (addButton) {
                    const sectionKey = addButton.getAttribute('data-package-content-add-row');
                    const templateContent = row.querySelector(`[data-package-content-template="${sectionKey}"]`)?.content?.cloneNode(true);
                    const targetBody = row.querySelector(`[data-package-content-body="${sectionKey}"]`);

                    if (!templateContent || !targetBody) {
                        return;
                    }

                    targetBody.appendChild(templateContent);
                    const latestRow = targetBody.querySelector('[data-package-content-row]:last-child');
                    latestRow?.querySelectorAll('[data-package-rich-editor]').forEach(initializeRichEditor);
                    latestRow?.querySelector('[data-package-rich-editor], textarea, input, [data-symbol-picker-trigger]')?.focus();
                    return;
                }

                if (!removeRowButton) {
                    return;
                }

                const currentRow = removeRowButton.closest('[data-package-content-row]');
                const targetBody = currentRow?.parentElement;

                if (!currentRow || !targetBody) {
                    return;
                }

                if (targetBody.querySelectorAll('[data-package-content-row]').length <= 1) {
                    currentRow.querySelectorAll('input, textarea, select, [data-package-rich-editor]').forEach((field) => {
                        if (field.matches('[data-symbol-picker-input]')) {
                            const picker = field.closest('[data-symbol-picker]');
                            const defaultOption = picker?.querySelector('[data-symbol-option]');
                            const display = picker?.querySelector('[data-symbol-picker-display]');

                            field.value = defaultOption?.getAttribute('data-symbol-option') ?? '';

                            if (display) {
                                display.textContent = defaultOption?.getAttribute('data-symbol-display') ?? '';
                            }

                            return;
                        }

                        if (field.matches('[data-package-rich-editor]')) {
                            field.innerHTML = '';
                            syncRichEditorInput(field);
                            return;
                        }

                        if (field.tagName === 'SELECT') {
                            field.selectedIndex = 0;
                            return;
                        }

                        if (field.type !== 'hidden') {
                            field.value = '';
                        }
                    });

                    closeSymbolPickers(currentRow);
                    return;
                }

                currentRow.remove();
            };

            row.querySelectorAll('[data-package-content-sections], [data-package-other-content-sections], [data-package-optional-activities-sections]').forEach((section) => {
                section.addEventListener('click', handlePackageContentSectionClick);
                section.addEventListener('focusout', (event) => {
                    if (event.currentTarget?.contains(event.relatedTarget)) {
                        return;
                    }

                    closeSymbolPickers(row);
                });
            });

            const handleRichToolbarAction = (event) => {
                const actionButton = event.target.closest('[data-package-rich-action]');
                const action = actionButton?.getAttribute('data-package-rich-action');

                event.preventDefault();
                event.stopPropagation();

                if (!action || !activeRichEditor) {
                    return;
                }

                focusRichEditor(activeRichEditor);
                if (!restoreRichSelection()) {
                    return;
                }

                if (['bold', 'italic', 'underline'].includes(action)) {
                    wrapSelectionWithTag(action === 'bold' ? 'strong' : (action === 'italic' ? 'em' : 'u'));

                    return;
                }

                if (action === 'unordered' || action === 'ordered' || action === 'tick' || action === 'round' || action === 'x' || action === 'warning') {
                    applyListStyleToSelection(action);
                }
            };

            packageRichToolbars.forEach((toolbar) => {
                toolbar.addEventListener('mousedown', handleRichToolbarAction);
            });

            document.addEventListener('selectionchange', () => {
                if (!activeRichEditor || !document.contains(activeRichEditor)) {
                    hideRichToolbar(true);

                    return;
                }

                const selection = window.getSelection();

                if (!selectionBelongsToEditor(selection, activeRichEditor) || selection?.isCollapsed) {
                    hideRichToolbar();

                    return;
                }

                saveRichSelection();
                updateRichToolbarPosition();
            });

            packageDetailsForm?.addEventListener('submit', () => {
                row.querySelectorAll('[data-package-rich-editor]').forEach(syncRichEditorInput);
            });

            packageContentForm?.addEventListener('submit', () => {
                row.querySelectorAll('[data-package-rich-editor]').forEach(syncRichEditorInput);
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
                const timeInput = slotRow.querySelector('input[name="itinerary_time[]"]');
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

            itineraryForm?.addEventListener('submit', async (event) => {
                event.preventDefault();

                const submitButton = itineraryForm.querySelector('button[type="submit"]');
                const originalLabel = submitButton?.textContent?.trim() || 'Save Itinerary';
                const formData = new FormData(itineraryForm);
                const itineraryPersistKey = itineraryForm.dataset.formPersist
                    ? `ueh-form-draft:${itineraryForm.dataset.formPersist}`
                    : '';

                if (submitButton instanceof HTMLButtonElement) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Saving...';
                    submitButton.classList.add('opacity-70', 'cursor-not-allowed');
                }

                try {
                    const response = await fetch(itineraryForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const responseType = response.headers.get('content-type') ?? '';

                    if (response.status === 422 && responseType.includes('application/json')) {
                        const data = await response.json();
                        const firstError = Object.values(data.errors ?? {}).flat()[0];
                        throw new Error(firstError || 'Unable to save package itinerary right now.');
                    }

                    if (!response.ok) {
                        throw new Error('Unable to save package itinerary right now.');
                    }

                    if (responseType.includes('application/json')) {
                        await response.json();
                    }

                    if (itineraryPersistKey) {
                        try {
                            window.localStorage.removeItem(itineraryPersistKey);
                        } catch (error) {
                            // Ignore localStorage failures.
                        }
                    }

                    closeItineraryModal();
                } catch (error) {
                    console.error(error);
                    alert(error instanceof Error ? error.message : 'Unable to save package itinerary right now. Please try again.');
                } finally {
                    if (submitButton instanceof HTMLButtonElement) {
                        submitButton.disabled = false;
                        submitButton.textContent = originalLabel;
                        submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
                    }
                }
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const originalSaveLabel = saveButton.textContent?.trim() || 'Save';
                const persistKey = form.dataset.formPersist ? `ueh-form-draft:${form.dataset.formPersist}` : '';

                saveButton.disabled = true;
                saveButton.textContent = 'Saving...';
                saveButton.classList.add('opacity-70', 'cursor-not-allowed');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData,
                    });

                    const responseType = response.headers.get('content-type') ?? '';

                    if (response.status === 422 && responseType.includes('application/json')) {
                        const data = await response.json();
                        const firstError = Object.values(data.errors ?? {}).flat()[0];
                        throw new Error(firstError || 'Unable to save package right now.');
                    }

                    if (!response.ok || !responseType.includes('application/json')) {
                        throw new Error('Unable to save package right now.');
                    }

                    const data = await response.json();
                    applySavedPackageImages(row, form, data.package ?? {});

                    if (persistKey) {
                        try {
                            window.localStorage.removeItem(persistKey);
                        } catch (error) {
                            // Ignore localStorage failures.
                        }
                    }

                    closeEditFormAfterSave();
                } catch (error) {
                    console.error(error);
                    alert(error instanceof Error ? error.message : 'Unable to save package right now. Please try again.');
                } finally {
                    saveButton.disabled = false;
                    saveButton.textContent = originalSaveLabel;
                    saveButton.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            });

            cancelButton.addEventListener('click', () => {
                resetForm();
                form.querySelectorAll('[data-package-rich-editor]').forEach(syncRichEditorInput);
                editSection.style.zIndex = '';
                editPanel.style.position = '';
                editPanel.style.zIndex = '';
                viewSection.classList.remove('hidden');
                editSection.classList.add('hidden');
                editSection.classList.remove('flex');
                editButton.classList.remove('hidden');
                syncRowOverlayState();
            });

            function closeEditFormAfterSave() {
                editSection.style.zIndex = '';
                editPanel.style.position = '';
                editPanel.style.zIndex = '';
                viewSection.classList.remove('hidden');
                editSection.classList.add('hidden');
                editSection.classList.remove('flex');
                editButton.classList.remove('hidden');
                syncRowOverlayState();
            }

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

            packageDeleteForm?.addEventListener('submit', async (event) => {
                event.preventDefault();

                if (!window.confirm('Delete this product?')) {
                    return;
                }

                if (!packageDeleteButton) {
                    packageDeleteForm.submit();

                    return;
                }

                const originalLabel = packageDeleteButton.textContent;
                packageDeleteButton.disabled = true;
                packageDeleteButton.textContent = 'Deleting...';
                packageDeleteButton.classList.add('opacity-70', 'cursor-not-allowed');

                try {
                    const response = await fetch(packageDeleteForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(packageDeleteForm),
                    });

                    if (!response.ok) {
                        throw new Error('Delete request failed.');
                    }

                    row.style.transition = 'opacity 0.2s ease, transform 0.2s ease, max-height 0.25s ease, margin 0.25s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(-8px)';
                    row.style.overflow = 'hidden';
                    row.style.maxHeight = `${row.offsetHeight}px`;

                    window.setTimeout(() => {
                        row.style.maxHeight = '0';
                        row.style.marginTop = '0';
                        row.style.marginBottom = '0';
                        row.style.paddingTop = '0';
                        row.style.paddingBottom = '0';
                    }, 10);

                    window.setTimeout(() => {
                        row.remove();
                    }, 260);

                    return;
                } catch (error) {
                    console.error(error);
                    window.alert('Unable to delete this product right now. Please try again.');
                }

                packageDeleteButton.disabled = false;
                packageDeleteButton.textContent = originalLabel;
                packageDeleteButton.classList.remove('opacity-70', 'cursor-not-allowed');
            });
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

        const updatePackageGallerySummary = (row) => {
            if (!(row instanceof HTMLElement)) {
                return;
            }

            const galleryGrid = row.querySelector('[data-package-gallery-grid]');
            const countElement = row.querySelector('[data-package-gallery-count]');
            const labelElement = row.querySelector('[data-package-gallery-label]');
            const statusElement = row.querySelector('[data-package-gallery-status]');
            const totalImages = galleryGrid?.querySelectorAll('.package-inline-gallery-item').length ?? 0;

            if (countElement instanceof HTMLElement) {
                countElement.textContent = `${totalImages}`;
            }

            if (labelElement instanceof HTMLElement) {
                labelElement.textContent = totalImages === 1 ? 'Image' : 'Images';
            }

            if (statusElement instanceof HTMLElement) {
                statusElement.textContent = totalImages > 0 ? 'Open folder' : 'Empty folder';
            }
        };

        const syncInlineMainImagePreview = (row) => {
            if (!(row instanceof HTMLElement)) {
                return;
            }

            const input = row.querySelector('[data-package-main-image-input]');
            const previewButton = row.querySelector('[data-package-main-image-button]');
            const cardImage = row.querySelector('[data-package-main-image-card]');
            const modalImage = row.querySelector('[data-package-main-image-preview]');
            const emptyState = row.querySelector('[data-package-main-image-empty]');

            if (!(input instanceof HTMLInputElement) || !(previewButton instanceof HTMLElement) || !(cardImage instanceof HTMLImageElement) || !(modalImage instanceof HTMLImageElement) || !(emptyState instanceof HTMLElement)) {
                return;
            }

            const previousObjectUrl = previewButton.dataset.objectUrl;
            if (previousObjectUrl) {
                URL.revokeObjectURL(previousObjectUrl);
                delete previewButton.dataset.objectUrl;
            }

            if (!previewButton.dataset.savedSrc) {
                previewButton.dataset.savedSrc = cardImage.getAttribute('src') ?? '';
            }

            const file = input.files?.[0];

            if (!file) {
                const savedSrc = previewButton.dataset.savedSrc ?? '';

                if (savedSrc) {
                    cardImage.src = savedSrc;
                    modalImage.src = savedSrc;
                    previewButton.classList.remove('hidden');
                    previewButton.classList.add('block');
                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                } else {
                    cardImage.removeAttribute('src');
                    modalImage.removeAttribute('src');
                    previewButton.classList.add('hidden');
                    previewButton.classList.remove('block');
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                }

                return;
            }

            const objectUrl = URL.createObjectURL(file);
            previewButton.dataset.objectUrl = objectUrl;
            cardImage.src = objectUrl;
            modalImage.src = objectUrl;
            previewButton.classList.remove('hidden');
            previewButton.classList.add('block');
            emptyState.classList.add('hidden');
            emptyState.classList.remove('flex');
        };

        const openPackageGalleryPreview = (button, event) => {
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
        };

        const buildTemporaryGalleryPreviewItem = ({ imageUrl, imageName, modalId }) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'package-inline-gallery-item relative overflow-hidden rounded-xl border border-stone-200 bg-stone-50';
            wrapper.dataset.tempGalleryItem = 'true';
            wrapper.dataset.objectUrl = imageUrl;

            const badge = document.createElement('span');
            badge.className = 'absolute left-2 top-2 z-10 inline-flex items-center rounded-full bg-amber-500 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.16em] text-white shadow-sm';
            badge.textContent = 'New';

            const previewButton = document.createElement('button');
            previewButton.type = 'button';
            previewButton.className = 'package-inline-gallery-preview-open block w-full';
            previewButton.dataset.galleryPreviewSrc = imageUrl;
            previewButton.dataset.galleryPreviewName = imageName;
            previewButton.dataset.galleryPreviewModal = modalId;
            previewButton.setAttribute('aria-label', 'Open gallery image preview');
            previewButton.addEventListener('click', (event) => {
                openPackageGalleryPreview(previewButton, event);
            });

            const image = document.createElement('img');
            image.src = imageUrl;
            image.alt = `${imageName} gallery image`;
            image.className = 'h-44 w-full object-cover transition hover:scale-[1.03]';

            previewButton.appendChild(image);
            wrapper.appendChild(badge);
            wrapper.appendChild(previewButton);

            return wrapper;
        };

        const buildSavedGalleryPreviewItem = ({ row, imageUrl, imageName, modalId }) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'package-inline-gallery-item relative overflow-hidden rounded-xl border border-stone-200 bg-stone-50';
            wrapper.dataset.galleryImage = imageUrl;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'package-inline-gallery-remove absolute right-2 top-2 z-10 inline-flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold leading-none text-white shadow-sm transition hover:bg-rose-700';
            removeButton.setAttribute('aria-label', 'Remove gallery image');
            removeButton.textContent = '-';
            removeButton.addEventListener('click', () => {
                row.querySelectorAll('input[name="existing_gallery_images[]"]').forEach((input) => {
                    if (input.value === imageUrl) {
                        input.remove();
                    }
                });

                wrapper.remove();
                updatePackageGallerySummary(row);
            });

            const previewButton = document.createElement('button');
            previewButton.type = 'button';
            previewButton.className = 'package-inline-gallery-preview-open block w-full';
            previewButton.dataset.galleryPreviewSrc = imageUrl;
            previewButton.dataset.galleryPreviewName = imageName;
            previewButton.dataset.galleryPreviewModal = modalId;
            previewButton.setAttribute('aria-label', 'Open gallery image preview');
            previewButton.addEventListener('click', (event) => {
                openPackageGalleryPreview(previewButton, event);
            });

            const image = document.createElement('img');
            image.src = imageUrl;
            image.alt = `${imageName} gallery image`;
            image.className = 'h-44 w-full object-cover transition hover:scale-[1.03]';

            previewButton.appendChild(image);
            wrapper.appendChild(removeButton);
            wrapper.appendChild(previewButton);

            return wrapper;
        };

        const syncExistingGalleryInputs = (row, form, galleryImages) => {
            row.querySelectorAll('input[name="existing_gallery_images[]"]').forEach((input) => input.remove());

            const imageUrlInput = form.querySelector('input[name="image_url"]');
            if (!(imageUrlInput instanceof HTMLInputElement)) {
                return;
            }

            galleryImages.forEach((imageUrl) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'existing_gallery_images[]';
                hiddenInput.value = imageUrl;
                imageUrlInput.insertAdjacentElement('afterend', hiddenInput);
            });
        };

        const syncPackageCardImage = (row, mainImageUrl, galleryImages) => {
            const cardImage = row.querySelector('[data-package-card-image]');
            const cardEmpty = row.querySelector('[data-package-card-empty]');
            const displayImage = mainImageUrl || galleryImages[0] || '';

            if (!(cardImage instanceof HTMLImageElement) || !(cardEmpty instanceof HTMLElement)) {
                return;
            }

            if (displayImage) {
                cardImage.src = displayImage;
                cardImage.classList.remove('hidden');
                cardImage.classList.add('block');
                cardEmpty.classList.add('hidden');
                cardEmpty.classList.remove('flex');
                return;
            }

            cardImage.removeAttribute('src');
            cardImage.classList.add('hidden');
            cardImage.classList.remove('block');
            cardEmpty.classList.remove('hidden');
            cardEmpty.classList.add('flex');
        };

        const applySavedPackageImages = (row, form, savedPackage) => {
            const imageUrl = typeof savedPackage.image_url === 'string' ? savedPackage.image_url : '';
            const galleryImages = Array.isArray(savedPackage.gallery_images)
                ? savedPackage.gallery_images.filter((image) => typeof image === 'string' && image.trim() !== '')
                : [];
            const mainImageInput = row.querySelector('[data-package-main-image-input]');
            const galleryInput = row.querySelector('[data-package-gallery-input]');
            const previewButton = row.querySelector('[data-package-main-image-button]');
            const cardImage = row.querySelector('[data-package-main-image-card]');
            const modalImage = row.querySelector('[data-package-main-image-preview]');
            const emptyState = row.querySelector('[data-package-main-image-empty]');
            const imageUrlInput = form.querySelector('input[name="image_url"]');
            const galleryGrid = row.querySelector('[data-package-gallery-grid]');
            const galleryPreviewTitle = row.querySelector('[data-package-gallery-image-title]')?.textContent?.trim() || 'Package';
            const galleryPreviewModal = row.querySelector('.package-inline-gallery-image-modal')?.id || '';

            if (previewButton instanceof HTMLElement) {
                const previousObjectUrl = previewButton.dataset.objectUrl;
                if (previousObjectUrl) {
                    URL.revokeObjectURL(previousObjectUrl);
                    delete previewButton.dataset.objectUrl;
                }

                previewButton.dataset.savedSrc = imageUrl;
            }

            if (imageUrlInput instanceof HTMLInputElement) {
                imageUrlInput.value = imageUrl;
                imageUrlInput.defaultValue = imageUrl;
            }

            if (cardImage instanceof HTMLImageElement && modalImage instanceof HTMLImageElement && previewButton instanceof HTMLElement && emptyState instanceof HTMLElement) {
                if (imageUrl) {
                    cardImage.src = imageUrl;
                    modalImage.src = imageUrl;
                    previewButton.classList.remove('hidden');
                    previewButton.classList.add('block');
                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                } else {
                    cardImage.removeAttribute('src');
                    modalImage.removeAttribute('src');
                    previewButton.classList.add('hidden');
                    previewButton.classList.remove('block');
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                }
            }

            if (galleryGrid instanceof HTMLElement) {
                galleryGrid.innerHTML = '';
                galleryImages.forEach((galleryImage) => {
                    galleryGrid.appendChild(buildSavedGalleryPreviewItem({
                        row,
                        imageUrl: galleryImage,
                        imageName: galleryPreviewTitle,
                        modalId: galleryPreviewModal,
                    }));
                });
            }

            syncExistingGalleryInputs(row, form, galleryImages);
            syncPackageCardImage(row, imageUrl, galleryImages);
            updatePackageGallerySummary(row);

            if (mainImageInput instanceof HTMLInputElement) {
                mainImageInput.value = '';
            }

            if (galleryInput instanceof HTMLInputElement) {
                galleryInput.value = '';
            }
        };

        const syncInlineGalleryPreviews = (row) => {
            if (!(row instanceof HTMLElement)) {
                return;
            }

            const input = row.querySelector('[data-package-gallery-input]');
            const galleryGrid = row.querySelector('[data-package-gallery-grid]');
            const galleryPreviewTitle = row.querySelector('[data-package-gallery-image-title]')?.textContent?.trim() || 'Package';
            const galleryPreviewModal = row.querySelector('.package-inline-gallery-image-modal')?.id || '';

            if (!(input instanceof HTMLInputElement) || !(galleryGrid instanceof HTMLElement)) {
                return;
            }

            galleryGrid.querySelectorAll('[data-temp-gallery-item="true"]').forEach((item) => {
                if (!(item instanceof HTMLElement)) {
                    return;
                }

                const objectUrl = item.dataset.objectUrl;
                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                }

                item.remove();
            });

            Array.from(input.files ?? []).forEach((file) => {
                const objectUrl = URL.createObjectURL(file);
                const previewItem = buildTemporaryGalleryPreviewItem({
                    imageUrl: objectUrl,
                    imageName: galleryPreviewTitle,
                    modalId: galleryPreviewModal,
                });

                galleryGrid.appendChild(previewItem);
            });

            updatePackageGallerySummary(row);
        };

        document.querySelectorAll('[data-package-inline-row]').forEach((row) => {
            if (!(row instanceof HTMLElement)) {
                return;
            }

            updatePackageGallerySummary(row);

            const mainImageInput = row.querySelector('[data-package-main-image-input]');
            if (mainImageInput instanceof HTMLInputElement) {
                mainImageInput.addEventListener('change', () => {
                    syncInlineMainImagePreview(row);
                });
            }

            const galleryInput = row.querySelector('[data-package-gallery-input]');
            if (galleryInput instanceof HTMLInputElement) {
                galleryInput.addEventListener('change', () => {
                    syncInlineGalleryPreviews(row);
                });
            }
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
                updatePackageGallerySummary(row);
            });
        });

        document.querySelectorAll('.package-inline-gallery-preview-open').forEach((button) => {
            button.addEventListener('click', (event) => {
                openPackageGalleryPreview(button, event);
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

                if (!image?.getAttribute('src')) {
                    return;
                }

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
                const currentValue = !nextValue;
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
                    applyState(currentValue);
                    console.error(error);
                    alert('Unable to update package visibility right now. Please try again.');
                } finally {
                    button.disabled = false;
                    button.classList.remove('opacity-70');
                }
            });
        });

        document.querySelectorAll('[data-package-active-button]').forEach((button) => {
            const label = button.querySelector('[data-package-active-label]');
            const row = button.closest('[data-package-inline-row]');
            const statusBadges = Array.from(row?.querySelectorAll('[data-package-status-badge]') ?? []);
            const editCheckboxes = Array.from(row?.querySelectorAll('input[type="checkbox"][name="is_active"]') ?? []);

            if (!label || !row) {
                return;
            }

            const applyState = (isActive) => {
                button.dataset.packageActiveValue = isActive ? '0' : '1';
                label.textContent = isActive ? 'Active' : 'Hidden';
                button.setAttribute('aria-label', isActive ? 'Hide package listing' : 'Show package listing');
                button.classList.toggle('border-emerald-300', isActive);
                button.classList.toggle('bg-emerald-50', isActive);
                button.classList.toggle('text-emerald-700', isActive);
                button.classList.toggle('hover:bg-emerald-100', isActive);
                button.classList.toggle('border-stone-300', !isActive);
                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('text-stone-700', !isActive);
                button.classList.toggle('hover:bg-stone-100', !isActive);

                statusBadges.forEach((statusBadge) => {
                    statusBadge.textContent = isActive ? 'Active' : 'Hidden';
                    statusBadge.classList.toggle('text-emerald-700', isActive);
                    statusBadge.classList.toggle('text-stone-500', !isActive);
                });

                editCheckboxes.forEach((checkbox) => {
                    checkbox.checked = isActive;
                });
            };

            button.addEventListener('click', async () => {
                const nextValue = button.dataset.packageActiveValue === '1';
                const currentValue = !nextValue;
                const formData = new FormData();
                formData.append('_token', button.dataset.packageActiveToken ?? '');
                formData.append('_method', 'PATCH');
                formData.append('is_active', nextValue ? '1' : '0');
                button.disabled = true;
                button.classList.add('opacity-70');
                applyState(nextValue);

                try {
                    const response = await fetch(button.dataset.packageActiveUrl ?? '', {
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

                    const responseType = response.headers.get('content-type') ?? '';

                    if (!responseType.includes('application/json')) {
                        throw new Error('Toggle request did not return JSON.');
                    }

                    const data = await response.json();
                    applyState(Boolean(data.is_active ?? nextValue));
                } catch (error) {
                    applyState(currentValue);
                    console.error(error);
                    alert('Unable to update package visibility right now. Please try again.');
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
