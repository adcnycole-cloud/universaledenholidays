<x-layouts.app title="Admin Testimonials | Universal Eden Holidays">
    <main class="mx-auto max-w-[1700px] px-6 py-6 lg:px-10">
        @include('admin.partials.testimonial-management', ['testimonials' => $testimonials])
    </main>
</x-layouts.app>
