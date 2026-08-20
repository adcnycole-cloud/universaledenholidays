@props(['title', 'pageHeading'])

<x-layouts.app :title="$title">
    <style>
        .legal-hero {
            position: relative;
            overflow: hidden;
            margin-top: -3.1rem;
            min-height: 20.25rem;
            background: url('{{ asset('images/bg_image.png') }}') center center / cover no-repeat;
        }

        .legal-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(7, 25, 44, 0.5);
            pointer-events: none;
        }

        .legal-hero-inner {
            position: absolute;
            inset: 0;
            min-height: inherit;
            z-index: 1;
        }

        .legal-hero-copy {
            width: min(100%, 84rem);
            margin: 0 auto;
            text-align: center;
        }

        .legal-hero-kicker {
            margin: 0;
            color: rgba(255, 255, 255, 0.96);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.34em;
            text-transform: uppercase;
            text-shadow: 0 8px 24px rgba(15, 23, 42, 0.28);
        }

        .legal-hero-title {
            margin: 0.7rem 0 0;
            font-family: "Prata", Georgia, serif;
            font-size: clamp(2.2rem, 4.4vw, 3.5rem);
            line-height: 1.1;
            color: #f8fafc;
            text-shadow: 0 10px 28px rgba(15, 23, 42, 0.34);
        }

        .legal-hero-subtitle {
            margin: 0.9rem auto 0;
            max-width: 52rem;
            font-size: 1rem;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.94);
            text-shadow: 0 6px 20px rgba(15, 23, 42, 0.28);
        }

        /* ── Document typography ───────────────────────────────────── */
        .legal-paper {
            color: #44403c;
            font-size: 1rem;
            line-height: 2;
        }

        .legal-paper .lead {
            color: #292524;
            font-size: 1.06rem;
            line-height: 2;
        }

        .legal-paper h2 {
            margin-top: 2.5rem;
            margin-bottom: 1.1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e7e5e4;
            color: #1c1917;
            font-family: 'Prata', Georgia, serif;
            font-size: 1.45rem;
            font-weight: 400;
            line-height: 1.35;
        }

        .legal-paper h2:first-of-type { margin-top: 0; }

        .legal-paper p,
        .legal-paper li { color: #44403c; font-size: 1rem; line-height: 2; }
        .legal-paper p   { margin-bottom: 1rem; }
        .legal-paper ul, .legal-paper ol { padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .legal-paper ul li { list-style: disc;    padding-left: 0.25rem; margin-bottom: 0.35rem; }
        .legal-paper ol li { list-style: decimal; padding-left: 0.25rem; margin-bottom: 0.35rem; }
        .legal-paper strong { color: #1c1917; }
        .legal-paper a  { color: #b45309; text-decoration: underline; }
        .legal-paper a:hover { color: #92400e; }

        .legal-paper table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #e7e5e4;
        }
        .legal-paper thead tr { background: #1c1917; color: #fff; }
        .legal-paper th, .legal-paper td { padding: 0.75rem 1rem; text-align: left; vertical-align: top; }
        .legal-paper thead th { font-size: 0.78rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; }
        .legal-paper tbody tr { border-top: 1px solid #e7e5e4; }
        .legal-paper tbody tr:nth-child(even) { background: #fafaf9; }

        .legal-paper address {
            border-left: 4px solid #d97706;
            padding: 1rem 1.25rem;
            background: #fffbeb;
            border-radius: 0.75rem;
            font-style: normal;
            margin: 1.25rem 0;
            line-height: 2;
        }
        .legal-paper hr { border: none; border-top: 1px solid #e7e5e4; margin: 2.25rem 0; }

        @media (max-width: 767px) {
            .legal-hero {
                margin-top: 0;
                min-height: 15rem;
            }

            .legal-hero-kicker {
                font-size: 0.72rem;
                letter-spacing: 0.24em;
            }

            .legal-hero-title {
                font-size: clamp(2rem, 8vw, 2.75rem);
            }

            .legal-hero-subtitle {
                font-size: 0.95rem;
                line-height: 1.65;
            }
        }
    </style>

    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))] bg-white">
        <section class="legal-hero">
            <div class="legal-hero-inner flex items-center justify-center px-6 py-6 text-white md:px-8 md:py-8">
                <div class="legal-hero-copy">
                    <p class="legal-hero-kicker">Legal Information</p>
                    <h1 class="legal-hero-title">{{ $pageHeading }}</h1>
                    <p class="legal-hero-subtitle">Universal Eden Holidays Sdn. Bhd. · SSM No. 202201026346</p>
                </div>
            </div>
        </section>

        <section class="w-full bg-white px-6 py-10 md:px-8 md:py-12">
            <div class="mx-auto max-w-4xl">
                <article class="legal-paper" aria-label="Legal document content">
                    {{ $slot }}
                </article>
            </div>
        </section>
    </main>

    @include('partials.footer')
</x-layouts.app>
