<x-layouts.app title="Admin About Us | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <section class="mt-5 space-y-8">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-600">About Us</p>
                        <h1 class="mt-2 text-2xl font-semibold text-stone-900">About Us management</h1>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">
                            Choose which About Us content you want to manage first, then continue on the dedicated page for that section.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                            {{ ($staffMembers ?? collect())->count() }} staff member{{ ($staffMembers ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                        <div class="rounded-full bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700">
                            {{ ($companyCertifications ?? collect())->count() }} certification{{ ($companyCertifications ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 md:grid-cols-2">
                <a href="{{ route('admin.staff') }}" class="group rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-sky-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-stone-900">Staff management profile</h2>
                            <p class="mt-3 max-w-xl text-sm leading-7 text-stone-600">
                                Add, edit, and remove the staff profiles shown on the public About Us page.
                            </p>
                        </div>
                        <div class="rounded-full bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700">
                            {{ ($staffMembers ?? collect())->count() }} profile{{ ($staffMembers ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition group-hover:bg-sky-100">
                        Open staff page
                        <span aria-hidden="true">→</span>
                    </div>
                </a>

                <a href="{{ route('admin.about-us.certifications') }}" class="group rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-amber-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-stone-900">Certification</h2>
                            <p class="mt-3 max-w-xl text-sm leading-7 text-stone-600">
                                Manage the company certification records and display order shown on the About Us page.
                            </p>
                        </div>
                        <div class="rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                            {{ ($companyCertifications ?? collect())->count() }} item{{ ($companyCertifications ?? collect())->count() === 1 ? '' : 's' }}
                        </div>
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition group-hover:bg-amber-100">
                        Open certification page
                        <span aria-hidden="true">→</span>
                    </div>
                </a>
            </section>
        </section>
    </main>
</x-layouts.app>
