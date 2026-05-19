@php($searchIdPrefix = $searchIdPrefix ?? 'admin-product')
@php($stackLayout = $stackLayout ?? false)

<section id="{{ $searchIdPrefix }}-listings" class="mt-5 {{ $stackLayout ? 'space-y-8' : 'grid gap-8 lg:grid-cols-[1.2fr_0.8fr]' }}">
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
                    <span class="sr-only">Search packages</span>
                    <input id="{{ $searchIdPrefix }}-search" type="search" placeholder="{{ $searchPlaceholder }}" class="w-full rounded-full border border-stone-300 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-sky-400 focus:bg-white">
                </label>
            </div>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <p id="{{ $searchIdPrefix }}-results" class="text-sm text-stone-500" aria-live="polite">Showing {{ $products->count() }} {{ $listHeading }}</p>
                <div class="flex items-center gap-2">
                    <button id="{{ $searchIdPrefix }}-prev" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-lg font-semibold leading-none text-stone-700 transition hover:bg-stone-100" aria-label="Previous page">&larr;</button>
                    <button id="{{ $searchIdPrefix }}-next" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-lg font-semibold leading-none text-stone-700 transition hover:bg-stone-100" aria-label="Next page">&rarr;</button>
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
