<x-layouts.app title="Payment Options | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))] bg-white">
        <div class="w-full">
            <section class="relative w-full bg-white">
                <img
                    src="{{ asset('images/payment.png') }}"
                    alt="Payment Options"
                    class="block w-full h-auto"
                >
                <div class="absolute inset-0 flex items-center justify-center px-6 text-center">
                    <h1 class="rounded-full bg-white/85 px-6 py-3 font-['Oswald'] text-4xl font-bold uppercase tracking-[0.06em] text-stone-900 shadow-[0_10px_24px_rgba(255,255,255,0.28)] md:text-5xl">
                        Payment Options
                    </h1>
                </div>
            </section>

            <section class="w-full bg-white px-6 py-10 lg:px-10">
                <div class="mx-auto max-w-6xl">
                    <p class="max-w-4xl text-base leading-8 text-stone-700">
                        To pay for your reservations, we offer the following payment options:
                    </p>

                    <ul class="mt-6 space-y-4 pl-6 text-base leading-8 text-stone-800 marker:text-stone-600">
                        @foreach ($paymentOptions as $option)
                            <li>
                                <span class="font-semibold">{{ $option['title'] }}</span>
                                <span> - {{ $option['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 border-t border-stone-200 pt-6">
                        @foreach ($paymentOptions as $index => $option)
                            <section class="{{ $index > 0 ? 'mt-8' : '' }}">
                                <h2 class="text-3xl font-semibold text-stone-900">
                                    {{ chr(65 + $index) }}) Payment by {{ $option['title'] }}
                                </h2>

                                <p class="mt-4 text-lg font-semibold uppercase tracking-[0.04em] text-stone-900">
                                    {{ $option['badge'] }}
                                </p>

                                <p class="mt-6 text-base leading-8 text-stone-800">
                                    {{ $option['description'] }}
                                </p>

                                <div class="mt-5 space-y-4 text-base leading-8 text-stone-800">
                                    @foreach ($option['notes'] as $note)
                                        <p>{{ $note }}</p>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>

                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
