<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('pageTitle', 'Legal | Universal Eden Holidays')</title>
        <meta name="description" content="@yield('metaDescription', 'Legal information for Universal Eden Holidays Sdn. Bhd.')">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|prata:400|oswald:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --legal-topbar-h: 68px;
                --ink-strong: #1c1917;
                --ink-soft: #57534e;
                --paper: #fafaf9;
                --panel: #ffffff;
                --line: #e7e5e4;
                --brand: #d97706;
                --brand-soft: #fef3c7;
                --brand-dark: #92400e;
            }

            body.legal-portal {
                background:
                    radial-gradient(circle at 8% 5%, #fff7ed 0%, transparent 38%),
                    radial-gradient(circle at 92% 12%, #fef3c7 0%, transparent 36%),
                    linear-gradient(180deg, #fafaf9 0%, #f8fafc 40%, #f8fafc 100%);
                color: var(--ink-strong);
                font-family: 'Figtree', sans-serif;
            }

            .legal-paper h2 {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                margin-top: 2.35rem;
                margin-bottom: 1.05rem;
                padding-bottom: 0.7rem;
                border-bottom: 1px solid var(--line);
                color: var(--ink-strong);
                font-family: 'Prata', serif;
                font-size: 1.3rem;
                font-weight: 400;
                line-height: 1.4;
                scroll-margin-top: calc(var(--legal-topbar-h) + 1.5rem);
            }

            .legal-paper h2:first-of-type { margin-top: 0; }

            .legal-paper h2 .section-num {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2rem;
                height: 2rem;
                flex-shrink: 0;
                border-radius: 999px;
                background: var(--brand-soft);
                color: var(--brand-dark);
                font-size: 0.68rem;
                font-weight: 800;
                letter-spacing: 0.06em;
                margin-top: 0.08rem;
            }

            .legal-paper p,
            .legal-paper li,
            .legal-paper address {
                color: var(--ink-soft);
                font-size: 0.96rem;
                line-height: 1.86;
            }

            .legal-paper ul,
            .legal-paper ol {
                padding-left: 1.35rem;
                margin-bottom: 1.15rem;
            }

            .legal-paper ul li {
                list-style: disc;
                margin-bottom: 0.25rem;
                padding-left: 0.2rem;
            }

            .legal-paper ol li {
                list-style: decimal;
                margin-bottom: 0.25rem;
                padding-left: 0.2rem;
            }

            .legal-paper strong {
                color: var(--ink-strong);
                font-weight: 700;
            }

            .legal-paper a {
                color: var(--brand);
                text-decoration: underline;
                text-decoration-thickness: 1.5px;
            }

            .legal-paper a:hover {
                color: var(--brand-dark);
            }

            .legal-paper table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 1.25rem;
                border-radius: 0.75rem;
                overflow: hidden;
                border: 1px solid var(--line);
                background: var(--panel);
            }

            .legal-paper thead tr {
                background: linear-gradient(90deg, #1c1917, #292524);
                color: white;
            }

            .legal-paper th,
            .legal-paper td {
                padding: 0.72rem 0.95rem;
                text-align: left;
                font-size: 0.86rem;
                vertical-align: top;
            }

            .legal-paper tbody tr {
                border-top: 1px solid var(--line);
            }

            .legal-paper tbody tr:nth-child(even) {
                background: #f8fbff;
            }

            .legal-paper address {
                padding: 0.95rem 1rem;
                border-left: 4px solid var(--brand);
                background: #fffbeb;
                border-radius: 0.45rem;
                font-style: normal;
            }

            .legal-paper hr {
                margin: 2.2rem 0;
                border: 0;
                border-top: 1px solid var(--line);
            }

            .toc-link {
                display: block;
                padding: 0.38rem 0.52rem;
                border-radius: 0.45rem;
                color: #475569;
                font-size: 0.82rem;
                line-height: 1.45;
                transition: 0.15s ease;
            }

            .toc-link:hover {
                background: #f1f5f9;
                color: #0f172a;
            }

            .toc-link.toc-active {
                background: var(--brand-soft);
                color: var(--brand-dark);
                font-weight: 700;
            }

            #mobile-toc-body {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            #mobile-toc-body.open {
                max-height: 1000px;
            }
        </style>
    </head>
    <body class="legal-portal min-h-screen">
        @php
            $docNav = [
                ['route' => 'legal.privacy-policy', 'label' => 'Privacy Policy', 'short' => 'Privacy'],
                ['route' => 'legal.terms-and-conditions', 'label' => 'Terms & Conditions', 'short' => 'Terms'],
                ['route' => 'legal.refund-cancellation-policy', 'label' => 'Booking, Cancellation & Refund', 'short' => 'Refunds'],
                ['route' => 'legal.car-rental-terms', 'label' => 'Car Rental Terms & Conditions', 'short' => 'Car Rental'],
            ];
        @endphp

        <nav class="sticky top-0 z-50 border-b border-stone-200/70 bg-white/95 backdrop-blur" style="height: var(--legal-topbar-h);">
            <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-5 lg:px-10">
                <a href="{{ route('home') }}" class="group flex items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-200" aria-label="Return to home">
                    <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Holidays Logo" class="h-9 w-9 rounded-full ring-2 ring-stone-200 object-cover">
                    <div>
                        <p class="text-sm font-semibold text-stone-900">Universal Eden Holidays</p>
                        <p class="text-[10px] uppercase tracking-[0.16em] text-stone-500">Legal Portal</p>
                    </div>
                </a>

                <div class="flex items-center gap-2.5">
                    <button onclick="window.print()" class="hidden rounded-full border border-stone-300 bg-stone-100 px-3.5 py-1.5 text-xs font-semibold text-stone-700 transition hover:bg-stone-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 sm:block" aria-label="Print document">
                        Print
                    </button>
                    <a href="{{ route('home') }}" class="rounded-full border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500">
                        Back to Site
                    </a>
                </div>
            </div>
        </nav>

        <header class="mx-auto max-w-7xl px-5 pt-8 lg:px-10 lg:pt-10">
            <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-[0_18px_40px_rgba(28,25,23,0.08)]">
                <div class="grid gap-8 px-6 py-8 md:grid-cols-[1fr_auto] lg:px-10">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-700">Universal Eden Holidays Sdn. Bhd.</p>
                        <h1 class="mt-3 font-['Prata'] text-3xl leading-tight text-stone-900 md:text-4xl">@yield('pageHeading')</h1>
                        <p class="mt-3 text-sm text-stone-500">SSM Registration: 202201026346</p>
                    </div>
                    <div class="space-y-2 text-sm text-stone-600 md:text-right">
                        <p><span class="font-semibold text-stone-800">Effective:</span> 1 January 2025</p>
                        <p><span class="font-semibold text-stone-800">Last reviewed:</span> June 2026</p>
                    </div>
                </div>

                <div class="border-t border-stone-200 bg-stone-50 px-4 py-3 lg:px-6">
                    <nav class="grid grid-cols-2 gap-2 md:grid-cols-4" aria-label="Legal documents">
                        @foreach($docNav as $doc)
                            @php $isActive = request()->routeIs($doc['route']); @endphp
                            <a href="{{ route($doc['route']) }}" class="rounded-xl border px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-[0.08em] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 {{ $isActive ? 'border-amber-300 bg-white text-amber-800 shadow-sm' : 'border-stone-200 bg-transparent text-stone-600 hover:bg-white hover:text-stone-900' }}" {{ $isActive ? 'aria-current=page' : '' }}>
                                {{ $doc['short'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-5 pb-12 pt-8 lg:px-10">
            <div class="flex gap-8 xl:gap-12">
                <aside class="hidden w-60 flex-shrink-0 lg:block" aria-label="Document sections">
                    <div class="sticky top-[calc(var(--legal-topbar-h)+1.25rem)] rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                        <p class="mb-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">On This Page</p>
                        <nav id="desktop-toc" aria-label="Section navigation"></nav>
                    </div>
                </aside>

                <div class="min-w-0 flex-1">
                    <div class="mb-6 rounded-2xl border border-stone-200 bg-white shadow-sm lg:hidden">
                        <button id="mobile-toc-toggle" type="button" aria-expanded="false" aria-controls="mobile-toc-body" class="flex w-full items-center justify-between px-4 py-3 text-sm font-semibold text-stone-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500">
                            Section Navigation
                            <span id="mobile-toc-chevron" class="transition-transform">▾</span>
                        </button>
                        <div id="mobile-toc-body" role="region" aria-labelledby="mobile-toc-toggle">
                            <nav id="mobile-toc" class="border-t border-stone-200 px-3 pb-3 pt-2"></nav>
                        </div>
                    </div>

                    <article class="legal-paper rounded-2xl border border-stone-200 bg-white px-6 py-8 shadow-[0_12px_30px_rgba(28,25,23,0.06)] md:px-10 md:py-10" aria-label="Legal document">
                        @yield('content')
                    </article>
                </div>
            </div>
        </main>

        @include('partials.footer')

        <script>
            (function () {
                const article = document.querySelector('.legal-paper');
                const headings = article ? Array.from(article.querySelectorAll('h2')) : [];

                headings.forEach((h2, index) => {
                    if (!h2.id) {
                        h2.id = 'section-' + (index + 1);
                    }

                    const badge = document.createElement('span');
                    badge.className = 'section-num';
                    badge.setAttribute('aria-hidden', 'true');
                    badge.textContent = String(index + 1).padStart(2, '0');
                    h2.prepend(badge);
                });

                function buildToc(target) {
                    if (!target) return;
                    target.innerHTML = '';

                    if (headings.length === 0) {
                        target.innerHTML = '<p class="px-2 py-1 text-xs text-stone-400">No sections available.</p>';
                        return;
                    }

                    headings.forEach((h2) => {
                        const label = h2.textContent.trim();
                        const link = document.createElement('a');
                        link.href = '#' + h2.id;
                        link.className = 'toc-link';
                        link.dataset.target = h2.id;
                        link.textContent = label;
                        target.appendChild(link);
                    });
                }

                buildToc(document.getElementById('desktop-toc'));
                buildToc(document.getElementById('mobile-toc'));

                const updateToc = (id) => {
                    document.querySelectorAll('.toc-link').forEach((el) => {
                        el.classList.toggle('toc-active', el.dataset.target === id);
                    });
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            updateToc(entry.target.id);
                        }
                    });
                }, { rootMargin: '-24% 0px -68% 0px', threshold: 0.01 });

                headings.forEach((h2) => observer.observe(h2));

                const toggle = document.getElementById('mobile-toc-toggle');
                const body = document.getElementById('mobile-toc-body');
                const chevron = document.getElementById('mobile-toc-chevron');

                if (toggle && body) {
                    toggle.addEventListener('click', () => {
                        const open = body.classList.toggle('open');
                        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                        if (chevron) {
                            chevron.classList.toggle('rotate-180', open);
                        }
                    });
                }
            })();
        </script>
    </body>
</html>
