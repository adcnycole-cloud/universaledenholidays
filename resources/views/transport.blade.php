<x-layouts.app title="Transport | Universal Eden Holidays">
    @php
        $transportCards = $transportServices
            ->take(3)
            ->values();

        $transportVisuals = [
            '41/44 Seaters Bus' => asset('images/44pax.png'),
            '17 Seaters Van' => asset('images/17pax.png'),
            '9/14 Seaters Van' => asset('images/14pax.png'),
            'Kota Kinabalu Airport Transfer' => asset('images/bus.png'),
            'West Coast Shuttle Pass' => asset('images/transport.png'),
        ];


        $transportMeta = [
            'Kota Kinabalu Airport Transfer' => ['Door-to-door pickup from KKIA and hotels.', ['1-6 Guests', 'One Way']],
            'West Coast Shuttle Pass' => ['Popular shared rides for flexible Sabah routes.', ['Scheduled', 'Budget Friendly']],
            '9/14 Seaters Van' => ['Comfortable rides for families and small groups.', ['Up to 14 Guests', 'With Driver']],
            '17 Seaters Van' => ['Extra room for larger groups and luggage.', ['Up to 17 Guests', 'With Driver']],
            '41/44 Seaters Bus' => ['Coach transport for events, teams, and tours.', ['Up to 44 Guests', 'Group Travel']],
        ];
    @endphp

    <style>
        html,
        body {
            overflow-x: hidden;
            background: #f5f7fb;
        }

        .transport-page {
            background:
                radial-gradient(circle at top, rgba(34, 197, 94, 0.08), transparent 30%),
                linear-gradient(180deg, #ffffff 0%, #f7f9fc 54%, #eef3f8 100%);
        }

        .transport-hero {
            position: relative;
            overflow: hidden;
            margin-top: -3.1rem;
            min-height: calc(100% + 3.1rem);
            background: #0f172a;
        }

        .transport-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('{{ asset('images/transport_top.png') }}') center center / cover no-repeat;
            opacity: 1;
            pointer-events: none;
        }

        @media (min-width: 768px) {
            .transport-hero::before {
                background-position: center 58%;
            }

            .transport-hero-shell {
                padding-left: 0.55rem;
            }
        }

        .transport-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.58) 0%, rgba(15, 23, 42, 0.32) 38%, rgba(15, 23, 42, 0.14) 100%);
            pointer-events: none;
        }

        .transport-hero-shell {
            position: relative;
            z-index: 1;
            width: min(100%, 1180px);
            margin: 0 auto;
            padding: calc(5.6rem + 30px) 1.5rem calc(4.6rem + 6px);
            display: block;
        }

        .transport-hero-copy-wrap {
            max-width: 64rem;
            margin-left: -22px;
            margin-top: 0;
            padding-top: 0;
            transform: none;
        }

        .transport-hero-title {
            margin: 0;
            max-width: none;
            font-family: "Prata", serif;
            font-size: clamp(2.35rem, 5.6vw, 4.4rem);
            line-height: 1.08;
            color: #f8fafc;
            text-shadow: 0 10px 28px rgba(15, 23, 42, 0.34);
        }

        .transport-hero-copy {
            margin: 1rem 0 0;
            max-width: 54rem;
            font-size: 1.02rem;
            line-height: 1.9;
            color: rgba(255, 255, 255, 0.94);
            text-shadow: 0 6px 20px rgba(15, 23, 42, 0.28);
            white-space: normal;
        }

        .transport-main {
            width: min(100%, 1420px);
            margin: 0 auto;
            padding: 3.4rem 1.5rem 4rem;
        }

        .transport-section-head {
            text-align: center;
        }

        .transport-section-kicker {
            color: #41ad43;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }

        .transport-section-title {
            margin: 0.8rem 0 0;
            font-family: "Prata", Georgia, serif;
            font-size: clamp(2.15rem, 4vw, 3.2rem);
            line-height: 1.08;
            color: #172b4d;
        }

        .transport-section-copy {
            margin: 0.7rem auto 0;
            max-width: 38rem;
            font-size: 1rem;
            line-height: 1.7;
            color: #64748b;
        }

        .transport-grid {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.15rem;
        }

        .transport-card {
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

        .transport-card:hover {
            transform: translateY(-8px);
            border-color: rgba(47, 159, 58, 0.24);
            box-shadow: 0 24px 46px rgba(15, 23, 42, 0.16);
        }

        .transport-card-media {
            position: relative;
            flex: 0 0 auto;
            height: 12.75rem;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(15, 23, 42, 0.04), rgba(15, 23, 42, 0.12)),
                linear-gradient(135deg, #d8f3dc, #eff6ff 55%, #f8fafc);
        }

        .transport-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .transport-card:hover .transport-card-media img {
            transform: scale(1.04);
        }

        .transport-card-body {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            padding: 0.9rem 0.95rem 0.95rem;
        }

        .transport-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .transport-card-title {
            margin: 0;
            font-family: "Inter", "Segoe UI", sans-serif;
            font-size: 1.35rem;
            line-height: 1.22;
            color: #16233d;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1 1 auto;
            min-width: 0;
        }

        .transport-card-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.4rem;
            margin-top: 0;
            flex: 0 0 auto;
        }

        .transport-card-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.55rem;
            border-radius: 999px;
            background: #eefbf0;
            color: #2f9f3a;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .transport-card-summary {
            margin: 0.8rem 0 0;
            min-height: 3rem;
            color: #5f6777;
            font-size: 0.93rem;
            line-height: 1.4;
        }

        .transport-card-divider {
            margin: 0.85rem 0 0;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
        }

        .transport-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: auto;
            padding-top: 0.85rem;
        }

        .transport-card-price span {
            display: block;
            color: #94a3b8;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .transport-card-price strong {
            display: block;
            color: #2f9f3a;
            font-size: 1.08rem;
            line-height: 1.2;
        }

        .transport-card-price small {
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .transport-card-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-width: 10.2rem;
            padding: 0.78rem 1.1rem;
            border-radius: 0.7rem;
            background: linear-gradient(180deg, #43b649 0%, #2f9f3a 100%);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(47, 159, 58, 0.22);
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .transport-card-link:hover {
            transform: translateY(-1px);
        }

        .transport-card:hover .transport-card-link {
            box-shadow: 0 12px 24px rgba(47, 159, 58, 0.28);
        }

        .transport-cta-band {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) auto minmax(0, 1.5fr);
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding: 0.8rem 1.1rem;
            border-radius: 1.15rem;
            background: linear-gradient(135deg, #07182b, #102847 60%, #0b1f36);
            color: #e2e8f0;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.16);
        }

        .transport-cta-band h3 {
            margin: 0;
            font-family: "Inter", "Segoe UI", sans-serif;
            font-size: 1.35rem;
            color: #ffffff;
            font-weight: 700;
            line-height: 1.2;
        }

        .transport-cta-band p {
            margin: 0.35rem 0 0;
            font-size: 0.9rem;
            line-height: 1.5;
            color: rgba(226, 232, 240, 0.8);
        }

        .transport-cta-band a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 10rem;
            padding: 0.8rem 1rem;
            border-radius: 0.75rem;
            background: #41ad43;
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
        }

        .transport-route {
            position: relative;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            align-items: center;
            min-height: 4rem;
        }

        .transport-route::before {
            content: "";
            position: absolute;
            left: 8%;
            right: 8%;
            top: 50%;
            height: 2px;
            background: linear-gradient(90deg, #4ade80 0%, #84cc16 50%, #4ade80 100%);
            transform: translateY(-50%);
            opacity: 0.85;
        }

        .transport-route-stop {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .transport-route-stop span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            border: 3px solid #9ae6b4;
            background: #07182b;
            color: #9ae6b4;
        }

        .transport-route-stop strong {
            display: block;
            margin-top: 0.45rem;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #f8fafc;
        }

        .transport-benefits {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .transport-benefit {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.94);
        }

        .transport-benefit-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 0.95rem;
            background: #eefbf0;
            color: #41ad43;
            flex: 0 0 auto;
        }

        .transport-benefit strong {
            display: block;
            color: #1e293b;
            font-size: 0.96rem;
        }

        .transport-benefit p {
            margin: 0.25rem 0 0;
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .transport-empty {
            margin-top: 2rem;
            padding: 1.4rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.94);
            color: #475569;
            text-align: center;
        }

        @media (max-width: 1199px) {
            .transport-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1023px) {
            .transport-hero-shell {
                text-align: center;
            }

            .transport-hero-copy-wrap {
                margin-left: auto;
                margin-right: auto;
                margin-top: 0;
                padding-top: 0;
                transform: none;
            }

            .transport-hero-copy {
                margin-left: auto;
                margin-right: auto;
                white-space: normal;
            }

            .transport-cta-band {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .transport-route {
                width: 100%;
            }

            .transport-benefits {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .transport-hero {
                margin-top: 0;
                min-height: calc(20rem - 65px);
                padding: calc(2.75rem - 32.5px) 1.25rem !important;
            }

            .transport-hero-shell {
                padding: 0 !important;
            }

            .transport-hero-title {
                font-size: clamp(2rem, 8vw, 3rem);
            }

            .transport-grid {
                grid-template-columns: 1fr;
            }

            .transport-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .transport-card-tags {
                justify-content: flex-start;
            }

            .transport-card-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .transport-card-link {
                width: 100%;
            }

            .transport-route {
                grid-template-columns: 1fr;
                gap: 1.4rem;
            }

            .transport-route::before {
                top: 8%;
                bottom: 8%;
                left: 50%;
                right: auto;
                width: 2px;
                height: auto;
                transform: translateX(-50%);
                background: linear-gradient(180deg, #4ade80 0%, #84cc16 50%, #4ade80 100%);
            }
        }
    </style>

    <div class="transport-page">
        <section class="transport-hero">
            <div class="transport-hero-shell">
                <div class="transport-hero-copy-wrap">
                    <h1 class="transport-hero-title">Choose Your Ride</h1>
                    <p class="transport-hero-copy">
                        Reliable transport for every journey, from airport arrivals to island gateways, city transfers, and flexible group travel across Sabah.
                    </p>
                </div>
            </div>
        </section>

        <main class="transport-main">
            <section id="transport-options">
                <div class="transport-section-head">
                    <span class="transport-section-kicker">Transport Options</span>
                    <h2 class="transport-section-title">A Better Way to Explore Sabah</h2>
                    <p class="transport-section-copy">
                        Choose the service that fits your route, group size, and travel style.
                    </p>
                </div>

                @if ($transportCards->isNotEmpty())
                    <div class="transport-grid">
                        @foreach ($transportCards as $transport)
                            @php
                                $visual = $transport->image_url ?: ($transportVisuals[$transport->name] ?? asset('images/transport.png'));
                                [$customSummary, $tags] = $transportMeta[$transport->name] ?? [
                                    $transport->summary ?: 'Reliable Sabah transport with direct support from the Universal Eden team.',
                                    [filled($transport->capacity) ? 'Up to '.$transport->capacity.' Guests' : 'Flexible Groups', filled($transport->pickup_location) ? $transport->pickup_location : 'With Driver'],
                                ];

                                $startingPrice = (float) ($transport->discounted_malaysia_adult_price_myr ?? 0) > 0
                                    ? (float) $transport->discounted_malaysia_adult_price_myr
                                    : ((float) ($transport->malaysia_adult_price_myr ?? 0) > 0
                                        ? (float) $transport->malaysia_adult_price_myr
                                        : (float) ($transport->price_myr ?? 0));
                            @endphp
                            <article class="transport-card">
                                <div class="transport-card-media">
                                    <img src="{{ $visual }}" alt="{{ $transport->name }}">
                                </div>
                                <div class="transport-card-body">
                                    <div class="transport-card-header">
                                        <h3 class="transport-card-title">{{ $transport->name }}</h3>
                                        <div class="transport-card-tags">
                                            @foreach (collect($tags)->take(2) as $tag)
                                                <span class="transport-card-tag">
                                                    <svg viewBox="0 0 24 24" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M8.5 12.5 10.8 14.8 15.8 9.8"></path></svg>
                                                    <span>{{ $tag }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p class="transport-card-summary">
                                        {{ \Illuminate\Support\Str::limit($transport->summary ?: $customSummary, 100) }}
                                    </p>
                                    <div class="transport-card-divider"></div>
                                    <div class="transport-card-footer">
                                        <div class="transport-card-price">
                                            <span>From</span>
                                            @if ($startingPrice > 0)
                                                <strong>
                                                    <span class="currency-price" data-myr="{{ $startingPrice }}" data-currency-decimals="0">MYR {{ number_format($startingPrice, 0) }}</span>
                                                </strong>
                                                <small>/ trip</small>
                                            @else
                                                <strong style="font-size: 1.1rem;">Price on request</strong>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $transport) }}" class="transport-card-link">View Details</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="transport-cta-band">
                        <div>
                            <h3>Not sure which ride fits your trip?</h3>
                            <p>Tell us your pickup point, destination, and group size. We’ll recommend the best option.</p>
                        </div>
                        <a href="{{ route('booking.create', ['mode' => 'enquiry']) }}">Plan My Transfer</a>
                        <div class="transport-route" aria-hidden="true">
                            <div class="transport-route-stop">
                                <span>
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 22s6-5.7 6-11a6 6 0 1 0-12 0c0 5.3 6 11 6 11Zm0-8.2a2.8 2.8 0 1 1 0-5.6 2.8 2.8 0 0 1 0 5.6Z"/></svg>
                                </span>
                                <strong>KKIA</strong>
                            </div>
                            <div class="transport-route-stop">
                                <span>
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="7" width="14" height="10" rx="2"></rect><path d="M8 17v2M16 17v2M5 11h14"></path></svg>
                                </span>
                                <strong>Kota Kinabalu</strong>
                            </div>
                            <div class="transport-route-stop">
                                <span>
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 22s6-5.7 6-11a6 6 0 1 0-12 0c0 5.3 6 11 6 11Zm0-8.2a2.8 2.8 0 1 1 0-5.6 2.8 2.8 0 0 1 0 5.6Z"/></svg>
                                </span>
                                <strong>Kundasang</strong>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="transport-empty">
                        Transport listings will appear here once active vehicles are available.
                    </div>
                @endif

                <div class="transport-benefits">
                    <div class="transport-benefit">
                        <span class="transport-benefit-icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3 5 6v6c0 4.5 2.9 7.8 7 9 4.1-1.2 7-4.5 7-9V6l-7-3Z"></path><path d="m9 12 2 2 4-4"></path></svg>
                        </span>
                        <div>
                            <strong>Licensed Drivers</strong>
                            <p>Professional, experienced, and local to Sabah travel routes.</p>
                        </div>
                    </div>
                    <div class="transport-benefit">
                        <span class="transport-benefit-icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 12V7l-5-4H9L4 7v5c0 5 4.5 8 8 9 3.5-1 8-4 8-9Z"></path><path d="M9 12h6"></path></svg>
                        </span>
                        <div>
                            <strong>Transparent Pricing</strong>
                            <p>No hidden fees. What you see is what you pay.</p>
                        </div>
                    </div>
                    <div class="transport-benefit">
                        <span class="transport-benefit-icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16v11H7l-3 3V4Z"></path><path d="M8 8h8"></path><path d="M8 11h5"></path></svg>
                        </span>
                        <div>
                            <strong>24/7 Trip Support</strong>
                            <p>We’re here whenever you need route help or arrangement support.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    @include('partials.footer')
</x-layouts.app>
