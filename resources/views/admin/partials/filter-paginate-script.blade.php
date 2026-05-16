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

        const list = document.getElementById('{{ $listId }}');

        if (!list) {
            return;
        }

        const items = Array.from(list.querySelectorAll('{{ $itemSelector }}'));
        const pageSize = {{ $pageSize }};
        let activePage = 0;
        let filteredItems = items;

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

        const applySearch = () => {
            const query = searchInput.value.trim().toLowerCase();
            filteredItems = items.filter((item) => item.textContent.toLowerCase().includes(query));
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

        searchInput.addEventListener('input', applySearch);
        renderPage();
    });
</script>
