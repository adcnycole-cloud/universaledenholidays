<x-layouts.app title="Universal Eden Holidays | Sabah Packages and Transport">
    <div class="flex min-h-[calc(100vh-var(--app-header-offset,0px))] flex-col" style="background-color: #f0f0e9;">
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
            height: 89svh;
            min-height: 89svh;
        }

        .home-screen-section--hero > div[style*="min-height"] {
            min-height: 80svh !important;
        }

        .hero-content-shell {
            padding-left: clamp(1.25rem, 4vw, 3.25rem) !important;
            transform: translateY(0rem);
        }

        .hero-copy {
            margin-left: 0;
            margin-top: clamp(5.5rem, 8.9vw, 8.6rem);
        }

        .hero-slider {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .hero-slider-slide {
            position: absolute;
            inset: 0;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            filter: brightness(1.05) saturate(1.08) contrast(1.02);
            opacity: 0;
            transform: scale(1.02);
            transition: opacity 0.9s ease;
        }

        .hero-slider-slide.is-active {
            opacity: 1;
        }

        .hero-slider-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(0,0,0,0.22) 0%, rgba(0,0,0,0.12) 35%, rgba(0,0,0,0.08) 100%),
                linear-gradient(180deg, rgba(0,0,0,0.04) 0%, rgba(0,0,0,0.12) 100%);
        }

        .hero-slider-controls {
            position: absolute;
            right: clamp(1rem, 4vw, 2rem);
            bottom: clamp(1rem, 4vw, 2rem);
            z-index: 20;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .hero-slider-arrow {
            display: inline-flex;
            height: 2.8rem;
            width: 2.8rem;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.48);
            border-radius: 999px;
            background: rgba(15,23,42,0.26);
            color: #ffffff;
            font-size: 1.35rem;
            line-height: 1;
            backdrop-filter: blur(8px);
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .hero-slider-arrow:hover {
            background: rgba(15,23,42,0.42);
            transform: translateY(-1px);
        }

        .hero-slider-dots {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .hero-slider-dot {
            height: 0.72rem;
            width: 0.72rem;
            border: 1px solid rgba(255,255,255,0.55);
            border-radius: 999px;
            background: rgba(255,255,255,0.38);
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .hero-slider-dot.is-active {
            background: #ffffff;
            transform: scale(1.18);
        }

        .hero-copy-stack {
            max-width: min(100%, 90rem);
        }

        .hero-bus {
            height: clamp(2.9rem, 4.3vw, 4rem) !important;
        }

        #discover-heading {
            max-width: min(100%, 25rem) !important;
            font-size: clamp(1.82rem, 3.3vw, 3.15rem) !important;
        }

        #discover-subheading {
            max-width: min(100%, 27rem) !important;
            margin-left: clamp(1rem, 4.1vw, 3.7rem) !important;
            font-size: clamp(1.98rem, 3.62vw, 3.45rem) !important;
        }

        .hero-tagline {
            width: min(100%, 33rem) !important;
            min-width: 0 !important;
            margin-left: clamp(0rem, 3.15vw, 2.35rem) !important;
        }

        .hero-tagline-inner {
            padding: clamp(0.28rem, 0.68vw, 0.42rem) clamp(1rem, 2.35vw, 1.95rem) clamp(0.3rem, 0.72vw, 0.44rem) !important;
        }

        .hero-tagline-text {
            font-size: clamp(0.96rem, 1.55vw, 1.36rem) !important;
        }

        .package-offer-section.home-screen-section {
            overflow: hidden;
        }

        .package-section-card {
            transition: transform 0.28s ease;
            transform-origin: center center;
        }

        .package-section-card:hover {
            transform: scale(1.04);
        }

        .package-showcase-card {
            transition: box-shadow 0.28s ease, transform 0.28s ease;
            transform-origin: center center;
        }

        .package-card-copy {
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        .package-section-card:hover .package-showcase-card {
            box-shadow: 0 24px 40px rgba(15, 23, 42, 0.16) !important;
        }

        .package-card-image {
            transition: transform 0.35s ease;
            transform-origin: center center;
        }

        .package-section-card:hover .package-card-image {
            transform: scale(1.04);
        }

        .package-section-card .package-book-button {
            transition: transform 0.28s ease, box-shadow 0.28s ease, background-color 0.28s ease;
        }

        .package-section-card:hover .package-book-button {
            box-shadow: 0 16px 26px rgba(0, 0, 0, 0.2) !important;
        }

        .popular-package-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .popular-picks-track {
            scrollbar-width: none;
        }

        .popular-picks-track::-webkit-scrollbar {
            display: none;
        }

        .popular-package-image {
            transition: transform 0.28s ease;
        }

        .popular-package-button {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .popular-package-shell:hover .popular-package-card {
            box-shadow: 0 20px 34px rgba(15, 23, 42, 0.14) !important;
        }

        .popular-package-shell:hover .popular-package-image {
            transform: scale(1.06);
        }

        .popular-package-shell:hover .popular-package-button {
            box-shadow: 0 8px 14px rgba(0, 0, 0, 0.12) !important;
            transform: translateY(-0.05rem);
        }

        .reviews-carousel-shell {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(186, 230, 253, 0.9);
            border-radius: 1.75rem;
            background: linear-gradient(180deg, rgba(240, 249, 255, 0.92), rgba(255, 255, 255, 0.96));
            padding: 0.7rem 0 0;
        }

        .reviews-carousel-shell::before,
        .reviews-carousel-shell::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: clamp(2rem, 6vw, 5rem);
            z-index: 2;
            pointer-events: none;
        }

        .reviews-carousel-shell::before {
            left: 0;
            background: linear-gradient(90deg, rgba(248, 250, 252, 0.98), rgba(248, 250, 252, 0));
        }

        .reviews-carousel-shell::after {
            right: 0;
            background: linear-gradient(270deg, rgba(248, 250, 252, 0.98), rgba(248, 250, 252, 0));
        }

        .reviews-carousel-track {
            display: flex;
            width: max-content;
            align-items: stretch;
            gap: 1rem;
            padding: 0 1rem;
            animation: reviews-carousel-scroll 34s linear infinite;
        }

        .reviews-carousel-shell:hover .reviews-carousel-track {
            animation-play-state: paused;
        }

        .reviews-carousel-slide {
            flex: 0 0 min(24rem, calc(100vw - 4.5rem));
        }

        .collapsible-copy {
            overflow: hidden;
            transition: max-height 0.2s ease;
        }

        .collapsible-toggle {
            margin-top: 0.55rem;
            display: inline-flex;
            align-items: center;
            border: 0;
            background: transparent;
            padding: 0;
            font-size: 0.82rem;
            font-weight: 700;
            color: #315fbd;
            cursor: pointer;
        }

        .collapsible-toggle:hover {
            color: #264a94;
        }

        .promo-poster-trigger {
            display: block;
            width: 100%;
            border: 0;
            background: transparent;
            padding: 0;
            text-align: left;
            cursor: pointer;
        }

        .promo-poster-modal[hidden] {
            display: none !important;
        }

        @keyframes reviews-carousel-scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(calc(-50% - 0.5rem));
            }
        }

        @media (min-width: 1024px) {
            #home-footer-grid {
                grid-template-columns: var(--footer-grid-columns-lg, 1fr);
            }
        }

        #promos,
        #testimonials,
        #about-us {
            scroll-margin-top: calc(var(--home-header-offset, 0px) + 1rem + 10px);
        }

        #transport {
            scroll-margin-top: calc(var(--home-header-offset, 0px) + 2.5rem + 10px);
        }

        #packages-showcase {
            scroll-margin-top: calc(var(--home-header-offset, 0px) + 5.5rem + 10px);
        }

        #popular-picks {
            scroll-margin-top: calc(var(--home-header-offset, 0px) + 3.5rem + 10px);
        }

        @media (max-width: 1365px) {
            .hero-content-shell {
                padding: 3rem 1.75rem 3rem clamp(1.25rem, 4vw, 2.5rem) !important;
                transform: translateY(0rem);
            }

            .hero-copy {
                margin-left: 0;
                margin-top: clamp(4.5rem, 7.3vw, 6.2rem);
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
                transform: translateY(0rem);
            }

            .hero-copy {
                margin-left: 0 !important;
                margin-top: clamp(3.9rem, 6.2vw, 5.2rem) !important;
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
            .home-screen-section.home-screen-section--hero,
            section.home-screen-section--hero {
                height: 14rem !important;
                min-height: 14rem !important;
                max-height: 14rem !important;
            }

            .hero-slider-slide {
                background-position: center top !important;
            }

            .home-screen-section--hero > div[style*="min-height"] {
                min-height: 14rem !important;
                height: 14rem !important;
                max-height: 14rem !important;
            }

            .hero-content-shell {
                min-height: 14rem !important;
                height: 14rem !important;
                max-height: 14rem !important;
                padding: 0.35rem 0.7rem 0.6rem 0.7rem !important;
                justify-content: flex-end !important;
                transform: translateY(-1.9rem) !important;
            }

            [data-mobile-hero-content] > div {
                gap: 0.5rem !important;
            }

            .hero-copy {
                margin-left: 0 !important;
                margin-top: 0 !important;
                width: 100%;
                gap: 0.25rem !important;
            }

            .hero-copy-stack {
                max-width: 100% !important;
            }

            .hero-plane {
                width: 54px !important;
            }

            .hero-bus {
                height: 1.55rem !important;
                margin-top: 0.7rem !important;
            }

            #discover-heading {
                max-width: 100% !important;
                font-size: clamp(1.15rem, 6.9vw, 1.7rem) !important;
                line-height: 0.92 !important;
                white-space: normal !important;
            }

            #discover-subheading {
                max-width: 100% !important;
                margin-left: 0 !important;
                text-align: left !important;
                font-size: clamp(1.3rem, 7.5vw, 1.95rem) !important;
                line-height: 0.95 !important;
            }

            .hero-tagline {
                width: min(100%, 16rem) !important;
                min-width: 0 !important;
                max-width: 16rem !important;
                margin-left: 0 !important;
                margin-top: 0.45rem !important;
            }

            .hero-tagline-inner {
                padding: 0.24rem 0.55rem 0.28rem !important;
            }

            .hero-tagline-text {
                font-size: clamp(0.6rem, 3.1vw, 0.78rem) !important;
                letter-spacing: 0.01em !important;
                transform: none !important;
            }

            .popular-picks-heading {
                left: 0 !important;
                font-size: 2.5rem !important;
                line-height: 1.05 !important;
            }

            .popular-package-card {
                width: min(100%, 390px) !important;
                min-height: 31rem !important;
            }

            .package-showcase-card {
                min-height: 35rem !important;
            }

            .popular-picks-mobile-nav {
                display: flex !important;
            }

            .popular-picks-track {
                flex-wrap: nowrap !important;
                justify-content: flex-start !important;
                gap: 0 !important;
                transition: transform 0.35s ease !important;
            }

            .popular-picks-track .popular-package-shell {
                width: 100% !important;
                min-width: 100% !important;
                flex: 0 0 100% !important;
            }

            .transport-shell {
                padding: 0.85rem 1rem 1.1rem !important;
            }

            .transport-copy {
                margin-left: 0 !important;
                max-width: 100% !important;
                padding: 0 0.15rem !important;
            }

            .transport-box {
                padding: 1rem 0.9rem !important;
                min-height: 0 !important;
            }

            .transport-grid {
                grid-template-columns: 1fr !important;
                gap: 0.7rem !important;
            }

            .transport-features {
                flex-wrap: wrap !important;
                gap: 0.8rem !important;
            }

            .transport-feature-item {
                width: calc(50% - 0.75rem) !important;
                min-width: 0 !important;
            }

            #transport {
                min-height: auto !important;
                padding-bottom: 0 !important;
            }

            #transport > div[style*="background-image"] {
                background-position: center top !important;
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
                overflow: visible !important;
            }

            .package-section-card {
                width: min(100%, 320px) !important;
                min-width: min(100%, 320px) !important;
                align-self: stretch !important;
            }

            .package-section-card > a:first-child {
                min-height: 38rem !important;
                height: 38rem !important;
            }

            .package-card-copy {
                padding: 1rem 1rem 0.9rem !important;
            }

            .package-card-title {
                min-height: 4.15rem !important;
            }

            .package-card-description {
                min-height: 7.25rem !important;
            }

            .transport-copy > div:first-child h2 {
                font-size: 2rem !important;
                line-height: 0.95 !important;
            }

            .transport-copy > div:first-child p {
                margin-top: 0.7rem !important;
                font-size: 0.88rem !important;
                line-height: 1.45 !important;
            }

        }

        @media (max-width: 1280px) {
            .transport-shell {
                padding-bottom: 2rem !important;
            }
        }
    </style>

    @php($heroSlides = collect($heroSlides ?? [])->values())
    @php($currentPromoSlide = $currentPromoSlide ?? null)
    @php($recentPromoSlides = collect($recentPromoSlides ?? []))
    @php($latestBlogPosts = collect($latestBlogPosts ?? []))
    @php($promoSlides = collect($currentPromoSlide ? [$currentPromoSlide] : [])->merge($recentPromoSlides)->values())
    <section class="home-screen-section home-screen-section--hero relative w-full overflow-hidden bg-black" data-mobile-hero-section>
        <div class="absolute inset-0">
            <div class="hero-slider" data-hero-slider>
                @foreach ($heroSlides as $index => $heroSlide)
                    <div
                        class="hero-slider-slide{{ $index === 0 ? ' is-active' : '' }}"
                        data-hero-slide
                        style="background-image: url('{{ $heroSlide['image_url'] }}');"
                    ></div>
                @endforeach
                <div class="hero-slider-overlay"></div>
            </div>
            <img
                src="{{ asset('images/plane.png') }}"
                alt="Plane"
                class="hero-plane pointer-events-none absolute right-0 top-0 z-10"
                style="width: 200px;"
            >
        </div>
        <div class="hero-content-shell" data-mobile-hero-content style="position: relative; margin: 0 auto; display: flex; min-height: 76svh; max-width: 92rem; flex-direction: column; justify-content: center; padding: 2.25rem 2rem 2.25rem 1rem;">
            <div style="display:flex; width:100%; align-items:center; gap:2.5rem;">
                <div class="hero-copy" style="display:flex; min-width:0; flex:1 1 0%; flex-direction:column; align-items:flex-start; gap:0.75rem; text-align:left;">
                    <img class="hero-bus" src="{{ asset('images/bus.png') }}" alt="Bus" style="width: auto; margin-top:6.15rem;">
                    <div class="hero-copy-stack" style="display:flex; width:100%; flex-direction:column; align-items:flex-start; gap:0; text-align:left;">
                        <h2 id="discover-heading" style="display: block; width: 100%; max-width: 34rem; font-family: 'Vendura', sans-serif; font-size: clamp(2.3rem, 4.2vw, 4rem); font-weight: 600; line-height: 0.9; letter-spacing: 0.01em; text-transform: uppercase; color: #ffffff; transform: scaleX(0.76); transform-origin: left center; white-space: nowrap;">TRAVEL AND RIDE</h2>
                        <h2 id="discover-subheading" style="display: block; width: 100%; max-width: 40rem; margin-left: 6.2rem; text-align: center; font-family: 'Vendura', sans-serif; font-size: clamp(2.45rem, 4.8vw, 4.5rem); font-weight: 700; color: #ffffff;">WITH US</h2>
                        <div class="hero-tagline" style="margin-top: 1.15rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; padding: 0.22rem; background: rgba(0,0,0,0.42); box-shadow: 0 14px 28px rgba(15,23,42,0.18);">
                            <div class="hero-tagline-inner" style="width: 100%; border-radius: 999px; border: 2px solid rgba(255,255,255,0.38); padding: 0.22rem 1.9rem 0.3rem; background: rgba(0,0,0,0.12);">
                                <span class="hero-tagline-text" style="display: block; width: 100%; text-align: center; font-family: 'Oswald', sans-serif; font-size: clamp(1.12rem, 1.9vw, 1.72rem); font-weight: 700; line-height: 1; letter-spacing: 0.05em; color: #ffffff; text-transform: none; transform: scaleX(1.04); transform-origin: center;">Discover All Of Sabah Borneo</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @if ($heroSlides->count() > 1)
            <div class="hero-slider-controls" data-hero-slider-controls>
                <div class="hero-slider-dots">
                    @foreach ($heroSlides as $index => $heroSlide)
                        <button
                            type="button"
                            class="hero-slider-dot{{ $index === 0 ? ' is-active' : '' }}"
                            data-hero-dot
                            data-hero-slide-index="{{ $index }}"
                            aria-label="Show homepage image {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

        <div id="promo-poster-modal" class="promo-poster-modal fixed inset-0 z-[9999] px-4 md:px-8" style="z-index: 2147483647; isolation: isolate; background: #dbeafe;" hidden>
            <div class="flex min-h-full items-center justify-center">
                <div id="promo-poster-modal-panel" class="relative overflow-hidden rounded-[1.8rem] shadow-[0_24px_60px_rgba(37,99,235,0.18)]" style="width: 78rem; max-width: calc(100vw - 2.5rem); min-height: 26rem; margin-top: 4rem; z-index: 2147483647; background: #eff6ff;">
                    <button type="button" data-promo-modal-close class="absolute right-4 top-4 z-20 inline-flex h-8 w-8 items-center justify-center rounded-full border border-blue-100 bg-[#eff6ff] text-[1.2rem] font-semibold leading-none text-slate-800 shadow-[0_8px_18px_rgba(37,99,235,0.16)] transition hover:bg-[#dbeafe]" aria-label="Close promo viewer">
                        &times;
                    </button>
                    <div class="flex flex-row flex-nowrap items-stretch">
                        <div class="flex w-[40rem] min-w-[40rem] items-center justify-center bg-[#f7efe3] p-6">
                            <img id="promo-poster-modal-image" src="" alt="" class="h-auto w-auto object-contain" style="max-width: 41rem; max-height: 35rem; background: #ffffff;">
                        </div>
                        <div id="promo-poster-modal-copy-column" class="flex min-w-0 flex-1 flex-col justify-center px-16 py-10 md:px-20" style="min-width: 0; width: 100%;">
                            <div id="promo-poster-modal-copy-inner" class="w-full max-w-[30rem]">
                                <p id="promo-poster-modal-date" class="text-sm font-bold uppercase tracking-[0.22em] text-amber-600"></p>
                                <h3 id="promo-poster-modal-title" class="mt-4 font-['Prata'] text-3xl leading-tight text-stone-900 md:text-4xl"></h3>
                                <p id="promo-poster-modal-summary" class="mt-6 text-base leading-8 text-stone-700 md:text-[1.02rem]"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php($initialPopularPackages = $popularPackages->take(3)->values())
        @php($initialPopularPackageIds = $initialPopularPackages->pluck('id'))
        @php($popularPackagesGrid = $initialPopularPackages
            ->concat($travelPackages->reject(fn ($package) => $initialPopularPackageIds->contains($package->id))->values())
            ->values())
        <section id="popular-picks" class="home-screen-section px-4 pb-6 pt-8 md:px-6 md:pb-8 md:pt-12" style="background: #ffffff;">
            <div class="mx-auto px-5 py-5 md:px-8 md:py-6" style="max-width: 1680px;">
                <div class="mx-auto px-4 pb-10 pt-2 text-center md:px-8 md:pb-14 md:pt-2" style="max-width: 90rem;">
                    <h2 class="mt-0 font-['Oswald'] font-bold text-stone-950" style="font-size: clamp(1.2rem, 2.8vw, 2.35rem); line-height: 1;">
                        Let's Explore Your Dream Destination Here!
                    </h2>

                    <div class="mx-auto mt-3 rounded-[1.7rem] bg-white px-5 py-5 shadow-[0_24px_54px_rgba(15,23,42,0.10)] md:px-7" style="max-width: 72rem;">
                        <div class="flex flex-nowrap items-end gap-3 overflow-x-auto pb-2" data-popular-filters>
                            <label class="flex min-w-[18rem] flex-1 items-center gap-3 text-left">
                                <span class="shrink-0 font-['Oswald'] text-[1.05rem] font-semibold text-stone-900">Location</span>
                                <input type="text" data-popular-filter="location" placeholder="Search location" class="w-full rounded-[0.9rem] border border-stone-200 bg-stone-50 px-4 py-2 text-sm text-stone-700 outline-none transition focus:border-orange-300 focus:bg-white" />
                            </label>
                            <label class="flex min-w-[18rem] items-center gap-3 text-left">
                                <span class="shrink-0 font-['Oswald'] text-[1.05rem] font-semibold text-stone-900">Duration</span>
                                <select data-popular-filter="duration" class="w-full rounded-[0.9rem] border border-stone-200 bg-stone-50 px-4 py-2 text-sm text-stone-700 outline-none transition focus:border-orange-300 focus:bg-white">
                                    <option value="">All</option>
                                    <option value="day-trip">Day Trip</option>
                                    <option value="2d1n">2D1N Trip</option>
                                    <option value="3d2n">3D2N Trip</option>
                                    <option value="4d3n">4D3N Trip</option>
                                </select>
                            </label>
                            <button type="button" data-popular-filter-reset class="inline-flex min-w-[9rem] items-center justify-center rounded-[0.9rem] bg-[#f97316] px-7 py-2 text-sm font-semibold text-white shadow-[0_18px_35px_rgba(249,115,22,0.32)] transition hover:bg-[#ea6b10]">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="mt-7 grid gap-6 xl:grid-cols-3" data-popular-grid>
                        <?php if ($popularPackagesGrid->isNotEmpty()): ?>
                        <?php foreach ($popularPackagesGrid as $package): ?>
                            <?php
                                $packageLocation = trim((string) $package->location);
                                $packageSummary = trim((string) ($package->summary ?: $package->description ?: ''));
                                $packageSummary = \Illuminate\Support\Str::limit($packageSummary !== '' ? $packageSummary : ($packageLocation !== '' ? $packageLocation : 'Sabah, Malaysia'), 150);
                                $packageRating = $package->package_review_average;
                                $discountBadge = $package->has_active_discount
                                    ? rtrim(rtrim(number_format((float) $package->discount_percentage, 2, '.', ''), '0'), '.').'% OFF'
                                    : null;
                                $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                                $originalPrice = (float) $package->malaysia_adult_price_myr;
                                $packageDuration = trim((string) $package->duration);
                                $packageDurationKey = strtolower(preg_replace('/[^a-z0-9]+/', '', $packageDuration));
                                $durationFilterValue = match (true) {
                                    str_contains($packageDurationKey, '4d3n'),
                                    str_contains($packageDurationKey, '4days3nights') => '4d3n',
                                    str_contains($packageDurationKey, '3d2n'),
                                    str_contains($packageDurationKey, '3days2nights') => '3d2n',
                                    str_contains($packageDurationKey, '2d1n'),
                                    str_contains($packageDurationKey, '2days1night') => '2d1n',
                                    str_contains($packageDurationKey, 'daytrip'),
                                    str_contains($packageDurationKey, 'daytime'),
                                    str_contains($packageDurationKey, '1day'),
                                    str_contains($packageDurationKey, 'oneday'),
                                    str_contains($packageDurationKey, 'hour'),
                                    str_contains($packageDurationKey, 'hours'),
                                    str_contains($packageDurationKey, 'fullday'),
                                    str_contains($packageDurationKey, 'halfday') => 'day-trip',
                                    default => '',
                                };
                                $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $packageDuration));
                            ?>
                            <article class="popular-package-shell flex h-full flex-col overflow-hidden text-left" style="min-height: 24rem; border: 1px solid rgba(120,113,108,0.18); background: rgba(255,255,255,0.96); box-shadow: 0 18px 32px rgba(15,23,42,0.14);" data-popular-card data-location="{{ strtolower($packageLocation !== '' ? $packageLocation : 'Sabah, Malaysia') }}" data-package="{{ strtolower((string) $package->name) }}" data-duration="{{ $durationFilterValue }}" data-rating="{{ $packageRating !== null ? number_format($packageRating, 1, '.', '') : '5.0' }}">
                                <a href="{{ route('packages.show', $package) }}" style="display: block; color: inherit; text-decoration: none;">
                                    <div class="relative overflow-hidden">
                                        @if ($package->image_url)
                                            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="popular-package-image" style="display: block; height: 16rem; width: 100%; object-fit: cover;">
                                        @else
                                            <div style="display: flex; height: 15.5rem; align-items: center; justify-content: center; background: linear-gradient(135deg, #60a5fa, #bfdbfe 40%, #fde68a); padding: 1rem; text-align: center;">
                                                <span style="font-family: 'Prata', serif; font-size: 1.5rem; line-height: 1.3; color: #1e3a8a;">{{ $package->name }}</span>
                                            </div>
                                        @endif
                                        <span style="position: absolute; left: 0.85rem; top: 0.85rem; background: rgba(255,255,255,0.95); padding: 0.38rem 0.65rem; font-size: 0.66rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #1d4ed8;">
                                            {{ $tripCode !== '' ? $tripCode : '3D2N' }}
                                        </span>
                                        @if ($discountBadge)
                                            <span style="position: absolute; right: 0.85rem; top: 0.85rem; background: #ffedd5; padding: 0.38rem 0.65rem; font-size: 0.66rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #c2410c;">
                                                {{ $discountBadge }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="padding: 1.2rem 1.2rem 1rem;">
                                        <p style="margin: 0; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: #78716c;">{{ $packageLocation !== '' ? strtoupper($packageLocation) : 'SABAH, MALAYSIA' }}</p>
                                        <h3 style="margin: 0.55rem 0 0; font-family: 'Oswald', sans-serif; font-size: 1.65rem; font-weight: 700; line-height: 1.08; color: #1f2937;">
                                            {{ $package->name }}
                                        </h3>
                                        <p style="margin: 0.75rem 0 0; min-height: 3rem; font-size: 0.94rem; line-height: 1.75; color: #57534e;">
                                            {{ $packageSummary }}
                                        </p>
                                    </div>
                                </a>
                                <div style="margin-top: auto; padding: 0 1.2rem 1.25rem;">
                                    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; border-top: 1px solid rgba(120,113,108,0.16); padding-top: 1rem;">
                                        <div>
                                            <p style="margin: 0; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: #b45309;">Starting From</p>
                                            @if ($package->has_active_discount)
                                                <p style="margin: 0.25rem 0 0; font-size: 0.82rem; color: #78716c; text-decoration: line-through;">
                                                    <span class="currency-price" data-myr="{{ $originalPrice }}" data-currency-decimals="2">RM {{ number_format($originalPrice, 2) }}</span>
                                                </p>
                                            @endif
                                            <p style="margin: 0.18rem 0 0; font-size: 1.55rem; font-weight: 700; line-height: 1; color: #0f4fb5;">
                                                <span class="currency-price" data-myr="{{ $currentPrice }}" data-currency-decimals="2">RM {{ number_format($currentPrice, 2) }}</span>
                                            </p>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <a href="{{ route('packages.show', $package) }}" style="display: inline-flex; align-items: center; justify-content: center; border: 1px solid #1d4ed8; padding: 0.7rem 1rem; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #1d4ed8; text-decoration: none;">
                                                View Details
                                            </a>
                                            <a href="{{ route('booking.create', ['package_id' => $package->id]) }}" style="display: inline-flex; align-items: center; justify-content: center; background: #ff1d0d; padding: 0.7rem 1rem; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #ffffff; text-decoration: none;">
                                                Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <div class="rounded-[1.8rem] border border-dashed border-stone-300 bg-white px-6 py-12 text-center text-sm text-stone-600 xl:col-span-3">
                                No popular packages are available right now.
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-6 hidden rounded-[1.4rem] border border-dashed border-stone-300 bg-white px-6 py-8 text-center text-sm text-stone-600" data-popular-empty>
                        No destinations match your current filters.
                    </div>
                    <div class="mt-6 text-center">
                        <button type="button" data-popular-load-more class="hidden inline-flex items-center justify-center rounded-[0.85rem] border border-stone-900 bg-white px-6 py-2 text-[0.82rem] font-semibold uppercase tracking-[0.12em] text-black shadow-[0_10px_20px_rgba(15,23,42,0.08)] transition hover:bg-stone-100">
                            View More
                        </button>
                    </div>

            </div>
        </section>

        <div>
            <section id="promos" class="px-5 pb-12 pt-0 md:px-7 md:pb-16 md:pt-0 lg:px-8" style="margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw); background: #ffffff;">
                <div class="relative mx-auto pt-0 md:pt-0" style="max-width: 1680px;">
                    <div class="mx-auto px-5 py-4 md:px-8 md:py-6">
                        <div class="flex flex-col gap-8 lg:flex-row lg:flex-nowrap lg:items-start lg:justify-between lg:gap-10">
                            <div class="min-w-0 lg:flex-1">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="font-['Oswald'] font-bold uppercase text-[#b45309]" style="font-size: clamp(1.2rem, 2.8vw, 2.35rem); line-height: 1;">Promotions</p>
                                    </div>
                                </div>

                                <?php if ($promoSlides->isNotEmpty()): ?>
                                    <div class="mt-8 flex flex-col gap-6 md:grid md:grid-cols-2 lg:flex lg:flex-row lg:flex-nowrap" style="width: fit-content; max-width: none; column-gap: 0; row-gap: 0;">
                                        <?php foreach ($promoSlides as $promo): ?>
                                            <article class="overflow-hidden border bg-white shadow-[0_18px_40px_rgba(15,23,42,0.08)]" style="width: 23rem; max-width: 23rem; margin-right: 2.5rem; border-color: rgba(120,113,108,0.16);">
                                                <?php if (!empty($promo['poster_url'])): ?>
                                                    <button
                                                        type="button"
                                                        class="promo-poster-trigger"
                                                        data-promo-modal-trigger
                                                        data-promo-poster="{{ $promo['poster_url'] }}"
                                                        data-promo-title="{{ $promo['title'] }}"
                                                        data-promo-date="{{ $promo['date_label'] ?? (($promo['status'] ?? (($promo['is_active_offer'] ?? false) ? 'Active offer' : 'Promotion'))) }}"
                                                        data-promo-summary="{{ $promo['summary'] ?: 'New promotions uploaded from the admin dashboard will appear here.' }}"
                                                        aria-label="View {{ $promo['title'] }} poster"
                                                    >
                                                        <div class="bg-white p-4">
                                                            <img src="{{ $promo['poster_url'] }}" alt="{{ $promo['title'] }}" class="w-full object-contain" style="display: block; width: 100%; height: 19rem; background: #ffffff;">
                                                        </div>
                                                    </button>
                                                <?php else: ?>
                                                    <button
                                                        type="button"
                                                        class="promo-poster-trigger"
                                                        data-promo-modal-trigger
                                                        data-promo-poster=""
                                                        data-promo-title="{{ $promo['title'] }}"
                                                        data-promo-date="{{ $promo['date_label'] ?? (($promo['status'] ?? (($promo['is_active_offer'] ?? false) ? 'Active offer' : 'Promotion'))) }}"
                                                        data-promo-summary="{{ $promo['summary'] ?: 'New promotions uploaded from the admin dashboard will appear here.' }}"
                                                        aria-label="View {{ $promo['title'] }} details"
                                                    >
                                                        <div class="flex items-center justify-center bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] px-6 text-center" style="height: 19rem;">
                                                            <span class="font-['Oswald'] text-2xl font-semibold leading-tight text-white">{{ $promo['title'] }}</span>
                                                        </div>
                                                    </button>
                                                <?php endif; ?>

                                                <div class="space-y-1 px-5 pb-5 pt-1">
                                                    <div class="flex flex-wrap gap-2">
                                                        <span class="rounded-full font-bold uppercase {{ ($promo['is_active_offer'] ?? false) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}" style="padding: 0.16rem 0.62rem; font-size: 0.52rem; letter-spacing: 0; line-height: 1;">
                                                            {{ $promo['status'] ?? (($promo['is_active_offer'] ?? false) ? 'Active offer' : 'Promotion') }}
                                                        </span>
                                                        <?php if (!empty($promo['date_label'])): ?>
                                                            <span class="rounded-full bg-amber-100 font-bold uppercase text-amber-700" style="padding: 0.16rem 0.62rem; font-size: 0.52rem; letter-spacing: 0; line-height: 1;">
                                                                {{ $promo['date_label'] }}
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div>
                                                        <h3 class="font-['Oswald'] text-[1.7rem] font-semibold uppercase tracking-[0.04em] text-stone-900">{{ $promo['title'] }}</h3>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <article class="mt-8 rounded-[1.6rem] border border-dashed bg-white px-6 py-10 text-center" style="border-color: rgba(120,113,108,0.28);">
                                        <p class="font-['Oswald'] text-sm font-bold uppercase tracking-[0.22em] text-stone-500">Notice</p>
                                        <h3 class="mt-3 font-['Prata'] text-3xl text-stone-900">No promotions yet</h3>
                                        <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-stone-600">
                                            New promotions uploaded from the admin dashboard will appear here as soon as they go live.
                                        </p>
                                    </article>
                                <?php endif; ?>
                            </div>

                            <aside class="w-full px-1 py-2 md:px-2 lg:w-[22rem] lg:min-w-[22rem] lg:flex-shrink-0" style="margin-top: 2.5rem; position: relative; top: -1rem; left: 32rem; border-left: 1px solid rgba(120, 74, 34, 0.18); padding-left: 3.5rem;">
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                    <h3 class="font-['Oswald'] text-[1.45rem] font-bold uppercase tracking-[0.12em] text-[#243f67]">
                                        Sabah Travel Articles
                                    </h3>
                                </div>

                                <?php if ($latestBlogPosts->isNotEmpty()): ?>
                                    <div class="mt-5 max-w-[22rem] space-y-7">
                                        <?php foreach ($latestBlogPosts as $post): ?>
                                            <a href="{{ route('blog.show', $post) }}" class="flex items-start gap-4 text-left transition hover:opacity-90">
                                                <div class="flex-shrink-0 overflow-hidden bg-stone-200" style="width: 58px; height: 58px; min-width: 58px;">
                                                    <?php if ($post->cover_image_url): ?>
                                                        <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="h-full w-full object-cover" style="width: 58px; height: 58px;">
                                                    <?php else: ?>
                                                        <div class="flex h-full w-full items-center justify-center bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] text-center">
                                                            <span class="text-[0.5rem] font-bold uppercase tracking-[0.08em] text-white">Blog</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <h4 class="text-[0.86rem] font-semibold uppercase leading-[1.35] text-[#4a4a4a]">
                                                        {{ $post->title }}
                                                    </h4>
                                                    <div class="mt-2 flex items-center gap-2 text-[0.82rem] text-[#a0aec0]">
                                                        <svg viewBox="0 0 24 24" class="flex-shrink-0" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                            <circle cx="12" cy="12" r="9"></circle>
                                                            <path d="M12 7v5l3 2"></path>
                                                        </svg>
                                                        <span>{{ $post->published_at?->format('F j, Y') ?? 'Latest article' }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex justify-start" style="margin-top: 12rem;">
                                        <a href="{{ route('blog.index') }}" class="text-base font-semibold text-amber-500 transition hover:text-amber-600">
                                            Read All Articles
                                        </a>
                                    </div>

                                <?php else: ?>
                                    <div class="mt-6 border border-dashed border-stone-200 bg-stone-50 px-5 py-8 text-center text-sm text-stone-500">
                                        Travel blog articles will appear here once they are published.
                                    </div>
                                <?php endif; ?>
                            </aside>
                        </div>
                    </div>
                </div>
            </section>
        </div>

<section
    class="home-screen-section"
    id="transport"
    style="position: relative; overflow: hidden; box-sizing: border-box; min-height: calc(100svh - var(--home-header-offset, 0px) + 90px); margin-top: -0.75rem; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);"
>
    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/transport.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat;"></div>
    <div class="transport-shell" style="position: relative; z-index: 2; min-height: 100%; width: 100%; padding: 1.5rem 3rem calc(2.25rem + 20px);">

    <div style="display: flex; min-height: 100%; width: 100%; align-items: center; justify-content: flex-start;">

            <!-- LEFT SIDE -->
            <div class="transport-copy" style="position: relative; z-index: 10; width: 100%; max-width: 980px; flex-shrink: 0; margin-top: 3rem; margin-left: 6rem;">
                <!-- TRANSPORT BOX -->
                <div class="transport-box" style="display: flex; flex-direction: column; justify-content: center; border-radius: 1rem; background: #ffffff; padding: 1.6rem 2.5rem 1.8rem; min-height: 330px; box-shadow: 0 14px 30px rgba(15,23,42,0.12);">

                    <div style="text-align: center;">
                        <h2 style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 2.55rem; font-weight: 700; text-transform: uppercase; line-height: 1; letter-spacing: 0.16em; color: #2f63bc;">
                            TRANSPORT
                        </h2>

                        <p style="max-width: 28rem; margin: 0.95rem auto 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; line-height: 1.25; letter-spacing: 0.12em; color: #9b4a14;">
                            We offer transport packages at the lowest prices.
                        </p>
                    </div>

                    <div class="transport-grid" style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); align-items: center; gap: 1.35rem; margin-top: 2.55rem;">
                        @foreach ($transportOptions as $option)
                            <a
                                href="{{ $option['url'] }}"
                                style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; text-decoration: none; color: inherit; padding-top: 0.78rem; transition: transform 0.25s ease;"
                                onmouseover="this.style.transform='translateY(-6px)'"
                                onmouseout="this.style.transform='translateY(0)'"
                            >
                                <div style="display: flex; height: 6.7rem; width: 100%; align-items: center; justify-content: center;">
                                    <img
                                        src="{{ $option['image'] }}"
                                        alt="{{ $option['name'] }}"
                                        style="max-height: 100%; width: auto; max-width: 100%; object-fit: contain; filter: drop-shadow(0 12px 24px rgba(15,23,42,0.24)); transition: transform 0.28s ease, filter 0.28s ease;"
                                        onmouseover="this.style.transform='scale(1.08)'; this.style.filter='drop-shadow(0 18px 30px rgba(15,23,42,0.28))'"
                                        onmouseout="this.style.transform='scale(1)'; this.style.filter='drop-shadow(0 12px 24px rgba(15,23,42,0.24))'"
                                    >
                                </div>

                                <span style="display: inline-flex; align-items: center; justify-content: center; margin-top: 0.85rem; border-radius: 999px; background: #365fb8; padding: 0.5rem 1.25rem; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #fff; box-shadow: 0 6px 14px rgba(54,95,184,0.3);">
                                    {{ $option['label'] }}
                                </span>

                            </a>
                        @endforeach
                    </div>

                </div>

                <!-- WHY CHOOSE US BOX -->
                <div class="transport-box" style="margin-top: 1rem; border-radius: 1rem; background: #ffffff; padding: 1.8rem 2.5rem 2.5rem; min-height: 240px; box-shadow: 0 14px 30px rgba(15,23,42,0.12);">

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

        <section id="packages" class="hidden w-full rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-amber-700">Packages</span>
                    <h2 class="mt-4 font-['Prata'] text-3xl text-stone-900">Sabah packages for short breaks and nature getaways</h2>
                </div>
                <p class="max-w-2xl text-sm leading-6 text-stone-600">Packages combine planning, logistics, and guided experiences into easier customer decisions.</p>
            </div>
            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <?php foreach ($travelPackages as $package): ?>
                    <?php
                        $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                        $originalPrice = (float) $package->malaysia_adult_price_myr;
                    ?>
                    <a href="{{ route('packages.show', $package) }}" class="block overflow-hidden rounded-3xl border border-stone-200 bg-stone-50 transition hover:-translate-y-1 hover:shadow-lg">
                        <?php if ($package->image_url): ?>
                            <div class="relative">
                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="h-52 w-full object-cover">
                                <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute bottom-3 right-3 h-7 w-auto opacity-90">
                            </div>
                        <?php else: ?>
                            <div class="flex h-52 items-center justify-center bg-[linear-gradient(135deg,_#fff7ed,_#ecfccb)] px-6 text-center text-xl font-semibold text-stone-700">{{ $package->name }}</div>
                        <?php endif; ?>
                        <div class="p-5">
                            <h3 class="text-2xl font-semibold text-stone-900">{{ $package->name }}</h3>
                            <p class="mt-2 text-sm text-stone-500">{{ $package->location }} · {{ $package->duration }}</p>
                            <div data-collapsible>
                                <p class="collapsible-copy mt-4 text-sm leading-6 text-stone-600" data-collapsible-copy data-collapsed-height="2.5rem">
                                    {{ $package->description }}
                                </p>
                                <button type="button" class="collapsible-toggle" data-collapsible-toggle style="display: none;">See more</button>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-sm text-stone-500">Package rate</span>
                                <div class="text-right">
                                    <?php if ($package->has_active_discount): ?>
                                        <div class="text-xs text-stone-400 line-through">
                                            <span class="currency-price" data-myr="{{ $originalPrice }}" data-currency-decimals="2">RM {{ number_format($originalPrice, 2) }}</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-lg font-semibold text-stone-900">
                                        <span class="currency-price" data-myr="{{ $currentPrice }}" data-currency-decimals="2">RM {{ number_format($currentPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.documentElement;
            const header = document.querySelector('.js-app-header');
            const mobileHeroSection = document.querySelector('[data-mobile-hero-section]');
            const mobileHeroContent = document.querySelector('[data-mobile-hero-content]');

            const updateHeaderOffset = () => {
                root.style.setProperty('--home-header-offset', `${header?.offsetHeight ?? 0}px`);
            };

            const syncMobileHeroHeight = () => {
                if (!mobileHeroSection || !mobileHeroContent) {
                    return;
                }

                if (window.innerWidth <= 767) {
                    mobileHeroSection.style.height = '14rem';
                    mobileHeroSection.style.minHeight = '14rem';
                    mobileHeroSection.style.maxHeight = '14rem';
                    mobileHeroContent.style.height = '14rem';
                    mobileHeroContent.style.minHeight = '14rem';
                    mobileHeroContent.style.maxHeight = '14rem';
                    mobileHeroContent.style.justifyContent = 'flex-end';
                    mobileHeroContent.style.padding = '0.35rem 0.7rem 0.6rem 0.7rem';
                    mobileHeroContent.style.transform = 'translateY(-1.9rem)';
                } else {
                    mobileHeroSection.style.height = '';
                    mobileHeroSection.style.minHeight = '';
                    mobileHeroSection.style.maxHeight = '';
                    mobileHeroContent.style.height = '';
                    mobileHeroContent.style.minHeight = '';
                    mobileHeroContent.style.maxHeight = '';
                    mobileHeroContent.style.justifyContent = '';
                    mobileHeroContent.style.padding = '';
                    mobileHeroContent.style.transform = '';
                }
            };

            updateHeaderOffset();
            syncMobileHeroHeight();
            window.addEventListener('resize', updateHeaderOffset);
            window.addEventListener('resize', syncMobileHeroHeight);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popularFilterSection = document.querySelector('[data-popular-filters]');
            const popularCards = Array.from(document.querySelectorAll('[data-popular-card]'));
            const popularEmptyState = document.querySelector('[data-popular-empty]');
            const popularLoadMoreButton = document.querySelector('[data-popular-load-more]');

            if (popularFilterSection && popularCards.length) {
                const locationInput = popularFilterSection.querySelector('[data-popular-filter="location"]');
                const durationSelect = popularFilterSection.querySelector('[data-popular-filter="duration"]');
                const resetButton = popularFilterSection.querySelector('[data-popular-filter-reset]');
                const popularBatchSize = 3;
                let visiblePopularCount = popularBatchSize;

                const applyPopularFilters = () => {
                    const locationValue = (locationInput?.value || '').trim().toLowerCase();
                    const durationValue = (durationSelect?.value || '').trim().toLowerCase();
                    const matchingCards = [];
                    const hasActiveFilters = Boolean(locationValue || durationValue);

                    popularCards.forEach((card) => {
                        const cardLocation = card.dataset.location || '';
                        const cardDuration = card.dataset.duration || '';

                        const matchesLocation = !locationValue || cardLocation.includes(locationValue);
                        const matchesDuration = !durationValue || cardDuration === durationValue;
                        const isVisible = matchesLocation && matchesDuration;

                        if (isVisible) {
                            matchingCards.push(card);
                        }

                        card.style.display = 'none';
                    });

                    const maxVisibleCards = hasActiveFilters ? matchingCards.length : visiblePopularCount;

                    matchingCards.forEach((card, index) => {
                        card.style.display = index < maxVisibleCards ? '' : 'none';
                    });

                    if (popularEmptyState) {
                        popularEmptyState.style.display = matchingCards.length === 0 ? 'block' : 'none';
                    }

                    if (popularLoadMoreButton) {
                        popularLoadMoreButton.style.display = !hasActiveFilters && matchingCards.length > visiblePopularCount ? 'inline-flex' : 'none';
                    }
                };

                [locationInput, durationSelect].forEach((field) => {
                    field?.addEventListener('input', () => {
                        visiblePopularCount = popularBatchSize;
                        applyPopularFilters();
                    });
                    field?.addEventListener('change', () => {
                        visiblePopularCount = popularBatchSize;
                        applyPopularFilters();
                    });
                });

                resetButton?.addEventListener('click', () => {
                    if (locationInput) {
                        locationInput.value = '';
                    }
                    if (durationSelect) {
                        durationSelect.value = '';
                    }

                    visiblePopularCount = popularBatchSize;
                    applyPopularFilters();
                });

                popularLoadMoreButton?.addEventListener('click', () => {
                    visiblePopularCount += popularBatchSize;
                    applyPopularFilters();
                });

                applyPopularFilters();
            }

            const promoModal = document.getElementById('promo-poster-modal');
            const promoModalImage = document.getElementById('promo-poster-modal-image');
            const promoModalDate = document.getElementById('promo-poster-modal-date');
            const promoModalTitle = document.getElementById('promo-poster-modal-title');
            const promoModalSummary = document.getElementById('promo-poster-modal-summary');
            const promoModalPanel = document.getElementById('promo-poster-modal-panel');
            const promoModalCopyColumn = document.getElementById('promo-poster-modal-copy-column');
            const promoModalCopyInner = document.getElementById('promo-poster-modal-copy-inner');
            const promoModalCloseButton = promoModal?.querySelector('[data-promo-modal-close]');
            const promoModalTriggers = Array.from(document.querySelectorAll('[data-promo-modal-trigger]'));
            const pageRoot = document.documentElement;
            const defaultPromoModalPanelWidth = '78rem';
            const defaultPromoModalPanelMinHeight = '26rem';
            const expandedPromoModalPanelWidth = '82rem';
            const expandedPromoModalPanelMinHeight = '29rem';
            const defaultPromoModalCopyWidth = '30rem';
            const expandedPromoModalCopyWidth = '34rem';

            const lockPromoModalScroll = () => {
                pageRoot.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
            };

            const unlockPromoModalScroll = () => {
                pageRoot.style.overflow = '';
                document.body.style.overflow = '';
            };

            const syncPromoModalLayout = (promoSummary = '') => {
                if (!promoModalPanel || !promoModalCopyInner || !promoModalCopyColumn) {
                    return;
                }

                const isLongSummary = promoSummary.trim().length > 260;

                promoModalPanel.style.width = isLongSummary ? expandedPromoModalPanelWidth : defaultPromoModalPanelWidth;
                promoModalPanel.style.minHeight = isLongSummary ? expandedPromoModalPanelMinHeight : defaultPromoModalPanelMinHeight;
                promoModalCopyColumn.style.paddingLeft = isLongSummary ? '4.25rem' : '';
                promoModalCopyColumn.style.paddingRight = isLongSummary ? '4.5rem' : '';
                promoModalCopyInner.style.maxWidth = isLongSummary ? expandedPromoModalCopyWidth : defaultPromoModalCopyWidth;
            };

            const closePromoModal = () => {
                if (!promoModal) {
                    return;
                }

                promoModal.hidden = true;
                unlockPromoModalScroll();
            };

            promoModalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    if (!promoModal || !promoModalImage || !promoModalDate || !promoModalTitle || !promoModalSummary) {
                        return;
                    }

                    const posterUrl = trigger.getAttribute('data-promo-poster') || '';
                    const promoTitle = trigger.getAttribute('data-promo-title') || 'Promotion';
                    const promoDate = trigger.getAttribute('data-promo-date') || '';
                    const promoSummary = trigger.getAttribute('data-promo-summary') || '';

                    promoModalImage.src = posterUrl;
                    promoModalImage.alt = promoTitle;
                    promoModalImage.style.display = posterUrl ? 'block' : 'none';
                    promoModalDate.textContent = promoDate;
                    promoModalTitle.textContent = promoTitle;
                    promoModalSummary.textContent = promoSummary;
                    syncPromoModalLayout(promoSummary);
                    promoModal.hidden = false;
                    lockPromoModalScroll();
                });
            });

            promoModalCloseButton?.addEventListener('click', closePromoModal);

            promoModal?.addEventListener('click', (event) => {
                if (event.target === promoModal) {
                    closePromoModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && promoModal && !promoModal.hidden) {
                    closePromoModal();
                }
            });

            const collapsibleBlocks = Array.from(document.querySelectorAll('[data-collapsible]'));

            collapsibleBlocks.forEach((block) => {
                const copy = block.querySelector('[data-collapsible-copy]');
                const toggle = block.querySelector('[data-collapsible-toggle]');

                if (!copy || !toggle) {
                    return;
                }

                const collapsedHeight = copy.dataset.collapsedHeight || '4.5rem';
                copy.style.maxHeight = collapsedHeight;

                const syncCollapsibleState = () => {
                    copy.style.maxHeight = 'none';
                    const expandedHeight = copy.scrollHeight;
                    copy.style.maxHeight = collapsedHeight;
                    const collapsedPixels = copy.clientHeight;
                    const needsToggle = expandedHeight - collapsedPixels > 6;

                    if (!needsToggle) {
                        copy.style.maxHeight = 'none';
                        toggle.style.display = 'none';
                        return;
                    }

                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.style.display = 'inline-flex';
                    copy.style.maxHeight = isExpanded ? `${expandedHeight}px` : collapsedHeight;
                    toggle.textContent = isExpanded ? 'See less' : 'See more';
                };

                toggle.setAttribute('aria-expanded', 'false');
                toggle.addEventListener('click', () => {
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    toggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                    syncCollapsibleState();
                });

                syncCollapsibleState();
                window.addEventListener('resize', syncCollapsibleState);
            });
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
            const popularTrack = document.querySelector('[data-popular-picks-track]');
            const popularPrevButton = document.querySelector('[data-popular-prev]');
            const popularNextButton = document.querySelector('[data-popular-next]');

            if (!popularTrack || !popularPrevButton || !popularNextButton) {
                return;
            }

            const popularCards = Array.from(popularTrack.querySelectorAll('.popular-package-shell'));

            if (!popularCards.length) {
                return;
            }

            const cardStep = () => {
                const firstCard = popularCards[0];

                if (!firstCard) {
                    return 0;
                }

                const cardStyles = window.getComputedStyle(firstCard);
                const cardGap = Number.parseFloat(cardStyles.marginRight || '0');

                return firstCard.getBoundingClientRect().width + cardGap + 20;
            };

            const updatePopularButtons = () => {
                const maxScrollLeft = popularTrack.scrollWidth - popularTrack.clientWidth;
                const isAtStart = popularTrack.scrollLeft <= 4;
                const isAtEnd = popularTrack.scrollLeft >= maxScrollLeft - 4;

                popularPrevButton.disabled = isAtStart;
                popularNextButton.disabled = isAtEnd;
                popularPrevButton.style.opacity = isAtStart ? '0.45' : '1';
                popularNextButton.style.opacity = isAtEnd ? '0.45' : '1';
                popularPrevButton.style.cursor = isAtStart ? 'not-allowed' : 'pointer';
                popularNextButton.style.cursor = isAtEnd ? 'not-allowed' : 'pointer';
            };

            popularPrevButton.addEventListener('click', () => {
                popularTrack.scrollBy({ left: -cardStep(), behavior: 'smooth' });
            });

            popularNextButton.addEventListener('click', () => {
                popularTrack.scrollBy({ left: cardStep(), behavior: 'smooth' });
            });

            popularTrack.addEventListener('scroll', updatePopularButtons, { passive: true });
            window.addEventListener('resize', updatePopularButtons);

            updatePopularButtons();
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
            const heroSlider = document.querySelector('[data-hero-slider]');
            const heroSlides = Array.from(document.querySelectorAll('[data-hero-slide]'));
            const heroPrev = document.querySelector('[data-hero-prev]');
            const heroNext = document.querySelector('[data-hero-next]');
            const heroDots = Array.from(document.querySelectorAll('[data-hero-dot]'));
            let heroActiveIndex = 0;
            let heroIntervalId = null;

            if (heroSlider && heroSlides.length > 1) {
                const setHeroSlide = (nextIndex) => {
                    heroActiveIndex = (nextIndex + heroSlides.length) % heroSlides.length;

                    heroSlides.forEach((slide, index) => {
                        slide.classList.toggle('is-active', index === heroActiveIndex);
                    });

                    heroDots.forEach((dot, index) => {
                        dot.classList.toggle('is-active', index === heroActiveIndex);
                        dot.setAttribute('aria-pressed', index === heroActiveIndex ? 'true' : 'false');
                    });
                };

                const stopHeroAutoplay = () => {
                    if (heroIntervalId) {
                        window.clearInterval(heroIntervalId);
                        heroIntervalId = null;
                    }
                };

                const startHeroAutoplay = () => {
                    stopHeroAutoplay();
                    heroIntervalId = window.setInterval(() => {
                        setHeroSlide(heroActiveIndex + 1);
                    }, 5000);
                };

                heroPrev?.addEventListener('click', () => {
                    setHeroSlide(heroActiveIndex - 1);
                    startHeroAutoplay();
                });

                heroNext?.addEventListener('click', () => {
                    setHeroSlide(heroActiveIndex + 1);
                    startHeroAutoplay();
                });

                heroDots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        setHeroSlide(index);
                        startHeroAutoplay();
                    });
                });

                heroSlider.addEventListener('mouseenter', stopHeroAutoplay);
                heroSlider.addEventListener('mouseleave', startHeroAutoplay);
                setHeroSlide(0);
                startHeroAutoplay();
            }

        });
    </script>
    </div>
</x-layouts.app>
