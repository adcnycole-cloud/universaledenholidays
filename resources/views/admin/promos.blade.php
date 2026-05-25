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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.querySelector('[data-create-panel-toggle]');
            const panelBody = document.querySelector('[data-create-panel-body]');

            if (!toggleButton || !panelBody) {
                return;
            }

            const syncLabel = () => {
                const isOpen = !panelBody.classList.contains('hidden');
                toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                toggleButton.textContent = isOpen ? 'Hide Form' : 'New Promo';
            };

            toggleButton.addEventListener('click', () => {
                panelBody.classList.toggle('hidden');
                syncLabel();
            });

            document.addEventListener('codex:form-draft-restored', () => {
                if (!panelBody.querySelector('form[data-draft-restored="true"]')) {
                    return;
                }

                panelBody.classList.remove('hidden');
                syncLabel();
            });

            syncLabel();
        });
    </script>
</x-layouts.app>
