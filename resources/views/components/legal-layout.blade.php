@props(['title', 'pageHeading'])

<x-layouts.app :title="$title">

    @php
        $docNav = [
            ['route' => 'legal.privacy-policy',            'short' => 'Privacy Policy'],
            ['route' => 'legal.terms-and-conditions',      'short' => 'Terms & Conditions'],
            ['route' => 'legal.refund-cancellation-policy','short' => 'Refund & Cancellation'],
            ['route' => 'legal.car-rental-terms',          'short' => 'Car Rental Terms'],
        ];
    @endphp

    <style>
        :root { --legal-header-h: var(--app-header-offset, 0px); }

        /* ── Document typography ───────────────────────────────────── */
        .legal-paper h2 {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            margin-top: 2.25rem;
            margin-bottom: 1rem;
            padding-bottom: 0.65rem;
            border-bottom: 1px solid #e7e5e4;
            color: #1c1917;
            font-family: 'Prata', serif;
            font-size: 1.2rem;
            font-weight: 400;
            line-height: 1.5;
            scroll-margin-top: calc(var(--legal-header-h) + 1.5rem);
        }

        .legal-paper h2:first-of-type { margin-top: 0; }

        .legal-paper h2 .snum {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 1.875rem;
            height: 1.875rem;
            border-radius: 0.5rem;
            background: #fef3c7;
            color: #92400e;
            font-family: 'Figtree', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            margin-top: 0.1rem;
        }

        .legal-paper p,
        .legal-paper li { color: #44403c; font-size: 0.9375rem; line-height: 1.85; }
        .legal-paper p   { margin-bottom: 0.9rem; }
        .legal-paper ul, .legal-paper ol { padding-left: 1.25rem; margin-bottom: 1.1rem; }
        .legal-paper ul li { list-style: disc;    padding-left: 0.2rem; margin-bottom: 0.25rem; }
        .legal-paper ol li { list-style: decimal; padding-left: 0.2rem; margin-bottom: 0.25rem; }
        .legal-paper strong { color: #1c1917; }
        .legal-paper a  { color: #b45309; text-decoration: underline; }
        .legal-paper a:hover { color: #92400e; }

        .legal-paper table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #e7e5e4;
        }
        .legal-paper thead tr { background: #1c1917; color: #fff; }
        .legal-paper th, .legal-paper td { padding: 0.7rem 0.95rem; text-align: left; vertical-align: top; }
        .legal-paper thead th { font-size: 0.78rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; }
        .legal-paper tbody tr { border-top: 1px solid #e7e5e4; }
        .legal-paper tbody tr:nth-child(even) { background: #fafaf9; }

        .legal-paper address {
            border-left: 3px solid #d97706;
            padding: 0.85rem 1rem;
            background: #fffbeb;
            border-radius: 0.5rem;
            font-style: normal;
            margin: 1.1rem 0;
        }
        .legal-paper hr { border: none; border-top: 1px solid #e7e5e4; margin: 2rem 0; }

        /* ── TOC ───────────────────────────────────────────────────── */
        .toc-link {
            display: block;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
            color: #78716c;
            font-size: 0.8125rem;
            line-height: 1.5;
            transition: all 0.12s ease;
            border-left: 3px solid transparent;
        }
        .toc-link:hover {
            background: #f5f5f4;
            color: #1c1917;
            border-left-color: #d97706;
        }
        .toc-link.active {
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
            border-left-color: #d97706;
        }

        /* ── Mobile TOC ────────────────────────────────────────────── */
        #mtoc-body { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        #mtoc-body.open { max-height: 1000px; }
    </style>

<main class="mx-auto max-w-[1500px] px-5 py-10 lg:px-10 lg:py-12">

        {{-- Professional header ------------------------------------------------ --}}
        <div class="mb-8 space-y-6">
            {{-- Header card with title and meta --}}
            <div class="rounded-[2rem] border border-stone-200 bg-white px-6 py-8 shadow-sm md:px-10 md:py-10">
                <div class="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-700">Legal Document</p>
                        <h1 class="mt-3 font-['Prata'] text-3xl leading-tight text-stone-900 md:text-4xl">
                            {{ $pageHeading }}
                        </h1>
                        <p class="mt-3 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            Universal Eden Holidays Sdn. Bhd. · SSM No. 202201026346
                        </p>
                    </div>
                    <div class="shrink-0 space-y-2 border-l border-stone-200 pl-6 text-sm text-stone-600 md:border-l md:pl-6">
                        <p><span class="block text-xs font-semibold uppercase tracking-[0.16em] text-stone-700">Effective Date</span>
                            <span class="text-xs text-stone-500">1 January 2025</span>
                        </p>
                        <p><span class="block text-xs font-semibold uppercase tracking-[0.16em] text-stone-700">Last Reviewed</span>
                            <span class="text-xs text-stone-500">June 2026</span>
                        </p>
                        <button onclick="window.print()"
                                class="mt-4 inline-flex items-center gap-2 rounded-full border border-stone-300 bg-stone-50 px-4 py-2 text-xs font-semibold text-stone-700 transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500"
                                aria-label="Print this document">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            {{-- Document tabs --}}
            <div class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-4 md:p-5">
                <nav class="grid grid-cols-2 gap-2 md:grid-cols-4" aria-label="Legal documents">
                    @foreach($docNav as $doc)
                        @php $active = request()->routeIs($doc['route']); @endphp
                        <a href="{{ route($doc['route']) }}"
                           {{ $active ? 'aria-current=page' : '' }}
                           class="rounded-[1rem] border px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-[0.12em] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500
                                  {{ $active
                                     ? 'border-amber-300 bg-white text-amber-800 shadow-sm'
                                     : 'border-stone-200 text-stone-600 hover:border-stone-300 hover:bg-white hover:text-stone-900' }}">
                            {{ $doc['short'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Two-column body ------------------------------------------------- --}}
        <div class="flex gap-6 lg:gap-8">

            {{-- Sticky sidebar TOC (desktop) --}}
            <aside class="hidden w-56 shrink-0 xl:block" aria-label="Table of contents">
                <div class="sticky rounded-[1.75rem] border border-stone-200 bg-white p-6 shadow-sm"
                     style="top: calc(var(--legal-header-h) + 1.5rem);">
                    <p class="mb-4 text-[10px] font-semibold uppercase tracking-[0.24em] text-stone-400">On This Page</p>
                    <nav id="dtoc" class="space-y-0.5" aria-label="Section navigation"></nav>
                </div>
            </aside>

            {{-- Content column --}}
            <div class="min-w-0 flex-1">

                {{-- Mobile TOC accordion --}}
                <div class="mb-6 rounded-[1.5rem] border border-stone-200 bg-white shadow-sm xl:hidden">
                    <button id="mtoc-toggle" type="button" aria-expanded="false" aria-controls="mtoc-body"
                            class="flex w-full items-center justify-between px-5 py-4 text-sm font-semibold text-stone-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 rounded-[1.5rem]">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                            </svg>
                            Document Sections
                        </span>
                        <svg id="mtoc-chevron" class="h-4 w-4 text-stone-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="mtoc-body">
                        <nav id="mtoc" class="border-t border-stone-200 space-y-0.5 px-4 pb-4 pt-3" aria-label="Section navigation"></nav>
                    </div>
                </div>

                {{-- Document body --}}
                <article class="legal-paper rounded-[2rem] border border-stone-200 bg-white px-6 py-8 shadow-sm md:px-10 md:py-10"
                         aria-label="Legal document content">
                    {{ $slot }}
                </article>

                {{-- Footer note --}}
                <div class="mt-8 rounded-[1.5rem] border border-stone-200 bg-stone-50 px-5 py-4 text-center text-xs text-stone-600">
                    <p>Questions about this document? Contact us at 
                        <a href="mailto:info@universaledenholiday.com" class="font-semibold text-amber-700 hover:text-amber-800">info@universaledenholiday.com</a>
                    </p>
                </div>

            </div>{{-- /content column --}}
        </div>

    </main>

    @include('partials.footer')

    <script>
        (function () {
            const article  = document.querySelector('.legal-paper');
            const headings = article ? Array.from(article.querySelectorAll('h2')) : [];

            headings.forEach((h2, i) => {
                if (!h2.id) h2.id = 'sec-' + (i + 1);
                const badge = document.createElement('span');
                badge.className = 'snum';
                badge.setAttribute('aria-hidden', 'true');
                badge.textContent = String(i + 1).padStart(2, '0');
                h2.prepend(badge);
            });

            function buildToc(nav) {
                if (!nav) return;
                nav.innerHTML = '';
                if (!headings.length) {
                    nav.innerHTML = '<p class="px-2 py-1 text-xs text-stone-400">No sections.</p>';
                    return;
                }
                headings.forEach(h2 => {
                    const text = h2.textContent.trim();
                    const a = document.createElement('a');
                    a.href = '#' + h2.id;
                    a.className = 'toc-link';
                    a.dataset.t = h2.id;
                    a.textContent = text;
                    nav.appendChild(a);
                });
            }

            buildToc(document.getElementById('dtoc'));
            buildToc(document.getElementById('mtoc'));

            const setActive = id => {
                document.querySelectorAll('.toc-link').forEach(l =>
                    l.classList.toggle('active', l.dataset.t === id));
            };

            const observer = new IntersectionObserver(entries => {
                entries.forEach(e => { if (e.isIntersecting) setActive(e.target.id); });
            }, { rootMargin: '-20% 0px -68% 0px', threshold: 0.01 });

            headings.forEach(h => observer.observe(h));

            const toggle  = document.getElementById('mtoc-toggle');
            const body    = document.getElementById('mtoc-body');
            const chevron = document.getElementById('mtoc-chevron');

            if (toggle && body) {
                toggle.addEventListener('click', () => {
                    const open = body.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                    chevron?.classList.toggle('rotate-180', open);
                });
                body.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
                    body.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                    chevron?.classList.remove('rotate-180');
                }));
            }
        })();
    </script>

</x-layouts.app>
