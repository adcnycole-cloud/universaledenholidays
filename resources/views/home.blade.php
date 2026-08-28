<x-layouts.app title="Universal Eden Holidays | Sabah Packages and Transport">
    @php
        $heroPackage = $popularPackages->first() ?? $travelPackages->first();
        $heroTransport = $transportServices->first();
        $packageDestinations = $travelPackages
            ->map(fn ($package) => trim((string) $package->location))
            ->filter()
            ->unique()
            ->values();
        $destinationPackages = $travelPackages
            ->unique(fn ($package) => trim((string) $package->name))
            ->values();
        $latestStories = $latestBlogPosts->take(3)->values();
        $storyFallbacks = collect([
            [
                'title' => 'A First-Timer\'s Guide to Exploring Sabah',
                'destination' => 'Destinations',
                'excerpt' => 'From Mount Kinabalu to island escapes, here\'s everything you need to know to plan your perfect Sabah adventure.',
                'published_at' => null,
                'cover_image_url' => asset('images/mount kinabalu.jpg'),
                'url' => route('blog.index'),
            ],
            [
                'title' => '5 Cultural Experiences You Shouldn\'t Miss',
                'destination' => 'Culture',
                'excerpt' => 'Discover the rich heritage and unique traditions that make Sabah so special.',
                'published_at' => null,
                'cover_image_url' => asset('images/mari mari.png'),
                'url' => route('blog.index'),
            ],
            [
                'title' => 'What to Eat When Visiting Kota Kinabalu',
                'destination' => 'Food & Drink',
                'excerpt' => 'A local food guide to must-try dishes and where to find them in KK.',
                'published_at' => null,
                'cover_image_url' => asset('images/shun shun.png'),
                'url' => route('blog.index'),
            ],
        ]);
        $storyCards = collect([0, 1, 2])->map(function ($index) use ($latestStories, $storyFallbacks) {
            $story = $latestStories->get($index);

            if ($story) {
                return [
                    'title' => $story->title,
                    'destination' => $story->destination ?: 'Travel Story',
                    'excerpt' => $story->excerpt ?: $story->description ?: 'Read the latest travel inspiration from Universal Eden Holidays.',
                    'published_at' => $story->published_at,
                    'cover_image_url' => $story->cover_image_url ?: $storyFallbacks[$index]['cover_image_url'],
                    'url' => route('blog.show', $story),
                ];
            }

            return $storyFallbacks[$index];
        });
    @endphp

    <style>
        html,
        body {
            overflow-x: hidden;
            background: #081320;
        }

        .home-hero-page {
            min-height: calc(112svh - var(--app-header-offset, 0px));
            background:
                linear-gradient(90deg, rgba(4, 18, 15, 0.84) 0%, rgba(4, 18, 15, 0.62) 34%, rgba(4, 18, 15, 0.16) 58%, rgba(4, 18, 15, 0.24) 100%),
                url('{{ asset('background.png') }}') center center / cover no-repeat;
        }

        .home-hero-shell {
            width: min(100%, 1200px);
            margin: 0 auto 0 max(0rem, calc((100vw - 1200px) / 2));
            padding: calc(clamp(3.7rem, 7vw, 5.6rem) + 20px) clamp(1.25rem, 4vw, 2.75rem) 3rem clamp(1.25rem, 4vw, 2.75rem);
        }

        .home-kicker {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.7rem;
            color: #9cde7a;
            font-size: 0.96rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .home-kicker::after {
            content: "";
            width: 64px;
            height: 2px;
            border-radius: 999px;
            background: #6fd15a;
        }

        .home-hero-title {
            margin: 1.35rem 0 0;
            max-width: 7.5ch;
            font-family: "Playfair Display", Georgia, serif;
            font-size: clamp(3.4rem, 7vw, 5.35rem);
            line-height: 0.95;
            color: #f8fafc;
        }

        .home-hero-copy {
            margin-top: 1.4rem;
            max-width: 31rem;
            font-size: 1.05rem;
            line-height: 1.65;
            color: rgba(248, 250, 252, 0.85);
        }

        .home-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.7rem;
        }

        .home-pill-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            min-width: 15.2rem;
            padding: 1rem 1.55rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.32);
            font-size: 1.02rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s ease, border-color 0.2s ease, background-color 0.2s ease;
        }

        .home-pill-button:hover {
            transform: translateY(-1px);
        }

        .home-pill-button--primary {
            border-color: #3ca543;
            background: #3ca543;
            color: #ffffff;
        }

        .home-pill-button--secondary {
            background: rgba(8, 19, 32, 0.28);
            color: #ffffff;
            backdrop-filter: blur(4px);
        }

        .home-search-card {
            margin-top: clamp(2rem, 6vw, 3rem);
            display: grid;
            width: min(100%, 76rem);
            grid-template-columns: 2.15fr 1.15fr 1.15fr 1.1fr;
            align-items: center;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 1.35rem;
            background: rgba(7, 25, 44, 0.78);
            box-shadow: 0 16px 40px rgba(2, 8, 23, 0.3);
            backdrop-filter: blur(16px);
        }

        .home-search-segment {
            display: flex;
            gap: 0.95rem;
            align-items: center;
            min-width: 0;
            padding: 1.2rem 1.35rem;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .home-search-segment:last-of-type {
            border-right: 0;
        }

        .home-search-icon {
            flex: 0 0 auto;
            width: 1.5rem;
            height: 1.5rem;
            color: #6fd15a;
        }

        .home-search-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: #f8fafc;
        }

        .home-search-control,
        .home-search-static {
            width: 100%;
            border: 0;
            background: transparent;
            padding: 0.12rem 1.5rem 0.12rem 0;
            font-size: 0.98rem;
            color: rgba(226, 232, 240, 0.82);
            outline: none;
        }

        .home-search-control option {
            color: #0f172a;
        }

        .home-search-control {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        .home-search-control::-ms-expand {
            display: none;
        }

        .home-search-field {
            position: relative;
            width: 100%;
            min-width: 0;
        }

        .home-search-field::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 0.15rem;
            width: 0.55rem;
            height: 0.55rem;
            border-right: 2px solid rgba(226, 232, 240, 0.68);
            border-bottom: 2px solid rgba(226, 232, 240, 0.68);
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
        }

        .home-search-field--date::after {
            display: none;
        }

        .home-search-field--guests::after {
            display: none;
        }

        .home-search-date-input {
            padding-right: 0;
            color-scheme: dark;
        }

        .home-search-date-input::-webkit-calendar-picker-indicator {
            position: relative;
            top: -0.22rem;
            opacity: 1;
            cursor: pointer;
        }

        .home-search-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: calc(100% - 1.1rem);
            margin: 0.55rem;
            padding: 1.1rem 1.2rem;
            border: 0;
            border-radius: 0.75rem;
            background: #3ca543;
            color: #ffffff;
            font-size: 1.08rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .home-search-button:hover {
            background: #32923a;
            transform: translateY(-1px);
        }

        .home-guests-stepper {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-top: 0.1rem;
        }

        .home-guests-value {
            min-width: 4.9rem;
            font-size: 0.98rem;
            color: rgba(226, 232, 240, 0.82);
        }

        .home-guests-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.45rem;
            height: 1.45rem;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            color: rgba(248, 250, 252, 0.9);
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .home-guests-button:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.34);
        }

        .home-guests-button:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .home-featured-strip {
            position: relative;
            margin-top: -2.2rem;
            padding: 0 1.25rem 4rem;
            background: #f7f4ec;
            overflow: visible;
        }

        .home-featured-wave {
            position: absolute;
            top: -3rem;
            left: 50%;
            width: 100vw;
            height: 4.5rem;
            transform: translateX(-50%);
            color: #f7f4ec;
            pointer-events: none;
            z-index: 0;
        }

        .home-featured-wave svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        .home-featured-shell {
            width: min(100%, 1420px);
            margin: 0 auto;
            padding: 4rem 0 2.4rem;
        }

        .home-featured-header {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .home-featured-header-copy {
            text-align: center;
        }

        .home-featured-kicker {
            display: inline-block;
            color: #2f9f3a;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .home-featured-title {
            margin-top: 0.8rem;
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            line-height: 1.05;
            color: #10233f;
            font-weight: 800;
        }

        .home-featured-copy {
            margin: 0;
            max-width: 40rem;
            font-size: 1.08rem;
            line-height: 1.7;
            color: #5b6474;
            text-align: left;
        }

        .home-featured-subrow {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem 14rem;
            margin-top: 0.85rem;
            width: min(100%, 68rem);
            margin-left: auto;
            margin-right: 0;
            transform: translateX(200px);
        }

        .home-featured-link {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #2d7f45;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            border-bottom: 2px solid rgba(45, 127, 69, 0.35);
            padding-bottom: 0.2rem;
            flex: 0 0 auto;
        }

        .home-featured-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.7rem;
        }

        .home-feature-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.25rem;
            background: #ffffff;
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.1);
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        }

        .home-feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(47, 159, 58, 0.24);
            box-shadow: 0 24px 46px rgba(15, 23, 42, 0.16);
        }

        .home-feature-card:hover .home-feature-card-media img {
            transform: scale(1.04);
        }

        .home-feature-card-media {
            position: relative;
            flex: 0 0 auto;
            overflow: hidden;
        }

        .home-feature-card img {
            width: 100%;
            height: 13.25rem;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .home-feature-badge {
            position: absolute;
            top: 0.9rem;
            left: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 3.35rem;
            padding: 0.35rem 0.65rem;
            border-radius: 0.55rem;
            background: linear-gradient(180deg, #43b649 0%, #2e9738 100%);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .home-feature-heart {
            position: absolute;
            top: 0.9rem;
            right: 0.9rem;
            color: #ffffff;
            text-shadow: 0 4px 14px rgba(15, 23, 42, 0.35);
            font-size: 1.55rem;
            line-height: 1;
        }

        .home-feature-card-body {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            padding: 0.85rem 0.95rem 0.95rem;
        }

        .home-feature-location {
            color: #34a047;
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .home-feature-card h3 {
            margin-top: 0.45rem;
            font-size: 1.35rem;
            line-height: 1.18;
            color: #16233d;
            font-weight: 800;
        }

        .home-feature-card p {
            margin-top: 0.55rem;
            min-height: 1.8rem;
            color: #5f6777;
            line-height: 1.4;
        }

        .home-feature-card-copy {
            flex: 1 1 auto;
        }

        .home-feature-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 0.8rem;
        }

        .home-feature-price {
            color: #1f2e46;
            font-size: 0.98rem;
            font-weight: 700;
        }

        .home-feature-price strong {
            font-size: 1.08rem;
        }

        .home-feature-card a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            padding: 0.75rem 1.15rem;
            border-radius: 0.7rem;
            background: linear-gradient(180deg, #43b649 0%, #2f9f3a 100%);
            color: #ffffff;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 8px 18px rgba(47, 159, 58, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .home-feature-card:hover a {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(47, 159, 58, 0.28);
        }

        .home-featured-meta {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0;
            width: fit-content;
            margin: 1.25rem auto 0;
            padding: 0.45rem 0.8rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        }

        .home-featured-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.55rem 1.3rem;
            color: #334155;
            font-size: 0.96rem;
            font-weight: 600;
        }

        .home-featured-meta-item + .home-featured-meta-item {
            border-left: 1px solid rgba(15, 23, 42, 0.08);
        }

        .home-featured-meta-icon {
            width: 1.35rem;
            height: 1.35rem;
            color: #2f9f3a;
        }

        .home-transport-section {
            padding: 2.6rem 1.25rem 3rem;
            background: #f7f4ec;
        }

        .home-transport-shell {
            width: min(100%, 1400px);
            margin: 0 auto;
        }

        .home-transport-header {
            margin-bottom: 0.55rem;
            text-align: center;
        }

        .home-transport-kicker {
            color: #2f9f3a;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .home-transport-title {
            margin-top: 0.55rem;
            color: #163254;
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1.05;
        }

        .home-transport-copy {
            margin: 0.45rem auto 0;
            max-width: 40rem;
            color: #687385;
            font-size: 1.08rem;
            line-height: 1.5;
        }

        .home-transport-feature {
            display: grid;
            grid-template-columns: minmax(0, 0.86fr) minmax(0, 1.14fr);
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.09);
            border-radius: 0.8rem;
            background: #ffffff;
            box-shadow: 0 9px 22px rgba(15, 23, 42, 0.08);
        }

        .home-transport-feature-copy {
            padding: 1.15rem 1.4rem;
        }

        .home-transport-feature-label {
            color: #2f9f3a;
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .home-transport-feature h3 {
            margin-top: 0.35rem;
            color: #163254;
            font-size: clamp(1.55rem, 2.5vw, 2rem);
            font-weight: 800;
            line-height: 1.04;
        }

        .home-transport-feature-copy > p {
            margin-top: 0.45rem;
            color: #687385;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .home-transport-feature-points {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem 0.65rem;
            margin-top: 0.55rem;
        }

        .home-transport-feature-points span {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            color: #2d5862;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .home-transport-feature-points span::before {
            content: '✓';
            display: inline-flex;
            width: 0.85rem;
            height: 0.85rem;
            align-items: center;
            justify-content: center;
            border: 1px solid #35a846;
            border-radius: 50%;
            color: #259638;
            font-size: 0.58rem;
        }

        .home-transport-actions {
            display: flex;
            align-items: center;
            gap: 1.15rem;
            margin-top: 0.6rem;
        }

        .home-transport-actions a {
            color: #163254;
            font-size: 0.86rem;
            font-weight: 800;
            text-decoration: none;
        }

        .home-transport-actions a:first-child {
            border-radius: 0.35rem;
            background: #2e9f42;
            color: #ffffff;
            padding: 0.55rem 0.8rem;
        }

        .home-transport-feature-media {
            overflow: hidden;
            min-height: 13.5rem;
            background: #dce9e2;
        }

        .home-transport-feature-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .home-transport-benefits {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.65rem;
            margin-top: 0.65rem;
        }

        .home-transport-benefit {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            min-height: 3.25rem;
            padding: 0.5rem 0.7rem;
            border: 1px solid #e6ebea;
            border-radius: 0.65rem;
            background: #ffffff;
        }

        .home-transport-benefit-icon {
            flex: 0 0 auto;
            color: #2f9f3a;
            font-size: 1.35rem;
        }

        .home-transport-benefit strong,
        .home-transport-benefit span:last-child {
            display: block;
        }

        .home-transport-benefit strong {
            color: #183150;
            font-size: 0.96rem;
            font-weight: 800;
        }

        .home-transport-benefit span:last-child {
            margin-top: 0.12rem;
            color: #778496;
            font-size: 0.78rem;
        }

        .home-stories-section {
            position: relative;
            padding: 6rem 1.25rem 5rem;
            background: #f7f4ec;
        }

        .home-stories-shell {
            width: min(100%, 1400px);
            margin: 0 auto;
        }

        .home-stories-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.8rem;
            text-align: center;
        }

        .home-stories-header-copy {
            flex: 1 1 100%;
        }

        .home-stories-kicker {
            display: inline-block;
            color: #2f9f3a;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .home-stories-title {
            margin-top: 0.45rem;
            font-size: clamp(2rem, 3.5vw, 3rem);
            line-height: 1.08;
            color: #163254;
            font-weight: 800;
        }

        .home-stories-copy {
            grid-column: 2;
            margin: 0;
            max-width: 44rem;
            color: #687385;
            font-size: 1rem;
            line-height: 1.7;
        }

        .home-stories-copy-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 44rem) minmax(0, 1fr);
            align-items: center;
            gap: 0.7rem 1rem;
            margin-top: 0.7rem;
            width: min(100%, 68rem);
            margin-left: auto;
            margin-right: auto;
        }

        .home-stories-link {
            grid-column: 3;
            justify-self: end;
            transform: translateX(5rem);
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #2d7f45;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            border-bottom: 2px solid rgba(45, 127, 69, 0.35);
            padding-bottom: 0.2rem;
            flex: 0 0 auto;
        }

        .home-stories-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.95fr);
            gap: 1rem;
        }

        .home-story-card {
            display: flex;
            min-width: 0;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.2rem;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.1);
            text-decoration: none;
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        }

        .home-story-card:hover {
            transform: translateY(-4px);
            border-color: rgba(47, 159, 58, 0.24);
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.13);
        }

        .home-story-card:hover .home-story-media img {
            transform: scale(1.04);
        }

        .home-story-card--featured {
            flex-direction: column;
        }

        .home-story-media {
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, #dbe8f6 0%, #bdd4ef 100%);
        }

        .home-story-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .home-story-card--featured .home-story-media {
            aspect-ratio: 16 / 6.2;
        }

        .home-story-stack {
            display: grid;
            gap: 1rem;
        }

        .home-story-stack .home-story-card {
            display: grid;
            grid-template-columns: minmax(180px, 42%) minmax(0, 1fr);
            align-items: stretch;
        }

        .home-story-stack .home-story-media {
            min-height: 100%;
        }

        .home-story-body {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            padding: 0.65rem 0.85rem 0.7rem;
        }

        .home-story-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.55rem;
            color: #8892a3;
            font-size: 0.78rem;
        }

        .home-story-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.18rem 0.45rem;
            border-radius: 0.35rem;
            background: rgba(111, 209, 90, 0.15);
            color: #2d8e3a;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .home-story-date {
            white-space: nowrap;
        }

        .home-story-card h3 {
            margin-top: 0.35rem;
            color: #16233d;
            font-size: 1.35rem;
            line-height: 1.18;
            font-weight: 800;
        }

        .home-story-stack .home-story-card h3 {
            font-size: 1.1rem;
        }

        .home-story-card p {
            margin-top: 0.25rem;
            color: #667084;
            line-height: 1.35;
        }

        .home-story-read {
            margin-top: auto;
            padding-top: 0.45rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: #2d8e3a;
            font-weight: 700;
            font-size: 0.96rem;
        }

        @media (max-width: 1023px) {
            .home-hero-shell {
                padding-bottom: 4rem;
            }

            .home-search-card {
                grid-template-columns: 1fr 1fr;
                margin-top: 1.6rem;
                margin-bottom: 1.5rem;
                border-radius: 1.1rem;
                position: relative;
                z-index: 2;
            }

            .home-search-segment {
                gap: 0.8rem;
                padding: 1rem 1.05rem;
            }

            .home-search-icon {
                width: 1.3rem;
                height: 1.3rem;
            }

            .home-search-label {
                font-size: 0.9rem;
            }

            .home-search-control,
            .home-search-static,
            .home-guests-value {
                font-size: 0.94rem;
            }

            .home-search-button {
                width: calc(100% - 0.85rem);
                margin: 0.425rem;
                padding: 1rem 1.05rem;
                font-size: 1rem;
            }

            .home-guests-button {
                width: 1.35rem;
                height: 1.35rem;
            }

            .home-featured-strip {
                margin-top: 0;
                padding-top: 1rem;
            }

            .home-featured-wave {
                top: -1.1rem;
                height: 3rem;
            }

            .home-featured-subrow {
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 0.9rem;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
                transform: none;
            }

            .home-featured-copy {
                max-width: 26rem;
                text-align: center;
            }

            .home-featured-link {
                justify-content: center;
            }

            .home-featured-grid {
                grid-template-columns: 1fr;
            }

            .home-featured-header {
                align-items: center;
            }

            .home-featured-link {
                justify-content: center;
            }

            .home-stories-section {
                padding-bottom: 4rem;
            }

            .home-transport-benefits {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-stories-grid {
                grid-template-columns: 1fr;
            }

            .home-story-stack {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .home-hero-page {
                min-height: calc(118svh - var(--app-header-offset, 0px));
                background-position: 68% center;
            }

            .home-hero-shell {
                margin: 0;
                padding-top: 4.5rem;
                padding-bottom: 2.25rem;
            }

            .home-hero-title {
                max-width: 9ch;
                font-size: 3rem;
            }

            .home-pill-button {
                min-width: 100%;
            }

            .home-search-card,
            .home-featured-grid {
                grid-template-columns: 1fr;
            }

            .home-search-card {
                margin-top: 1.5rem;
                margin-bottom: 1.75rem;
                border-radius: 1.05rem;
            }

            .home-featured-strip {
                margin-top: 0;
                padding-top: 0.75rem;
            }

            .home-featured-wave {
                top: -1rem;
                height: 2.8rem;
            }

            .home-search-segment {
                align-items: flex-start;
                justify-content: flex-start;
                gap: 0.85rem;
                padding: 1.1rem 1.15rem;
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                text-align: left;
            }

            .home-search-icon {
                flex: 0 0 auto;
                width: 1.15rem;
                height: 1.15rem;
                margin-top: 0.2rem;
            }

            .home-search-field {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .home-search-label {
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                color: rgba(248, 250, 252, 0.55);
            }

            .home-search-control,
            .home-search-static,
            .home-guests-value {
                font-size: 0.98rem;
                font-weight: 500;
                text-align: left;
                color: rgba(226, 232, 240, 0.92);
            }

            .home-search-date-input {
                text-align: left;
            }

            .home-search-field::after {
                top: 64%;
                right: 0.5rem;
                transform: translateY(-65%) rotate(45deg);
            }

            .home-search-button {
                width: calc(100% - 0.8rem);
                margin: 0.4rem;
                padding: 0.95rem 1rem;
                font-size: 1rem;
            }

            .home-guests-button {
                width: 1.3rem;
                height: 1.3rem;
            }

            .home-search-button {
                width: calc(100% - 1rem);
            }

            .home-featured-strip {
                margin-top: 0;
                padding-left: 0.8rem;
                padding-right: 0.8rem;
                padding-bottom: 3rem;
            }

            .home-featured-wave {
                top: -0.8rem;
                height: 2.7rem;
            }

            .home-featured-shell {
                padding: 3rem 0 1.6rem;
            }

            .home-featured-subrow {
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 0.9rem;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
                transform: none;
            }

            .home-featured-copy {
                max-width: 26rem;
                text-align: center;
            }

            .home-featured-link {
                justify-content: center;
            }

            .home-feature-card-body {
                padding: 0.95rem;
            }

            .home-feature-card-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .home-feature-card a {
                width: 100%;
            }

            .home-featured-meta {
                width: 100%;
                border-radius: 1.5rem;
            }

            .home-featured-meta-item {
                width: 100%;
                justify-content: center;
            }

            .home-featured-meta-item + .home-featured-meta-item {
                border-left: 0;
                border-top: 1px solid rgba(15, 23, 42, 0.08);
            }

            .home-stories-section {
                padding-top: 4.5rem;
                padding-left: 0.8rem;
                padding-right: 0.8rem;
                padding-bottom: 3.2rem;
            }

            .home-transport-section {
                padding: 2.4rem 0.8rem;
            }

            .home-transport-feature {
                grid-template-columns: 1fr;
            }

            .home-transport-feature-copy {
                padding: 1rem;
            }

            .home-transport-feature-media {
                min-height: 10.5rem;
            }

            .home-transport-benefits {
                grid-template-columns: 1fr;
            }

            .home-stories-header {
                justify-content: center;
                margin-bottom: 1.4rem;
            }

            .home-stories-copy-row {
                display: flex;
                flex-direction: column;
                justify-content: center;
                width: 100%;
            }

            .home-stories-link {
                justify-self: auto;
                transform: none;
            }

            .home-stories-title {
                font-size: 2rem;
            }

            .home-story-stack .home-story-card {
                grid-template-columns: 1fr;
            }

            .home-story-card h3,
            .home-story-stack .home-story-card h3 {
                font-size: 1.05rem;
            }

            .home-story-card p {
                font-size: 0.95rem;
            }

            .home-story-body {
                padding: 0.65rem 0.8rem 0.7rem;
            }

            .home-story-stack .home-story-media {
                aspect-ratio: 16 / 9;
            }
        }

        @media (max-height: 820px) {
            .home-hero-page {
                min-height: calc(122svh - var(--app-header-offset, 0px));
            }

            .home-hero-shell {
                padding-bottom: 4.25rem;
            }

            .home-search-card {
                margin-bottom: 1.75rem;
            }

            .home-featured-strip {
                margin-top: 0;
                padding-top: 0.85rem;
            }

            .home-featured-wave {
                top: calc(-0.95rem - 20px);
                height: 2.9rem;
            }
        }
    </style>

    <section class="home-hero-page">
        <div class="home-hero-shell">
            <div style="max-width: 76rem; padding-top: 0;">
                <span class="home-kicker">Discover All Of Sabah Borneo</span>
                <h1 class="home-hero-title">Travel And Ride With Us</h1>
                <p class="home-hero-copy">
                    Private rides, curated tours, and unforgettable journeys across Sabah.
                </p>

                <form method="GET" action="{{ route('tours.index') }}" class="home-search-card" id="home-search-form">
                    <div class="home-search-segment">
                        <svg class="home-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M12 21s-6-5.33-6-10a6 6 0 1 1 12 0c0 4.67-6 10-6 10Z"></path>
                            <circle cx="12" cy="11" r="2.2"></circle>
                        </svg>
                        <div class="home-search-field">
                            <label class="home-search-label" for="home-destination">Destination</label>
                            <select class="home-search-control" id="home-destination" name="search" required>
                                <option value="">Where would you like to go?</option>
                                @if ($packageDestinations->isNotEmpty())
                                    @foreach ($packageDestinations as $destination)
                                        <option value="{{ $destination }}">{{ $destination }}</option>
                                    @endforeach
                                @endif
                                @if ($destinationPackages->isNotEmpty())
                                    @foreach ($destinationPackages as $package)
                                        <option value="{{ $package->name }}">{{ $package->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="home-search-segment">
                        <svg class="home-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path d="M16 3v4M8 3v4M3 11h18"></path>
                        </svg>
                        <div class="home-search-field home-search-field--date">
                            <label class="home-search-label" for="home-travel-date">Travel date</label>
                            <input class="home-search-static home-search-date-input" id="home-travel-date" type="date" name="travel_date">
                        </div>
                    </div>

                    <div class="home-search-segment">
                        <svg class="home-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9.5" cy="7" r="3.2"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 4.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <div class="home-search-field home-search-field--guests">
                            <label class="home-search-label" for="home-guests">Guests</label>
                            <input type="hidden" id="home-guests" name="estimated_guest_count" value="6">
                            <div class="home-guests-stepper" aria-label="Guest count selector">
                                <button type="button" class="home-guests-button" id="home-guests-decrease" aria-label="Decrease guests">-</button>
                                <span class="home-guests-value" id="home-guests-display">6 Guests</span>
                                <button type="button" class="home-guests-button" id="home-guests-increase" aria-label="Increase guests">+</button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="home-search-button" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="home-featured-strip">
        <div class="home-featured-wave" aria-hidden="true">
            <svg viewBox="0 0 1440 72" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M0 44C92 28 184 20 310 22C487 24 640 42 810 33C954 25 1070 18 1186 21C1281 24 1362 29 1440 17V72H0V44Z"/>
            </svg>
        </div>
        <div class="home-featured-shell">
            <div class="home-featured-header">
                <div class="home-featured-header-copy">
                    <span class="home-featured-kicker">Featured Experiences</span>
                    <h2 class="home-featured-title">Explore the Best of Sabah</h2>
                    <div class="home-featured-subrow">
                        <p class="home-featured-copy">Handpicked island escapes and unforgettable journeys across Sabah.</p>
                        <a
                            href="{{ route('tours.index') }}"
                            class="home-featured-link"
                        >
                            <span>View Packages</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="home-featured-grid">
            @foreach ($popularPackages->take(3) as $package)
                <article class="home-feature-card">
                    <div class="home-feature-card-media">
                        <img src="{{ $package->image_url ?: asset('images/semporna.png') }}" alt="{{ $package->name }}">
                        <span class="home-feature-badge">{{ strtoupper($package->duration ?: '3D2N') }}</span>
                        <span class="home-feature-heart" aria-hidden="true">♡</span>
                    </div>
                    <div class="home-feature-card-body">
                        <div class="home-feature-card-copy">
                            <p class="home-feature-location">
                                {{ strtoupper($package->location ?: 'Sabah') }}
                            </p>
                            <h3>{{ $package->name }}</h3>
                            <p>
                                {{ \Illuminate\Support\Str::limit($package->summary ?: $package->description ?: ($package->location ?: 'Sabah travel package'), 110) }}
                            </p>
                        </div>
                        <div class="home-feature-card-footer">
                            <p class="home-feature-price">
                                <span>From</span>
                                <strong>
                                    @if ((float) ($package->discounted_malaysia_adult_price_myr ?? 0) > 0)
                                        <span class="currency-price" data-myr="{{ (float) $package->discounted_malaysia_adult_price_myr }}" data-currency-decimals="0">
                                            MYR {{ number_format((float) $package->discounted_malaysia_adult_price_myr, 0) }}
                                        </span>
                                    @elseif ((float) ($package->malaysia_adult_price_myr ?? 0) > 0)
                                        <span class="currency-price" data-myr="{{ (float) $package->malaysia_adult_price_myr }}" data-currency-decimals="0">
                                            MYR {{ number_format((float) $package->malaysia_adult_price_myr, 0) }}
                                        </span>
                                    @elseif ((float) ($package->price_myr ?? 0) > 0)
                                        <span class="currency-price" data-myr="{{ (float) $package->price_myr }}" data-currency-decimals="0">
                                            MYR {{ number_format((float) $package->price_myr, 0) }}
                                        </span>
                                    @else
                                        Price on request
                                    @endif
                                </strong>
                            </p>
                            <a href="{{ route('packages.show', $package) }}">
                                <span>View Package</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
            </div>

            <div class="home-featured-meta">
                <div class="home-featured-meta-item">
                    <svg class="home-featured-meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path d="M12 3 4 7v5c0 5 3.5 8.5 8 9 4.5-.5 8-4 8-9V7l-8-4Z"></path>
                        <path d="M9 12h6"></path>
                        <path d="M12 9v6"></path>
                    </svg>
                    <span>Local Sabah Expertise</span>
                </div>
                <div class="home-featured-meta-item">
                    <svg class="home-featured-meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                        <path d="M8 11V8a4 4 0 1 1 8 0v3"></path>
                    </svg>
                    <span>Secure Booking</span>
                </div>
                <div class="home-featured-meta-item">
                    <svg class="home-featured-meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path d="M12 3c2.6 2.2 4 4.7 4 7.1A5 5 0 0 1 11 15c-1.2 0-2.4-.4-3.4-1.2"></path>
                        <path d="M5 21c.4-4.5 3-7 7-7 1.9 0 3.8.6 5.2 1.8"></path>
                        <path d="M5 5c1.4 0 2.5 1.1 2.5 2.5S6.4 10 5 10 2.5 8.9 2.5 7.5 3.6 5 5 5Z"></path>
                    </svg>
                    <span>Curated Experiences</span>
                </div>
            </div>
        </div>
    </section>

    <section class="home-transport-section">
        <div class="home-transport-shell">
            <div class="home-transport-header">
                <span class="home-transport-kicker">Travel In Comfort</span>
                <h2 class="home-transport-title">Your Journey Across Sabah Starts Here</h2>
                <p class="home-transport-copy">Reliable transport for airport transfers, private tours, and group travel.</p>
            </div>

            <div class="home-transport-feature">
                <div class="home-transport-feature-copy">
                    <span class="home-transport-feature-label">Universal Eden Transport</span>
                    <h3>Comfortable Rides.<br>Local Expertise.</h3>
                    <p>Travel with confidence in our air-conditioned fleet, driven by experienced local professionals who know Sabah best.</p>
                    <div class="home-transport-feature-points"><span>Private &amp; Group Travel</span><span>Sabah-Wide Service</span><span>Flexible Scheduling</span></div>
                    <div class="home-transport-actions"><a href="{{ route('transport.index') }}">Explore Transport <span aria-hidden="true">→</span></a><a href="{{ route('booking.create', ['mode' => 'enquiry']) }}">Contact Us</a></div>
                </div>
                <div class="home-transport-feature-media"><img src="{{ asset('images/transport_top.png') }}" alt="Universal Eden transport vehicle travelling through Sabah"></div>
            </div>
            <div class="home-transport-benefits">
                <div class="home-transport-benefit"><span class="home-transport-benefit-icon" aria-hidden="true">✈</span><div><strong>Airport Transfer</strong><span>Easy arrivals &amp; departures</span></div></div>
                <div class="home-transport-benefit"><span class="home-transport-benefit-icon" aria-hidden="true">▰</span><div><strong>Private Charter</strong><span>Travel at your own pace</span></div></div>
                <div class="home-transport-benefit"><span class="home-transport-benefit-icon" aria-hidden="true">♧</span><div><strong>Group Transport</strong><span>Comfort for every group</span></div></div>
                <div class="home-transport-benefit"><span class="home-transport-benefit-icon" aria-hidden="true">⌖</span><div><strong>Custom Routes</strong><span>Planned around your trip</span></div></div>
            </div>
        </div>
    </section>

    <section class="home-stories-section">
        <div class="home-stories-shell">
            <div class="home-stories-header">
                <div class="home-stories-header-copy">
                    <span class="home-stories-kicker">Travel Stories</span>
                    <h2 class="home-stories-title">Latest from Sabah</h2>
                    <div class="home-stories-copy-row">
                        <p class="home-stories-copy">Travel inspiration, local guides and the latest updates from Universal Eden Holidays.</p>
                        <a href="{{ route('blog.index') }}" class="home-stories-link">
                            <span>View All Stories</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="home-stories-grid">
                @php($featuredStory = $storyCards->get(0))
                @php($sideStories = $storyCards->slice(1, 2))

                <a href="{{ $featuredStory['url'] }}" class="home-story-card home-story-card--featured">
                    <div class="home-story-media">
                        <img src="{{ $featuredStory['cover_image_url'] }}" alt="{{ $featuredStory['title'] }}">
                    </div>
                    <div class="home-story-body">
                        <div class="home-story-meta">
                            <span class="home-story-tag">{{ $featuredStory['destination'] }}</span>
                            <span class="home-story-date">{{ $featuredStory['published_at']?->format('d M Y') ?? 'Travel Guide' }}</span>
                        </div>
                        <h3>{{ $featuredStory['title'] }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($featuredStory['excerpt'], 150) }}</p>
                        <span class="home-story-read">
                            <span>Read Article</span>
                            <span aria-hidden="true">→</span>
                        </span>
                    </div>
                </a>

                <div class="home-story-stack">
                    @foreach ($sideStories as $story)
                        <a href="{{ $story['url'] }}" class="home-story-card">
                            <div class="home-story-media">
                                <img src="{{ $story['cover_image_url'] }}" alt="{{ $story['title'] }}">
                            </div>
                            <div class="home-story-body">
                                <div class="home-story-meta">
                                    <span class="home-story-tag">{{ $story['destination'] }}</span>
                                    <span class="home-story-date">{{ $story['published_at']?->format('d M Y') ?? 'Travel Guide' }}</span>
                                </div>
                                <h3>{{ $story['title'] }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($story['excerpt'], 110) }}</p>
                                <span class="home-story-read">
                                    <span>Read Article</span>
                                    <span aria-hidden="true">→</span>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const destinationField = document.getElementById('home-destination');
            const guestsInput = document.getElementById('home-guests');
            const guestsDisplay = document.getElementById('home-guests-display');
            const guestsDecrease = document.getElementById('home-guests-decrease');
            const guestsIncrease = document.getElementById('home-guests-increase');
            const form = document.getElementById('home-search-form');

            if (!destinationField || !guestsInput || !guestsDisplay || !guestsDecrease || !guestsIncrease || !form) {
                return;
            }

            const syncGuests = () => {
                const guestCount = Number.parseInt(guestsInput.value || '1', 10);
                const safeGuestCount = Number.isNaN(guestCount) ? 1 : Math.max(1, Math.min(12, guestCount));
                guestsInput.value = String(safeGuestCount);
                guestsDisplay.textContent = `${safeGuestCount} Guest${safeGuestCount === 1 ? '' : 's'}`;
                guestsDecrease.disabled = safeGuestCount <= 1;
            };

            guestsDecrease.addEventListener('click', () => {
                guestsInput.value = String(Math.max(1, Number.parseInt(guestsInput.value || '1', 10) - 1));
                syncGuests();
            });

            guestsIncrease.addEventListener('click', () => {
                guestsInput.value = String(Math.min(12, Number.parseInt(guestsInput.value || '1', 10) + 1));
                syncGuests();
            });

            syncGuests();
        });
    </script>
</x-layouts.app>
