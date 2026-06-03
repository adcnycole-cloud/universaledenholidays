<x-layouts.app title="Admin Blog | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        @include('admin.partials.blog-management', ['blogPosts' => $blogPosts])
    </main>
    @include('admin.partials.filter-paginate-script', [
        'sectionId' => 'admin-blog-listings',
        'searchInputId' => 'admin-blog-search',
        'prevButtonId' => 'admin-blog-prev',
        'nextButtonId' => 'admin-blog-next',
        'resultsLabelId' => 'admin-blog-results',
        'listId' => 'admin-blog-list',
        'itemSelector' => '[data-admin-blog-item]',
        'pageSize' => 4,
        'emptyMessage' => 'No blog posts match your search.',
        'resultsNoun' => 'blog posts',
    ])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.querySelector('[data-blog-create-toggle]');
            const panelBody = document.querySelector('[data-blog-create-body]');

            if (toggleButton && panelBody) {
                const syncLabel = () => {
                    const isOpen = !panelBody.classList.contains('hidden');
                    toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    toggleButton.textContent = isOpen ? 'Hide Form' : 'New Post';
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
            }

            document.querySelectorAll('[data-admin-blog-item]').forEach((item) => {
                const openButton = item.querySelector('[data-blog-edit-open]');
                const modal = item.querySelector('[data-blog-edit-modal]');
                const closeButtons = item.querySelectorAll('[data-blog-edit-close]');

                if (!openButton || !modal) {
                    return;
                }

                openButton.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });

                closeButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    });
                });

                modal.addEventListener('click', (event) => {
                    if (event.target !== modal) {
                        return;
                    }

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });
        });
    </script>
</x-layouts.app>
