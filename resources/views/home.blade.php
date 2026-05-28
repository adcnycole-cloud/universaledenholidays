<x-layouts.app title="Universal Eden Holidays | Sabah Packages and Transport">
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

        .promo-current-layout {
            display: flex;
            width: fit-content;
            align-items: stretch;
        }

        .promo-inline-card {
            position: relative;
        }

        .promo-inline-info-shell {
            position: relative;
        }

        .promo-more-info-button {
            position: absolute;
        }

        .promo-cards-row {
            display: flex;
            flex-wrap: nowrap;
            align-items: flex-start;
            justify-content: center;
            gap: 0.45rem;
            transition: gap 0.28s ease;
        }

        .promo-card-column {
            min-width: 0;
            flex-shrink: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .promo-card-column--current {
            width: min(100%, 470px);
            flex-basis: 470px;
        }

        .promo-card-column--past {
            width: min(100%, 470px);
            flex-basis: 470px;
        }

        .promo-book-shell {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            width: min(100%, 1760px);
            margin: 0 auto;
            background: transparent;
            box-shadow: none;
        }

        .promo-book-shell::after {
            content: "";
            position: absolute;
            left: 4.5rem;
            right: 4.5rem;
            bottom: -0.2rem;
            height: 2.9rem;
            border-radius: 999px;
            background: radial-gradient(ellipse at center, rgba(15,23,42,0.18) 0%, rgba(15,23,42,0.1) 38%, rgba(15,23,42,0) 76%);
            filter: blur(16px);
            opacity: 0.62;
            pointer-events: none;
            z-index: 0;
        }

        .promo-book-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.2rem 1.25rem 0;
        }

        .promo-book-nav {
            display: inline-flex;
            height: 3rem;
            width: 3rem;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(15,23,42,0.1);
            font-size: 2rem;
            font-weight: 300;
            line-height: 1;
            color: #7b93c8;
            cursor: pointer;
            transition: transform 0.22s ease, box-shadow 0.22s ease, opacity 0.22s ease;
        }

        .promo-book-nav:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(15,23,42,0.14);
        }

        .promo-book-status {
            min-width: 0;
            flex: 1;
            text-align: center;
        }

        .promo-book-mobile {
            display: none;
        }

        .promo-book-desktop {
            display: block;
            padding: 1.1rem 3rem;
            position: relative;
            z-index: 1;
        }

        .promo-book-cover {
            position: absolute;
            inset: 0.9rem 2.35rem 0.9rem;
            border-radius: 0.85rem;
            background:
                linear-gradient(145deg, #405735 0%, #5f7f49 34%, #7b9860 56%, #33492b 100%);
            box-shadow:
                inset 0 0 0 1px rgba(248,250,252,0.14),
                inset 0 1px 0 rgba(255,255,255,0.14),
                inset 1.1rem 0 1.8rem rgba(24,43,20,0.28),
                inset -0.9rem 0 1.5rem rgba(24,42,20,0.24),
                0 20px 34px rgba(15,23,42,0.16),
                0 34px 48px rgba(15,23,42,0.08);
            pointer-events: none;
            z-index: 0;
        }

        .promo-book-cover::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: calc(50% - 0.45rem);
            width: 0.9rem;
            background:
                linear-gradient(90deg, rgba(27,47,22,0.96), rgba(97,126,72,0.78) 42%, rgba(25,43,20,0.96));
            box-shadow:
                inset 1px 0 0 rgba(255,255,255,0.1),
                inset -1px 0 0 rgba(0,0,0,0.2);
        }

        .promo-book-cover::after {
            content: "";
            position: absolute;
            inset: 0.65rem;
            border: 1px solid rgba(255,255,255,0.08);
            opacity: 0.7;
            pointer-events: none;
        }

        .promo-book-spread {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 0;
            height: 36rem;
            min-height: 36rem;
            overflow: hidden;
            border-radius: 0.7rem;
            margin: 0.7rem;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.99), rgba(247,244,237,0.98));
            box-shadow:
                inset 0 0 0 1px rgba(231,229,228,0.9),
                inset 0 18px 28px rgba(255,255,255,0.4),
                inset 0 -10px 18px rgba(120,113,108,0.04),
                0 16px 26px rgba(15,23,42,0.1),
                0 30px 42px rgba(15,23,42,0.06);
            position: relative;
            z-index: 1;
            perspective: 2400px;
            transform-style: preserve-3d;
        }

        .promo-book-spread.is-turning .promo-book-page--info {
            opacity: 0;
        }

        .promo-book-spread.is-updating .promo-book-page--poster,
        .promo-book-spread.is-updating .promo-book-page--info {
            opacity: 0.58;
            transform: translateY(0.35rem);
        }

        .promo-book-turn-zone {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4.75rem;
            border: none;
            background: transparent;
            cursor: pointer;
            z-index: 4;
        }

        .promo-book-turn-zone--prev {
            left: 0;
        }

        .promo-book-turn-zone--next {
            right: 0;
        }

        .promo-book-turn-zone::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
        }

        .promo-book-turn-zone--prev::before {
            left: 0;
            background: linear-gradient(90deg, rgba(49,95,189,0.08), rgba(49,95,189,0));
        }

        .promo-book-turn-zone--next::before {
            right: 0;
            background: linear-gradient(270deg, rgba(49,95,189,0.08), rgba(49,95,189,0));
        }

        .promo-book-turn-zone:hover::before {
            opacity: 1;
        }

        .promo-book-page-corner {
            position: absolute;
            right: 0.85rem;
            bottom: 0.8rem;
            z-index: 4;
            height: 0.95rem;
            width: 0.95rem;
            background: linear-gradient(135deg, rgba(255,255,255,1) 0 50%, rgba(228,232,236,0.98) 50% 100%);
            box-shadow:
                -1px -1px 0 rgba(255,255,255,0.95),
                -3px -3px 8px rgba(15,23,42,0.05);
            pointer-events: none;
            clip-path: polygon(100% 0, 0 100%, 100% 100%);
            opacity: 0.72;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .promo-book-spread:hover .promo-book-page-corner {
            transform: translate(-1px, -1px);
            opacity: 1;
        }

        .promo-book-page {
            min-width: 0;
            height: 100%;
        }

        .promo-book-page--poster {
            display: flex;
            align-items: stretch;
            justify-content: center;
            border: none;
            border-right: 1px solid rgba(214,211,209,0.5);
            background: linear-gradient(180deg, #fffefb, #fcfaf6);
            padding: 0;
            text-align: left;
            cursor: pointer;
            position: relative;
            z-index: 1;
            transition: opacity 0.22s ease, transform 0.22s ease;
        }

        .promo-book-page--poster::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 0.9rem;
            background: linear-gradient(90deg, rgba(15,23,42,0), rgba(15,23,42,0.055));
            pointer-events: none;
        }

        .promo-book-page--poster::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15,23,42,0.02), rgba(15,23,42,0.22));
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.56s ease;
        }

        .promo-book-page--poster img {
            display: block;
            height: auto;
            width: 100%;
            max-height: 36rem;
            max-width: 100%;
            border-radius: 0;
            object-fit: contain;
            background: #fff;
            box-shadow: 0 10px 18px rgba(15,23,42,0.05);
        }

        .promo-book-page--poster.is-landscape {
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            top: -3px;
        }

        .promo-book-page--poster.is-landscape img {
            height: auto;
            width: 100%;
            max-height: none;
            max-width: 100%;
            object-fit: contain;
            object-position: center;
        }

        .promo-book-page--poster.is-portrait {
            align-items: flex-start;
            align-self: start;
            padding-top: 0;
            padding-bottom: 0;
        }

        .promo-book-page--poster.is-portrait img {
            height: auto !important;
            max-height: 36rem !important;
            width: auto !important;
            max-width: none;
            border-radius: 0;
            object-fit: contain;
            box-shadow: 0 16px 34px rgba(15,23,42,0.12);
        }

        .promo-book-page--info {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 2rem 2.2rem;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.99), rgba(250,247,240,0.98)),
                radial-gradient(circle at top right, rgba(203,213,225,0.22), transparent 36%);
            position: relative;
            z-index: 1;
            transition: opacity 0.18s ease;
            transition: opacity 0.22s ease, transform 0.22s ease;
        }

        .promo-book-page--info::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 0.9rem;
            background: linear-gradient(90deg, rgba(15,23,42,0.055), rgba(15,23,42,0));
            pointer-events: none;
        }

        .promo-book-turn-sheet {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 50%;
            transform-origin: left bottom;
            transform-style: preserve-3d;
            pointer-events: none;
            z-index: 5;
            opacity: 0;
            transform: perspective(1800px) rotateY(0deg);
        }

        .promo-book-turn-sheet.is-active {
            opacity: 1;
        }

        .promo-book-turn-sheet.is-flipping {
            transform: perspective(2000px) rotateY(-180deg);
        }

        .promo-book-turn-face {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            overflow: hidden;
            background: linear-gradient(90deg, #e1ddd8 0%, #fffbf6 12%, #ffffff 100%);
            box-shadow:
                inset 0 -1px 2px rgba(50,50,50,0.08),
                inset -1px 0 1px rgba(150,150,150,0.16),
                0 16px 30px rgba(15,23,42,0.12);
        }

        .promo-book-turn-face--front {
            transform: rotateY(0deg);
        }

        .promo-book-turn-face--front::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 1rem;
            background: linear-gradient(90deg, rgba(15,23,42,0.1), rgba(15,23,42,0));
            pointer-events: none;
        }

        .promo-book-turn-face--back {
            transform: rotateY(180deg);
            background: #ffffff;
        }

        .promo-book-turn-face--back::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 1rem;
            background: linear-gradient(270deg, rgba(15,23,42,0.12), rgba(15,23,42,0));
            pointer-events: none;
        }

        .promo-book-turn-info {
            height: 100%;
            padding: 2rem 2.2rem;
        }

        .promo-book-turn-poster {
            display: flex;
            height: 100%;
            align-items: stretch;
            justify-content: center;
            background: #ffffff;
        }

        .promo-book-turn-poster img {
            display: block;
            height: auto;
            width: 100%;
            max-height: 36rem;
            max-width: 100%;
            object-fit: contain;
            background: #fff;
        }

        .promo-book-turn-poster.is-landscape {
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            top: -3px;
        }

        .promo-book-turn-poster.is-portrait {
            align-items: flex-start;
            padding-top: 0;
            padding-bottom: 0;
        }

        .promo-book-turn-poster.is-portrait img {
            height: auto !important;
            max-height: 36rem !important;
            width: auto !important;
            max-width: none;
            object-fit: contain;
            box-shadow: 0 16px 34px rgba(15,23,42,0.12);
        }

        .promo-book-turn-sheet {
            display: none;
        }

        .promo-book-mobile-card {
            position: relative;
            perspective: 1400px;
        }

        .promo-book-mobile-inner {
            position: relative;
            min-height: 28rem;
            transform-style: preserve-3d;
            transition: transform 0.7s ease;
        }

        .promo-book-mobile-card.is-flipped .promo-book-mobile-inner {
            transform: rotateY(180deg);
        }

        .promo-book-face {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            overflow: hidden;
            border-radius: 0;
            background: #fff;
            box-shadow: 0 18px 36px rgba(15,23,42,0.1);
        }

        .promo-book-face--front {
            background: linear-gradient(145deg, #4f6f42 0%, #709458 46%, #36502f 100%);
        }

        .promo-book-face--front.is-portrait > div {
            padding-top: 0.8rem !important;
            padding-bottom: 0.8rem !important;
            background: linear-gradient(145deg, #4f6f42 0%, #709458 46%, #36502f 100%) !important;
        }

        .promo-book-face--front.is-portrait img {
            height: 98% !important;
            object-fit: contain !important;
        }

        .promo-book-face--back {
            transform: rotateY(180deg);
            background:
                linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.98)),
                radial-gradient(circle at top right, rgba(191,219,254,0.35), transparent 34%);
            overflow-y: auto;
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
            scroll-margin-top: calc(var(--home-header-offset, 0px) + 6rem + 10px);
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
                padding: 1rem 1.4rem 1.5rem !important;
            }

            .transport-copy {
                margin-left: 0 !important;
                max-width: 100% !important;
                padding: 0 0.15rem !important;
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

            .promo-book-toolbar {
                padding: 0.9rem 0.9rem 0 !important;
            }

            .promo-book-nav {
                height: 2.7rem !important;
                width: 2.7rem !important;
                font-size: 2rem !important;
            }

            .promo-book-desktop {
                display: none !important;
            }

            .promo-book-mobile {
                display: block !important;
                padding: 0.9rem 0.9rem 1rem !important;
            }

            .promo-book-mobile-inner {
                min-height: 26rem !important;
            }
        }

        @media (min-width: 768px) {
            .promo-book-nav {
                display: none !important;
            }

            .promo-book-toolbar {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .promo-book-status {
                flex: 0 1 auto;
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

    <main class="relative mx-auto flex max-w-7xl flex-col gap-14 rounded-[2.5rem] bg-[#e6eee8] px-6 pb-20 pt-0 lg:px-10 lg:max-w-[1900px]" style="overflow-x: clip;">
        <div class="pointer-events-none absolute inset-x-0 top-8 -z-10 h-72 rounded-[3rem] bg-[radial-gradient(circle_at_top_left,_rgba(134,239,172,0.16),_transparent_38%),radial-gradient(circle_at_top_right,_rgba(190,242,100,0.14),_transparent_34%)]"></div>

        <div>
            <section id="promos" class="bg-white px-5 pt-14 pb-24 md:px-7 md:pt-20 md:pb-28 lg:px-8" style="margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);">
                @php
                    $currentPromoSlide = $currentPromo ? [
                        'title' => $currentPromo->title,
                        'summary' => $currentPromo->summary,
                        'poster_url' => $currentPromo->poster_url,
                        'promo_label' => $currentPromo->promo_label ?: 'Discover Sabah',
                        'date_label' => $currentPromo->ends_at ? 'Until '.$currentPromo->ends_at->format('d M Y') : null,
                        'range_label' => ($currentPromo->starts_at?->format('d M Y') ?: 'Available now').' - '.($currentPromo->ends_at?->format('d M Y') ?: 'While active'),
                        'status' => 'Current Promotion',
                    ] : null;

                    $pastPromoSlides = $pastPromos->map(function ($promo) {
                        return [
                            'title' => $promo->title,
                            'summary' => $promo->summary,
                            'poster_url' => $promo->poster_url,
                            'promo_label' => $promo->promo_label ?: 'Discover Sabah',
                            'date_label' => $promo->ends_at ? 'Ended '.$promo->ends_at->format('d M Y') : null,
                            'range_label' => ($promo->starts_at?->format('d M Y') ?: 'Available now').' - '.($promo->ends_at?->format('d M Y') ?: 'While active'),
                            'status' => 'Past Promotion',
                        ];
                    })->values();

                    $promoBookSlides = collect();

                    if ($currentPromoSlide) {
                        $promoBookSlides->push($currentPromoSlide);
                    }

                    $promoBookSlides = $promoBookSlides->concat($pastPromoSlides)->values();
                    $initialPromoBookSlide = $promoBookSlides->first();
                @endphp

                <div class="relative mx-auto pt-6 md:pt-8" style="max-width: 1920px; height: 780px;">
                    <h2 class="mb-2 text-center font-['Oswald'] text-4xl font-bold uppercase tracking-[0.22em] text-[#315fbd] md:mb-3 md:text-5xl lg:text-6xl">
                        Promotion & News
                    </h2>
                    @if ($initialPromoBookSlide)
                            <div id="promo-book-shell" class="promo-book-shell" style="margin-bottom: 3rem;">
                                <div class="promo-book-toolbar">
                                    <button id="promo-book-prev" type="button" class="promo-book-nav" aria-label="Show previous promo offer">&lsaquo;</button>
                                    <div class="promo-book-status">
                                        <p id="promo-book-count" class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">1 / {{ $promoBookSlides->count() }}</p>
                                    </div>
                                    <button id="promo-book-next" type="button" class="promo-book-nav" aria-label="Show next promo offer">&rsaquo;</button>
                                </div>

                                <div class="promo-book-mobile">
                                    <div id="promo-mobile-card" class="promo-book-mobile-card">
                                        <div id="promo-mobile-inner" class="promo-book-mobile-inner">
                                            <div class="promo-book-face promo-book-face--front">
                                                <div style="position: relative; height: 100%; background: linear-gradient(145deg, #4f6f42 0%, #709458 46%, #36502f 100%); padding: 0.95rem;">
                                                    <img id="promo-mobile-image" src="{{ $initialPromoBookSlide['poster_url'] }}" alt="{{ $initialPromoBookSlide['title'] }}" style="display: block; height: 100%; width: 100%; border-radius: 0; object-fit: cover; background: #fff;">
                                                    <div style="position: absolute; left: 1.6rem; top: 1.55rem; right: 1.6rem; display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                                                        <span id="promo-mobile-label" style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.55rem 0.8rem; font-size: 0.64rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #315fbd;">{{ $initialPromoBookSlide['promo_label'] }}</span>
                                                        <span id="promo-mobile-date" style="border-radius: 999px; background: rgba(255,255,255,0.94); padding: 0.55rem 0.8rem; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #b45309;">{{ $initialPromoBookSlide['date_label'] }}</span>
                                                    </div>
                                                    <div style="position: absolute; left: 1.6rem; right: 1.6rem; bottom: 1.55rem; border-radius: 0; background: linear-gradient(180deg, rgba(15,23,42,0), rgba(15,23,42,0.72)); padding: 2.3rem 1rem 1rem;">
                                                        <p id="promo-mobile-status-front" style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.18em; color: #bfdbfe;">{{ $initialPromoBookSlide['status'] }}</p>
                                                        <h3 id="promo-mobile-title-front" style="margin: 0.45rem 0 0; font-size: 1.6rem; font-weight: 700; line-height: 1.08; color: #fff;">{{ $initialPromoBookSlide['title'] }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="promo-book-face promo-book-face--back">
                                                <div style="display: flex; height: 100%; flex-direction: column; padding: 1.35rem 1.2rem 1.2rem;">
                                                    <p id="promo-mobile-status-back" style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.18em; color: #315fbd;">{{ $initialPromoBookSlide['status'] }}</p>
                                                    <h3 id="promo-mobile-title-back" style="margin: 0.7rem 0 0; font-size: 1.65rem; font-weight: 700; line-height: 1.08; color: #1c1917;">{{ $initialPromoBookSlide['title'] }}</h3>
                                                    <div style="margin-top: 0.8rem; display: flex; flex-wrap: wrap; gap: 0.4rem; font-size: 0.63rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em; color: #a16207;">
                                                        <span id="promo-mobile-range">{{ $initialPromoBookSlide['range_label'] }}</span>
                                                    </div>
                                                    <p id="promo-mobile-summary" style="margin-top: 1rem; flex: 1; font-size: 0.94rem; line-height: 1.72; color: #57534e;">{{ \Illuminate\Support\Str::limit($initialPromoBookSlide['summary'], 420) }}</p>
                                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.8rem;">
                                                        <button id="promo-mobile-flip-toggle" type="button" style="display: inline-flex; min-width: 8rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: #315fbd; padding: 0.8rem 1rem; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #fff;">Back to Poster</button>
                                                        <button id="promo-mobile-view-poster" type="button" class="promo-poster-trigger" data-promo-title="{{ $initialPromoBookSlide['title'] }}" data-promo-summary="{{ $initialPromoBookSlide['summary'] }}" data-promo-poster="{{ $initialPromoBookSlide['poster_url'] }}" data-promo-label="{{ $initialPromoBookSlide['promo_label'] }}" data-promo-date="{{ $initialPromoBookSlide['date_label'] }}" data-promo-range="{{ $initialPromoBookSlide['range_label'] }}" data-promo-status="{{ $initialPromoBookSlide['status'] }}" style="display: inline-flex; min-width: 8rem; align-items: center; justify-content: center; border: 1px solid #cbd5e1; border-radius: 999px; background: #fff; padding: 0.8rem 1rem; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #475569;">Open Poster</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button id="promo-mobile-flip-front" type="button" style="margin-top: 0.5rem; display: inline-flex; width: 100%; align-items: center; justify-content: center; border: none; border-radius: 999px; background: #315fbd; padding: 0.85rem 1rem; font-size: 0.76rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #fff;">Flip for Details</button>
                                </div>

                                <div class="promo-book-desktop">
                                    <div class="promo-book-cover" aria-hidden="true"></div>
                                    <div class="promo-book-spread">
                                        <div id="promo-desktop-turn-sheet" class="promo-book-turn-sheet" aria-hidden="true">
                                            <div class="promo-book-turn-face promo-book-turn-face--front">
                                                <div id="promo-desktop-turn-info" class="promo-book-turn-info"></div>
                                            </div>
                                            <div class="promo-book-turn-face promo-book-turn-face--back">
                                                <div id="promo-desktop-turn-poster" class="promo-book-turn-poster"></div>
                                            </div>
                                        </div>
                                        <button id="promo-desktop-prev-zone" type="button" class="promo-book-turn-zone promo-book-turn-zone--prev" aria-label="Show previous promo offer"></button>
                                        <button id="promo-desktop-poster" type="button" class="promo-book-page promo-book-page--poster promo-poster-trigger" data-promo-title="{{ $initialPromoBookSlide['title'] }}" data-promo-summary="{{ $initialPromoBookSlide['summary'] }}" data-promo-poster="{{ $initialPromoBookSlide['poster_url'] }}" data-promo-label="{{ $initialPromoBookSlide['promo_label'] }}" data-promo-date="{{ $initialPromoBookSlide['date_label'] }}" data-promo-range="{{ $initialPromoBookSlide['range_label'] }}" data-promo-status="{{ $initialPromoBookSlide['status'] }}">
                                            <img id="promo-desktop-image" src="{{ $initialPromoBookSlide['poster_url'] }}" alt="{{ $initialPromoBookSlide['title'] }}">
                                        </button>
                                        <div class="promo-book-page promo-book-page--info">
                                            <p id="promo-desktop-status" style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.2em; color: #315fbd;">{{ $initialPromoBookSlide['status'] }}</p>
                                            <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.45rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em; color: #b45309;">
                                                <span id="promo-desktop-label" style="border-radius: 999px; background: #eff6ff; padding: 0.45rem 0.75rem; color: #315fbd;">{{ $initialPromoBookSlide['promo_label'] }}</span>
                                                <span id="promo-desktop-date" style="border-radius: 999px; background: #fff7ed; padding: 0.45rem 0.75rem; color: #b45309;">{{ $initialPromoBookSlide['date_label'] }}</span>
                                            </div>
                                            <h3 id="promo-desktop-title" style="margin: 1.15rem 0 0; font-size: clamp(2rem, 2.4vw, 2.8rem); font-weight: 700; line-height: 1.04; color: #1c1917;">{{ $initialPromoBookSlide['title'] }}</h3>
                                            <p id="promo-desktop-summary" style="margin: 1.15rem 0 0; font-size: 1rem; line-height: 1.85; color: #57534e;">{{ \Illuminate\Support\Str::limit($initialPromoBookSlide['summary'], 620) }}</p>
                                            <div style="margin-top: 1.5rem; padding-top: 0;">
                                                <p style="margin: 0; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.18em; color: #94a3b8;">Offer Window</p>
                                                <p id="promo-desktop-range" style="margin: 0.55rem 0 0; font-size: 0.95rem; line-height: 1.7; color: #57534e;">{{ $initialPromoBookSlide['range_label'] }}</p>
                                            </div>
                                        </div>
                                        <span class="promo-book-page-corner" aria-hidden="true"></span>
                                        <button id="promo-desktop-next-zone" type="button" class="promo-book-turn-zone promo-book-turn-zone--next" aria-label="Show next promo offer"></button>
                                    </div>
                                </div>
                            </div>
                    @else
                        <div style="margin-top: 1.25rem; border-radius: 1.25rem; border: 1px dashed rgb(214 211 209); background: rgb(250 250 249); padding: 2.5rem 1.25rem; text-align: center; font-size: 0.875rem; line-height: 1.5rem; color: rgb(87 83 78);">
                            No promotion is available yet.
                        </div>
                    @endif
                </div>
            </section>

            <div
                id="promo-detail-modal"
                style="position: fixed; inset: 0; z-index: 80; display: none; align-items: flex-start; justify-content: center; background: rgba(15,23,42,0.72); padding: 4.5rem 2rem 2rem;"
            >
                <div
                    id="promo-detail-panel"
                    style="position: relative; width: min(1120px, 100%); height: min(760px, 88vh); margin-top: 2.25rem; border-radius: 1.5rem; background: #fff; box-shadow: 0 24px 60px rgba(15,23,42,0.24); overflow: hidden;"
                >
                    <button
                        id="promo-detail-close"
                        type="button"
                        style="position: absolute; right: 1rem; top: 1rem; z-index: 2; display: inline-flex; height: 2.75rem; width: 2.75rem; align-items: center; justify-content: center; border: none; border-radius: 999px; background: rgba(255,255,255,0.96); box-shadow: 0 8px 18px rgba(15,23,42,0.12); font-size: 1.5rem; color: #52627f; cursor: pointer;"
                        aria-label="Close promotion details"
                    >&times;</button>

                    <div class="promo-detail-layout" style="display: grid; height: 100%; gap: 0; grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);">
                        <div class="promo-detail-media" style="display: flex; height: 100%; align-items: center; justify-content: center; background: #fff; padding: 1.5rem;">
                            <img id="promo-detail-image" src="" alt="" style="display: block; width: 100%; height: 100%; max-height: 680px; object-fit: contain; background: #fff;">
                        </div>
                        <div style="height: 100%; overflow-y: auto; padding: 1.75rem 1.75rem 2rem;">
                            <p id="promo-detail-status" style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.16em; color: #2563eb;"></p>
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

        <section id="popular-picks" class="-mt-20 home-screen-section relative overflow-hidden md:overflow-visible px-6 pb-8 pt-0 md:px-8 md:pb-10 md:pt-0">

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

            <div class="relative mx-auto rounded-[2rem] bg-white px-5 py-3 md:overflow-visible md:px-8 md:py-7" style="max-width: 1920px;">
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

                <div class="mt-2 overflow-hidden px-2 py-6 md:overflow-visible md:py-10">
                    <div class="popular-picks-track flex flex-wrap justify-center" data-popular-picks-track style="gap: 2.5rem;">
                        @foreach ($popularPackages as $package)
                            @php
                                $locationTag = strtoupper(str_contains(strtolower($package->location), 'kundasang') ? 'Kundasang' : (str_contains(strtolower($package->location), 'kota belud') ? 'Kota Belud' : (str_contains(strtolower($package->location), 'ranau') ? 'Kundasang-Ranau' : 'Kota Kinabalu')));
                                $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $package->duration));
                                $discountBadge = $package->has_active_discount
                                    ? rtrim(rtrim(number_format((float) $package->discount_percentage, 2, '.', ''), '0'), '.').'% OFF'
                                    : null;
                                $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                                $originalPrice = (float) $package->malaysia_adult_price_myr;
                                $packageRating = $package->package_review_average;
                                $packageReviewCount = (int) ($package->package_review_count ?? 0);
                            @endphp
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
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

<section
    class="home-screen-section"
    id="transport"
    style="position: relative; overflow: hidden; box-sizing: border-box; min-height: calc(100svh - var(--home-header-offset, 0px) + 40px); margin-top: -0.75rem; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);"
>
    @php
        $transportImageMap = [
            '41/44 Seaters Bus' => asset('images/44pax.png'),
            '17 Seaters Van' => asset('images/17pax.png'),
            '9/14 Seaters Van' => asset('images/14pax.png'),
        ];

        $transportOptions = $transportServices->map(function ($transport) use ($transportImageMap) {
            return [
                'label' => $transport->name,
                'name' => $transport->name,
                'image' => $transportImageMap[$transport->name] ?? $transport->image_url,
                'url' => route('products.show', $transport),
            ];
        })->values();

        $transportFeatures = [
            ['label' => 'HYGIENE', 'icon' => 'spark'],
            ['label' => 'SAFETY', 'icon' => 'shield'],
            ['label' => 'PROFESIONAL DRIVER', 'icon' => 'driver'],
            ['label' => 'LICENSED VAN/BUS PERSIARAN', 'icon' => 'license'],
        ];
    @endphp

    <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/transport.png') }}'); background-size: cover; background-position: center center; background-repeat: no-repeat;"></div>
    <div class="transport-shell" style="position: relative; z-index: 2; min-height: 100%; width: 100%; padding: 1.5rem 3rem calc(2.25rem + 20px);">

    <div style="display: flex; min-height: 100%; width: 100%; align-items: center; justify-content: flex-start;">

            <!-- LEFT SIDE -->
            <div class="transport-copy" style="position: relative; z-index: 10; width: 100%; max-width: 980px; flex-shrink: 0; margin-top: 3rem; margin-left: 6rem;">
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
                                <p class="package-section-summary" style="margin-top: 0.45rem; max-width: 70rem; font-size: 1.3rem; line-height: 1.65; color: rgba(255,255,255,0.96);{{ in_array($section['key'], ['kk-beach', 'kundasang'], true) ? ' white-space: nowrap;' : '' }}">
                                    {{ $section['summary'] }}
                                </p>
                            </div>

                            @if ($visiblePackages->isNotEmpty())
                                <div class="package-carousel-shell" style="margin: 1.7rem auto 0; max-width: calc((390px * 3) + 5rem); padding: 0.8rem 1rem 1.2rem; overflow: visible;">
                                <div class="package-section-grid" data-package-grid="{{ $section['key'] }}" data-package-page-count="{{ $pageCount }}" style="display: flex; gap: 2.5rem; align-items: start; transition: transform 0.45s ease;">
                                    @foreach ($visiblePackages as $package)
                                        @php
                                            $locationTag = strtoupper(str_contains(strtolower($package->location), 'kundasang') ? 'Kundasang' : (str_contains(strtolower($package->location), 'marine') || str_contains(strtolower($package->location), 'island') ? 'Semporna' : 'Kota Kinabalu'));
                                            $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $package->duration));
                                            $discountBadge = $package->has_active_discount
                                                ? rtrim(rtrim(number_format((float) $package->discount_percentage, 2, '.', ''), '0'), '.').'% OFF'
                                                : null;
                                            $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                                            $originalPrice = (float) $package->malaysia_adult_price_myr;
                                            $packageRating = $package->package_review_average;
                                            $packageReviewCount = (int) ($package->package_review_count ?? 0);
                                        @endphp
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
                    @php
                        $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                        $originalPrice = (float) $package->malaysia_adult_price_myr;
                    @endphp
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
                                <div class="text-right">
                                    @if ($package->has_active_discount)
                                        <div class="text-xs text-stone-400 line-through">
                                            <span class="currency-price" data-myr="{{ $originalPrice }}">{{ number_format($originalPrice, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="text-lg font-semibold text-stone-900">
                                        <span class="currency-price" data-myr="{{ $currentPrice }}">{{ number_format($currentPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section id="testimonials" class="home-screen-section rounded-[2rem] border border-white/70 bg-white/90 p-4 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="font-['Prata'] text-3xl text-stone-900">Customer reviews</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if (($googleReviewData['reviews_count'] ?? 0) > 0 && !is_null($googleReviewData['rating'] ?? null))
                        <a
                            href="{{ $googleReviewData['place_url'] }}"
                            target="_blank"
                            rel="noreferrer"
                            class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100"
                        >
                            Google {{ number_format((float) $googleReviewData['rating'], 1) }}/5 from {{ $googleReviewData['reviews_count'] }} review{{ $googleReviewData['reviews_count'] === 1 ? '' : 's' }}
                        </a>
                    @endif
                    @if (($websiteReviewStats['reviews_count'] ?? 0) > 0 && !is_null($websiteReviewStats['average_rating'] ?? null))
                        <div class="rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                            Website {{ number_format((float) $websiteReviewStats['average_rating'], 1) }}/5 from {{ $websiteReviewStats['reviews_count'] }} review{{ $websiteReviewStats['reviews_count'] === 1 ? '' : 's' }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-3 grid gap-4 md:grid-cols-2">
                <div class="space-y-3">
                    @if (!empty($googleReviewData['landscape_photo_url']))
                        <article class="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white shadow-sm">
                            <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="block">
                                <img
                                    src="{{ $googleReviewData['landscape_photo_url'] }}"
                                    alt="{{ $googleReviewData['place_name'] ?: 'Universal Eden Holidays' }}"
                                    class="h-56 w-full object-cover"
                                >
                            </a>
                            <div class="flex flex-wrap items-center justify-between gap-3 p-4">
                                <div>
                                    <p class="text-sm font-semibold text-stone-900">{{ $googleReviewData['place_name'] ?: 'Universal Eden Holidays' }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.16em] text-stone-500">Google business photo</p>
                                </div>
                                <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="text-sm font-semibold text-sky-700 transition hover:text-sky-800">
                                    Open on Google
                                </a>
                            </div>
                            @if (!empty($googleReviewData['landscape_photo_attribution']))
                                <div class="border-t border-stone-200 px-4 py-3 text-xs text-stone-500">
                                    Photo:
                                    @foreach ($googleReviewData['landscape_photo_attribution'] as $author)
                                        @if (!empty($author['uri']))
                                            <a href="{{ $author['uri'] }}" target="_blank" rel="noreferrer" class="font-medium text-stone-700 hover:text-sky-700">{{ $author['name'] }}</a>@if (! $loop->last), @endif
                                        @else
                                            <span class="font-medium text-stone-700">{{ $author['name'] }}</span>@if (! $loop->last), @endif
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endif

                    <section class="rounded-[1.75rem] border border-rose-200 bg-rose-50/60 p-4 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm uppercase tracking-[0.28em] text-rose-600">Google Reviews</p>
                                <h3 class="mt-2 font-['Prata'] text-2xl text-stone-900">Live from Google Maps</h3>
                            </div>
                            @if (!empty($googleReviewData['place_url']))
                                <a href="{{ $googleReviewData['place_url'] }}" target="_blank" rel="noreferrer" class="rounded-full border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                                    Open Google page
                                </a>
                            @endif
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse ($googleReviews as $review)
                                @include('partials.public-review-card', ['review' => $review])
                            @empty
                                <div class="rounded-3xl border border-dashed border-rose-200 bg-white/80 p-5 text-sm text-stone-600">
                                    Google reviews will appear here after the Google Places API key is connected and reviews are enabled.
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-[1.75rem] border border-sky-200 bg-sky-50/50 p-4 shadow-sm">
                        <div>
                            <p class="text-sm uppercase tracking-[0.28em] text-sky-600">Website Reviews</p>
                            <h3 class="mt-2 font-['Prata'] text-2xl text-stone-900">Customer reviews from this website</h3>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse ($websiteReviews as $review)
                                @include('partials.public-review-card', ['review' => $review])
                            @empty
                                <div class="rounded-3xl border border-dashed border-sky-200 bg-white/80 p-5 text-sm text-stone-600">
                                    No website customer reviews are available yet.
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

                <section class="rounded-[1.75rem] border border-stone-200 bg-stone-50/80 p-4 shadow-sm">
                    <p class="text-sm uppercase tracking-[0.28em] text-amber-600">Add Reviews</p>
                    <p class="mt-3 text-sm leading-6 text-stone-600">If you have travelled with Universal Eden Holidays before, you can leave a short review here. We will check it before showing it on the landing page.</p>

                    <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data" class="mt-4 space-y-3" data-form-persist="landing-testimonial-review">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="landing_testimonial_name" class="mb-2 block text-sm font-medium text-stone-700">Your name</label>
                                <input id="landing_testimonial_name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label for="landing_testimonial_email" class="mb-2 block text-sm font-medium text-stone-700">Gmail / Email</label>
                                <input id="landing_testimonial_email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="yourname@gmail.com" autocomplete="email" inputmode="email" spellcheck="false" required>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="landing_testimonial_location" class="mb-2 block text-sm font-medium text-stone-700">Your location</label>
                                <input id="landing_testimonial_location" name="location" type="text" value="{{ old('location') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" required>
                            </div>
                            <div>
                                <label for="landing_testimonial_profile_photo" class="mb-2 block text-sm font-medium text-stone-700">Upload image</label>
                                <input id="landing_testimonial_profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-2xl border border-dashed border-stone-300 bg-white px-4 py-3 text-stone-700">
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-[1fr_8rem]">
                            <div>
                                <label for="landing_testimonial_trip_name" class="mb-2 block text-sm font-medium text-stone-700">Trip or package name</label>
                                <input id="landing_testimonial_trip_name" name="trip_name" type="text" value="{{ old('trip_name') }}" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Example: Kundasang Day Tour" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-stone-700">Rating</label>
                                <div class="rounded-2xl border border-stone-300 bg-white px-3 py-3">
                                    <input id="landing_testimonial_rating" type="hidden" name="rating" value="{{ old('rating', 5) }}">
                                    <div class="flex items-center gap-1 text-2xl text-stone-300" data-star-rating data-target="landing_testimonial_rating" data-label="landing_testimonial_rating_label">
                                        @for ($rating = 1; $rating <= 5; $rating++)
                                            <button type="button" class="leading-none transition hover:scale-110" data-rating-value="{{ $rating }}" aria-label="Rate {{ $rating }} out of 5">&#9733;</button>
                                        @endfor
                                    </div>
                                    <p id="landing_testimonial_rating_label" class="mt-2 text-xs font-medium text-stone-500">{{ old('rating', 5) }}/5</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="landing_testimonial_quote" class="mb-2 block text-sm font-medium text-stone-700">Your review</label>
                            <textarea id="landing_testimonial_quote" name="quote" rows="5" class="w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-stone-800" placeholder="Tell future customers what your trip was like." required>{{ old('quote') }}</textarea>
                        </div>
                        <button type="submit" class="w-full rounded-full bg-amber-600 px-5 py-3 text-sm font-semibold uppercase tracking-[0.22em] text-white transition hover:bg-amber-700">
                            Submit Review
                        </button>
                    </form>
                </section>
            </div>
        </section>

        <section id="about-us" class="home-section-compact relative mb-16 overflow-hidden rounded-[2rem] border border-stone-200 bg-[linear-gradient(135deg,_#fffdf9,_#eff6ff_60%,_#ecfeff)] p-5 shadow-sm">
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
                        We help travelers explore Sabah with smoother planning, reliable transport, curated holiday packages, and practical booking support from the first enquiry to the final trip detail.
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

    <div class="h-12"></div>

    <footer class="border-t border-stone-200/80 bg-stone-950 text-stone-200">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 lg:grid-cols-[1.2fr_0.8fr_0.8fr_1fr] lg:px-10">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/10">
                    <div>
                        <p class="font-['Prata'] text-xl text-white">Universal Eden Holidays</p>
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
                    <a href="{{ route('bookings.track.form') }}" class="transition hover:text-white">Track Your Bookings</a>
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
            <div class="mx-auto flex max-w-7xl items-center justify-center px-6 py-5 text-center text-xs uppercase tracking-[0.22em] text-stone-500 lg:px-10">
                <p>Adcey &copy; Universal Eden Holidays - {{ now()->year }}</p>
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
            document.querySelectorAll('[data-star-rating]').forEach((ratingGroup) => {
                const target = document.getElementById(ratingGroup.dataset.target);
                const label = document.getElementById(ratingGroup.dataset.label);
                const buttons = Array.from(ratingGroup.querySelectorAll('[data-rating-value]'));

                if (!target || buttons.length === 0) {
                    return;
                }

                const render = (value) => {
                    buttons.forEach((button) => {
                        const active = Number(button.dataset.ratingValue) <= value;
                        button.style.color = active ? '#f59e0b' : '#d6d3d1';
                    });

                    if (label) {
                        label.textContent = `${value}/5`;
                    }
                };

                buttons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const value = Number(button.dataset.ratingValue);
                        target.value = value;
                        render(value);
                    });
                });

                render(Number(target.value || 5));
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
            const promoSlides = @json($promoBookSlides ?? []);
            const prevButton = document.getElementById('promo-book-prev');
            const nextButton = document.getElementById('promo-book-next');
            const statusLabel = document.getElementById('promo-book-status');
            const countLabel = document.getElementById('promo-book-count');
            const desktopSpread = document.querySelector('.promo-book-spread');
            const desktopTurnSheet = document.getElementById('promo-desktop-turn-sheet');
            const desktopTurnInfo = document.getElementById('promo-desktop-turn-info');
            const desktopTurnPoster = document.getElementById('promo-desktop-turn-poster');
            const desktopPrevZone = document.getElementById('promo-desktop-prev-zone');
            const desktopNextZone = document.getElementById('promo-desktop-next-zone');
            const desktopPoster = document.getElementById('promo-desktop-poster');
            const desktopInfoPage = document.querySelector('.promo-book-page--info');
            const desktopImage = document.getElementById('promo-desktop-image');
            const desktopStatus = document.getElementById('promo-desktop-status');
            const desktopLabel = document.getElementById('promo-desktop-label');
            const desktopDate = document.getElementById('promo-desktop-date');
            const desktopTitle = document.getElementById('promo-desktop-title');
            const desktopSummary = document.getElementById('promo-desktop-summary');
            const desktopRange = document.getElementById('promo-desktop-range');
            const mobileCard = document.getElementById('promo-mobile-card');
            const mobileFrontFace = mobileCard?.querySelector('.promo-book-face--front');
            const mobileImage = document.getElementById('promo-mobile-image');
            const mobileLabel = document.getElementById('promo-mobile-label');
            const mobileDate = document.getElementById('promo-mobile-date');
            const mobileStatusFront = document.getElementById('promo-mobile-status-front');
            const mobileTitleFront = document.getElementById('promo-mobile-title-front');
            const mobileStatusBack = document.getElementById('promo-mobile-status-back');
            const mobileTitleBack = document.getElementById('promo-mobile-title-back');
            const mobileRange = document.getElementById('promo-mobile-range');
            const mobileSummary = document.getElementById('promo-mobile-summary');
            const mobileFlipFront = document.getElementById('promo-mobile-flip-front');
            const mobileFlipBack = document.getElementById('promo-mobile-flip-toggle');
            const mobileViewPoster = document.getElementById('promo-mobile-view-poster');

            if (
                !promoSlides.length ||
                !prevButton ||
                !nextButton ||
                !countLabel ||
                !desktopTurnSheet ||
                !desktopTurnInfo ||
                !desktopTurnPoster ||
                !desktopPoster ||
                !desktopInfoPage ||
                !desktopImage ||
                !desktopStatus ||
                !desktopLabel ||
                !desktopDate ||
                !desktopTitle ||
                !desktopSummary ||
                !desktopRange ||
                !mobileCard ||
                !mobileImage ||
                !mobileLabel ||
                !mobileDate ||
                !mobileStatusFront ||
                !mobileTitleFront ||
                !mobileStatusBack ||
                !mobileTitleBack ||
                !mobileRange ||
                !mobileSummary ||
                !mobileFlipFront ||
                !mobileFlipBack ||
                !mobileViewPoster
            ) {
                return;
            }

            let activePromoIndex = 0;
            let desktopFlipStartTimeout = null;
            let desktopFlipTimeout = null;
            let desktopFlipResetTimeout = null;
            let isDesktopFlipping = false;

            const isDesktopPromoView = () => window.matchMedia('(min-width: 768px)').matches;

            const syncMobileCardHeight = () => {
                const mobileInner = document.getElementById('promo-mobile-inner');

                if (!mobileCard || !mobileInner || !mobileFrontFace) {
                    return;
                }

                const frontHeight = mobileFrontFace.scrollHeight;
                const nextHeight = Math.max(frontHeight, 380);

                mobileCard.style.minHeight = `${nextHeight}px`;
                mobileInner.style.minHeight = `${nextHeight}px`;
                mobileCard.style.height = `${nextHeight}px`;
                mobileInner.style.height = `${nextHeight}px`;
            };

            const syncTriggerData = (element, promo) => {
                element.dataset.promoTitle = promo.title ?? '';
                element.dataset.promoSummary = promo.summary ?? '';
                element.dataset.promoPoster = promo.poster_url ?? '';
                element.dataset.promoLabel = promo.promo_label ?? '';
                element.dataset.promoDate = promo.date_label ?? '';
                element.dataset.promoRange = promo.range_label ?? '';
                element.dataset.promoStatus = promo.status ?? '';
            };

            const setFlipped = (isFlipped) => {
                mobileCard.classList.toggle('is-flipped', isFlipped);
                mobileFlipFront.style.display = isFlipped ? 'none' : 'inline-flex';
                requestAnimationFrame(syncMobileCardHeight);
            };

            const clearDesktopFlipTimers = () => {
                if (desktopFlipStartTimeout) {
                    window.clearTimeout(desktopFlipStartTimeout);
                    desktopFlipStartTimeout = null;
                }

                if (desktopFlipTimeout) {
                    window.clearTimeout(desktopFlipTimeout);
                    desktopFlipTimeout = null;
                }

                if (desktopFlipResetTimeout) {
                    window.clearTimeout(desktopFlipResetTimeout);
                    desktopFlipResetTimeout = null;
                }
            };

            const clearDesktopTurnClasses = () => {
                desktopSpread.classList.remove('is-turning', 'is-updating');
                desktopTurnSheet.classList.remove('is-active', 'is-flipping');
                desktopTurnInfo.innerHTML = '';
                desktopTurnPoster.innerHTML = '';
            };

            const buildDesktopInfoHtml = (promo) => `
                <p style="margin: 0; font-family: 'Oswald', sans-serif; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.2em; color: #315fbd;">${promo.status ?? ''}</p>
                <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.45rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.14em; color: #b45309;">
                    <span style="border-radius: 999px; background: #eff6ff; padding: 0.45rem 0.75rem; color: #315fbd;">${promo.promo_label ?? ''}</span>
                    <span style="border-radius: 999px; background: #fff7ed; padding: 0.45rem 0.75rem; color: #b45309;">${promo.date_label ?? ''}</span>
                </div>
                <h3 style="margin: 1.15rem 0 0; font-size: clamp(2rem, 2.4vw, 2.8rem); font-weight: 700; line-height: 1.04; color: #1c1917;">${promo.title ?? ''}</h3>
                <p style="margin: 1.15rem 0 0; font-size: 1rem; line-height: 1.85; color: #57534e;">${promo.summary ?? ''}</p>
                <div style="margin-top: 1.5rem; padding-top: 0;">
                    <p style="margin: 0; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.18em; color: #94a3b8;">Offer Window</p>
                    <p style="margin: 0.55rem 0 0; font-size: 0.95rem; line-height: 1.7; color: #57534e;">${promo.range_label ?? ''}</p>
                </div>
            `;

            const buildDesktopTurnPoster = (promo) => {
                desktopTurnPoster.classList.remove('is-portrait', 'is-landscape');
                const img = document.createElement('img');
                img.src = promo.poster_url ?? '';
                img.alt = promo.title ?? 'Promotion poster';
                img.addEventListener('load', () => {
                    if (!img.naturalWidth || !img.naturalHeight) {
                        return;
                    }

                    desktopTurnPoster.classList.toggle('is-portrait', img.naturalHeight > img.naturalWidth);
                    desktopTurnPoster.classList.toggle('is-landscape', img.naturalWidth >= img.naturalHeight);
                }, { once: true });
                desktopTurnPoster.replaceChildren(img);
            };

            const applyPromoOrientation = (imageElement, containerElement) => {
                if (!imageElement || !containerElement) {
                    return;
                }

                const update = () => {
                    if (!imageElement.naturalWidth || !imageElement.naturalHeight) {
                        return;
                    }

                    const isPortrait = imageElement.naturalHeight > imageElement.naturalWidth;
                    containerElement.classList.toggle('is-portrait', isPortrait);
                    containerElement.classList.toggle('is-landscape', !isPortrait);
                };

                if (imageElement.complete) {
                    update();
                } else {
                    imageElement.addEventListener('load', update, { once: true });
                }
            };

            const renderPromo = (nextIndex) => {
                activePromoIndex = (nextIndex + promoSlides.length) % promoSlides.length;
                const promo = promoSlides[activePromoIndex];

                if (statusLabel) {
                    statusLabel.textContent = promo.status ?? 'Promotion';
                }
                countLabel.textContent = `${activePromoIndex + 1} / ${promoSlides.length}`;

                desktopImage.src = promo.poster_url ?? '';
                desktopImage.alt = promo.title ?? 'Promotion poster';
                desktopStatus.textContent = promo.status ?? '';
                desktopLabel.textContent = promo.promo_label ?? '';
                desktopDate.textContent = promo.date_label ?? '';
                desktopTitle.textContent = promo.title ?? '';
                desktopSummary.textContent = promo.summary ?? '';
                desktopRange.textContent = promo.range_label ?? '';

                mobileImage.src = promo.poster_url ?? '';
                mobileImage.alt = promo.title ?? 'Promotion poster';
                mobileLabel.textContent = promo.promo_label ?? '';
                mobileDate.textContent = promo.date_label ?? '';
                mobileStatusFront.textContent = promo.status ?? '';
                mobileTitleFront.textContent = promo.title ?? '';
                mobileStatusBack.textContent = promo.status ?? '';
                mobileTitleBack.textContent = promo.title ?? '';
                mobileRange.textContent = promo.range_label ?? '';
                mobileSummary.textContent = promo.summary ?? '';

                syncTriggerData(desktopPoster, promo);
                syncTriggerData(mobileViewPoster, promo);
                applyPromoOrientation(desktopImage, desktopPoster);
                applyPromoOrientation(mobileImage, mobileFrontFace);
                setFlipped(false);
                requestAnimationFrame(syncMobileCardHeight);
            };

            const animateDesktopPromoTurn = (direction) => {
                if (!desktopSpread || !isDesktopPromoView()) {
                    renderPromo(activePromoIndex + direction);
                    return;
                }

                if (isDesktopFlipping) {
                    return;
                }

                isDesktopFlipping = true;
                clearDesktopFlipTimers();
                clearDesktopTurnClasses();
                desktopSpread.classList.add('is-updating');

                desktopFlipTimeout = window.setTimeout(() => {
                    renderPromo(activePromoIndex + direction);
                }, 110);

                desktopFlipResetTimeout = window.setTimeout(() => {
                    clearDesktopTurnClasses();
                    isDesktopFlipping = false;
                }, 240);
            };

            if (promoSlides.length <= 1) {
                prevButton.style.opacity = '0.45';
                nextButton.style.opacity = '0.45';
            }

            nextButton.addEventListener('click', () => {
                animateDesktopPromoTurn(1);
            });

            prevButton.addEventListener('click', () => {
                animateDesktopPromoTurn(-1);
            });

            desktopNextZone?.addEventListener('click', () => {
                animateDesktopPromoTurn(1);
            });

            desktopPrevZone?.addEventListener('click', () => {
                animateDesktopPromoTurn(-1);
            });

            mobileFlipFront.addEventListener('click', () => {
                setFlipped(true);
            });

            mobileFlipBack.addEventListener('click', (event) => {
                event.stopPropagation();
                setFlipped(false);
            });

            mobileViewPoster.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            mobileCard.addEventListener('click', (event) => {
                if (event.target.closest('button') || event.target.closest('.promo-poster-trigger')) {
                    return;
                }

                setFlipped(!mobileCard.classList.contains('is-flipped'));
            });

            mobileImage.addEventListener('load', syncMobileCardHeight);
            window.addEventListener('resize', () => {
                syncMobileCardHeight();

                if (!isDesktopPromoView() && desktopSpread) {
                    clearDesktopFlipTimers();
                    clearDesktopTurnClasses();
                    isDesktopFlipping = false;
                }
            });
            renderPromo(0);
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
