<script>
    document.addEventListener('DOMContentLoaded', () => {
        const section = document.getElementById('{{ $sectionId }}');
        const searchInput = document.getElementById('{{ $searchInputId }}');
        const prevButton = document.getElementById('{{ $prevButtonId }}');
        const nextButton = document.getElementById('{{ $nextButtonId }}');
        const resultsLabel = document.getElementById('{{ $resultsLabelId }}');

        if (!section || !searchInput || !prevButton || !nextButton || !resultsLabel) {
            return;
        }

        const filterButtons = Array.from(section.querySelectorAll('[data-list-filter]'));

        const list = document.getElementById('{{ $listId }}');

        if (!list) {
            return;
        }

        const items = Array.from(list.querySelectorAll('{{ $itemSelector }}'));
        const pageSize = {{ $pageSize }};
        let activePage = 0;
        let filteredItems = items;
        let activeFilter = filterButtons.find((button) => button.getAttribute('aria-pressed') === 'true')?.dataset.filterValue ?? 'all';

        const updateResults = () => {
            const total = filteredItems.length;

            if (!total) {
                resultsLabel.textContent = '{{ $emptyMessage }}';
                return;
            }

            const start = activePage * pageSize + 1;
            const end = Math.min(total, start + pageSize - 1);
            resultsLabel.textContent = `Showing ${start}-${end} of ${total} {{ $resultsNoun }}`;
        };

        const renderPage = () => {
            const pageCount = Math.max(1, Math.ceil(filteredItems.length / pageSize));
            activePage = Math.min(activePage, pageCount - 1);

            items.forEach((item) => {
                item.style.display = filteredItems.includes(item) ? 'block' : 'none';
            });

            filteredItems.forEach((item, index) => {
                const pageIndex = Math.floor(index / pageSize);
                item.style.display = pageIndex === activePage ? 'block' : 'none';
            });

            prevButton.disabled = activePage === 0;
            nextButton.disabled = activePage >= pageCount - 1 || filteredItems.length === 0;
            prevButton.classList.toggle('opacity-50', prevButton.disabled);
            nextButton.classList.toggle('opacity-50', nextButton.disabled);
            prevButton.classList.toggle('cursor-not-allowed', prevButton.disabled);
            nextButton.classList.toggle('cursor-not-allowed', nextButton.disabled);

            updateResults();
        };

        const applyFilters = () => {
            const query = searchInput.value.trim().toLowerCase();
            filteredItems = items.filter((item) => {
                const matchesQuery = item.textContent.toLowerCase().includes(query);
                const itemFilterValue = item.dataset.listFilterValue ?? 'all';
                const matchesGroup = activeFilter === 'all' || itemFilterValue === activeFilter;

                return matchesQuery && matchesGroup;
            });
            activePage = 0;
            renderPage();
        };

        prevButton.addEventListener('click', () => {
            if (activePage === 0) {
                return;
            }

            activePage -= 1;
            renderPage();
        });

        nextButton.addEventListener('click', () => {
            const pageCount = Math.max(1, Math.ceil(filteredItems.length / pageSize));

            if (activePage >= pageCount - 1) {
                return;
            }

            activePage += 1;
            renderPage();
        });

        filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.filterValue ?? 'all';

                filterButtons.forEach((candidate) => {
                    const isActive = candidate === button;
                    candidate.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    candidate.classList.toggle('bg-stone-900', isActive);
                    candidate.classList.toggle('border-stone-900', isActive);
                    candidate.classList.toggle('text-white', isActive);
                    candidate.classList.toggle('text-stone-700', !isActive);
                    candidate.classList.toggle('bg-white', !isActive);
                });

                applyFilters();
            });
        });

        searchInput.addEventListener('input', applyFilters);
        filterButtons.find((button) => button.dataset.filterValue === activeFilter)?.click();
        renderPage();
    });
</script>
