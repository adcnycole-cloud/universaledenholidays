<x-layouts.app title="Admin Packages | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-6 lg:px-10">
        @include('admin.partials.product-management-page', [
            'sectionLabel' => 'Packages',
            'labelColor' => 'text-amber-600',
            'heading' => 'Add travel packages',
            'category' => 'package',
            'title' => 'Travel package',
            'products' => $packageProducts,
            'listHeading' => 'Current package listings',
            'searchIdPrefix' => 'admin-package',
            'searchPlaceholder' => 'Search packages by name, location, or summary',
            'stackLayout' => true,
        ])
    </main>
    @include('admin.partials.filter-paginate-script', [
        'sectionId' => 'admin-package-listings',
        'searchInputId' => 'admin-package-search',
        'prevButtonId' => 'admin-package-prev',
        'nextButtonId' => 'admin-package-next',
        'resultsLabelId' => 'admin-package-results',
        'listId' => 'admin-package-product-list',
        'itemSelector' => '[data-admin-package-item]',
        'pageSize' => 4,
        'emptyMessage' => 'No package listings match your search.',
        'resultsNoun' => 'package listings',
    ])
</x-layouts.app>
