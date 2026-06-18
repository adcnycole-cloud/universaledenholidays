<x-layouts.app title="Payment Options | Universal Eden Holidays">
    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))] bg-[linear-gradient(180deg,_#fffdf9,_#f8fafc)] px-6 py-10 lg:px-10">
        <div class="mx-auto max-w-6xl">
            <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
                <div class="bg-[linear-gradient(135deg,_#1f2937,_#315fbd_58%,_#5b8def)] px-6 py-10 md:px-10 md:py-12">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-black">Payment Options</p>
                    <h1 class="mt-4 font-['Prata'] text-4xl leading-tight text-black md:text-5xl">Choose the payment method that fits your booking flow.</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-black md:text-base">
                        Universal Eden Holidays supports multiple payment paths so customers can confirm travel arrangements in the way that feels most comfortable for them.
                    </p>
                </div>

                <div class="px-6 py-8 md:px-10 md:py-10">
                    <div class="grid gap-5 lg:grid-cols-2">
                        @foreach ($paymentOptions as $option)
                            <article class="rounded-[1.6rem] border border-stone-200 bg-stone-50/70 p-6 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 class="font-['Oswald'] text-2xl font-bold uppercase tracking-[0.12em] text-stone-900">{{ $option['title'] }}</h2>
                                        <p class="mt-3 text-sm leading-7 text-stone-600">{{ $option['description'] }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-sky-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-700">
                                        {{ $option['badge'] }}
                                    </span>
                                </div>
                                <div class="mt-5 space-y-3">
                                    @foreach ($option['notes'] as $note)
                                        <div class="flex items-start gap-3 rounded-2xl bg-white px-4 py-3 text-sm leading-6 text-stone-600">
                                            <span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-700">i</span>
                                            <span>{{ $note }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <section class="mt-8 rounded-[1.6rem] border border-amber-200 bg-amber-50/70 p-6">
                        <h2 class="font-['Oswald'] text-2xl font-bold uppercase tracking-[0.12em] text-stone-900">How it works</h2>
                        <div class="mt-5 grid gap-4 md:grid-cols-3">
                            <div class="rounded-[1.25rem] bg-white px-5 py-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Step 1</p>
                                <p class="mt-3 text-sm leading-7 text-stone-600">Choose your tour or transport service and submit the booking or reserve form.</p>
                            </div>
                            <div class="rounded-[1.25rem] bg-white px-5 py-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Step 2</p>
                                <p class="mt-3 text-sm leading-7 text-stone-600">Receive your Booking ID, then review or track your request before continuing to payment.</p>
                            </div>
                            <div class="rounded-[1.25rem] bg-white px-5 py-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Step 3</p>
                                <p class="mt-3 text-sm leading-7 text-stone-600">Complete payment using the method chosen for your booking flow once the request is ready.</p>
                            </div>
                        </div>
                    </section>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('bookings.track.form') }}" class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-6 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-stone-700 transition hover:bg-stone-50">
                            Track Booking ID
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
