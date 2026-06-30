<x-layouts.app title="Sabah Travel Info | Universal Eden Holidays">
<main class="min-h-[calc(100vh-var(--app-header-offset,0px))] bg-white">
<section class="sabah-map-section" id="sabah-map-section">
    <style>
        .sabah-map-section {
            --sabah-forest: #28543a;
            --sabah-forest-deep: #1b3a28;
            --sabah-sand: #e8d3af;
            --sabah-sand-soft: #f7f1e7;
            --sabah-ink: #243127;
            --sabah-muted: #627164;
            --sabah-line: rgba(40, 84, 58, 0.12);
            --sabah-shadow: 0 24px 50px rgba(24, 39, 28, 0.12);
            padding: 0;
            background:
                radial-gradient(circle at top left, rgba(232, 211, 175, 0.3), transparent 32%),
                linear-gradient(180deg, #ffffff 0%, #fbfaf7 100%);
            color: var(--sabah-ink);
        }

        .sabah-map-shell {
            width: 100%;
            max-width: none;
            margin: 0 auto;
            border-top: 1px solid var(--sabah-line);
            border-bottom: 1px solid var(--sabah-line);
            border-left: 0;
            border-right: 0;
            border-radius: 0;
            background: #ffffff;
            box-shadow: var(--sabah-shadow);
            overflow: hidden;
        }

        .sabah-map-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.28fr) minmax(320px, 0.72fr);
            gap: clamp(1rem, 2vw, 1.5rem);
            padding: clamp(1.1rem, 2vw, 1.75rem);
        }

        .sabah-map-stage-card,
        .sabah-map-info-card {
            border: 1px solid var(--sabah-line);
            border-radius: 28px;
            background: linear-gradient(180deg, #ffffff 0%, #fcfbf8 100%);
        }

        .sabah-map-stage-card {
            padding: clamp(1.1rem, 2vw, 1.5rem);
            border: 0;
            border-radius: 0;
            background: transparent;
        }

        .sabah-map-kicker {
            margin: 0;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--sabah-forest);
        }

        .sabah-map-title {
            margin: 0.65rem 0 0;
            font-family: "Prata", serif;
            font-size: clamp(2rem, 4vw, 3.6rem);
            line-height: 1.06;
            color: var(--sabah-forest-deep);
        }

        .sabah-map-intro {
            margin: 0.9rem 0 0;
            max-width: 42rem;
            font-size: 0.98rem;
            line-height: 1.8;
            color: var(--sabah-muted);
        }

        .sabah-map-visual {
            position: relative;
            margin-top: 1.4rem;
            aspect-ratio: 1.12 / 1;
            min-height: clamp(22rem, 36vw, 30rem);
            border-radius: 0;
            background: transparent;
            overflow: hidden;
            border: 0;
        }

        .sabah-map-base {
            position: absolute;
            inset: -2%;
            display: block;
            width: 104%;
            height: 104%;
            object-fit: contain;
            opacity: 0.22;
            pointer-events: none;
            user-select: none;
        }

        .sabah-map-hotspot {
            position: absolute;
            display: block;
            padding: 0;
            margin: 0;
            border: 0;
            background: transparent;
            cursor: pointer;
            transform-origin: top left;
            transition: opacity 0.35s ease, transform 0.35s ease, z-index 0.2s ease;
        }

        .sabah-map-image-button {
            display: inline-block;
            padding: 0;
            margin: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            appearance: none;
            -webkit-appearance: none;
        }

        .sabah-map-image-button img {
            display: block;
            background: transparent;
        }

        .sabah-map-hotspot img {
            display: block;
            width: 100%;
            height: auto;
            transition: opacity 0.35s ease, filter 0.35s ease, transform 0.35s ease;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            user-select: none;
            pointer-events: none;
        }

        .sabah-map-hotspot:hover img,
        .sabah-map-hotspot:focus img {
            opacity: 1;
            filter: none;
            transform: none;
        }

        .sabah-map-hotspot:hover,
        .sabah-map-hotspot:focus {
            z-index: 20 !important;
        }

        .sabah-map-hotspot.is-dimmed img {
            opacity: 1;
            filter: none;
        }

        .sabah-map-hotspot.is-active img {
            opacity: 1;
            filter: brightness(0.72) drop-shadow(0 18px 28px rgba(40, 84, 58, 0.2));
            transform: none;
        }

        .sabah-map-note {
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            background: var(--sabah-sand-soft);
            padding: 0.7rem 1rem;
            font-size: 0.85rem;
            line-height: 1.5;
            color: var(--sabah-forest-deep);
        }

        .sabah-map-note-dot {
            width: 0.7rem;
            height: 0.7rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--sabah-forest), #4d7a5b);
            box-shadow: 0 0 0 6px rgba(40, 84, 58, 0.08);
            flex-shrink: 0;
        }

        .sabah-map-info-card {
            padding: clamp(1.1rem, 2vw, 1.5rem);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1rem;
        }

        .sabah-map-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            width: fit-content;
            border-radius: 999px;
            background: rgba(232, 211, 175, 0.34);
            color: var(--sabah-forest-deep);
            padding: 0.5rem 0.85rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .sabah-map-info-title {
            margin: 0.9rem 0 0;
            font-family: "Prata", serif;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            line-height: 1.12;
            color: var(--sabah-forest-deep);
        }

        .sabah-map-info-text {
            margin: 0.95rem 0 0;
            font-size: 0.98rem;
            line-height: 1.8;
            color: var(--sabah-muted);
        }

        .sabah-map-grid {
            display: grid;
            gap: 0.9rem;
            margin-top: 1.3rem;
        }

        .sabah-map-detail {
            border-radius: 22px;
            border: 1px solid var(--sabah-line);
            background: #fffdfa;
            padding: 1rem 1rem 1.05rem;
            box-shadow: 0 12px 24px rgba(24, 39, 28, 0.04);
        }

        .sabah-map-detail h3 {
            margin: 0;
            font-size: 0.86rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--sabah-forest);
        }

        .sabah-map-list {
            margin: 0.85rem 0 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        .sabah-map-list li {
            border-radius: 999px;
            background: var(--sabah-sand-soft);
            padding: 0.5rem 0.8rem;
            font-size: 0.9rem;
            line-height: 1.45;
            color: var(--sabah-ink);
        }

        .sabah-map-cta {
            margin-top: 1.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            width: fit-content;
            min-height: 3.2rem;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--sabah-forest), #3d6a49);
            padding: 0.9rem 1.35rem;
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #ffffff;
            text-decoration: none;
            box-shadow: 0 16px 30px rgba(40, 84, 58, 0.22);
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        }

        .sabah-map-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 34px rgba(40, 84, 58, 0.28);
            background: linear-gradient(135deg, #214631, #31583e);
        }

        .sabah-map-cta:focus-visible {
            outline: 3px solid rgba(40, 84, 58, 0.22);
            outline-offset: 4px;
        }

        .sabah-map-hotspot:focus {
            outline: none;
        }

        @media (max-width: 980px) {
            .sabah-map-layout {
                grid-template-columns: 1fr;
            }

            .sabah-map-info-card {
                order: 2;
            }

            .sabah-map-visual {
                min-height: clamp(20rem, 58vw, 26rem);
            }
        }

        @media (max-width: 640px) {
            .sabah-map-shell {
                border-radius: 0;
            }

            .sabah-map-stage-card,
            .sabah-map-info-card {
                border-radius: 22px;
            }

            .sabah-map-list {
                flex-direction: column;
            }

            .sabah-map-list li {
                width: 100%;
                border-radius: 16px;
            }

            .sabah-map-cta {
                width: 100%;
            }

            .sabah-map-visual {
                aspect-ratio: 1 / 1;
                min-height: auto;
            }
        }
    </style>

        <div class="sabah-map-layout">
            <div class="sabah-map-stage-card">
                <div class="sabah-map-visual" aria-label="Interactive Sabah map">

                    <button
                        class="sabah-map-hotspot is-dimmed"
                        type="button"
                        data-division-key="kudat"
                        aria-pressed="false"
                        aria-label="Select Kudat Division"
                        style="top: 4.3%; left: 37.5%; transform: scale(0.72); z-index: 5;"
                    >
                        <img src="{{ asset('images/b_kudat.png') }}" alt="Kudat Division map">
                    </button>

                    <button
                        class="sabah-map-hotspot is-active"
                        type="button"
                        data-division-key="west-coast"
                        aria-pressed="true"
                        aria-label="Select West Coast Division"
                        style="top: 17%; left: 23%; transform: scale(0.72); z-index: 5;"
                    >
                        <img src="{{ asset('images/b_pantai barat.png') }}" alt="West Coast Division map">
                    </button>

                    <button
                        class="sabah-map-hotspot is-dimmed"
                        type="button"
                        data-division-key="interior"
                        aria-pressed="false"
                        aria-label="Select Interior Division"
                        style="top: 32.8%; left: 14.2%; transform: scale(0.73); z-index: 5;"
                    >
                        <img src="{{ asset('images/b_pendalaman.png') }}" alt="Interior Division map">
                    </button>

                    <button
                        class="sabah-map-hotspot is-dimmed"
                        type="button"
                        data-division-key="sandakan"
                        aria-pressed="false"
                        aria-label="Select Sandakan Division"
                        style="top: 16.1%; left: 35%; transform: scale(0.815); z-index: 5;"
                    >
                        <img src="{{ asset('images/b_sandakan.png') }}" alt="Sandakan Division map">
                    </button>

                    <button
                        class="sabah-map-hotspot is-dimmed"
                        type="button"
                        data-division-key="tawau"
                        aria-pressed="false"
                        aria-label="Select Tawau Division"
                        style="top: 46.3%; left: 45.2%; transform: scale(0.80); z-index: 5;"
                    >
                        <img src="{{ asset('images/b_tawau.png') }}" alt="Tawau Division map">
                    </button>

                </div>
            </div>

            <div class="sabah-map-info-card">
                <div>
                    <div class="sabah-map-pill">Selected Division</div>
                    <h3 class="sabah-map-info-title" data-division-name></h3>
                    <p class="sabah-map-info-text" data-division-description></p>

                    <div class="sabah-map-grid">
                        <div class="sabah-map-detail">
                            <h3>Top Attractions</h3>
                            <ul class="sabah-map-list" data-division-attractions></ul>
                        </div>

                        <div class="sabah-map-detail">
                            <h3>Recommended Activities</h3>
                            <ul class="sabah-map-list" data-division-activities></ul>
                        </div>

                        <div class="sabah-map-detail">
                            <h3>Food Highlights</h3>
                            <ul class="sabah-map-list" data-division-food></ul>
                        </div>
                    </div>
                </div>

                <a class="sabah-map-cta" data-division-link href="#">
                    View Tours
                </a>
            </div>
        </div>


    <script>
        (function () {
            const root = document.getElementById('sabah-map-section');

            if (!root) {
                return;
            }

            const divisions = {
                'west-coast': {
                    name: 'West Coast Division',
                    description: 'Gateway to Sabah, home to Kota Kinabalu, Kundasang, Mount Kinabalu, islands and beaches.',
                    attractions: ['Mount Kinabalu', 'Kundasang', 'Tanjung Aru Beach', 'Manukan Island', 'Mari Mari Cultural Village'],
                    activities: ['Hiking', 'Island hopping', 'White water rafting', 'Sunset cruise'],
                    food: ['Tuaran Mee', 'Hinava', 'Seafood'],
                    url: "{{ route('home') }}#packages-showcase"
                },
                interior: {
                    name: 'Interior Division',
                    description: 'A peaceful highland region known for countryside views, cultural villages and nature escapes.',
                    attractions: ['Keningau', 'Tambunan', 'Crocker Range', 'Maliau Basin', 'Murut Cultural Village'],
                    activities: ['Nature trekking', 'Camping', 'Cultural experience', 'River adventure'],
                    food: ['Linopot', 'Bambangan', 'Bosou'],
                    url: "{{ route('home') }}#packages-showcase"
                },
                kudat: {
                    name: 'Kudat Division',
                    description: 'The northern tip of Borneo with beaches, islands and rich Rungus culture.',
                    attractions: ['Tip of Borneo', 'Kelambu Beach', 'Banggi Island', 'Rungus Longhouse'],
                    activities: ['Beach escape', 'Snorkelling', 'Sunset watching', 'Photography'],
                    food: ['Fresh seafood', 'Tuhau', 'Coconut desserts'],
                    url: "{{ route('home') }}#packages-showcase"
                },
                sandakan: {
                    name: 'Sandakan Division',
                    description: 'Sabah\'s wildlife paradise with rainforest, rivers and rare animal encounters.',
                    attractions: ['Sepilok Orangutan Centre', 'Kinabatangan River', 'Sun Bear Conservation Centre', 'Turtle Islands'],
                    activities: ['Wildlife river cruise', 'Bird watching', 'Jungle trekking', 'Photography'],
                    food: ['Sandakan noodles', 'Seafood', 'Fresh fruits'],
                    url: "{{ route('home') }}#packages-showcase"
                },
                tawau: {
                    name: 'Tawau Division',
                    description: 'A marine paradise famous for Semporna, Sipadan and beautiful diving islands.',
                    attractions: ['Semporna', 'Sipadan Island', 'Bohey Dulang', 'Tawau Hills Park'],
                    activities: ['Diving', 'Snorkelling', 'Island hopping', 'Hiking'],
                    food: ['Amplang', 'Seafood', 'Sea grapes'],
                    url: "{{ route('home') }}#packages-showcase"
                }
            };

            const hotspots = Array.from(root.querySelectorAll('.sabah-map-hotspot'));
            const nameTarget = root.querySelector('[data-division-name]');
            const descriptionTarget = root.querySelector('[data-division-description]');
            const attractionsTarget = root.querySelector('[data-division-attractions]');
            const activitiesTarget = root.querySelector('[data-division-activities]');
            const foodTarget = root.querySelector('[data-division-food]');
            const linkTarget = root.querySelector('[data-division-link]');

            const renderList = (target, items) => {
                target.innerHTML = '';

                items.forEach((item) => {
                    const li = document.createElement('li');
                    li.textContent = item;
                    target.appendChild(li);
                });
            };

            const setActiveDivision = (divisionKey) => {
                const division = divisions[divisionKey];

                if (!division) {
                    return;
                }

                hotspots.forEach((hotspot) => {
                    const isActive = hotspot.dataset.divisionKey === divisionKey;
                    hotspot.classList.toggle('is-active', isActive);
                    hotspot.classList.toggle('is-dimmed', !isActive);
                    hotspot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                nameTarget.textContent = division.name;
                descriptionTarget.textContent = division.description;
                renderList(attractionsTarget, division.attractions);
                renderList(activitiesTarget, division.activities);
                renderList(foodTarget, division.food);
                linkTarget.setAttribute('href', division.url);
            };

            hotspots.forEach((hotspot) => {
                hotspot.addEventListener('click', () => {
                    setActiveDivision(hotspot.dataset.divisionKey);
                });

                hotspot.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        setActiveDivision(hotspot.dataset.divisionKey);
                    }
                });
            });

            setActiveDivision('west-coast');
        }());
</script>
</section>
@include('partials.footer')
</main>
</x-layouts.app>
