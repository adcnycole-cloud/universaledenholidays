<x-layouts.app title="About Us | Universal Eden Holidays">
    <style>
        .about-hero {
            position: relative;
            overflow: hidden;
            margin-top: -3.1rem;
            min-height: 20.25rem;
            background: url('{{ asset('images/aboutus_bg.png') }}') center center / cover no-repeat;
        }

        .about-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(7, 25, 44, 0.42);
            pointer-events: none;
        }

        .about-hero-inner {
            position: absolute;
            inset: 0;
            min-height: inherit;
            z-index: 1;
        }

        .about-hero-copy {
            width: min(100%, 84rem);
            margin: 0 auto;
            text-align: center;
        }

        .about-hero-kicker {
            margin: 0;
            color: rgba(255, 255, 255, 0.96);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            text-shadow: 0 8px 24px rgba(15, 23, 42, 0.28);
        }

        .about-hero-title {
            margin: 0.7rem 0 0;
            font-family: "Prata", Georgia, serif;
            font-size: clamp(2.2rem, 4.4vw, 3.5rem);
            line-height: 1.1;
            color: #f8fafc;
            text-shadow: 0 10px 28px rgba(15, 23, 42, 0.34);
        }

        .about-hero-subtitle {
            margin: 0.9rem auto 0;
            max-width: 52rem;
            font-size: 1rem;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.94);
            text-shadow: 0 6px 20px rgba(15, 23, 42, 0.28);
        }

        .about-intro-card {
            position: relative;
            margin-top: 0;
            z-index: 2;
            border: 0;
            background: #ffffff;
            box-shadow: none;
        }

        .about-intro-photo {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-intro-media {
            width: 100%;
            max-width: 30rem;
            margin: 0 auto;
            height: 11rem;
        }

        .about-fact-item + .about-fact-item {
            border-top: 1px solid #e7e5e4;
        }

        .about-fact-icon {
            flex-shrink: 0;
            color: #6ca73f;
        }

        .about-section-head {
            text-align: center;
        }

        .about-section-kicker {
            color: #41ad43;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .about-section-title {
            margin: 0.8rem 0 0;
            font-family: "Prata", Georgia, serif;
            font-size: clamp(2.15rem, 4vw, 3.2rem);
            line-height: 1.08;
            color: #172b4d;
        }

        @media (min-width: 768px) {
            .about-intro-media {
                height: 12.5rem;
            }
        }

        @media (min-width: 1024px) {
            .about-intro-media {
                height: 15.5rem;
            }
        }

        @media (max-width: 767px) {
            .about-hero {
                margin-top: 0;
                min-height: 15rem;
            }

            .about-intro-media {
                max-width: 20rem;
            }

            .about-hero-kicker {
                font-size: 0.72rem;
                letter-spacing: 0.24em;
            }

            .about-hero-title {
                font-size: clamp(2rem, 8vw, 2.75rem);
            }

            .about-hero-subtitle {
                font-size: 0.95rem;
                line-height: 1.65;
            }
        }

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
        <section class="about-hero">
            <div class="about-hero-inner flex items-center justify-center px-6 py-6 text-white md:px-8 md:py-8">
                <div class="about-hero-copy">
                    <p class="about-hero-kicker">
                        Discover Sabah With Us
                    </p>
                    <h1 class="about-hero-title">
                        About Universal Eden Holidays
                    </h1>
                    <p class="about-hero-subtitle">
                        Local knowledge. Flexible journeys. Memorable experiences.
                    </p>
                </div>
            </div>
        </section>

        <section id="about-story" class="w-full bg-white px-6 pt-5 pb-4 md:px-8 md:pt-6 md:pb-5">
            <div class="about-intro-card mx-auto max-w-5xl px-0 py-3 md:py-4">
                <div class="about-section-head">
                    <span class="inline-flex rounded-full bg-[#ffe8df] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#f97316]">Who We Are</span>
                    <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900 md:text-4xl">
                        Your Local Travel Partner in Sabah
                    </h2>
                </div>

                <div class="mt-7 flex flex-col gap-6 lg:flex-row lg:items-center lg:gap-12">
                    <div class="about-intro-media overflow-hidden rounded-[1rem] bg-stone-100 lg:w-[30rem] lg:flex-none">
                        <img
                            src="{{ asset('images/aboutus.png') }}"
                            alt="Universal Eden Holidays guests and transport service"
                            class="about-intro-photo"
                        >
                    </div>

                    <div class="lg:min-w-0 lg:flex-1" style="margin-top: 2.5rem;">
                        <h3 class="font-['Prata'] text-[1.9rem] leading-tight text-[#16284c]">
                            Explore Sabah with confidence
                        </h3>
                        <p class="mt-4 text-[1.03rem] leading-8 text-stone-700" style="text-align: justify;">
                            {{ $companyOverview['summary'] }}
                        </p>
                        <a
                            href="#about-team"
                            class="mt-6 inline-flex items-center justify-center rounded-[0.35rem] border border-[#6ca73f] px-8 py-3 text-sm font-semibold text-[#356b2b] transition hover:bg-[#6ca73f] hover:text-white"
                            style="margin-bottom: 1.75rem;"
                        >
                            Learn More About Us
                        </a>
                    </div>
                </div>

                <div class="mt-16 grid gap-4 pt-5 md:grid-cols-2 xl:grid-cols-4 xl:gap-0">
                    @foreach ($companyFacts as $fact)
                        <div class="about-fact-item flex items-start gap-3 px-1 py-4 md:px-3 xl:border-l xl:border-stone-200 xl:px-4 xl:first:border-l-0">
                            <div class="about-fact-icon mt-0.5">
                                @if ($loop->first)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                @elseif ($loop->iteration === 2)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 21s-6-4.35-6-10a6 6 0 1 1 12 0c0 5.65-6 10-6 10z"></path>
                                        <circle cx="12" cy="11" r="2.5"></circle>
                                    </svg>
                                @elseif ($loop->iteration === 3)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4 21h16"></path>
                                        <path d="M7 21V7l5-4 5 4v14"></path>
                                        <path d="M9 10h1"></path>
                                        <path d="M14 10h1"></path>
                                        <path d="M9 14h1"></path>
                                        <path d="M14 14h1"></path>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M6 7h12"></path>
                                        <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <rect x="3" y="7" width="18" height="13" rx="2"></rect>
                                        <path d="M3 12h18"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.22em] text-[#334155]">{{ $fact['label'] }}</p>
                                <p class="mt-1.5 text-[0.96rem] font-semibold leading-6 text-stone-900">{{ $fact['value'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full bg-white px-6 pt-3 pb-4 md:px-8 md:pt-4 md:pb-6">
            <div class="mx-auto max-w-6xl">
                <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-2">
                    @foreach ($storyBlocks as $block)
                        <article class="border border-stone-200 bg-stone-50 px-6 py-6">
                            <h3 class="font-['Oswald'] text-2xl font-bold uppercase tracking-[0.08em] text-stone-900">{{ $block['title'] }}</h3>
                            <p class="mt-4 text-base leading-8 text-stone-700" style="text-align: justify;">{{ $block['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="about-team" class="w-full bg-[linear-gradient(180deg,_#ffffff_0%,_#f8fafc_100%)] px-6 pt-20 pb-14 md:px-8 md:pt-24 md:pb-18">
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
