@php($searchIdPrefix = $searchIdPrefix ?? 'admin-product')

<section id="{{ $searchIdPrefix }}-listings" class="mt-8 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <p class="text-sm uppercase tracking-[0.3em] {{ $labelColor }}">{{ $sectionLabel }}</p>
        <h1 class="mt-2 text-3xl font-semibold text-stone-900">{{ $heading }}</h1>
        @include('admin.partials.product-form', ['category' => $category, 'title' => $title])
    </section>
    <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-2xl font-semibold text-stone-900">{{ $listHeading }}</h2>
                <label class="relative block w-full lg:max-w-sm">
                    <span class="sr-only">Search entries</span>
                    <input id="{{ $searchIdPrefix }}-search" type="search" placeholder="{{ $searchPlaceholder }}" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white">
                </label>
            </div>
            <div class="flex items-center justify-between gap-3">
                <p id="{{ $searchIdPrefix }}-results" class="text-sm text-stone-500">Showing listings</p>
                <div class="flex items-center gap-2">
                    <button id="{{ $searchIdPrefix }}-prev" type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">Previous</button>
                    <button id="{{ $searchIdPrefix }}-next" type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">Next</button>
                </div>
            </div>
        </div>
        @include('admin.partials.product-table', [
            'products' => $products,
            'editable' => true,
            'wrapperId' => $searchIdPrefix . '-product-list',
            'itemAttribute' => 'data-' . $searchIdPrefix . '-item',
        ])
    </section>
</section>
