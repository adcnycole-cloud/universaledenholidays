<x-layouts.app title="Admin Transport | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-6 lg:px-10">
        <section class="mt-5 space-y-8">
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-sm uppercase tracking-[0.3em] text-sky-700">Transport</p>
                <h1 class="mt-2 text-3xl font-semibold text-stone-900">Manage fixed transport fleet</h1>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Transport is limited to these three options for now: <strong>41/44 Seaters Bus</strong>, <strong>17 Seaters Van</strong>, and <strong>9/14 Seaters Van</strong>.
                    Use the edit and delete controls on the right to manage them.
                </p>
                <div class="mt-6 rounded-[1.5rem] border border-sky-100 bg-sky-50 p-5 text-sm leading-7 text-sky-900">
                    Additional transport products are disabled for now so the homepage and booking flow stay aligned with the current fleet options.
                </div>
            </section>
            <section class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-stone-900">Current transport listings</h2>
                @include('admin.partials.product-table', ['products' => $transportProducts, 'editable' => true])
            </section>
        </section>
    </main>
</x-layouts.app>
