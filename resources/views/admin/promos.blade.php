<x-layouts.app title="Admin Promos | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
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
