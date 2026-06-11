<x-layouts.app title="Admin Landing Page | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset))] w-full bg-gradient-to-br from-white via-stone-50 to-stone-100 px-6 py-8 lg:px-8">
        @include('admin.partials.landing-page-management', ['homeHeroSlides' => $homeHeroSlides])
    </main>
</x-layouts.app>
