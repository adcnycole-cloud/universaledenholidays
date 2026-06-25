<x-layouts.app title="Admin Certifications | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="mt-5 space-y-8">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-600">About Us</p>
                        <h1 class="mt-2 text-2xl font-semibold text-stone-900">Certification management</h1>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">
                            Add, update, and remove the company certification details shown on the public About Us page.
                        </p>
                    </div>
                    <div class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                        {{ ($companyCertifications ?? collect())->count() }} certification{{ ($companyCertifications ?? collect())->count() === 1 ? '' : 's' }}
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.about-us') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-300 hover:bg-stone-50">
                        About Us navigation
                    </a>
                    <a href="{{ route('admin.staff') }}" class="rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-300 hover:bg-stone-50">
                        Staff management profile
                    </a>
                    <a href="{{ route('admin.about-us.certifications') }}" class="rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100">
                        Certification
                    </a>
                </div>
            </section>

            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-700">Company Credentials</p>
                        <h2 class="mt-2 text-2xl font-semibold text-stone-900">Company certification management</h2>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 xl:grid-cols-[24rem_minmax(0,1fr)]">
                    <form method="POST" action="{{ route('admin.company-certifications.store') }}" enctype="multipart/form-data" class="space-y-4 rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5" data-form-persist="admin-company-certifications-create">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-medium text-stone-700">Title</label>
                            <input name="title" type="text" value="{{ old('title') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-stone-700">Logo</label>
                            <input name="logo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700">
                            <p class="mt-2 text-xs text-stone-500">Upload logo image: JPG, PNG, or WebP.</p>
                        </div>
                        <div class="space-y-3 rounded-[1.25rem] border border-stone-200 bg-white p-4" data-certificate-source-wrap>
                            <div>
                                <p class="text-sm font-medium text-stone-700">Certificate Source</p>
                                <p class="mt-1 text-xs text-stone-500">Choose whether this certification opens a file upload or an external link.</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100" data-certificate-source-button data-source-value="file">
                                    Upload File
                                </button>
                                <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100" data-certificate-source-button data-source-value="link">
                                    Upload Link
                                </button>
                            </div>
                            <input type="hidden" name="certificate_source" value="{{ old('certificate_source', 'file') }}" data-certificate-source-input>

                            <div data-certificate-source-panel="file">
                                <label class="mb-2 block text-sm font-medium text-stone-700">Certificate File</label>
                                <input name="certificate" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700">
                                <p class="mt-2 text-xs text-stone-500">Upload certificate as image or PDF.</p>
                            </div>

                            <div data-certificate-source-panel="link">
                                <label class="mb-2 block text-sm font-medium text-stone-700">Certificate Link</label>
                                <input name="certificate_link" type="url" value="{{ old('certificate_link') }}" placeholder="https://example.com/certificate.pdf" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                <p class="mt-2 text-xs text-stone-500">Paste a public certificate URL.</p>
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-stone-700">Display Order</label>
                            <input name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', 0) }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                        </div>
                        <button type="submit" class="inline-flex rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-amber-700">Add Certification</button>
                    </form>

                    <div class="overflow-hidden rounded-[1.5rem] border border-stone-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-stone-200 text-left text-sm">
                                <thead class="bg-stone-100 text-stone-700">
                                    <tr>
                                        <th class="px-5 py-4 font-semibold">Logo</th>
                                        <th class="px-5 py-4 font-semibold">Title</th>
                                        <th class="px-5 py-4 font-semibold">Certificate</th>
                                        <th class="px-5 py-4 font-semibold">Order</th>
                                        <th class="px-5 py-4 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-200 bg-white">
                                    @forelse (($companyCertifications ?? collect()) as $certification)
                                        <tr>
                                            <td class="px-5 py-4 align-top">
                                                @if ($certification->logo_url)
                                                    <img src="{{ $certification->logo_url }}" alt="{{ $certification->title }} logo" class="h-14 w-14 rounded-2xl object-contain border border-stone-200 bg-white p-1">
                                                @else
                                                    <span class="text-sm text-stone-400">No logo</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 align-top font-semibold text-stone-900">{{ $certification->title }}</td>
                                            <td class="px-5 py-4 align-top text-stone-700">
                                                @if ($certification->certificate_url)
                                                    <a href="{{ $certification->certificate_url }}" target="_blank" rel="noopener noreferrer" class="font-medium text-[#315fbd] transition hover:text-[#25478d]">
                                                        {{ $certification->certificate_link ? 'View link' : 'View file' }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 align-top text-stone-700">{{ $certification->sort_order }}</td>
                                            <td class="px-5 py-4 align-top">
                                                <div class="flex flex-wrap gap-2">
                                                    <details class="group min-w-[16rem]">
                                                        <summary class="cursor-pointer list-none rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-700 transition hover:bg-stone-100">
                                                            <span class="group-open:hidden">Edit</span>
                                                            <span class="hidden group-open:inline">Close</span>
                                                        </summary>
                                                        <form method="POST" action="{{ route('admin.company-certifications.update', $certification) }}" enctype="multipart/form-data" class="mt-4 grid gap-4 rounded-2xl border border-stone-200 bg-stone-50 p-4" data-form-persist="admin-company-certifications-update-{{ $certification->id }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div>
                                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Title</label>
                                                                <input name="title" type="text" value="{{ $certification->title }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800" required>
                                                            </div>
                                                            <div>
                                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Replace Logo</label>
                                                                <input name="logo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700">
                                                                @if ($certification->logo_url)
                                                                    <div class="mt-3">
                                                                        <img src="{{ $certification->logo_url }}" alt="{{ $certification->title }} logo" class="h-16 w-16 rounded-2xl object-contain border border-stone-200 bg-white p-1">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="space-y-3 rounded-[1.25rem] border border-stone-200 bg-white p-4" data-certificate-source-wrap>
                                                                <div>
                                                                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Certificate Source</p>
                                                                    <p class="mt-1 text-xs text-stone-500">Choose which certificate type this card should open.</p>
                                                                </div>
                                                                <div class="flex flex-wrap gap-2">
                                                                    <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-certificate-source-button data-source-value="file">
                                                                        Upload File
                                                                    </button>
                                                                    <button type="button" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-100" data-certificate-source-button data-source-value="link">
                                                                        Upload Link
                                                                    </button>
                                                                </div>
                                                                <input type="hidden" name="certificate_source" value="{{ old('certificate_source', $certification->certificate_link ? 'link' : 'file') }}" data-certificate-source-input>

                                                                <div data-certificate-source-panel="file">
                                                                    <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Replace Certificate File</label>
                                                                    <input name="certificate" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-sm text-stone-700">
                                                                    @if ($certification->certificate_path)
                                                                        <a href="{{ $certification->certificate_url }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex text-sm font-medium text-[#315fbd] transition hover:text-[#25478d]">
                                                                            View current file
                                                                        </a>
                                                                    @endif
                                                                </div>

                                                                <div data-certificate-source-panel="link">
                                                                    <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Certificate Link</label>
                                                                    <input name="certificate_link" type="url" value="{{ old('certificate_link', $certification->certificate_link) }}" placeholder="https://example.com/certificate.pdf" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                                                    @if ($certification->certificate_link)
                                                                        <a href="{{ $certification->certificate_link }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex text-sm font-medium text-[#315fbd] transition hover:text-[#25478d]">
                                                                            View current link
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="mb-2 block text-xs font-medium uppercase tracking-[0.18em] text-stone-500">Display Order</label>
                                                                <input name="sort_order" type="number" min="0" max="9999" value="{{ $certification->sort_order }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800">
                                                            </div>
                                                            <div>
                                                                <button type="submit" class="inline-flex rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-emerald-700">Save Certification</button>
                                                            </div>
                                                        </form>
                                                    </details>
                                                    <form method="POST" action="{{ route('admin.company-certifications.destroy', $certification) }}" onsubmit="return confirm('Delete this certification?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-full border border-rose-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:bg-rose-50">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-8 text-center text-sm text-stone-600">No company certifications saved yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-certificate-source-wrap]').forEach((wrap) => {
                const input = wrap.querySelector('[data-certificate-source-input]');
                const buttons = Array.from(wrap.querySelectorAll('[data-certificate-source-button]'));
                const panels = Array.from(wrap.querySelectorAll('[data-certificate-source-panel]'));

                if (!input) {
                    return;
                }

                const syncSource = (source) => {
                    input.value = source;

                    buttons.forEach((button) => {
                        const active = button.dataset.sourceValue === source;
                        button.classList.toggle('border-amber-300', active);
                        button.classList.toggle('bg-amber-50', active);
                        button.classList.toggle('text-amber-700', active);
                        button.classList.toggle('border-stone-300', !active);
                        button.classList.toggle('bg-white', !active);
                        button.classList.toggle('text-stone-700', !active);
                    });

                    panels.forEach((panel) => {
                        panel.classList.toggle('hidden', panel.dataset.certificateSourcePanel !== source);
                    });
                };

                buttons.forEach((button) => {
                    button.addEventListener('click', () => syncSource(button.dataset.sourceValue || 'file'));
                });

                syncSource(input.value || 'file');
            });
        });
    </script>
</x-layouts.app>
