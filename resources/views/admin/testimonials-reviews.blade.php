<x-layouts.app title="Customer Reviews Management | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        <div class="mx-auto max-w-[1600px]">
            @include('admin.partials.testimonial-management', ['testimonials' => $testimonials])
        </div>
    </main>
</x-layouts.app>
