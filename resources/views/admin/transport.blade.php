<x-layouts.app title="Admin Transport | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-6 lg:px-10">
        @include('admin.partials.product-management-page', [
            'sectionLabel' => 'Transport',
            'labelColor' => 'text-sky-700',
            'heading' => 'Add transport info',
            'category' => 'transport',
            'title' => 'Transport',
            'products' => $transportProducts,
            'listHeading' => 'Current transport listings',
            'searchIdPrefix' => 'admin-transport',
            'searchPlaceholder' => 'Search transport by name, location, or summary',
            'stackLayout' => true,
            'gridColumns' => 1,
            'collapsibleCreatePanel' => true,
        ])
    </main>
    @include('admin.partials.filter-paginate-script', [
        'sectionId' => 'admin-transport-listings',
        'searchInputId' => 'admin-transport-search',
        'prevButtonId' => 'admin-transport-prev',
        'nextButtonId' => 'admin-transport-next',
        'resultsLabelId' => 'admin-transport-results',
        'listId' => 'admin-transport-product-list',
        'itemSelector' => '[data-admin-transport-item]',
        'pageSize' => 4,
        'emptyMessage' => 'No transport listings match your search.',
        'resultsNoun' => 'transport listings',
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
                toggleButton.textContent = isOpen ? 'Hide Form' : 'New Transport';
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
