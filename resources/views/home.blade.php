<x-layouts.app title="Universal Eden Holidays | Sabah Tours and Transport">
    <div style="background-color: #f1f0e9;">
    <style>
        html,
        body {
            overflow-x: hidden;
        }

        .home-screen-section {
            min-height: calc(100svh - var(--home-header-offset, 0px));
            height: auto;
        }

        .home-section-compact {
            min-height: 0;
            height: auto;
        }

        .home-screen-section--hero {
            height: 100svh;
            min-height: 100svh;
        }

        .home-screen-section--hero > div[style*="min-height"] {
            min-height: 100svh !important;
        }

        .hero-content-shell {
            padding-left: clamp(1.25rem, 4vw, 3.25rem) !important;
            transform: translateY(clamp(-4rem, -5vw, -1.5rem));
        }

        .hero-copy {
            margin-left: 0;
        }

        .hero-copy-stack {
            max-width: min(100%, 90rem);
        }

        .hero-bus {
            height: clamp(3.8rem, 6vw, 5.4rem) !important;
        }

        #discover-heading {
            max-width: min(100%, 34rem) !important;
            font-size: clamp(2.3rem, 4.8vw, 4.8rem) !important;
        }

        #discover-subheading {
            max-width: min(100%, 40rem) !important;
            margin-left: clamp(1.2rem, 7vw, 6.2rem) !important;
            font-size: clamp(2.5rem, 5.3vw, 5.4rem) !important;
        }

        .hero-tagline {
            width: min(100%, 48rem) !important;
            min-width: 0 !important;
            margin-left: clamp(0rem, 5vw, 4.2rem) !important;
        }

        .hero-tagline-inner {
            padding: clamp(0.45rem, 1vw, 0.55rem) clamp(1.1rem, 4vw, 3.4rem) clamp(0.5rem, 1.1vw, 0.65rem) !important;
        }

        .hero-tagline-text {
            font-size: clamp(1.15rem, 2.6vw, 2.7rem) !important;
        }

        .package-offer-section.home-screen-section {
            overflow: hidden;
        }

        @media (max-width: 1365px) {
            .hero-content-shell {
                padding: 3rem 1.75rem 3rem clamp(1.25rem, 4vw, 2.5rem) !important;
                transform: translateY(-2.5rem);
            }

            .hero-copy {
                margin-left: 0;
            }

            #discover-heading {
                font-size: clamp(2.15rem, 4.1vw, 3.8rem) !important;
            }

            #discover-subheading {
                margin-left: clamp(1rem, 5.5vw, 4rem) !important;
                font-size: clamp(2.3rem, 4.7vw, 4.4rem) !important;
            }

            .hero-tagline {
                width: min(100%, 40rem) !important;
                margin-left: clamp(0.5rem, 4vw, 2.8rem) !important;
                margin-top: 1rem !important;
            }

            .hero-tagline-text {
                font-size: clamp(1.05rem, 2.1vw, 2rem) !important;
                letter-spacing: 0.05em !important;
            }
        }

        @media (max-width: 1180px) {
            .hero-content-shell {
                padding: 2.5rem 1.5rem 2.5rem clamp(1.1rem, 3.5vw, 2rem) !important;
                transform: translateY(-1.5rem);
            }

            .hero-copy {
                margin-left: 0 !important;
            }

            .hero-bus {
                height: 3.9rem !important;
            }

            #discover-heading {
                max-width: 100% !important;
                font-size: clamp(2rem, 3.8vw, 3.2rem) !important;
                white-space: normal !important;
            }

            #discover-subheading {
                max-width: 100% !important;
                margin-left: clamp(0.75rem, 4vw, 2.4rem) !important;
                text-align: left !important;
                font-size: clamp(2.15rem, 4.3vw, 3.7rem) !important;
            }

            .hero-tagline {
                width: min(100%, 34rem) !important;
                margin-left: clamp(0rem, 2.5vw, 1.5rem) !important;
            }

            .hero-tagline-inner {
                padding: 0.5rem clamp(1rem, 2.5vw, 1.8rem) 0.6rem !important;
            }

            .hero-tagline-text {
                font-size: clamp(1rem, 1.9vw, 1.55rem) !important;
                transform: none !important;
            }
        }

        @media (max-width: 1023px) {
            .home-screen-section {
                height: auto;
                min-height: 100svh;
            }

            .package-offer-section.home-screen-section {
                overflow: hidden;
            }
        }

        @media (max-width: 767px) {
            .hero-content-shell {
                min-height: 100svh !important;
                padding: 2rem 1rem 2.5rem 1rem !important;
                transform: none !important;
            }

            .hero-copy {
                margin-left: 0 !important;
                width: 100%;
            }

            .hero-copy-stack {
                max-width: 100% !important;
            }

            .hero-plane {
                width: 120px !important;
            }

            .hero-bus {
                height: 4rem !important;
            }

            #discover-heading {
                max-width: 100% !important;
                font-size: clamp(2rem, 11vw, 3rem) !important;
                white-space: normal !important;
            }

            #discover-subheading {
                max-width: 100% !important;
                margin-left: 0 !important;
                text-align: left !important;
                font-size: clamp(2.2rem, 12vw, 3.2rem) !important;
            }

            .hero-tagline {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                margin-left: 0 !important;
            }

            .hero-tagline-inner {
                padding: 0.55rem 1rem 0.65rem !important;
            }

            .hero-tagline-text {
                font-size: clamp(1rem, 5vw, 1.35rem) !important;
                letter-spacing: 0.03em !important;
                transform: none !important;
            }

            .popular-picks-heading {
                left: 0 !important;
                font-size: 2.5rem !important;
                line-height: 1.05 !important;
            }

            #promos [id$="-button"] {
                height: 2.7rem !important;
                width: 2.7rem !important;
                font-size: 2.2rem !important;
            }

            .promo-prev-wrap {
                left: 0.35rem !important;
            }

            .promo-next-wrap {
                right: 0.35rem !important;
            }

            #promo-detail-modal {
                padding: 1rem !important;
            }

            .promo-detail-layout {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .promo-detail-media {
                min-height: auto !important;
                padding: 3.5rem 1rem 1rem !important;
            }

            .popular-package-card {
                width: min(100%, 350px) !important;
                min-height: auto !important;
            }

            #transport {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .transport-shell {
                padding: 1rem 1rem 1.5rem !important;
            }

            .transport-copy {
                margin-left: 0 !important;
                max-width: 100% !important;
            }

            .transport-box {
                padding: 1.4rem 1rem !important;
                min-height: 0 !important;
            }

            .transport-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }

            .transport-features {
                flex-wrap: wrap !important;
                gap: 1.25rem !important;
            }

            .transport-feature-item {
                width: calc(50% - 0.75rem) !important;
                min-width: 0 !important;
            }

            .package-section-stage {
                padding: 0 1rem 2rem !important;
            }

            .package-section-prev-wrap {
                left: 1rem !important;
            }

            .package-section-next-wrap {
                right: 1rem !important;
            }

            .package-section-label {
                min-width: 0 !important;
                width: 100% !important;
                max-width: 18rem !important;
                padding: 0.8rem 1.2rem 0.9rem !important;
            }

            .package-section-summary {
                font-size: 1rem !important;
                line-height: 1.55 !important;
            }

            .package-carousel-shell {
                max-width: 100% !important;
                overflow: hidden !important;
            }

            .package-section-card {
                width: min(100%, 320px) !important;
                min-width: min(100%, 320px) !important;
            }

            .package-section-card > a:first-child {
                min-height: 0 !important;
            }
        }

        @media (max-width: 1280px) {
            .transport-shell {
                padding-bottom: 2rem !important;
            }
        }
    </style>

    <section class="home-screen-section home-screen-section--hero relative w-full overflow-hidden bg-black min-h-screen">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg_image.png') }}'); background-size: cover; background-position: center center;"></div>
            <img
                src="{{ asset('images/plane.png') }}"
                alt="Plane"
                class="hero-plane pointer-events-none absolute right-0 top-0 z-10"
                style="width: 200px;"
            >
        </div>
        <div class="hero-content-shell" style="position: relative; margin: 0 auto; display: flex; min-height: 100vh; max-width: 92rem; flex-direction: column; justify-content: center; padding: 3rem 2rem 3rem 1rem;">
            <div style="display:flex; width:100%; align-items:center; gap:2.5rem;">
                <div class="hero-copy" style="display:flex; min-width:0; flex:1 1 0%; flex-direction:column; align-items:flex-start; gap:0.75rem; text-align:left;">
                    <img class="hero-bus" src="{{ asset('images/bus.png') }}" alt="Bus" style="width: auto;">
                    <div class="hero-copy-stack" style="display:flex; width:100%; flex-direction:column; align-items:flex-start; gap:0; text-align:left;">
                        <h2 id="discover-heading" style="display: block; width: 100%; max-width: 34rem; font-family: 'Vendura', sans-serif; font-size: clamp(2.8rem, 5vw, 4.8rem); font-weight: 600; line-height: 0.9; letter-spacing: 0.01em; text-transform: uppercase; color: #ffffff; transform: scaleX(0.76); transform-origin: left center; white-space: nowrap;">TRAVEL AND RIDE</h2>
                        <h2 id="discover-subheading" style="display: block; width: 100%; max-width: 40rem; margin-left: 6.2rem; text-align: center; font-family: 'Vendura', sans-serif; font-size: clamp(3rem, 5.8vw, 5.4rem); font-weight: 700; color: #ffffff;">WITH US</h2>
                        <div class="hero-tagline" style="margin-top: 1.35rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; padding: 0.35rem; background: linear-gradient(90deg, rgba(38,164,232,0.96), rgba(58,86,195,0.96)); box-shadow: 0 14px 28px rgba(15,23,42,0.22);">
                            <div class="hero-tagline-inner" style="width: 100%; border-radius: 999px; border: 2px solid rgba(255,255,255,0.45); padding: 0.45rem 3.4rem 0.55rem; background: linear-gradient(90deg, rgba(62,180,242,0.18), rgba(76,65,186,0.18));">
                                <span class="hero-tagline-text" style="display: block; width: 100%; text-align: center; font-family: 'Oswald', sans-serif; font-size: clamp(1.7rem, 3vw, 2.7rem); font-weight: 700; line-height: 1; letter-spacing: 0.07em; color: #ffffff; text-transform: none; transform: scaleX(1.14); transform-origin: center;">Discover All Of Sabah Borneo</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <main class="relative mx-auto flex max-w-7xl flex-col gap-14 rounded-[2.5rem] bg-[#e6eee8] px-6 py-10 lg:px-10 lg:max-w-[1900px]" style="overflow-x: clip;">
        <div class="pointer-events-none absolute inset-x-0 top-8 -z-10 h-72 rounded-[3rem] bg-[radial-gradient(circle_at_top_left,_rgba(134,239,172,0.16),_transparent_38%),radial-gradient(circle_at_top_right,_rgba(190,242,100,0.14),_transparent_34%)]"></div>

        <div>
            <section id="promos" class="rounded-[2.2rem] bg-white px-4 py-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)] md:px-8 md:py-10">
                @php
                    $promoSlides = collect();

                    if ($currentPromo) {
                        $promoSlides->push([
                            'title' => $currentPromo->title,
                            'summary' => $currentPromo->summary,
                            'poster_url' => $currentPromo->poster_url,
                            'promo_label' => $currentPromo->promo_label ?: 'Discover Sabah',
                            'date_label' => $currentPromo->ends_at ? 'Until '.$currentPromo->ends_at->format('d M Y') : null,
                            'range_label' => ($currentPromo->starts_at?->format('d M Y') ?: 'Available now').' - '.($currentPromo->ends_at?->format('d M Y') ?: 'While active'),
                            'status' => 'Current Promotion',
                        ]);
                    }

                    foreach ($pastPromos as $promo) {
                        $promoSlides->push([
                            'title' => $promo->title,
                            'summary' => $promo->summary,
                            'poster_url' => $promo->poster_url,
                            'promo_label' => $promo->promo_label ?: 'Discover Sabah',
                            'date_label' => $promo->ends_at ? 'Ended '.$promo->ends_at->format('d M Y') : null,
                            'range_label' => ($promo->starts_at?->format('d M Y') ?: 'Available now').' - '.($promo->ends_at?->format('d M Y') ?: 'While active'),
                            'status' => 'Past Promotion',
                        ]);
                    }
                @endphp

                <h2 class="text-center font-['Prata'] text-3xl text-stone-900 md:text-4xl">
                    Promotion & News
                </h2>

                <div class="mx-auto mt-8 max-w-[1280px] rounded-[1.5rem] bg-white/95 px-6 py-8 shadow-[0_16px_36px_rgba(15,23,42,0.08)] md:px-10">
                    <div style="position: relative;">
                        <div class="promo-prev-wrap" style="position: absolute; left: -1.1rem; top: 50%; z-index: 20; display: flex; align-items: center; justify-content: center; transform: translateY(-50%);">
                            <button
                                id="promo-prev-button"
                                type="button"
                                style="display: inline-flex; height: 3.2rem; width: 3.2rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.9); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 2.8rem; font-weight: 300; line-height: 1; color: #8aa0d7; transition: transform 0.2s ease, color 0.2s ease;"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.color='#6e87c9';"
                                onmouseout="this.style.transform='scale(1)'; this.style.color='#8aa0d7';"
                                aria-label="Show previous promotion"
                            >&lsaquo;</button>
                        </div>

                        <div style="width: 100%; max-width: 980px; margin: 0 auto;">
                            <h3 style="text-align: left; font-size: 1.15rem; font-weight: 600; color: rgb(41 37 36);">
                                Latest promos and limited-time offers
                            </h3>

                            @if ($promoSlides->isNotEmpty())
                                <div id="promo-carousel" style="position: relative; margin-top: 1.25rem; min-height: clamp(38rem, 70vh, 48rem);">
                                    @foreach ($promoSlides as $index => $promoSlide)
                                        <article
                                            class="promo-slide-item"
                                            data-promo-slide-index="{{ $index }}"
                                            data-promo-status="{{ $promoSlide['status'] }}"
                                            style="position: absolute; inset: 0; overflow: hidden; border-radius: 1.25rem; border: 1px solid rgb(231 229 228); background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05); opacity: {{ $index === 0 ? '1' : '0' }}; transform: translateX({{ $index === 0 ? '0' : '70px' }}); pointer-events: {{ $index === 0 ? 'auto' : 'none' }}; transition: transform 0.4s ease, opacity 0.4s ease;"
                                        >
                                            <button
                                                type="button"
                                                class="promo-poster-trigger"
                                                data-promo-title="{{ $promoSlide['title'] }}"
                                                data-promo-summary="{{ $promoSlide['summary'] }}"
                                                data-promo-poster="{{ $promoSlide['poster_url'] }}"
                                                data-promo-label="{{ $promoSlide['promo_label'] }}"
                                                data-promo-date="{{ $promoSlide['date_label'] }}"
                                                data-promo-range="{{ $promoSlide['range_label'] }}"
                                                data-promo-status="{{ $promoSlide['status'] }}"
                                                style="display: block; width: 100%; border: none; background: transparent; padding: 0; cursor: pointer;"
                                            >
                                                <img src="{{ $promoSlide['poster_url'] }}" alt="{{ $promoSlide['title'] }}" style="height: clamp(20rem, 42vh, 28rem); width: 100%; object-fit: contain; background: #fff;">
                                            </button>
                                            <div style="border-top: 1px solid rgb(245 245 244); padding: 1.25rem 1.5rem 1.5rem;">
                                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.22em; color: #d65f6d;">
                                                    <span>{{ $promoSlide['promo_label'] }}</span>
                                                    @if ($promoSlide['date_label'])
                                                        <span style="color: #d6a24b;">{{ $promoSlide['date_label'] }}</span>
                                                    @endif
                                                </div>
                                                <h4 style="margin-top: 0.85rem; font-size: 1.95rem; font-weight: 600; color: rgb(28 25 23);">{{ $promoSlide['title'] }}</h4>
                                                @if ($promoSlide['summary'])
                                                    <div style="margin-top: 0.85rem;">
                                                        <p style="font-size: 1rem; line-height: 1.7rem; color: rgb(87 83 78);">{{ \Illuminate\Support\Str::limit($promoSlide['summary'], 360) }}</p>
                                                        @if (\Illuminate\Support\Str::length($promoSlide['summary']) > 360)
                                                            <details style="margin-top: 0.65rem;">
                                                                <summary style="cursor: pointer; list-style: none; font-size: 0.9rem; font-weight: 600; color: #d65f6d;">
                                                                    See more
                                                                </summary>
                                                                <div style="margin-top: 0.7rem; max-height: 210px; overflow-y: auto; padding-right: 0.35rem;">
                                                                    <p style="font-size: 1rem; line-height: 1.7rem; color: rgb(87 83 78);">{{ $promoSlide['summary'] }}</p>
                                                                </div>
                                                            </details>
                                                        @endif
                                                    </div>
                                                @endif
                                                <p style="margin-top: 1.1rem; font-size: 0.76rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.18em; color: rgb(168 162 158);">
                                                    {{ $promoSlide['range_label'] }}
                                                </p>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div style="margin-top: 1.25rem; border-radius: 1.25rem; border: 1px dashed rgb(214 211 209); background: rgb(250 250 249); padding: 2.5rem 1.25rem; text-align: center; font-size: 0.875rem; line-height: 1.5rem; color: rgb(87 83 78);">
                                    No promotion is available yet.
                                </div>
                            @endif
                        </div>

                        <div class="promo-next-wrap" style="position: absolute; right: -1.1rem; top: 50%; z-index: 20; display: flex; align-items: center; justify-content: center; transform: translateY(-50%);">
                            <button
                                id="promo-next-button"
                                type="button"
                                style="display: inline-flex; height: 3.2rem; width: 3.2rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.9); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 2.8rem; font-weight: 300; line-height: 1; color: #8aa0d7; transition: transform 0.2s ease, color 0.2s ease;"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.color='#6e87c9';"
                                onmouseout="this.style.transform='scale(1)'; this.style.color='#8aa0d7';"
                                aria-label="Show next promotion"
                            >&rsaquo;</button>
                        </div>
                    </div>
                </div>
            </section>

            <div
                id="promo-detail-modal"
                style="position: fixed; inset: 0; z-index: 80; display: none; align-items: center; justify-content: center; background: rgba(15,23,42,0.72); padding: 2rem;"
            >
                <div
                    id="promo-detail-panel"
                    style="position: relative; width: min(1120px, 100%); max-height: 90vh; overflow-y: auto; border-radius: 1.5rem; background: #fff; box-shadow: 0 24px 60px rgba(15,23,42,0.24);"
                >
                    <button
                        id="promo-detail-close"
                        type="button"
                        style="position: absolute; right: 1rem; top: 1rem; z-index: 2; display: inline-flex; height: 2.75rem; width: 2.75rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.96); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 1.5rem; color: #52627f; cursor: pointer;"
                        aria-label="Close promotion details"
                    >&times;</button>

                    <div class="promo-detail-layout" style="display: grid; gap: 0; grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);">
                        <div class="promo-detail-media" style="display: flex; min-height: 78vh; align-items: center; justify-content: center; background: #fff; padding: 1.5rem;">
                            <img id="promo-detail-image" src="" alt="" style="display: block; width: 100%; max-height: 72vh; object-fit: contain; background: #fff;">
                        </div>
                        <div style="padding: 1.75rem 1.75rem 2rem;">
                            <p id="promo-detail-status" style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.16em; color: #2f63bc;"></p>
                            <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.18em; color: #d65f6d;">
                                <span id="promo-detail-label"></span>
                                <span id="promo-detail-date" style="color: #d6a24b;"></span>
                            </div>
                            <h3 id="promo-detail-title" style="margin: 1rem 0 0; font-size: 2rem; font-weight: 600; color: rgb(28 25 23);"></h3>
                            <p id="promo-detail-summary" style="margin: 1rem 0 0; font-size: 1rem; line-height: 1.8rem; color: rgb(87 83 78); white-space: pre-line;"></p>
                            <p id="promo-detail-range" style="margin: 1.25rem 0 0; font-size: 0.76rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.18em; color: rgb(255, 255, 255, 1);"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section id="popular-picks" class="home-screen-section relative overflow-hidden px-6 py-8 shadow-[0_18px_40px_rgba(15,23,42,0.10)] md:px-8 md:py-10" >

                <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="hidden md:block md:w-[18rem]"></div>
                    <h2 class="popular-picks-heading flex-1 text-center font-['Oswald'] text-5xl font-bold uppercase tracking-[0.24em] md:text-6xl lg:text-7xl" style="position: relative; left: 9rem;">
                        <span style="color: #ff2b2b;">Popular</span>
                        <span class="ml-3" style="color: #315fbd;">Picks</span>
                    </h2>
                    <div class="flex justify-center md:w-[18rem] md:justify-end">
                        <a href="{{ route('home') }}#packages-showcase" class="inline-flex items-center justify-center rounded-full px-8 py-3.5 text-[0.95rem] font-semibold uppercase tracking-[0.28em] text-white shadow-[0_14px_30px_rgba(49,95,189,0.22)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(49,95,189,0.28)]" style="border: 1px solid #315fbd; background-color: #315fbd;">
                            See All Package
                        </a>
                    </div>
                </div>

            <div class="relative mx-auto rounded-[1.75rem] px-5 py-4  md:px-8 md:py-5" style="max-width: 1920px; background: #ffffff;">

                <div class="mt-6 px-2 py-4">
                    <div class="flex flex-wrap justify-center" style="gap: 2.5rem;">
                        @foreach ($popularPackages as $package)
                            @php
                                $locationTag = strtoupper(str_contains(strtolower($package->location), 'kundasang') ? 'Kundasang' : (str_contains(strtolower($package->location), 'kota belud') ? 'Kota Belud' : (str_contains(strtolower($package->location), 'ranau') ? 'Kundasang-Ranau' : 'Kota Kinabalu')));
                                $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $package->duration));
                            @endphp
                            <div class="flex h-full flex-col items-center">
                                <a href="{{ route('products.show', $package) }}" class="popular-package-card flex h-full flex-col overflow-hidden text-left shadow-[0_14px_26px_rgba(15,23,42,0.08)] transition duration-300" style="width: 350px; min-height: 580px; border-radius: 1.6rem 1.6rem 0 0; background: #f1f0e9; transition: transform 0.25s ease, box-shadow 0.25s ease;" onmouseover="this.style.transform='scale(1.06)'; this.style.boxShadow='0 20px 34px rgba(15,23,42,0.14)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 14px 26px rgba(15,23,42,0.08)'">
                                    <div class="relative overflow-hidden">
                                        @if ($package->image_url)
                                            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="h-52 w-full object-cover">
                                            <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-7 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.65rem, -0.65rem);">
                                        @else
                                            <div class="flex h-52 items-center justify-center bg-[linear-gradient(135deg,_#f59e0b,_#fde68a_45%,_#fed7aa)] px-6 text-center text-xl font-semibold text-stone-800">{{ $package->name }}</div>
                                        @endif
                                        <span style="position: absolute; left: 0.75rem; top: 0.75rem; z-index: 2; border-radius: 0.2rem; background: #2c22c9; padding: 0.28rem 0.55rem; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #fff;">{{ $locationTag }}</span>
                                        <span style="position: absolute; right: 0.75rem; top: 0.75rem; z-index: 2; border-radius: 0.2rem; background: #ff1d0d; padding: 0.28rem 0.55rem; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #fff;">24% OFF</span>
                                    </div>

                                    <div class="flex flex-1 flex-col p-4">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="font-['Oswald'] text-4xl font-bold leading-none" style="color: #ff1d0d;">{{ $tripCode }}</p>
                                            <div class="flex items-center gap-1.5" style="color: #ffd84d;">
                                                @for ($star = 0; $star < 5; $star++)
                                                    <span class="text-2xl leading-none">&#9733;</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <h3 class="mt-3 font-['Oswald'] text-2xl font-bold uppercase leading-tight text-[#1c2f7d]">{{ $package->name }}</h3>
                                        <p class="mt-3 flex-1 text-sm font-medium leading-6 text-stone-900">{{ \Illuminate\Support\Str::limit($package->description, 180) }}</p>
                                        <div class="mt-5 pt-2">
                                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#ff1d0d]">Starting From</p>
                                            <p class="mt-1 text-base text-stone-900">
                                                MYR <span class="currency-price text-2xl font-bold leading-none" data-myr="{{ $package->malaysia_adult_price_myr }}" style="color: #0f4fb5;">{{ number_format((float) $package->malaysia_adult_price_myr, 0) }}</span> Per Pax
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <a href="{{ route('booking.create', ['product_id' => $package->id]) }}" class="mt-3 inline-flex min-w-[160px] items-center justify-center rounded-full px-8 py-3 font-['Oswald'] text-lg font-bold uppercase tracking-[0.08em] text-white shadow-[0_12px_18px_rgba(0,0,0,0.16)] transition hover:-translate-y-0.5 hover:shadow-[0_16px_24px_rgba(0,0,0,0.2)]" style="background-color: #ff1d0d;">
                                    Book Now
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section
    class="home-screen-section"
    id="transport"
    style="position: relative; overflow: hidden; background-image: url('{{ asset('images/transport.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; box-sizing: border-box; margin-top: -1.5rem; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);"
>
    @php
        $transportOptions = [
            [
                'label' => '41/44 SEATERS',
                'name' => '41/44 Seaters Bus',
                'image' => asset('images/44pax.png'),
                'url' => route('booking.create'),
            ],
            [
                'label' => '17 SEATERS',
                'name' => '17 Seaters Van',
                'image' => asset('images/17pax.png'),
                'url' => route('booking.create'),
            ],
            [
                'label' => '9/14 SEATERS',
                'name' => '9/14 Seaters Van',
                'image' => asset('images/14pax.png'),
                'url' => route('booking.create'),
            ],
        ];

        $transportFeatures = [
            ['label' => 'HYGIENE', 'icon' => 'spark'],
            ['label' => 'SAFETY', 'icon' => 'shield'],
            ['label' => 'PROFESIONAL DRIVER', 'icon' => 'driver'],
            ['label' => 'LICENSED VAN/BUS PERSIARAN', 'icon' => 'license'],
        ];
    @endphp

    <div class="transport-shell" style="position: relative; min-height: 100%; width: 100%; padding: 1.5rem 3rem 2.25rem;">

    <div style="display: flex; min-height: 100%; width: 100%; align-items: center; justify-content: flex-start;">

            <!-- LEFT SIDE -->
            <div class="transport-copy" style="position: relative; z-index: 10; width: 100%; max-width: 980px; flex-shrink: 0; margin-left: 6rem;">
                <!-- TRANSPORT BOX -->
                <div class="transport-box" style="border-radius: 1rem; background: rgba(255,255,255,0.85); padding: 2rem 2.5rem; min-height: 390px; box-shadow: 0 14px 30px rgba(15,23,42,0.12); backdrop-filter: blur(4px);">

                    <div style="text-align: center;">
                        <h2 style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 2.55rem; font-weight: 700; text-transform: uppercase; line-height: 1; letter-spacing: 0.16em; color: #2f63bc;">
                            TRANSPORT
                        </h2>

                        <p style="max-width: 28rem; margin: 0.95rem auto 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; line-height: 1.25; letter-spacing: 0.12em; color: #9b4a14;">
                            We offer transport packages at the lowest prices.
                        </p>
                    </div>

                    <div class="transport-grid" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; margin-top: 2.2rem;">
                        @foreach ($transportOptions as $option)
                            <a
                                href="{{ $option['url'] }}"
                                style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; text-align: center; text-decoration: none; color: inherit; transition: transform 0.25s ease;"
                                onmouseover="this.style.transform='translateY(-6px)'"
                                onmouseout="this.style.transform='translateY(0)'"
                            >
                                <div style="display: flex; height: 8.5rem; width: 100%; align-items: flex-end; justify-content: center;">
                                    <img
                                        src="{{ $option['image'] }}"
                                        alt="{{ $option['name'] }}"
                                        style="max-height: 100%; width: auto; max-width: 100%; object-fit: contain; filter: drop-shadow(0 12px 24px rgba(15,23,42,0.24)); transition: transform 0.28s ease, filter 0.28s ease;"
                                        onmouseover="this.style.transform='scale(1.08)'; this.style.filter='drop-shadow(0 18px 30px rgba(15,23,42,0.28))'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.filter='drop-shadow(0 12px 24px rgba(15,23,42,0.24))'"
                                    >
                                </div>

                                <span style="display: inline-flex; align-items: center; justify-content: center; margin-top: 1rem; border-radius: 999px; background: #365fb8; padding: 0.5rem 1.25rem; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #fff; box-shadow: 0 6px 14px rgba(54,95,184,0.3);">
                                    {{ $option['label'] }}
                                </span>

                            </a>
                        @endforeach
                    </div>

                </div>

                <!-- WHY CHOOSE US BOX -->
                <div class="transport-box" style="margin-top: 1rem; border-radius: 1rem; background: rgba(255,255,255,0.85); padding: 1.8rem 2.5rem 2.5rem; min-height: 240px; box-shadow: 0 14px 30px rgba(15,23,42,0.12); backdrop-filter: blur(4px);">

                    <h3 style="margin: 0; text-align: center; font-family: 'Oswald', sans-serif; font-size: 1.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: #9b4a14;">
                        Why Choose Us?
                    </h3>

                    <div class="transport-features" style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.9rem;">
                        @foreach ($transportFeatures as $feature)
                            <div class="transport-feature-item" style="position: relative; display: flex; width: 8.5rem; flex-direction: column; align-items: center; text-align: center;">

                                <div style="display: flex; height: 6.4rem; width: 6.4rem; align-items: center; justify-content: center; border-radius: 999px; border: 2.5px solid #2f63bc; background: #fff; color: #2f63bc; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

                                    @if ($feature['icon'] === 'spark')
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="24" cy="31" r="12"/>
                                            <path d="M21 31h6"/>
                                            <path d="M24 28v6"/>
                                            <path d="M39 20c5 0 9 4 9 9 0 8-9 14-16 20-2-1-4-3-6-5"/>
                                            <path d="m41 18 2 4 4 2-4 2-2 4-2-4-4-2 4-2 2-4Z"/>
                                        </svg>
                                    @elseif ($feature['icon'] === 'shield')
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M32 10 48 16v13c0 12-7 19-16 25-9-6-16-13-16-25V16l16-6Z"/>
                                            <path d="m24 31 6 6 10-12"/>
                                        </svg>
                                    @elseif ($feature['icon'] === 'driver')
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="17" r="7"/>
                                            <path d="M19 50v-8c0-8 6-13 13-13s13 5 13 13v8"/>
                                            <circle cx="32" cy="42" r="8"/>
                                            <path d="M24 42h16"/>
                                            <path d="M32 34v16"/>
                                        </svg>
                                    @else
                                        <svg style="height: 5.3rem; width: 5.3rem;" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="14" y="15" width="28" height="34" rx="4"/>
                                            <path d="M21 24h14"/>
                                            <path d="M21 31h14"/>
                                            <path d="M21 38h8"/>
                                            <rect x="41" y="21" width="10" height="16" rx="2"/>
                                            <path d="m44 30 2 2 4-5"/>
                                        </svg>
                                    @endif

                                </div>

                                <p style="max-width: 8rem; margin-top: 0.95rem; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; line-height: 1.1rem; letter-spacing: 0.1em; color: #9b4a14;">
                                    {{ $feature['label'] }}
                                </p>

                            </div>
                        @endforeach
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

        @php
            $packageSections = [
                [
                    'key' => 'kundasang',
                    'title' => 'KUNDASANG',
                    'summary' => 'Discover Kundasang, a serene highland paradise nestled in the cool hills near Mount Kinabalu, offering breathtaking mountain views, fresh air, and a peaceful escape from the city.',
                    'background' => asset('images/kundasang_bg.png'),
                    'keywords' => ['kundasang', 'kinabalu', 'ranau', 'nabalu', 'desa'],
                ],
                [
                    'key' => 'island',
                    'title' => 'ISLAND HOPPING',
                    'summary' => 'Sabah has around 395 islands, offering everything from easy day trips to world-class diving destinations.',
                    'background' => asset('images/semporna.png'),
                    'keywords' => ['island', 'marine', 'semporna', 'snork', 'div', 'sipadan', 'mabul', 'mataking', 'pom pom', 'bohey'],
                ],
                [
                    'key' => 'kk-beach',
                    'title' => 'KK BEACH',
                    'summary' => 'Kota Kinabalu is home to some of Sabah\'s most scenic beaches, known for their breathtaking sunsets and relaxed coastal lifestyle.',
                    'background' => asset('images/beach.png'),
                    'keywords' => ['kota kinabalu', 'kk ', 'beach', 'tanjung aru', 'city', 'island hopping', 'manukan', 'sapi', 'mamutik'],
                ],
            ];

            $packagePageSize = 3;
        @endphp

        <section id="packages-showcase" style="box-sizing: border-box; margin-top: -4.5rem; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);">
            <div style="display: flex; flex-direction: column; gap: 0;">
                @foreach ($packageSections as $section)
                    @php
                        $sectionPackages = $travelPackages
                            ->filter(function ($package) use ($section) {
                                $haystack = strtolower(trim(($package->name ?? '').' '.($package->location ?? '').' '.($package->description ?? '')));

                                foreach ($section['keywords'] as $keyword) {
                                    if (str_contains($haystack, $keyword)) {
                                        return true;
                                    }
                                }

                                return false;
                            })
                            ->values();

                        $visiblePackages = $sectionPackages->isNotEmpty() ? $sectionPackages : $travelPackages->take($packagePageSize)->values();
                        $pageCount = max(1, $visiblePackages->count() - $packagePageSize + 1);
                    @endphp

                    <article class="package-offer-section home-screen-section" data-package-section="{{ $section['key'] }}" style="position: relative; overflow: hidden; box-shadow: 0 20px 60px rgba(15,23,42,0.18);">
                        <div style="position: absolute; inset: 0; background-image: url('{{ $section['background'] }}'); background-size: cover; background-position: center center;"></div>
                        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(7,29,58,0.22), rgba(7,29,58,0.12));"></div>

                        <div class="package-section-stage" style="position: relative; z-index: 2; padding: 0 3.2rem 2.75rem;">
                            @if ($pageCount > 1)
                                <div class="package-section-prev-wrap" style="position: absolute; left: 7rem; top: 50%; z-index: 20; display: flex; align-items: center; justify-content: center; transform: translateY(-50%);">
                                    <button
                                        type="button"
                                        class="package-section-prev"
                                        data-package-prev="{{ $section['key'] }}"
                                        aria-label="Show previous {{ strtolower($section['title']) }} packages"
                                        style="display: inline-flex; height: 3.2rem; width: 3.2rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.9); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 2.8rem; font-weight: 300; line-height: 1; color: #8aa0d7; transition: transform 0.2s ease, color 0.2s ease;"
                                        onmouseover="this.style.transform='scale(1.05)'; this.style.color='#6e87c9';"
                                        onmouseout="this.style.transform='scale(1)'; this.style.color='#8aa0d7';"
                                    >&lsaquo;</button>
                                </div>

                                <div class="package-section-next-wrap" style="position: absolute; right: 7rem; top: 50%; z-index: 20; display: flex; align-items: center; justify-content: center; transform: translateY(-50%);">
                                    <button
                                        type="button"
                                        class="package-section-next"
                                        data-package-next="{{ $section['key'] }}"
                                        aria-label="Show more {{ strtolower($section['title']) }} packages"
                                        style="display: inline-flex; height: 3.2rem; width: 3.2rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.9); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 2.8rem; font-weight: 300; line-height: 1; color: #8aa0d7; transition: transform 0.2s ease, color 0.2s ease;"
                                        onmouseover="this.style.transform='scale(1.05)'; this.style.color='#6e87c9';"
                                        onmouseout="this.style.transform='scale(1)'; this.style.color='#8aa0d7';"
                                    >&rsaquo;</button>
                                </div>
                            @endif

                            <div style="position: relative; min-height: 4.5rem; margin-top: 0;">
                                <div style="display: flex; justify-content: center;">
                                    <div class="package-section-label" style="min-width: 320px; clip-path: polygon(10% 0, 90% 0, 100% 100%, 0 100%); background: #1f80c4; padding: 0.9rem 2.4rem 1rem; text-align: center; margin-top: 0;">
                                        <span style="font-family: 'Prata', serif; font-size: 1.95rem; color: #fff;">Packages</span>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 0;">
                                <h2 style="font-family: 'Oswald', sans-serif; font-size: 2.5rem; font-weight: 700; line-height: 0.95; letter-spacing: 0.02em; color: #fff;">
                                    {{ $section['title'] }}
                                </h2>
                                <p class="package-section-summary" style="margin-top: 0.45rem; max-width: 70rem; font-size: 1.3rem; line-height: 1.65; color: rgba(255,255,255,0.96);">
                                    {{ $section['summary'] }}
                                </p>
                            </div>

                            @if ($visiblePackages->isNotEmpty())
                                <div class="package-carousel-shell" style="margin: 1.7rem auto 0; max-width: calc((390px * 3) + 5rem); overflow: hidden;">
                                <div class="package-section-grid" data-package-grid="{{ $section['key'] }}" data-package-page-count="{{ $pageCount }}" style="display: flex; gap: 2.5rem; align-items: start; transition: transform 0.45s ease;">
                                    @foreach ($visiblePackages as $package)
                                        @php
                                            $locationTag = strtoupper(str_contains(strtolower($package->location), 'kundasang') ? 'Kundasang' : (str_contains(strtolower($package->location), 'marine') || str_contains(strtolower($package->location), 'island') ? 'Semporna' : 'Kota Kinabalu'));
                                            $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $package->duration));
                                        @endphp
                                        <div class="package-section-card" data-package-card="{{ $section['key'] }}" style="display: flex; width: 390px; min-width: 390px; flex-direction: column; align-items: center;">
                                            <a href="{{ route('products.show', $package) }}" style="display: flex; width: 100%; max-width: 390px; min-height: 520px; flex-direction: column; overflow: hidden; border-radius: 1.6rem 1.6rem 0 0; background: #fff; text-decoration: none; box-shadow: 0 18px 30px rgba(15,23,42,0.22); transition: transform 0.25s ease, box-shadow 0.25s ease;" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 24px 38px rgba(15,23,42,0.28)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 18px 30px rgba(15,23,42,0.22)'">
                                                <div style="position: relative;">
                                                    @if ($package->image_url)
                                                        <img src="{{ $package->image_url }}" alt="{{ $package->name }}" style="display: block; height: 210px; width: 100%; object-fit: cover;">
                                                        <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" style="pointer-events: none; position: absolute; right: 0.75rem; bottom: 0.75rem; height: 1.85rem; width: auto; opacity: 0.9;">
                                                    @else
                                                        <div style="display: flex; height: 230px; align-items: center; justify-content: center; background: linear-gradient(135deg, #60a5fa, #bfdbfe 40%, #fde68a); padding: 1rem; text-align: center; font-size: 1.25rem; font-weight: 700; color: #1e3a8a;">
                                                            {{ $package->name }}
                                                        </div>
                                                    @endif
                                                    <span style="position: absolute; left: 0.7rem; top: 0.7rem; border-radius: 0.2rem; background: #2c22c9; padding: 0.28rem 0.55rem; font-size: 0.56rem; font-weight: 700; text-transform: uppercase; color: #fff;">
                                                        {{ $locationTag }}
                                                    </span>
                                                    <span style="position: absolute; right: 0.7rem; top: 0.7rem; border-radius: 0.2rem; background: #ff1d0d; padding: 0.28rem 0.55rem; font-size: 0.56rem; font-weight: 700; text-transform: uppercase; color: #fff;">
                                                        24% OFF
                                                    </span>
                                                </div>

                                                <div style="display: flex; flex: 1; flex-direction: column; padding: 0.95rem 0.95rem 0.8rem;">
                                                    <p style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; line-height: 1; color: #ff1d0d;">{{ $tripCode }}</p>
                                                    <h3 style="margin-top: 0.35rem; font-family: 'Oswald', sans-serif; font-size: 1.65rem; font-weight: 700; line-height: 1.04; color: #1c2f7d;">
                                                        {{ strtoupper($package->name) }}
                                                    </h3>
                                                    <p style="margin-top: 0.45rem; flex: 1; min-height: 5.8rem; font-size: 0.86rem; line-height: 1.3; color: #111827;">
                                                        {{ \Illuminate\Support\Str::limit($package->description, 170) }}
                                                    </p>
                                                    <div style="margin-top: auto; padding-top: 0.7rem;">
                                                        <p style="margin: 0; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #ff1d0d;">Starting from</p>
                                                        <p style="margin: 0.1rem 0 0; font-size: 1rem; color: #111827;">
                                                            MYR <span class="currency-price" data-myr="{{ $package->malaysia_adult_price_myr }}" style="font-size: 1.5rem; font-weight: 700; color: #0f4fb5;">{{ number_format((float) $package->malaysia_adult_price_myr, 0) }}</span> Per Pax
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>

                                            <a href="{{ route('booking.create', ['product_id' => $package->id]) }}" style="margin-top: 0.65rem; display: inline-flex; min-width: 170px; align-items: center; justify-content: center; border-radius: 999px; background: #ff1d0d; padding: 0.7rem 1.6rem; font-family: 'Oswald', sans-serif; font-size: 1rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #fff; text-decoration: none; box-shadow: 0 12px 18px rgba(0,0,0,0.18);">
                                                Book Now
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                </div>
                            @else
                                <div style="margin-top: 1.7rem; background: rgba(255,255,255,0.88); padding: 1.5rem 1.75rem; color: #1f2937;">
                                    Packages for this destination will show here once they are added.
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="packages" class="hidden w-full rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-amber-700">Packages</span>
                    <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900">Sabah packages for short breaks and nature getaways</h2>
                </div>
                <p class="max-w-2xl text-sm leading-6 text-stone-600">Packages combine planning, logistics, and guided experiences into easier customer decisions.</p>
            </div>
            <div class="mt-6 grid gap-5 md:grid-cols-2">
                @foreach ($travelPackages as $package)
                    <a href="{{ route('products.show', $package) }}" class="block overflow-hidden rounded-3xl border border-stone-200 bg-stone-50 transition hover:-translate-y-1 hover:shadow-lg">
                        @if ($package->image_url)
                            <div class="relative">
                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="h-52 w-full object-cover">
                                <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute bottom-3 right-3 h-7 w-auto opacity-90">
                            </div>
                        @else
                            <div class="flex h-52 items-center justify-center bg-[linear-gradient(135deg,_#fff7ed,_#ecfccb)] px-6 text-center text-xl font-semibold text-stone-700">{{ $package->name }}</div>
                        @endif
                        <div class="p-5">
                            <h3 class="text-2xl font-semibold text-stone-900">{{ $package->name }}</h3>
                            <p class="mt-2 text-sm text-stone-500">{{ $package->location }} Â· {{ $package->duration }}</p>
                            <p class="mt-4 text-sm leading-6 text-stone-600">{{ $package->description }}</p>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-sm text-stone-500">Package rate</span>
                                <span class="text-lg font-semibold text-stone-900 currency-price" data-myr="{{ $package->malaysia_adult_price_myr }}">RM {{ number_format((float) $package->malaysia_adult_price_myr, 2) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section id="testimonials" class="home-screen-section rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="font-['Prata'] text-3xl text-stone-900">Customer reviews for Sabah trips</h2>
                </div>
                <div class="rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">Average rating 4.7/5</div>
            </div>
            <div class="mt-6 space-y-4">
                @foreach ($testimonials as $testimonial)
                    <article class="rounded-3xl border border-stone-200 bg-stone-50 p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <img
                                    src="{{ $testimonial->profile_photo_url }}"
                                    alt="{{ $testimonial->name }}"
                                    class="h-14 w-14 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-white"
                                    style="aspect-ratio: 1 / 1; border-radius: 9999px;"
                                >
                                <div>
                                    <h3 class="text-lg font-semibold text-stone-900">{{ $testimonial->name }}</h3>
                                <p class="text-sm text-stone-500">{{ $testimonial->location }} &middot; {{ $testimonial->trip_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-amber-500">
                                @for ($star = 0; $star < $testimonial->rating; $star++)
                                    <span class="text-lg leading-none">&#9733;</span>
                                @endfor
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-7 text-stone-600">"{{ $testimonial->quote }}"</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="about-us" class="home-section-compact relative overflow-hidden rounded-[2rem] border border-stone-200 bg-[linear-gradient(135deg,_#fffdf9,_#eff6ff_60%,_#ecfeff)] p-5 shadow-sm">
            <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-sky-100/70 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-28 w-28 rounded-full bg-amber-100/70 blur-3xl"></div>
            <div class="relative grid gap-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div class="rounded-[1.75rem] border border-white/70 bg-white/80 p-5 shadow-sm backdrop-blur">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-16 w-16 rounded-full object-cover shadow-sm">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-amber-600">About Us</p>
                            <h2 class="mt-2 font-['Prata'] text-3xl text-stone-900">Universal Eden Holidays</h2>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-stone-600">
                        We help travelers explore Sabah with smoother planning, reliable transport, curated tour packages, and practical booking support from the first enquiry to the final trip detail.
                    </p>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div class="rounded-[1.5rem] border border-white/70 bg-white/85 p-4 shadow-sm">
                        <h3 class="text-lg font-semibold text-stone-900">What we do</h3>
                        <p class="mt-2 text-sm leading-6 text-stone-600">We organize transport services, holiday packages, and customer assistance so visitors can book Sabah experiences in one clear place.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/70 bg-white/85 p-4 shadow-sm">
                        <h3 class="text-lg font-semibold text-stone-900">Why people choose us</h3>
                        <p class="mt-2 text-sm leading-6 text-stone-600">Travelers come to us for local knowledge, simpler planning, flexible options, and a more personal holiday experience across Sabah.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/70 bg-white/85 p-4 shadow-sm">
                        <h3 class="text-lg font-semibold text-stone-900">Our focus</h3>
                        <p class="mt-2 text-sm leading-6 text-stone-600">We aim to make every journey feel organized, comfortable, and memorable, whether it is an airport transfer, a family package, or a full sightseeing trip.</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/70 bg-white/85 p-4 shadow-sm">
                        <h3 class="text-lg font-semibold text-stone-900">Travel with confidence</h3>
                        <p class="mt-2 text-sm leading-6 text-stone-600">Our team is built around helpful support, straightforward booking, and dependable service for guests discovering Sabah and beyond.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-stone-200/80 bg-stone-950 text-stone-200">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 lg:grid-cols-[1.2fr_0.8fr_0.8fr_1fr] lg:px-10">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10">
                    <div>
                        <p class="font-['Prata'] text-xl text-white">Universal Eden Holidays</p>
                        <p class="text-xs uppercase tracking-[0.28em] text-stone-400">Sabah Tours and Transport</p>
                    </div>
                </div>
                <p class="mt-5 max-w-md text-sm leading-7 text-stone-400">
                    Travel planning for Sabah made easier with transport services, holiday packages, and practical booking support in one place.
                </p>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Explore</p>
                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-400">
                    <a href="{{ route('home') }}#promos" class="transition hover:text-white">Promos</a>
                    <a href="{{ route('home') }}#transport" class="transition hover:text-white">Transport</a>
                    <a href="{{ route('home') }}#packages-showcase" class="transition hover:text-white">Packages</a>
                    <a href="{{ route('home') }}#testimonials" class="transition hover:text-white">Testimonials</a>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Company</p>
                <div class="mt-5 flex flex-col gap-3 text-sm text-stone-400">
                    <a href="{{ route('home') }}#about-us" class="transition hover:text-white">About Us</a>
                    <a href="{{ route('home') }}#popular-picks" class="transition hover:text-white">Popular Picks</a>
                    @auth
                        <a href="{{ route('profile.show') }}" class="transition hover:text-white">My Profile</a>
                    @else
                        <a href="{{ route('login') }}" class="transition hover:text-white">Login</a>
                    @endauth
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Contact</p>
                <div class="mt-5 space-y-4 text-sm text-stone-400">
                    <p>Email: <a href="mailto:info@universaledenholiday.com" class="transition hover:text-white">info@universaledenholiday.com</a></p>
                    <p>Phone: <a href="tel:+6088212345" class="transition hover:text-white">+60 88 212 345</a></p>
                    <p>Kota Kinabalu, Sabah, Malaysia</p>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col gap-3 px-6 py-5 text-xs uppercase tracking-[0.22em] text-stone-500 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <p>&copy; {{ now()->year }} Universal Eden Holidays. All rights reserved.</p>
                <p>Sabah travel experiences, packages, and transport services.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.documentElement;
            const header = document.querySelector('.js-app-header');

            const updateHeaderOffset = () => {
                root.style.setProperty('--home-header-offset', `${header?.offsetHeight ?? 0}px`);
            };

            updateHeaderOffset();
            window.addEventListener('resize', updateHeaderOffset);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const discoverHeading = document.getElementById('discover-heading');
            const discoverSubheading = document.getElementById('discover-subheading');

            if (!discoverHeading || !discoverSubheading) {
                return;
            }

            const matchDiscoverWidth = () => {
                if (window.innerWidth <= 767) {
                    discoverHeading.style.transform = 'scaleX(1)';
                    return;
                }

                discoverHeading.style.transform = 'scaleX(1)';

                const targetWidth = discoverSubheading.getBoundingClientRect().width;
                const headingWidth = discoverHeading.getBoundingClientRect().width;

                if (!targetWidth || !headingWidth) {
                    return;
                }

                discoverHeading.style.transform = `scaleX(${targetWidth / headingWidth})`;
            };

            matchDiscoverWidth();
            window.addEventListener('resize', matchDiscoverWidth);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slideshow = document.getElementById('sabah-slideshow');

            if (!slideshow) {
                return;
            }

            const slides = Array.from(slideshow.querySelectorAll('.sabah-slide'));
            const cards = Array.from(slideshow.querySelectorAll('.sabah-slide-card'));
            const dots = Array.from(slideshow.querySelectorAll('.sabah-slide-dot'));
            const prevButton = slideshow.querySelector('.sabah-slide-prev');
            const nextButton = slideshow.querySelector('.sabah-slide-next');

            if (!slides.length) {
                return;
            }

            let activeIndex = 0;
            let autoplayId;

            const renderSlides = (index) => {
                activeIndex = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    const isActive = slideIndex === activeIndex;
                    const slideImage = slide.querySelector('.sabah-slide-image');
                    const slideContent = slide.querySelector('.sabah-slide-content');

                    slide.classList.toggle('z-10', isActive);
                    slide.classList.toggle('z-0', !isActive);
                    slide.classList.toggle('opacity-100', isActive);
                    slide.classList.toggle('opacity-0', !isActive);
                    slide.classList.toggle('pointer-events-none', !isActive);

                    slideImage?.classList.toggle('scale-100', isActive);
                    slideImage?.classList.toggle('scale-105', !isActive);

                    slideContent?.classList.toggle('translate-y-0', isActive);
                    slideContent?.classList.toggle('opacity-100', isActive);
                    slideContent?.classList.toggle('translate-y-6', !isActive);
                    slideContent?.classList.toggle('opacity-0', !isActive);
                });

                cards.forEach((card, cardIndex) => {
                    const isActive = cardIndex === activeIndex;
                    card.classList.toggle('ring-2', isActive);
                    card.classList.toggle('ring-sky-100', isActive);
                    card.classList.toggle('border-sky-100', isActive);
                    card.classList.toggle('bg-sky-50/55', isActive);
                    card.classList.toggle('border-white/80', !isActive);
                    card.classList.toggle('bg-white/85', !isActive);
                });

                dots.forEach((dot, dotIndex) => {
                    const isActive = dotIndex === activeIndex;
                    dot.classList.toggle('w-10', isActive);
                    dot.classList.toggle('bg-sky-500', isActive);
                    dot.classList.toggle('w-2.5', !isActive);
                    dot.classList.toggle('bg-stone-300', !isActive);
                });
            };

            const startAutoplay = () => {
                window.clearInterval(autoplayId);
                autoplayId = window.setInterval(() => {
                    renderSlides(activeIndex + 1);
                }, 5000);
            };

            prevButton?.addEventListener('click', () => {
                renderSlides(activeIndex - 1);
                startAutoplay();
            });

            nextButton?.addEventListener('click', () => {
                renderSlides(activeIndex + 1);
                startAutoplay();
            });

            cards.forEach((card, index) => {
                card.addEventListener('click', () => {
                    renderSlides(index);
                    startAutoplay();
                });
            });

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    renderSlides(index);
                    startAutoplay();
                });
            });

            renderSlides(0);
            startAutoplay();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const promoCarousel = document.getElementById('promo-carousel');
            const prevButton = document.getElementById('promo-prev-button');
            const nextButton = document.getElementById('promo-next-button');

            if (!promoCarousel || !prevButton || !nextButton) {
                return;
            }

            const promoItems = Array.from(promoCarousel.querySelectorAll('.promo-slide-item'));
            let isAnimating = false;
            let activePromoIndex = 0;

            const renderPromo = (index) => {
                activePromoIndex = (index + promoItems.length) % promoItems.length;

                promoItems.forEach((item, itemIndex) => {
                    const isActive = itemIndex === activePromoIndex;
                    item.style.opacity = isActive ? '1' : '0';
                    item.style.transform = isActive ? 'translateX(0)' : 'translateX(70px)';
                    item.style.pointerEvents = isActive ? 'auto' : 'none';
                });
            };

            const animateToPromo = (index, direction = 1) => {
                if (!promoItems.length || isAnimating) {
                    return;
                }

                const nextIndex = (index + promoItems.length) % promoItems.length;

                if (nextIndex === activePromoIndex) {
                    return;
                }

                isAnimating = true;

                const currentItem = promoItems[activePromoIndex];
                const nextItem = promoItems[nextIndex];

                nextItem.style.transition = 'none';
                nextItem.style.opacity = '0';
                nextItem.style.transform = 'translateX(70px)';
                nextItem.style.pointerEvents = 'none';

                requestAnimationFrame(() => {
                    nextItem.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
                    currentItem.style.transition = 'transform 0.4s ease, opacity 0.4s ease';

                    currentItem.style.opacity = '0';
                    currentItem.style.transform = direction > 0 ? 'translateX(-70px)' : 'translateX(70px)';
                    currentItem.style.pointerEvents = 'none';

                    nextItem.style.opacity = '1';
                    nextItem.style.transform = 'translateX(0)';
                    nextItem.style.pointerEvents = 'auto';

                    window.setTimeout(() => {
                        promoItems.forEach((item, itemIndex) => {
                            if (itemIndex !== nextIndex) {
                                item.style.opacity = '0';
                                item.style.transform = 'translateX(70px)';
                                item.style.pointerEvents = 'none';
                            }
                        });

                        activePromoIndex = nextIndex;
                        isAnimating = false;
                    }, 420);
                });
            };

            if (promoItems.length <= 1) {
                renderPromo(0);
                return;
            }

            nextButton.addEventListener('click', () => {
                if (promoItems.length <= 1) {
                    return;
                }

                animateToPromo(activePromoIndex + 1, 1);
            });

            prevButton.addEventListener('click', () => {
                if (promoItems.length <= 1) {
                    return;
                }

                animateToPromo(activePromoIndex - 1, -1);
            });

            renderPromo(0);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const packageSections = Array.from(document.querySelectorAll('[data-package-grid]'));

            if (!packageSections.length) {
                return;
            }

            packageSections.forEach((section) => {
                const sectionKey = section.dataset.packageGrid;
                const pageCount = Number(section.dataset.packagePageCount || '1');
                const cards = Array.from(section.querySelectorAll(`[data-package-card="${sectionKey}"]`));
                const prevButton = document.querySelector(`[data-package-prev="${sectionKey}"]`);
                const nextButton = document.querySelector(`[data-package-next="${sectionKey}"]`);

                if (!cards.length || pageCount <= 1 || !prevButton || !nextButton) {
                    return;
                }

                let activeIndex = 0;

                const updateButtons = () => {
                    const isAtStart = activeIndex <= 0;
                    const isAtEnd = activeIndex >= pageCount - 1;

                    prevButton.disabled = isAtStart;
                    nextButton.disabled = isAtEnd;
                    prevButton.style.opacity = isAtStart ? '0.45' : '1';
                    nextButton.style.opacity = isAtEnd ? '0.45' : '1';
                    prevButton.style.cursor = isAtStart ? 'not-allowed' : 'pointer';
                    nextButton.style.cursor = isAtEnd ? 'not-allowed' : 'pointer';
                };

                const renderSlide = (nextIndex) => {
                    activeIndex = Math.max(0, Math.min(nextIndex, pageCount - 1));

                    const gap = Number.parseFloat(window.getComputedStyle(section).columnGap || window.getComputedStyle(section).gap || '0');
                    const cardWidth = cards[0].getBoundingClientRect().width;
                    const offset = activeIndex * (cardWidth + gap);

                    section.style.transform = `translateX(-${offset}px)`;
                    updateButtons();
                };

                prevButton.addEventListener('click', () => {
                    if (activeIndex <= 0) {
                        return;
                    }

                    renderSlide(activeIndex - 1);
                });

                nextButton.addEventListener('click', () => {
                    if (activeIndex >= pageCount - 1) {
                        return;
                    }

                    renderSlide(activeIndex + 1);
                });

                window.addEventListener('resize', () => {
                    renderSlide(activeIndex);
                });

                renderSlide(0);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('promo-detail-modal');
            const panel = document.getElementById('promo-detail-panel');
            const closeButton = document.getElementById('promo-detail-close');
            const triggers = Array.from(document.querySelectorAll('.promo-poster-trigger'));

            if (!modal || !panel || !closeButton || !triggers.length) {
                return;
            }

            const image = document.getElementById('promo-detail-image');
            const status = document.getElementById('promo-detail-status');
            const label = document.getElementById('promo-detail-label');
            const date = document.getElementById('promo-detail-date');
            const title = document.getElementById('promo-detail-title');
            const summary = document.getElementById('promo-detail-summary');
            const range = document.getElementById('promo-detail-range');

            const closeModal = () => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            };

            const openModal = (trigger) => {
                image.src = trigger.dataset.promoPoster ?? '';
                image.alt = trigger.dataset.promoTitle ?? 'Promotion poster';
                status.textContent = trigger.dataset.promoStatus ?? '';
                label.textContent = trigger.dataset.promoLabel ?? '';
                date.textContent = trigger.dataset.promoDate ?? '';
                title.textContent = trigger.dataset.promoTitle ?? '';
                summary.textContent = trigger.dataset.promoSummary ?? '';
                range.textContent = trigger.dataset.promoRange ?? '';

                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => openModal(trigger));
            });

            closeButton.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.style.display === 'flex') {
                    closeModal();
                }
            });
        });
    </script>
    </div>
</x-layouts.app>


