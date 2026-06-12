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
            height: 85svh;
            min-height: 85svh;
        }

        .home-screen-section--hero > div[style*="min-height"] {
            min-height: 76svh !important;
        }

        .hero-content-shell {
            padding-left: clamp(1.25rem, 4vw, 3.25rem) !important;
            transform: translateY(clamp(-4rem, -5vw, -1.5rem));
        }

        .hero-copy {
            margin-left: 0;
            margin-top: clamp(2.5rem, 5vw, 4.75rem);
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
                linear-gradient(90deg, rgba(0,0,0,0.48) 0%, rgba(0,0,0,0.34) 35%, rgba(0,0,0,0.24) 100%),
                linear-gradient(180deg, rgba(0,0,0,0.16) 0%, rgba(0,0,0,0.28) 100%);
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

        .popular-package-shell {
            transition: transform 0.25s ease;
            transform-origin: center center;
        }

        .popular-package-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .popular-package-button {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .popular-package-shell:hover {
            transform: scale(1.06);
        }

        .popular-package-shell:hover .popular-package-card {
            box-shadow: 0 20px 34px rgba(15, 23, 42, 0.14) !important;
        }

        .popular-package-shell:hover .popular-package-button {
            box-shadow: 0 8px 14px rgba(0, 0, 0, 0.12) !important;
            transform: translateY(-0.05rem);
        }

        .bulletin-board {
            position: relative;
            overflow: visible;
            border: 0;
            border-radius: 0;
            background:
                linear-gradient(135deg, #5f3517 0%, #7b4720 18%, #9b6332 42%, #7d4821 68%, #5c3317 100%);
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,0.14),
                inset 0 0 0 6px rgba(78,43,17,0.48),
                inset 0 1px 0 rgba(255,255,255,0.14),
                0 22px 42px rgba(15,23,42,0.14);
        }

        .bulletin-board::before {
            content: "";
            position: absolute;
            inset: 1rem;
            border-radius: 0;
            background:
                radial-gradient(circle at 18% 20%, rgba(255,222,173,0.18) 0, rgba(255,222,173,0) 22%),
                radial-gradient(circle at 78% 34%, rgba(92,51,22,0.14) 0, rgba(92,51,22,0) 24%),
                repeating-linear-gradient(
                    0deg,
                    rgba(121,73,38,0.16) 0,
                    rgba(121,73,38,0.16) 2px,
                    rgba(0,0,0,0) 2px,
                    rgba(0,0,0,0) 34px
                ),
                repeating-linear-gradient(
                    92deg,
                    rgba(88,50,24,0.12) 0,
                    rgba(88,50,24,0.12) 1px,
                    rgba(0,0,0,0) 1px,
                    rgba(0,0,0,0) 18px
                ),
                linear-gradient(180deg, #b97a45 0%, #a96936 44%, #955a2b 100%);
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,0.1),
                inset 0 2px 10px rgba(255,255,255,0.08),
                inset 0 -8px 18px rgba(84,46,19,0.18);
            pointer-events: none;
        }

        .bulletin-board::after {
            content: "";
            position: absolute;
            inset: 0.7rem;
            border: 1px solid rgba(70, 38, 16, 0.38);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08);
            pointer-events: none;
        }

        .bulletin-board-inner {
            position: relative;
            z-index: 1;
            padding: 1.55rem;
        }

        .bulletin-note {
            position: relative;
            border: 1px solid rgba(120,113,108,0.2);
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 18px 28px rgba(15,23,42,0.14);
            transition: transform 0.24s ease, box-shadow 0.24s ease;
        }

        .bulletin-note:hover {
            transform: translateY(-4px) rotate(0deg) !important;
            box-shadow: 0 22px 34px rgba(15,23,42,0.18);
        }

        .bulletin-note::before {
            content: "";
            position: absolute;
            top: 0.85rem;
            left: 50%;
            height: 0.95rem;
            width: 0.95rem;
            transform: translateX(-50%);
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #fff7ed, #dc2626 55%, #7f1d1d 100%);
            box-shadow: 0 3px 6px rgba(15,23,42,0.25);
        }

        .bulletin-note--gold {
            background: linear-gradient(180deg, #fff8c9, #fef3a5);
        }

        .bulletin-note--blue {
            background: linear-gradient(180deg, #eff6ff, #dbeafe);
        }

        .bulletin-note--rose {
            background: linear-gradient(180deg, #fff1f2, #ffe4e6);
        }

        .bulletin-note--cream {
            background: linear-gradient(180deg, #fffdf7, #f6efe1);
        }

        .bulletin-note--green {
            border-color: rgba(22, 101, 52, 0.32);
            background: linear-gradient(180deg, #86efac, #4ade80 52%, #22c55e 100%);
            box-shadow: 0 18px 28px rgba(22,101,52,0.24);
        }

        .bulletin-note--red {
            border-color: rgba(153, 27, 27, 0.32);
            background: linear-gradient(180deg, #fca5a5, #f87171 52%, #ef4444 100%);
            box-shadow: 0 18px 28px rgba(153,27,27,0.22);
        }

        .bulletin-photo {
            border-radius: 1.1rem;
            border: 1px solid rgba(120,113,108,0.18);
            background: #ffffff;
            box-shadow: 0 8px 18px rgba(15,23,42,0.08);
        }

        .promo-poster-card {
            transition: transform 0.24s ease, filter 0.24s ease;
        }

        .promo-poster-card:hover {
            transform: translateY(-6px) rotate(0deg) !important;
            filter: drop-shadow(0 16px 24px rgba(15,23,42,0.16));
        }

        .reviews-carousel-shell {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(186, 230, 253, 0.9);
            border-radius: 1.75rem;
            background: linear-gradient(180deg, rgba(240, 249, 255, 0.92), rgba(255, 255, 255, 0.96));
            padding: 1.1rem 0;
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

        @keyframes reviews-carousel-scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(calc(-50% - 0.5rem));
            }
        }

        @media (min-width: 1024px) {
            .promo-posters-row {
                display: flex !important;
                align-items: flex-start !important;
                justify-content: flex-start !important;
                gap: 5rem !important;
                margin-top: 4.4rem !important;
                margin-left: 8.3rem !important;
                margin-right: 3.2rem !important;
            }

            .promo-primary-poster-card {
                margin: 0 !important;
                flex: 0 0 21rem !important;
            }

            .promo-secondary-posters {
                margin-top: 0 !important;
                margin-left: 0 !important;
                width: auto !important;
                flex: 0 0 auto !important;
                justify-content: flex-start !important;
                align-items: flex-start !important;
            }

            .promo-secondary-poster-card {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        .promo-poster-pin {
            position: absolute;
            left: 50%;
            top: 0.15rem;
            z-index: 3;
            width: 0.9rem;
            height: 0.9rem;
            transform: translateX(-50%);
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #fff7ed, #dc2626 55%, #7f1d1d 100%);
            box-shadow: 0 3px 6px rgba(15,23,42,0.25);
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
                min-height: 35rem !important;
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
                        <div class="hero-tagline" style="margin-top: 1.15rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; padding: 0.22rem; background: linear-gradient(90deg, rgba(38,164,232,0.96), rgba(58,86,195,0.96)); box-shadow: 0 14px 28px rgba(15,23,42,0.22);">
                            <div class="hero-tagline-inner" style="width: 100%; border-radius: 999px; border: 2px solid rgba(255,255,255,0.45); padding: 0.22rem 1.9rem 0.3rem; background: linear-gradient(90deg, rgba(62,180,242,0.18), rgba(76,65,186,0.18));">
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

        <div class="pointer-events-none absolute inset-x-0 top-8 -z-10 h-72 rounded-[3rem] bg-[radial-gradient(circle_at_top_left,_rgba(134,239,172,0.16),_transparent_38%),radial-gradient(circle_at_top_right,_rgba(190,242,100,0.14),_transparent_34%)]"></div>

        <div>
            <section id="promos" class="bg-white px-5 pb-12 pt-3 md:px-7 md:pb-16 md:pt-5 lg:px-8" style="margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);">
                <div class="relative mx-auto pt-6 md:pt-8" style="max-width: 1920px;">
                    <div class="bulletin-board mx-auto mt-8 hidden md:block" style="width: min(96vw, 1880px); max-width: 1880px;">
                        <div class="bulletin-board-inner">
                            <div class="flex flex-row items-start justify-between gap-8">
                                <div class="space-y-6 border p-4" style="width: 1160px; min-width: 0; flex: 0 1 1600px; height: 36rem; border-color: rgba(120, 74, 34, 0.28); background: linear-gradient(180deg, #f8f1e4 0%, #f1e4d1 100%);">
                                    <div class="flex items-center justify-center px-4 py-2">
                                        <div class="text-center">
                                            <div style="position: relative; display: inline-block; border: 1px solid rgba(78,43,17,0.42); background: linear-gradient(180deg, rgba(255,255,255,0.14), rgba(255,255,255,0) 24%), repeating-linear-gradient(92deg, rgba(118,71,35,0.14) 0, rgba(118,71,35,0.14) 1px, rgba(0,0,0,0) 1px, rgba(0,0,0,0) 16px), linear-gradient(180deg, #d4a46f 0%, #bd834e 46%, #a46434 100%); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12), inset 0 -4px 10px rgba(78,43,17,0.16), 0 8px 16px rgba(15,23,42,0.12); padding: 0.55rem 1.9rem 0.65rem; text-align: center;">
                                                <span style="position: absolute; left: 0.7rem; top: 50%; width: 0.7rem; height: 0.7rem; transform: translateY(-50%); border-radius: 999px; background: radial-gradient(circle at 30% 30%, #fff7ed, #c2410c 56%, #7c2d12 100%); box-shadow: 0 2px 5px rgba(15,23,42,0.24);"></span>
                                                <span style="position: absolute; right: 0.7rem; top: 50%; width: 0.7rem; height: 0.7rem; transform: translateY(-50%); border-radius: 999px; background: radial-gradient(circle at 30% 30%, #fff7ed, #c2410c 56%, #7c2d12 100%); box-shadow: 0 2px 5px rgba(15,23,42,0.24);"></span>
                                                <h3 class="m-0 font-['Oswald'] font-bold uppercase text-[#fff8ed]" style="font-size: 1rem; letter-spacing: 0.18em; text-shadow: 0 2px 0 rgba(97,52,20,0.28);">Promotions & Offers</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="promo-posters-row">
                                    <?php if ($currentPromoSlide): ?>
                                        <article class="promo-poster-card promo-primary-poster-card bulletin-note {{ ($currentPromoSlide['is_active_offer'] ?? false) ? 'bulletin-note--green' : 'bulletin-note--red' }} mx-auto" style="position: relative; width: min(100%, 21rem); margin-top: 2.8rem; margin-left: 3.5rem; margin-right: auto; padding: 0.18rem; border-radius: 0; transform: rotate(-1.2deg);" data-past-promo-card>
                                            <button
                                                type="button"
                                                class="bulletin-photo overflow-hidden"
                                                style="position: relative; display: block; width: 100%; max-width: 100%; cursor: pointer; padding: 0.15rem; background: #fff9ef; border-radius: 0;"
                                                data-promo-modal-trigger
                                                data-promo-title="{{ $currentPromoSlide['title'] }}"
                                                data-promo-summary="{{ $currentPromoSlide['summary'] }}"
                                                data-promo-poster="{{ $currentPromoSlide['poster_url'] }}"
                                                data-promo-date="{{ $currentPromoSlide['date_label'] }}"
                                            >
                                                <span class="promo-poster-pin"></span>
                                                <div style="position: absolute; left: 0.55rem; top: 0.55rem; z-index: 2; display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                                                    <span style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.26rem 0.62rem; font-size: 0.48rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #315fbd; box-shadow: 0 6px 12px rgba(15,23,42,0.12);">{{ $currentPromoSlide['status'] }}</span>
                                                    <span style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.26rem 0.62rem; font-size: 0.46rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #b45309; box-shadow: 0 6px 12px rgba(15,23,42,0.12);">{{ $currentPromoSlide['date_label'] }}</span>
                                                </div>
                                                <?php if ($currentPromoSlide['poster_url']): ?>
                                                    <img src="{{ $currentPromoSlide['poster_url'] }}" alt="{{ $currentPromoSlide['title'] }}" class="mx-auto object-contain" style="display: block; width: 100%; height: auto; max-height: 40rem; background: #ffffff; border-radius: 0;">
                                                <?php else: ?>
                                                    <div class="mx-auto flex w-full items-center justify-center bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] px-6 text-center" style="height: 31rem; border-radius: 0;">
                                                        <span class="font-['Prata'] text-2xl leading-tight text-white">{{ $currentPromoSlide['title'] }}</span>
                                                    </div>
                                                <?php endif; ?>
                                            </button>
                                        </article>
                                    <?php else: ?>
                                        <article class="bulletin-note bulletin-note--cream" style="transform: rotate(-1deg);">
                                            <div class="pt-6 text-center">
                                                <p class="text-[0.72rem] font-bold uppercase tracking-[0.22em] text-stone-500">Notice</p>
                                                <h3 class="mt-3 font-['Prata'] text-3xl text-stone-900">No current promotion yet</h3>
                                                <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-stone-600">
                                                    New promotions uploaded from the admin dashboard will appear here as soon as they go live.
                                                </p>
                                            </div>
                                        </article>
                                    <?php endif; ?>

                                    <div class="promo-secondary-posters flex flex-col md:flex-row md:items-start" style="margin-left: 5rem; margin-top: -15rem; gap: 4rem;">
                                        <?php foreach ($recentPromoSlides->reverse()->values() as $index => $promo): ?>
                                            <?php $isPortraitPromo = ($promo['poster_orientation'] ?? null) === 'portrait'; ?>
                                            <article class="promo-poster-card promo-secondary-poster-card bulletin-note {{ ($promo['is_active_offer'] ?? false) ? 'bulletin-note--green' : 'bulletin-note--red' }}" style="margin-top: {{ $isPortraitPromo ? '-2.4rem' : '6.35rem' }}; margin-left: 0; margin-right: 0; width: {{ $isPortraitPromo ? 'min(100%, 21rem)' : '20rem' }}; flex: 0 0 {{ $isPortraitPromo ? '21rem' : '16.75rem' }}; padding: 0.18rem; border-radius: 0; transform: rotate({{ $index % 2 === 0 ? '-0.9deg' : '1.1deg' }});" data-past-promo-card>
                                                <?php if ($promo['poster_url']): ?>
                                                    <button
                                                        type="button"
                                                        class="bulletin-photo mx-auto overflow-hidden"
                                                        style="position: relative; display: block; width: 100%; max-width: {{ $isPortraitPromo ? '100%' : '16.1rem' }}; cursor: pointer; padding: {{ $isPortraitPromo ? '0.15rem' : '0.08rem' }}; background: #fff9ef; border-radius: 0;"
                                                        data-promo-modal-trigger
                                                        data-promo-title="{{ $promo['title'] }}"
                                                        data-promo-summary="{{ $promo['summary'] }}"
                                                        data-promo-poster="{{ $promo['poster_url'] }}"
                                                        data-promo-date="{{ $promo['date_label'] }}"
                                                        data-past-promo-trigger
                                                        data-promo-orientation="{{ $promo['poster_orientation'] }}"
                                                    >
                                                        <span class="promo-poster-pin"></span>
                                                        <div style="position: absolute; left: 0.5rem; top: 0.5rem; z-index: 2; display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                                                            <span style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.24rem 0.6rem; font-size: 0.46rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #315fbd; box-shadow: 0 6px 12px rgba(15,23,42,0.12);">{{ $promo['status'] }}</span>
                                                            <span style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.24rem 0.6rem; font-size: 0.44rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #b45309; box-shadow: 0 6px 12px rgba(15,23,42,0.12);">{{ $promo['date_label'] }}</span>
                                                        </div>
                                                        <img src="{{ $promo['poster_url'] }}" alt="{{ $promo['title'] }}" class="w-full {{ $isPortraitPromo ? 'object-contain' : 'object-cover' }}" style="display: block; width: 100%; {{ $isPortraitPromo ? 'height: auto; max-height: 40rem;' : 'height: 22rem;' }} background: #ffffff; border-radius: 0; object-position: center;" data-past-promo-poster>
                                                    </button>
                                                <?php else: ?>
                                                    <button
                                                        type="button"
                                                        class="bulletin-photo mx-auto overflow-hidden"
                                                        style="position: relative; display: block; width: 100%; max-width: 16.1rem; cursor: pointer; padding: 0.08rem; background: #fff9ef; border-radius: 0;"
                                                        data-promo-modal-trigger
                                                        data-promo-title="{{ $promo['title'] }}"
                                                        data-promo-summary="{{ $promo['summary'] }}"
                                                        data-promo-poster=""
                                                        data-promo-date="{{ $promo['date_label'] }}"
                                                        data-past-promo-trigger
                                                        data-promo-orientation="{{ $promo['poster_orientation'] }}"
                                                    >
                                                        <span class="promo-poster-pin"></span>
                                                        <div style="position: absolute; left: 0.5rem; top: 0.5rem; z-index: 2; display: flex; flex-direction: column; gap: 0.35rem; align-items: flex-start;">
                                                            <span style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.24rem 0.6rem; font-size: 0.46rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #315fbd; box-shadow: 0 6px 12px rgba(15,23,42,0.12);">{{ $promo['status'] }}</span>
                                                            <span style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.24rem 0.6rem; font-size: 0.44rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #b45309; box-shadow: 0 6px 12px rgba(15,23,42,0.12);">{{ $promo['date_label'] }}</span>
                                                        </div>
                                                        <div class="flex w-full items-center justify-center bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] px-4 text-center" style="min-height: 13.5rem; height: 16.5rem; border-radius: 0;">
                                                            <span class="text-xs font-bold uppercase tracking-[0.12em] text-white">Promo</span>
                                                        </div>
                                                    </button>
                                                <?php endif; ?>
                                            </article>
                                        <?php endforeach; ?>
                                        <?php if ($recentPromoSlides->isEmpty()): ?>
                                            <article class="bulletin-note bulletin-note--cream w-full" style="transform: rotate(0.4deg);">
                                                <div class="pt-5 text-center">
                                                    <p class="text-sm leading-7 text-stone-600">Recent promotions will be pinned here after new offers are uploaded.</p>
                                                </div>
                                            </article>
                                        <?php endif; ?>
                                    </div>
                                    </div>
                                </div>

                                <div class="space-y-5 border p-4" style="width: 300px; min-width: 300px; flex: 0 0 300px; margin-left: auto; height: 36rem; border-color: rgba(120, 74, 34, 0.28); background: linear-gradient(180deg, #f8f1e4 0%, #f1e4d1 100%);">
                                    <div class="flex items-center justify-center px-4 py-2">
                                        <div class="text-center">
                                            <div style="position: relative; display: inline-block; border: 1px solid rgba(78,43,17,0.42); background: linear-gradient(180deg, rgba(255,255,255,0.14), rgba(255,255,255,0) 24%), repeating-linear-gradient(92deg, rgba(118,71,35,0.14) 0, rgba(118,71,35,0.14) 1px, rgba(0,0,0,0) 1px, rgba(0,0,0,0) 16px), linear-gradient(180deg, #d4a46f 0%, #bd834e 46%, #a46434 100%); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12), inset 0 -4px 10px rgba(78,43,17,0.16), 0 8px 16px rgba(15,23,42,0.12); padding: 0.55rem 1.9rem 0.65rem; text-align: center;">
                                                <span style="position: absolute; left: 0.7rem; top: 50%; width: 0.7rem; height: 0.7rem; transform: translateY(-50%); border-radius: 999px; background: radial-gradient(circle at 30% 30%, #fff7ed, #c2410c 56%, #7c2d12 100%); box-shadow: 0 2px 5px rgba(15,23,42,0.24);"></span>
                                                <span style="position: absolute; right: 0.7rem; top: 50%; width: 0.7rem; height: 0.7rem; transform: translateY(-50%); border-radius: 999px; background: radial-gradient(circle at 30% 30%, #fff7ed, #c2410c 56%, #7c2d12 100%); box-shadow: 0 2px 5px rgba(15,23,42,0.24);"></span>
                                                <h3 class="m-0 font-['Oswald'] font-bold uppercase text-[#fff8ed]" style="font-size: 1rem; letter-spacing: 0.18em; text-shadow: 0 2px 0 rgba(97,52,20,0.28);">Blog News</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <?php foreach ($latestBlogPosts as $index => $post): ?>
                                        <a href="{{ route('blog.show', $post) }}" class="bulletin-note {{ $index % 2 === 0 ? 'bulletin-note--cream' : 'bulletin-note--rose' }} block" style="transform: rotate({{ $index % 2 === 0 ? '0.9deg' : '-0.8deg' }});">
                                            <div class="flex gap-3 pt-3">
                                                <div class="bulletin-photo h-20 w-20 shrink-0 overflow-hidden p-1.5">
                                                    <?php if ($post->cover_image_url): ?>
                                                        <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="h-full w-full rounded-[0.75rem] object-cover">
                                                    <?php else: ?>
                                                        <div class="flex h-full w-full items-center justify-center rounded-[0.75rem] bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] px-3 text-center">
                                                            <span class="text-xs font-bold uppercase tracking-[0.12em] text-white">Blog</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0 flex-1" style="padding-top: 0.35rem;">
                                                    <h3 class="line-clamp-2 text-[1.55rem] font-semibold leading-[1.15] text-stone-900">{{ $post->title }}</h3>
                                                    <p class="mt-2 text-[0.68rem] font-bold uppercase tracking-[0.18em] text-stone-500">{{ $post->published_at?->format('d M Y') ?? 'Latest post' }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                    <?php if ($latestBlogPosts->isEmpty()): ?>
                                        <article class="bulletin-note bulletin-note--cream" style="transform: rotate(0.8deg);">
                                            <div class="pt-5 text-center">
                                                <p class="text-sm leading-7 text-stone-600">No blog posts have been uploaded yet.</p>
                                            </div>
                                        </article>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        id="promo-poster-modal"
                        style="position: fixed; inset: 0; z-index: 80; display: none; align-items: center; justify-content: center; background: rgba(15,23,42,0.72); padding: 1.25rem;"
                    >
                        <div
                            id="promo-poster-modal-panel"
                            style="position: relative; width: min(100%, 1100px); max-height: calc(100vh - 2.5rem); overflow-y: auto; border-radius: 1.6rem; background: linear-gradient(180deg, #fffdf7 0%, #f8f2e7 100%); box-shadow: 0 30px 80px rgba(15,23,42,0.3);"
                        >
                            <button
                                type="button"
                                id="promo-poster-modal-close"
                                aria-label="Close promotion popup"
                                style="position: absolute; top: 1rem; right: 1rem; display: inline-flex; height: 2.4rem; width: 2.4rem; align-items: center; justify-content: center; border: 0; border-radius: 999px; background: rgba(255,255,255,0.94); color: #1f2937; font-size: 1.5rem; cursor: pointer; box-shadow: 0 10px 24px rgba(15,23,42,0.16);"
                            >
                                ×
                            </button>
                            <div style="display: flex; align-items: flex-start; gap: 1.75rem; padding: 2rem;">
                                <div id="promo-poster-modal-media" class="bulletin-photo overflow-hidden p-2" style="width: 500px; min-width: 500px; flex: 0 0 500px; margin: 0; background: #ffffff;">
                                    <img id="promo-poster-modal-image" src="" alt="" class="w-full rounded-[1rem] object-contain" style="display: none; width: 100%; height: 520px; background: #ffffff;">
                                    <div id="promo-poster-modal-fallback" class="flex items-center justify-center rounded-[1rem] bg-[linear-gradient(145deg,#77a6d8_0%,#5f8fcb_45%,#315fbd_100%)] px-5 text-center" style="display: none; min-height: 320px;">
                                        <span id="promo-poster-modal-fallback-title" class="font-['Prata'] text-3xl leading-tight text-white"></span>
                                    </div>
                                </div>
                                <div style="min-width: 0; flex: 1 1 auto; padding-top: 1.35rem; padding-right: 0.5rem;">
                                    <p id="promo-poster-modal-date" style="margin: 0; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: #b45309;"></p>
                                    <h3 id="promo-poster-modal-title" style="margin: 0.9rem 0 0; font-family: 'Prata', serif; font-size: clamp(1.6rem, 2vw, 2.2rem); line-height: 1.15; color: #1c1917;"></h3>
                                    <p id="promo-poster-modal-summary" style="margin: 1rem 0 0; font-size: 0.98rem; line-height: 1.8; color: #57534e;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section id="popular-picks" class="mt-10 home-screen-section relative overflow-hidden md:overflow-visible px-6 pb-8 pt-0 md:mt-12 md:px-8 md:pb-10 md:pt-0">

                <div class="mb-4 flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="hidden md:block md:w-[18rem]"></div>
                    <h2 class="popular-picks-heading flex-1 text-center font-['Oswald'] text-4xl font-bold uppercase tracking-[0.22em] md:text-5xl lg:text-6xl" style="position: relative; left: 0;">
                        <span style="color: #ff2b2b;">Popular</span>
                        <span class="ml-3" style="color: #315fbd;">Picks</span>
                    </h2>
                    <div class="flex justify-center md:w-[18rem] md:justify-end">
                        <a href="{{ route('home') }}#packages-showcase" class="inline-flex items-center justify-center rounded-full px-8 py-3.5 text-[0.95rem] font-semibold uppercase tracking-[0.28em] text-white shadow-[0_14px_30px_rgba(49,95,189,0.22)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(49,95,189,0.28)]" style="border: 1px solid #315fbd; background-color: #315fbd;">
                            See All Package
                        </a>
                    </div>
                </div>

            <div class="relative mx-auto rounded-[2rem] bg-white px-5 py-2 md:overflow-visible md:px-8 md:py-4" style="max-width: 1920px;">
                <div class="popular-picks-mobile-nav hidden items-center justify-between px-2 pb-2 md:hidden">
                    <button
                        type="button"
                        data-popular-prev
                        aria-label="Show previous popular package"
                        style="display: inline-flex; height: 2.8rem; width: 2.8rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.96); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 2.2rem; font-weight: 300; line-height: 1; color: #8aa0d7;"
                    >&lsaquo;</button>
                    <button
                        type="button"
                        data-popular-next
                        aria-label="Show next popular package"
                        style="display: inline-flex; height: 2.8rem; width: 2.8rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.96); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 2.2rem; font-weight: 300; line-height: 1; color: #8aa0d7;"
                    >&rsaquo;</button>
                </div>

                <div class="mt-1 overflow-hidden px-2 py-4 md:overflow-visible md:py-6">
                    <div class="popular-picks-track flex flex-wrap justify-center" data-popular-picks-track style="gap: 2.5rem;">
                        <?php if ($popularPackages->isNotEmpty()): ?>
                        <?php foreach ($popularPackages as $package): ?>
                            <?php
                                $packageLocation = strtolower((string) $package->location);
                                $locationTag = str_contains($packageLocation, 'kundasang')
                                    ? 'KUNDASANG'
                                    : (str_contains($packageLocation, 'kota belud')
                                        ? 'KOTA BELUD'
                                        : (str_contains($packageLocation, 'ranau') ? 'KUNDASANG-RANAU' : 'KOTA KINABALU'));
                                $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], (string) $package->duration));
                                $discountBadge = $package->has_active_discount
                                    ? rtrim(rtrim(number_format((float) $package->discount_percentage, 2, '.', ''), '0'), '.').'% OFF'
                                    : null;
                                $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                                $originalPrice = (float) $package->malaysia_adult_price_myr;
                                $packageRating = $package->package_review_average;
                                $packageReviewCount = (int) ($package->package_review_count ?? 0);
                            ?>
                            <div class="popular-package-shell flex h-full flex-col items-center">
                                <a href="{{ route('products.show', $package) }}" class="popular-package-card flex h-full flex-col overflow-hidden text-left shadow-[0_14px_26px_rgba(15,23,42,0.08)] duration-300" style="width: 390px; min-height: 580px; border-radius: 1.6rem 1.6rem 0 0; background: #f1f0e9;">
                                    <div class="relative overflow-hidden">
                                        @if ($package->image_url)
                                            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="h-52 w-full object-cover">
                                            <img src="{{ asset('images/UE.png') }}" alt="Universal Eden trademark" class="pointer-events-none absolute z-10 h-7 w-auto opacity-90" style="right: 0; bottom: 0; transform: translate(-0.65rem, -0.65rem);">
                                        @else
                                            <div class="flex h-52 items-center justify-center bg-[linear-gradient(135deg,_#f59e0b,_#fde68a_45%,_#fed7aa)] px-6 text-center text-xl font-semibold text-stone-800">{{ $package->name }}</div>
                                        @endif

                                        <span style="position: absolute; left: 0.75rem; top: 0.75rem; z-index: 2; border-radius: 0.2rem; background: #2c22c9; padding: 0.28rem 0.55rem; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #fff;">{{ $locationTag }}</span>

                                        @if ($discountBadge)
                                            <span style="position: absolute; right: 0.75rem; top: 0.75rem; z-index: 2; border-radius: 0.2rem; background: #ff1d0d; padding: 0.28rem 0.55rem; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #fff;">{{ $discountBadge }}</span>
                                        @endif
                                    </div>

                                    <div class="flex flex-1 flex-col p-4">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="font-['Oswald'] text-4xl font-bold leading-none" style="color: #ff1d0d;">{{ $tripCode }}</p>
                                            <div class="rounded-full bg-white/80 px-3 py-1.5 text-right shadow-sm">
                                                @if ($packageRating !== null && $packageReviewCount > 0)
                                                    <div class="text-lg font-bold leading-none text-amber-500">{{ number_format($packageRating, 1) }}/5</div>
                                                    <div class="mt-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-stone-500">{{ $packageReviewCount }} review{{ $packageReviewCount === 1 ? '' : 's' }}</div>
                                                @else
                                                    <div class="text-sm font-semibold leading-none text-stone-500">No reviews</div>
                                                @endif
                                            </div>
                                        </div>

                                        <h3 class="mt-3 font-['Oswald'] text-2xl font-bold uppercase leading-tight text-[#1c2f7d]">{{ $package->name }}</h3>
                                        <p class="mt-3 flex-1 text-sm font-medium leading-6 text-stone-900">{{ \Illuminate\Support\Str::limit($package->description, 180) }}</p>

                                        <div class="mt-5 pt-2">
                                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#ff1d0d]">Starting From</p>
                                            @if ($package->has_active_discount)
                                                <p class="mt-1 text-sm font-medium text-stone-500 line-through">
                                                    <span class="currency-price" data-myr="{{ $originalPrice }}">{{ number_format($originalPrice, 2) }}</span>
                                                </p>
                                            @endif
                                            <p class="mt-1 text-base text-stone-900">
                                                <span class="currency-price text-2xl font-bold leading-none" data-myr="{{ $currentPrice }}" style="color: #0f4fb5;">{{ number_format($currentPrice, 2) }}</span> Per Pax
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <a href="{{ route('booking.create', ['product_id' => $package->id]) }}" class="popular-package-button mt-3 inline-flex min-w-[160px] items-center justify-center rounded-full px-8 py-3 font-['Oswald'] text-lg font-bold uppercase tracking-[0.08em] text-white shadow-[0_12px_18px_rgba(0,0,0,0.16)] transition hover:-translate-y-0.5 hover:shadow-[0_16px_24px_rgba(0,0,0,0.2)]" style="background-color: #ff1d0d;">
                                    Book Now
                                </a>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50 px-6 py-10 text-center text-sm text-stone-600">
                                No popular packages are available right now.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

<section
    class="home-screen-section"
    id="transport"
    style="position: relative; overflow: hidden; box-sizing: border-box; min-height: calc(100svh - var(--home-header-offset, 0px) + 90px); margin-top: -0.75rem; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);"
>
    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/transport.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat;"></div>
    <div class="transport-shell" style="position: relative; z-index: 2; min-height: 100%; width: 100%; padding: 1.5rem 3rem calc(2.25rem + 20px);">

    <div style="display: flex; min-height: 100%; width: 100%; align-items: center; justify-content: flex-start;">

            <!-- LEFT SIDE -->
            <div class="transport-copy" style="position: relative; z-index: 10; width: 100%; max-width: 980px; flex-shrink: 0; margin-top: 0.75rem; margin-left: 6rem;">
                <!-- TRANSPORT BOX -->
                <div class="transport-box" style="display: flex; flex-direction: column; justify-content: center; border-radius: 1rem; background: rgba(255,255,255,0.85); padding: 1.6rem 2.5rem 1.8rem; min-height: 330px; box-shadow: 0 14px 30px rgba(15,23,42,0.12); backdrop-filter: blur(4px);">

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

        <section id="packages-showcase" style="box-sizing: border-box; margin-top: -4.5rem; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw); padding-bottom: 2.25rem;">
            <div style="display: flex; flex-direction: column; gap: 0;">
                <?php foreach ($packageSections as $section): ?>
                    <?php
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
                    ?>

                    <article class="package-offer-section home-screen-section" data-package-section="{{ $section['key'] }}" style="position: relative; overflow: hidden; box-shadow: 0 20px 60px rgba(15,23,42,0.18);{{ $section['key'] === $defaultPackageSection ? '' : ' display: none;' }}">
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
                                <div style="position: relative; left: 15rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 1rem 1.25rem;">
                                    <div class="package-section-label" style="min-width: 320px; clip-path: polygon(10% 0, 90% 0, 100% 100%, 0 100%); transform: scaleY(-1); background: #00000060; padding: 0.9rem 2.4rem 1rem; text-align: center; margin-top: 0;">
                                        <span style="display: inline-block; font-family: 'Prata', serif; font-size: 1.95rem; color: rgb(255, 255, 255); transform: scaleY(-1);">Packages</span>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem;">
                                        <?php foreach ($packageSections as $switchSection): ?>
                                            <?php $isActivePackageSwitch = $section['key'] === $switchSection['key']; ?>
                                            <button
                                                type="button"
                                                data-package-switch="{{ $switchSection['key'] }}"
                                                aria-pressed="{{ $isActivePackageSwitch ? 'true' : 'false' }}"
                                                style="border: 1px solid {{ $isActivePackageSwitch ? '#ffffff' : 'rgba(255,255,255,0.7)' }}; border-radius: 999px; background: {{ $isActivePackageSwitch ? '#ffffff' : 'rgba(255,255,255,0.14)' }}; padding: 0.68rem 1.2rem; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: {{ $isActivePackageSwitch ? '#1f4da2' : '#ffffff' }}; box-shadow: {{ $isActivePackageSwitch ? '0 10px 22px rgba(15,23,42,0.14)' : 'none' }}; cursor: pointer; transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;"
                                            >
                                                {{ $switchSection['title'] }}
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 0;">
                                <h2 style="font-family: 'Oswald', sans-serif; font-size: 2.5rem; font-weight: 700; line-height: 0.95; letter-spacing: 0.02em; color: #fff;">
                                    {{ $section['title'] }}
                                </h2>
                                <p class="package-section-summary" style="margin-top: 0.45rem; max-width: 70rem; font-size: 1.3rem; line-height: 1.65; color: rgba(255,255,255,0.96);{{ in_array($section['key'], ['kk-beach', 'kundasang'], true) ? ' white-space: nowrap;' : '' }}">
                                    {{ $section['summary'] }}
                                </p>
                            </div>

                            <?php if ($visiblePackages->isNotEmpty()): ?>
                                <div class="package-carousel-shell" style="margin: 1.7rem auto 0; max-width: calc((390px * 3) + 5rem); padding: 0.8rem 1rem 1.2rem; overflow: visible;">
                                <div class="package-section-grid" data-package-grid="{{ $section['key'] }}" data-package-page-count="{{ $pageCount }}" style="display: flex; gap: 2.5rem; align-items: start; transition: transform 0.45s ease;">
                                    <?php foreach ($visiblePackages as $package): ?>
                                        <?php
                                            $locationTag = strtoupper(str_contains(strtolower($package->location), 'kundasang') ? 'Kundasang' : (str_contains(strtolower($package->location), 'marine') || str_contains(strtolower($package->location), 'island') ? 'Semporna' : 'Kota Kinabalu'));
                                            $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $package->duration));
                                            $discountBadge = $package->has_active_discount
                                                ? rtrim(rtrim(number_format((float) $package->discount_percentage, 2, '.', ''), '0'), '.').'% OFF'
                                                : null;
                                            $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                                            $originalPrice = (float) $package->malaysia_adult_price_myr;
                                            $packageRating = $package->package_review_average;
                                            $packageReviewCount = (int) ($package->package_review_count ?? 0);
                                        ?>
                                        <div class="package-section-card" data-package-card="{{ $section['key'] }}" style="display: flex; width: 390px; min-width: 390px; flex-direction: column; align-items: center;">
                                            <a href="{{ route('products.show', $package) }}" class="package-showcase-card" style="display: flex; width: 100%; max-width: 390px; min-height: 520px; flex-direction: column; overflow: hidden; border-radius: 1.6rem 1.6rem 0 0; background: #fff; text-decoration: none; box-shadow: 0 18px 30px rgba(15,23,42,0.22);">
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
                                                    @if ($discountBadge)
                                                        <span style="position: absolute; right: 0.7rem; top: 0.7rem; border-radius: 0.2rem; background: #ff1d0d; padding: 0.28rem 0.55rem; font-size: 0.56rem; font-weight: 700; text-transform: uppercase; color: #fff;">
                                                            {{ $discountBadge }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="package-card-copy" style="padding: 0.95rem 0.95rem 0.8rem;">
                                                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.8rem;">
                                                        <p style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; line-height: 1; color: #ff1d0d;">{{ $tripCode }}</p>
                                                        <div style="min-width: 84px; border-radius: 999px; background: rgba(255,255,255,0.92); padding: 0.42rem 0.7rem; text-align: right; box-shadow: 0 8px 18px rgba(15,23,42,0.08);">
                                                            @if ($packageRating !== null && $packageReviewCount > 0)
                                                                <div style="font-size: 1rem; font-weight: 700; line-height: 1; color: #f59e0b;">{{ number_format($packageRating, 1) }}/5</div>
                                                                <div style="margin-top: 0.22rem; font-size: 0.55rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #78716c;">{{ $packageReviewCount }} review{{ $packageReviewCount === 1 ? '' : 's' }}</div>
                                                            @else
                                                                <div style="font-size: 0.7rem; font-weight: 700; line-height: 1.1; color: #78716c;">No reviews</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <h3 class="package-card-title" style="margin-top: 0.35rem; font-family: 'Oswald', sans-serif; font-size: 1.65rem; font-weight: 700; line-height: 1.04; color: #1c2f7d;">
                                                        {{ strtoupper($package->name) }}
                                                    </h3>
                                                    <p class="package-card-description" style="margin-top: 0.45rem; flex: 1; min-height: 5.8rem; font-size: 0.86rem; line-height: 1.3; color: #111827;">
                                                        {{ \Illuminate\Support\Str::limit($package->description, 170) }}
                                                    </p>
                                                    <div style="margin-top: auto; padding-top: 0.7rem;">
                                                        <p style="margin: 0; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #ff1d0d;">Starting from</p>
                                                        @if ($package->has_active_discount)
                                                            <p style="margin: 0.15rem 0 0; font-size: 0.85rem; color: #78716c; text-decoration: line-through;">
                                                                <span class="currency-price" data-myr="{{ $originalPrice }}">{{ number_format($originalPrice, 2) }}</span>
                                                            </p>
                                                        @endif
                                                        <p style="margin: 0.1rem 0 0; font-size: 1rem; color: #111827;">
                                                            <span class="currency-price" data-myr="{{ $currentPrice }}" style="font-size: 1.5rem; font-weight: 700; color: #0f4fb5;">{{ number_format($currentPrice, 2) }}</span> Per Pax
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>

                                            <a href="{{ route('booking.create', ['product_id' => $package->id]) }}" class="package-book-button" style="margin-top: 0.65rem; display: inline-flex; min-width: 170px; align-items: center; justify-content: center; border-radius: 999px; background: #ff1d0d; padding: 0.7rem 1.6rem; font-family: 'Oswald', sans-serif; font-size: 1rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #fff; text-decoration: none; box-shadow: 0 12px 18px rgba(0,0,0,0.18);">
                                                Book Now
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                </div>
                            <?php else: ?>
                                <div style="margin-top: 1.7rem; background: rgba(255,255,255,0.88); padding: 1.5rem 1.75rem; color: #1f2937;">
                                    Packages for this destination will show here once they are added.
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
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
                    <a href="{{ route('products.show', $package) }}" class="block overflow-hidden rounded-3xl border border-stone-200 bg-stone-50 transition hover:-translate-y-1 hover:shadow-lg">
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
                            <p class="mt-4 text-sm leading-6 text-stone-600">{{ $package->description }}</p>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-sm text-stone-500">Package rate</span>
                                <div class="text-right">
                                    <?php if ($package->has_active_discount): ?>
                                        <div class="text-xs text-stone-400 line-through">
                                            <span class="currency-price" data-myr="{{ $originalPrice }}">{{ number_format($originalPrice, 2) }}</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-lg font-semibold text-stone-900">
                                        <span class="currency-price" data-myr="{{ $currentPrice }}">{{ number_format($currentPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="testimonials" class="mt-[4.5rem] home-screen-section px-6 pb-8 pt-6 md:mt-24 md:px-8 md:pb-12 md:pt-8">
            <div class="mx-auto rounded-[2rem] border border-white/70 bg-white/90 px-4 pb-8 pt-4 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur md:px-4 md:pb-10 md:pt-7" style="max-width: 1920px;">
            <div class="-mt-1 flex flex-col items-center gap-3 text-center">
                <div>
                    <h2 class="font-['Oswald'] text-4xl font-bold uppercase tracking-[0.22em] text-stone-900 md:text-5xl">Customer reviews</h2>
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    @if (($websiteReviewStats['reviews_count'] ?? 0) > 0 && !is_null($websiteReviewStats['average_rating'] ?? null))
                        <div class="rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                            {{ number_format((float) $websiteReviewStats['average_rating'], 1) }}/5 from {{ $websiteReviewStats['reviews_count'] }} review{{ $websiteReviewStats['reviews_count'] === 1 ? '' : 's' }}
                        </div>
                    @endif
                </div>
            </div>
            <section class="mt-4 rounded-[1.75rem] border border-sky-200 bg-sky-50/50 p-4 shadow-sm">
                <div class="text-center">
                    <p class="text-sm uppercase tracking-[0.28em] text-sky-600">Reviews</p>
                    <h3 class="mt-2 font-['Prata'] text-2xl text-stone-900">What travellers are saying</h3>
                </div>
                <div class="mt-5">
                    @if ($websiteReviews->isNotEmpty())
                        <div class="reviews-carousel-shell">
                            <div class="reviews-carousel-track">
                                @foreach ($websiteReviews as $review)
                                    <div class="reviews-carousel-slide">
                                        @include('partials.public-review-card', ['review' => $review])
                                    </div>
                                @endforeach
                                @foreach ($websiteReviews as $review)
                                    <div class="reviews-carousel-slide" aria-hidden="true">
                                        @include('partials.public-review-card', ['review' => $review])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="rounded-3xl border border-dashed border-sky-200 bg-white/80 p-5 text-sm text-stone-600">
                            No website customer reviews are available yet.
                        </div>
                    @endif
                </div>
            </section>
            </div>
        </section>

        <div class="h-12 md:h-16"></div>
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

            let activePopularIndex = 0;

            const updatePopularButtons = () => {
                if (window.innerWidth >= 768) {
                    popularPrevButton.disabled = false;
                    popularNextButton.disabled = false;
                    popularPrevButton.style.opacity = '1';
                    popularNextButton.style.opacity = '1';
                    popularPrevButton.style.cursor = 'pointer';
                    popularNextButton.style.cursor = 'pointer';
                    return;
                }

                const isAtStart = activePopularIndex <= 0;
                const isAtEnd = activePopularIndex >= popularCards.length - 1;

                popularPrevButton.disabled = isAtStart;
                popularNextButton.disabled = isAtEnd;
                popularPrevButton.style.opacity = isAtStart ? '0.45' : '1';
                popularNextButton.style.opacity = isAtEnd ? '0.45' : '1';
                popularPrevButton.style.cursor = isAtStart ? 'not-allowed' : 'pointer';
                popularNextButton.style.cursor = isAtEnd ? 'not-allowed' : 'pointer';
            };

            const renderPopularSlide = (nextIndex) => {
                if (window.innerWidth >= 768) {
                    activePopularIndex = 0;
                    popularTrack.style.transform = 'translateX(0)';
                    updatePopularButtons();
                    return;
                }

                activePopularIndex = Math.max(0, Math.min(nextIndex, popularCards.length - 1));
                popularTrack.style.transform = `translateX(-${activePopularIndex * 100}%)`;
                updatePopularButtons();
            };

            popularPrevButton.addEventListener('click', () => {
                if (activePopularIndex <= 0) {
                    return;
                }

                renderPopularSlide(activePopularIndex - 1);
            });

            popularNextButton.addEventListener('click', () => {
                if (activePopularIndex >= popularCards.length - 1) {
                    return;
                }

                renderPopularSlide(activePopularIndex + 1);
            });

            window.addEventListener('resize', () => {
                renderPopularSlide(activePopularIndex);
            });

            renderPopularSlide(0);
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

            const pastPromoPosters = Array.from(document.querySelectorAll('[data-past-promo-poster]'));

            const applyPastPromoShape = (image) => {
                if (!image.naturalWidth || !image.naturalHeight) {
                    return;
                }

                const orientation = image.closest('[data-past-promo-trigger]')?.dataset.promoOrientation || '';
                const isLandscape = orientation
                    ? orientation === 'landscape'
                    : image.naturalWidth >= image.naturalHeight;
                const card = image.closest('[data-past-promo-card]');
                const trigger = image.closest('[data-past-promo-trigger]');

                if (card) {
                    card.style.marginTop = isLandscape ? '6.35rem' : '-2.4rem';
                    card.style.marginLeft = '0';
                    card.style.marginRight = '0';
                    card.style.width = isLandscape ? '20rem' : 'min(100%, 21rem)';
                    card.style.flex = isLandscape ? '0 0 16.75rem' : '0 0 21rem';
                    card.style.padding = '0.18rem';
                }

                if (trigger) {
                    trigger.style.maxWidth = isLandscape ? '16.1rem' : '100%';
                    trigger.style.padding = isLandscape ? '0.08rem' : '0.15rem';
                }

                image.style.maxHeight = isLandscape ? '22rem' : '40rem';
                image.style.height = isLandscape ? '22rem' : 'auto';
                image.style.objectFit = isLandscape ? 'cover' : 'contain';
            };

            pastPromoPosters.forEach((image) => {
                if (image.complete) {
                    applyPastPromoShape(image);
                } else {
                    image.addEventListener('load', () => applyPastPromoShape(image), { once: true });
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const promoModal = document.getElementById('promo-poster-modal');
            const promoModalPanel = document.getElementById('promo-poster-modal-panel');
            const promoModalClose = document.getElementById('promo-poster-modal-close');
            const promoModalImage = document.getElementById('promo-poster-modal-image');
            const promoModalFallback = document.getElementById('promo-poster-modal-fallback');
            const promoModalFallbackTitle = document.getElementById('promo-poster-modal-fallback-title');
            const promoModalDate = document.getElementById('promo-poster-modal-date');
            const promoModalTitle = document.getElementById('promo-poster-modal-title');
            const promoModalSummary = document.getElementById('promo-poster-modal-summary');
            const promoTriggers = Array.from(document.querySelectorAll('[data-promo-modal-trigger]'));

            if (
                !promoModal ||
                !promoModalPanel ||
                !promoModalClose ||
                !promoModalImage ||
                !promoModalFallback ||
                !promoModalFallbackTitle ||
                !promoModalDate ||
                !promoModalTitle ||
                !promoModalSummary ||
                !promoTriggers.length
            ) {
                return;
            }

            const closePromoModal = () => {
                promoModal.style.display = 'none';
                document.body.style.overflow = '';
            };

            const setPromoModalImageHeight = () => {
                if (!promoModalImage.naturalWidth || !promoModalImage.naturalHeight) {
                    promoModalImage.style.height = '520px';
                    promoModalImage.style.width = '100%';
                    return;
                }

                const isLandscape = promoModalImage.naturalWidth > promoModalImage.naturalHeight;

                promoModalImage.style.height = isLandscape ? '320px' : '520px';
                promoModalImage.style.width = '100%';
            };

            const openPromoModal = (trigger) => {
                const posterUrl = trigger.dataset.promoPoster || '';
                const title = trigger.dataset.promoTitle || 'Promotion';
                const summary = trigger.dataset.promoSummary || 'No description available yet.';
                const dateLabel = trigger.dataset.promoDate || '';

                promoModalDate.textContent = dateLabel;
                promoModalTitle.textContent = title;
                promoModalSummary.textContent = summary;
                promoModalImage.alt = title;
                promoModalFallbackTitle.textContent = title;

                if (posterUrl) {
                    promoModalImage.src = posterUrl;
                    promoModalImage.style.display = 'block';
                    promoModalImage.style.height = '520px';
                    promoModalImage.style.width = '100%';
                    promoModalFallback.style.display = 'none';

                    if (promoModalImage.complete) {
                        setPromoModalImageHeight();
                    } else {
                        promoModalImage.addEventListener('load', setPromoModalImageHeight, { once: true });
                    }
                } else {
                    promoModalImage.removeAttribute('src');
                    promoModalImage.style.display = 'none';
                    promoModalImage.style.height = '520px';
                    promoModalImage.style.width = '100%';
                    promoModalFallback.style.display = 'flex';
                }

                promoModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            promoTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    openPromoModal(trigger);
                });
            });

            promoModalClose.addEventListener('click', closePromoModal);

            promoModal.addEventListener('click', (event) => {
                if (event.target === promoModal) {
                    closePromoModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && promoModal.style.display === 'flex') {
                    closePromoModal();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sectionPanels = Array.from(document.querySelectorAll('[data-package-section]'));
            const switchButtons = Array.from(document.querySelectorAll('[data-package-switch]'));

            if (!sectionPanels.length || !switchButtons.length) {
                return;
            }

            const setActiveSection = (sectionKey) => {
                sectionPanels.forEach((panel) => {
                    panel.style.display = panel.dataset.packageSection === sectionKey ? 'block' : 'none';
                });

                switchButtons.forEach((button) => {
                    const isActive = button.dataset.packageSwitch === sectionKey;

                    button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    button.style.borderColor = isActive ? '#ffffff' : 'rgba(255,255,255,0.7)';
                    button.style.background = isActive ? '#ffffff' : 'rgba(255,255,255,0.14)';
                    button.style.color = isActive ? '#1f4da2' : '#ffffff';
                    button.style.boxShadow = isActive ? '0 10px 22px rgba(15,23,42,0.14)' : 'none';
                    button.style.transform = 'translateY(0)';
                });

                window.dispatchEvent(new Event('resize'));
            };

            switchButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    setActiveSection(button.dataset.packageSwitch || 'kundasang');
                });

                button.addEventListener('mouseenter', () => {
                    if (button.getAttribute('aria-pressed') !== 'true') {
                        button.style.transform = 'translateY(-2px)';
                    }
                });

                button.addEventListener('mouseleave', () => {
                    button.style.transform = 'translateY(0)';
                });
            });

            const initialButton = switchButtons.find((button) => button.getAttribute('aria-pressed') === 'true') ?? switchButtons[0];
            setActiveSection(initialButton?.dataset.packageSwitch || 'kundasang');
        });
    </script>
    </div>
</x-layouts.app>
