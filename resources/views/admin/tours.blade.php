<x-layouts.app title="Admin Tours | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-10 lg:px-10">
        @include('admin.partials.overview-cards', ['stats' => $stats])
        @include('admin.partials.product-management-page', [
            'sectionLabel' => 'Tours',
            'labelColor' => 'text-emerald-600',
            'heading' => 'Add tour products',
            'category' => 'tour',
            'title' => 'Tour product',
            'products' => $tourProducts,
            'listHeading' => 'Current tour listings',
            'searchIdPrefix' => 'admin-tour',
            'searchPlaceholder' => 'Search tours by name, location, or summary',
        ])
    </main>
    @include('admin.partials.filter-paginate-script', [
        'sectionId' => 'admin-tour-listings',
        'searchInputId' => 'admin-tour-search',
        'prevButtonId' => 'admin-tour-prev',
        'nextButtonId' => 'admin-tour-next',
        'resultsLabelId' => 'admin-tour-results',
        'listId' => 'admin-tour-product-list',
        'itemSelector' => '[data-admin-tour-item]',
        'pageSize' => 4,
        'emptyMessage' => 'No tour listings match your search.',
        'resultsNoun' => 'tour listings',
    ])
</x-layouts.app>
