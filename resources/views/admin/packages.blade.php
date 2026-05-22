<x-layouts.app title="Admin Packages | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
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
            'gridColumns' => 2,
            'collapsibleCreatePanel' => true,
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
                toggleButton.textContent = isOpen ? 'Hide Form' : 'New Package';
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
