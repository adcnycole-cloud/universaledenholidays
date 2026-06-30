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
            'createPanelClosedLabel' => 'Add New Packages Manually',
            'createPanelOpenLabel' => 'Hide Manual Form',
            'showImportTools' => true,
            'importPanelAction' => route('admin.packages.import'),
            'templateDownloads' => [
                ['label' => 'Excel Template', 'url' => route('admin.packages.template', ['format' => 'xlsx'])],
            ],
            'listingFilters' => [
                ['label' => 'All Tours / Packages', 'value' => 'all'],
                ['label' => 'Day Trip', 'value' => 'day-trip'],
                ['label' => '2D1N', 'value' => '2d1n'],
                ['label' => '3D2N', 'value' => '3d2n'],
                ['label' => '4D3N', 'value' => '4d3n'],
            ],
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
            const bindPanelToggle = ({ toggleSelector, panelSelector, onOpen }) => {
                const toggleButton = document.querySelector(toggleSelector);
                const panelBody = document.querySelector(panelSelector);

                if (!toggleButton || !panelBody) {
                    return { open: () => {} };
                }

                const syncLabel = () => {
                    const isOpen = !panelBody.classList.contains('hidden');
                    toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    toggleButton.textContent = isOpen
                        ? (toggleButton.dataset.openLabel || 'Hide Form')
                        : (toggleButton.dataset.closedLabel || 'Show Form');
                };

                toggleButton.addEventListener('click', () => {
                    const willOpen = panelBody.classList.contains('hidden');
                    panelBody.classList.toggle('hidden');
                    syncLabel();

                    if (willOpen && typeof onOpen === 'function') {
                        onOpen();
                    }
                });

                syncLabel();

                return {
                    open: () => {
                        panelBody.classList.remove('hidden');
                        syncLabel();
                    },
                };
            };

            const importPanel = document.querySelector('[data-import-panel-body]');
            const importToggle = bindPanelToggle({
                toggleSelector: '[data-import-panel-toggle]',
                panelSelector: '[data-import-panel-body]',
                onOpen: () => {
                    const fileInput = importPanel?.querySelector('input[type="file"]');
                    fileInput?.focus();
                },
            });

            const createToggle = bindPanelToggle({
                toggleSelector: '[data-create-panel-toggle]',
                panelSelector: '[data-create-panel-body]',
            });

            document.addEventListener('codex:form-draft-restored', () => {
                const panelBody = document.querySelector('[data-create-panel-body]');

                if (!panelBody?.querySelector('form[data-draft-restored="true"]')) {
                    return;
                }

                createToggle.open();
            });

            if (importPanel && !importPanel.classList.contains('hidden')) {
                importToggle.open();
            }
        });
    </script>
</x-layouts.app>
