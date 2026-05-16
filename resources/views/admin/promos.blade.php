<x-layouts.app title="Admin Promos | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-10 lg:px-10">
        @include('admin.partials.overview-cards', ['stats' => $stats])
        @include('admin.partials.promo-management', ['newsFeatures' => $newsFeatures])
    </main>
    @include('admin.partials.filter-paginate-script', [
        'sectionId' => 'admin-promo-listings',
        'searchInputId' => 'admin-promo-search',
        'prevButtonId' => 'admin-promo-prev',
        'nextButtonId' => 'admin-promo-next',
        'resultsLabelId' => 'admin-promo-results',
        'listId' => 'admin-promo-list',
        'itemSelector' => '[data-admin-promo-item]',
        'pageSize' => 4,
        'emptyMessage' => 'No promo entries match your search.',
        'resultsNoun' => 'promo entries',
    ])
</x-layouts.app>
