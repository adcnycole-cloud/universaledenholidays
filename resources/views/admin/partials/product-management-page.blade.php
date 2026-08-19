@php($searchIdPrefix = $searchIdPrefix ?? 'admin-product')
@php($stackLayout = $stackLayout ?? false)
@php($collapsibleCreatePanel = $collapsibleCreatePanel ?? false)
@php($listingFilters = $listingFilters ?? [])
@php($createPanelClosedLabel = $createPanelClosedLabel ?? 'New Package')
@php($createPanelOpenLabel = $createPanelOpenLabel ?? 'Hide Form')
@php($showImportTools = $showImportTools ?? false)
@php($importPanelAction = $importPanelAction ?? null)
@php($templateDownloads = $templateDownloads ?? [])

<section id="{{ $searchIdPrefix }}-listings" class="mt-5 {{ $stackLayout ? 'space-y-8' : 'grid gap-8 lg:grid-cols-[1.2fr_0.8fr]' }}" data-product-management-stack>
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm" data-product-create-panel>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] {{ $labelColor }}">{{ $sectionLabel }}</p>
                <h1 class="mt-2 text-2xl font-semibold text-stone-900">{{ $heading }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if ($collapsibleCreatePanel)
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold uppercase tracking-[0.12em] text-stone-700 transition hover:bg-stone-100"
                        data-create-panel-toggle
                        data-open-label="{{ $createPanelOpenLabel }}"
                        data-closed-label="{{ $createPanelClosedLabel }}"
                        aria-expanded="false"
                        aria-controls="{{ $searchIdPrefix }}-create-panel-body"
                    >
                        {{ $createPanelClosedLabel }}
                    </button>
                @endif

                @if ($showImportTools && $importPanelAction)
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-full border border-sky-200 bg-sky-50 px-4 py-2.5 text-sm font-semibold uppercase tracking-[0.12em] text-sky-700 transition hover:border-sky-300 hover:bg-sky-100"
                        data-import-panel-toggle
                        data-open-label="Hide Import Form"
                        data-closed-label="Import New Packages File"
                        aria-expanded="false"
                        aria-controls="{{ $searchIdPrefix }}-import-panel-body"
                    >
                        Import New Packages File
                    </button>
                @endif

                @if (!empty($templateDownloads))
                    <details class="relative">
                        <summary class="inline-flex cursor-pointer list-none items-center justify-center rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold uppercase tracking-[0.12em] text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100">
                            Get Packages File Template
                        </summary>
                        <div class="absolute right-0 z-20 mt-2 min-w-[220px] rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                            @foreach ($templateDownloads as $download)
                                <a
                                    href="{{ $download['url'] }}"
                                    class="flex rounded-xl px-3 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100"
                                >
                                    {{ $download['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>
        </div>
        <div
            id="{{ $searchIdPrefix }}-create-panel-body"
            @class([
                'mt-6',
                'hidden' => $collapsibleCreatePanel,
            ])
            data-create-panel-body
        >
            @include('admin.partials.product-form', ['category' => $category, 'title' => $title])
        </div>
        @if ($showImportTools && $importPanelAction)
            <div
                id="{{ $searchIdPrefix }}-import-panel-body"
                @class([
                    'mt-6 hidden rounded-[1.5rem] border border-sky-100 bg-sky-50 p-5',
                    '!block' => $errors->has('package_import_file') || $errors->has('package_import'),
                ])
                data-import-panel-body
            >
                <form method="POST" action="{{ $importPanelAction }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-700">Package File Import</p>
                        <p class="mt-2 text-sm leading-6 text-stone-600">Upload an Excel or CSV file to create packages in bulk. Use the downloaded template so the columns match the admin package fields.</p>
                    </div>
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                        <div class="w-full">
                            <label for="{{ $searchIdPrefix }}-package-import-file" class="mb-2 block text-sm font-medium text-stone-700">Package file</label>
                            <input id="{{ $searchIdPrefix }}-package-import-file" name="package_import_file" type="file" accept=".xlsx,.xls,.csv" class="block w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                        </div>
                        <button type="submit" class="inline-flex h-11 min-w-[10rem] items-center justify-center rounded-full border border-sky-900 bg-sky-900 px-5 text-sm font-semibold uppercase tracking-[0.14em] text-black shadow-sm transition hover:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-300">
                            Import File
                        </button>
                    </div>
                    @error('package_import_file')
                        <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        @endif
    </section>
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-2xl font-semibold text-stone-900">{{ $listHeading }}</h2>
                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
                    <label class="relative block w-full lg:w-[10rem] lg:shrink-0">
                        <input id="{{ $searchIdPrefix }}-search" type="search" placeholder="{{ $searchPlaceholder }}" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white">
                    </label>
                    <div class="flex items-center gap-2 lg:shrink-0">
                        <button id="{{ $searchIdPrefix }}-prev" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-lg font-semibold leading-none text-stone-700 transition hover:bg-stone-100" aria-label="Previous page">&larr;</button>
                        <button id="{{ $searchIdPrefix }}-next" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-lg font-semibold leading-none text-stone-700 transition hover:bg-stone-100" aria-label="Next page">&rarr;</button>
                    </div>
                </div>
            </div>
            @if (!empty($listingFilters))
                <div class="flex flex-wrap gap-2" data-list-filter-group>
                    @foreach ($listingFilters as $filter)
                        <button
                            type="button"
                            class="rounded-full border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100"
                            data-list-filter
                            data-filter-value="{{ $filter['value'] }}"
                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                        >
                            {{ $filter['label'] }}
                        </button>
                    @endforeach
                </div>
            @endif
            <p id="{{ $searchIdPrefix }}-results" class="text-sm text-stone-500" aria-live="polite">Showing {{ $products->count() }} {{ $listHeading }}</p>
        </div>
        @include('admin.partials.product-table', [
            'products' => $products,
            'editable' => true,
            'wrapperId' => $searchIdPrefix . '-product-list',
            'itemAttribute' => 'data-' . $searchIdPrefix . '-item',
            'gridColumns' => $gridColumns ?? 1,
        ])
    </section>
</section>
