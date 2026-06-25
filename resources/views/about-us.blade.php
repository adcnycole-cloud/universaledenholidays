<x-layouts.app title="About Us | Universal Eden Holidays">
    <style>
        .about-team-image-frame {
            overflow: hidden;
        }

        .about-team-image {
            transition: transform 0.28s ease;
        }

        .about-team-card:hover .about-team-image {
            transform: scale(1.06);
        }
    </style>

    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))] bg-white">
        <section class="w-full bg-white">
            <img
                src="{{ asset('images/banner.png') }}"
                alt="Universal Eden Holidays banner"
                class="block w-full h-auto"
            >
        </section>

        <section
            class="w-full bg-white px-6 pt-12 pb-8 md:px-8 md:pt-16 md:pb-10"
            style="background-image: url('{{ asset('images/tree.png') }}'), url('{{ asset('images/tree2.png') }}'); background-repeat: no-repeat, no-repeat; background-position: left top, right top; background-size: auto 48%, auto 48%;"
        >
            <div class="mx-auto max-w-6xl">
                <div class="mt-6 text-center md:mt-8">
                <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Who We Are</span>
                <h1 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">
                    About Us
                </h1>
                </div>
                <p class="mt-6 text-justify text-base leading-8 text-stone-700 md:text-lg">
                    {{ $companyOverview['summary'] }}
                </p>

                <div class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($companyFacts as $fact)
                        <div class="border border-stone-200 bg-white px-5 py-3 shadow-[0_16px_30px_rgba(15,23,42,0.06)]">
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-stone-500">{{ $fact['label'] }}</p>
                            <p class="mt-2 text-sm font-semibold leading-6 text-stone-900 md:text-base">{{ $fact['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full bg-white px-6 pt-3 pb-4 md:px-8 md:pt-4 md:pb-6">
            <div class="mx-auto max-w-6xl">
                <div class="mx-auto grid max-w-4xl gap-6 md:grid-cols-2">
                    @foreach ($storyBlocks as $block)
                        <article class="border border-stone-200 bg-stone-50 px-6 py-6">
                            <h3 class="font-['Oswald'] text-2xl font-bold uppercase tracking-[0.08em] text-stone-900">{{ $block['title'] }}</h3>
                            <p class="mt-4 text-base leading-8 text-stone-700">{{ $block['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full bg-[linear-gradient(180deg,_#ffffff_0%,_#f8fafc_100%)] px-6 pt-20 pb-14 md:px-8 md:pt-24 md:pb-18">
            <div class="mx-auto max-w-6xl">
                <div class="mt-6 text-center md:mt-8">
                    <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Our Team</span>
                    <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">Meet Our Team</h2>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($teamCards as $member)
                        <article class="about-team-card overflow-hidden rounded-[1.6rem] border border-stone-200 bg-white shadow-[0_24px_45px_rgba(15,23,42,0.08)]">
                            <div class="about-team-image-frame bg-stone-100" style="height: 16rem;">
                                <img src="{{ $member['photo_url'] }}" alt="{{ $member['name'] }}" class="about-team-image h-full w-full object-cover" style="height: 16rem;">
                            </div>
                            <div class="space-y-2 px-5 py-5">
                                <div>
                                    <h3 class="text-base font-semibold text-stone-900">{{ $member['name'] }}</h3>
                                    <p class="mt-1 text-sm text-stone-500">{{ $member['designation'] }}</p>
                                </div>
                                @if (!empty($member['email']))
                                    <p class="text-sm text-stone-500"><span class="font-medium text-stone-500">Email:</span> {{ $member['email'] }}</p>
                                @endif
                                @if (!empty($member['contact']))
                                    <p class="text-sm text-stone-500"><span class="font-medium text-stone-500">Contact:</span> {{ $member['contact'] }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full bg-white px-6 pt-2 pb-14 md:px-8 md:pt-3 md:pb-18">
            <div class="mx-auto max-w-6xl">
                <div class="mt-6 text-center md:mt-8">
                    <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Where We Are</span>
                    <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">{{ $officeLocation['title'] }}</h2>
                </div>

                <div class="mt-8 grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                    <div>
                    <p class="text-base leading-8 text-stone-700">
                        Universal Eden Holidays is based in Kota Kinabalu and supports guests travelling across Sabah for tours, transfers, and local holiday arrangements.
                    </p>

                    <div class="mt-8 space-y-5 border-t border-stone-200 pt-6 text-base leading-8 text-stone-800">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-stone-500">Address</p>
                            <p class="mt-2">
                                <a href="{{ $officeLocation['mapUrl'] }}" target="_blank" rel="noopener noreferrer" class="transition hover:text-[#315fbd]">
                                    {{ $officeLocation['address'] }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-stone-500">Phone</p>
                            <p class="mt-2">{{ $officeLocation['phone'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-stone-500">Email</p>
                            <p class="mt-2">{{ $officeLocation['email'] }}</p>
                        </div>
                    </div>
                    </div>

                    <div class="overflow-hidden border border-stone-200 bg-stone-100 shadow-[0_20px_40px_rgba(15,23,42,0.08)]">
                        <iframe
                            src="{{ $officeLocation['mapEmbedUrl'] }}"
                            width="100%"
                            height="460"
                            style="border: 0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Universal Eden Holidays office location map"
                        ></iframe>
                        <div class="border-t border-stone-200 bg-white px-5 py-4">
                            <a
                                href="{{ $officeLocation['mapUrl'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-sm font-medium text-[#315fbd] transition hover:text-[#25478d]"
                            >
                                Open location in Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full bg-white px-6 pt-14 pb-24 md:px-8 md:pt-18 md:pb-28">
            <div class="mx-auto max-w-6xl">
                <div class="mx-auto mt-6 max-w-3xl text-center md:mt-8">
                    <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Company Certifications</span>
                    <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">Registered Certification and Membership</h2>
                </div>

                <div class="mx-auto mt-10 grid max-w-6xl gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($certifications as $certification)
                        <a
                            href="{{ $certification['certificate_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex min-h-[18.5rem] w-full flex-col items-center border border-stone-200 bg-white px-5 py-5 text-center shadow-[0_10px_24px_rgba(15,23,42,0.06)] transition hover:-translate-y-1 hover:border-[#315fbd] hover:shadow-[0_18px_36px_rgba(15,23,42,0.1)]"
                        >
                            <div class="flex flex-col items-center">
                                <div class="flex h-44 w-44 items-center justify-center rounded-full bg-[#1d66d1] p-2 shadow-[0_12px_28px_rgba(29,102,209,0.24)]">
                                    <img src="{{ $certification['logo_url'] }}" alt="{{ $certification['title'] }} logo" class="h-36 w-auto object-contain">
                                </div>
                                <h3 class="-mt-8 text-lg font-semibold text-stone-900">{{ $certification['title'] }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        @include('partials.footer')
    </main>
</x-layouts.app>
