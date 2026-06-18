<x-layouts.app :title="$tourPage['heading'].' | Universal Eden Holidays'">
    <style>
        @media (min-width: 1024px) {
            #home-footer-grid {
                grid-template-columns: var(--footer-grid-columns-lg, 1fr);
            }
        }

        .tour-package-image-frame {
            overflow: hidden;
        }

        .tour-package-image {
            transition: transform 0.28s ease;
        }

        .tour-package-card:hover .tour-package-image {
            transform: scale(1.06);
        }
    </style>
    @php
        $heroImageStyle = match ($tourPage['slug']) {
            'day-trip' => 'background-image: url(\''.asset('images/tourist.png').'\'); background-size: cover; background-position: center center;',
            '2d1n-trip' => 'background-image: url(\''.asset('images/2d1n.png').'\'); background-size: cover; background-position: center center;',
            '3d2n-trip' => 'background-image: url(\''.asset('images/3d2n.png').'\'); background-size: cover; background-position: center center;',
            '4d3n-trip' => 'background-image: url(\''.asset('images/4d3n.png').'\'); background-size: cover; background-position: center center;',
            default => 'background: linear-gradient(135deg, #f5ede0 0%, #f3dfc4 52%, #e5b883 100%);',
        };
    @endphp
    <main class="min-h-[calc(100vh-var(--app-header-offset,0px))] px-0 pt-0 pb-10" style="background: #ffffff;">
        <section style="position: relative; overflow: hidden; width: 100%; {{ $heroImageStyle }} box-shadow: 0 22px 45px rgba(15,23,42,0.16); padding: 7.5rem 1.5rem 6.9rem;">
            @if (in_array($tourPage['slug'], ['day-trip', '2d1n-trip', '3d2n-trip', '4d3n-trip'], true))
                <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(15,23,42,0.58) 0%, rgba(15,23,42,0.32) 38%, rgba(15,23,42,0.14) 100%);"></div>
            @else
                <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.22), rgba(255,255,255,0.08) 48%, rgba(120,53,15,0.08) 100%);"></div>
                <div style="position: absolute; right: -2rem; top: -2.5rem; height: 10rem; width: 10rem; border-radius: 999px; background: radial-gradient(circle, rgba(59,130,246,0.16) 0%, rgba(59,130,246,0) 70%);"></div>
            @endif
            <div style="position: relative; z-index: 1; margin: 0 auto; width: min(100%, 1180px);">
                <h1 style="margin: 0; font-family: 'Prata', serif; font-size: clamp(2.35rem, 5.6vw, 4.4rem); line-height: 1.08; color: {{ in_array($tourPage['slug'], ['day-trip', '2d1n-trip', '3d2n-trip', '4d3n-trip'], true) ? '#ffffff' : '#1c1917' }}; text-shadow: {{ in_array($tourPage['slug'], ['day-trip', '2d1n-trip', '3d2n-trip', '4d3n-trip'], true) ? '0 10px 28px rgba(15,23,42,0.34)' : 'none' }};">
                    {{ $tourPage['heading'] }}
                </h1>
                <p style="margin: 1rem 0 0; max-width: 54rem; font-size: 1.02rem; line-height: 1.9; color: {{ in_array($tourPage['slug'], ['day-trip', '2d1n-trip', '3d2n-trip', '4d3n-trip'], true) ? 'rgba(255,255,255,0.94)' : '#57534e' }}; text-shadow: {{ in_array($tourPage['slug'], ['day-trip', '2d1n-trip', '3d2n-trip', '4d3n-trip'], true) ? '0 6px 20px rgba(15,23,42,0.28)' : 'none' }};">
                    {{ $tourPage['description'] }}
                </p>
            </div>
        </section>

        <div class="mx-auto px-4 lg:px-6" style="max-width: 1480px;">
            @if ($tourPackages->isNotEmpty())
                <section class="mt-8 px-6 lg:px-10">
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($tourPackages as $package)
                            @php
                                $tripCode = strtoupper(str_replace([' days', ' day', ' nights', ' night', ' '], ['D', 'D', 'N', 'N', ''], $package->duration));
                                $discountBadge = $package->has_active_discount
                                    ? rtrim(rtrim(number_format((float) $package->discount_percentage, 2, '.', ''), '0'), '.').'% OFF'
                                    : null;
                                $currentPrice = (float) $package->discounted_malaysia_adult_price_myr;
                                $originalPrice = (float) $package->malaysia_adult_price_myr;
                            @endphp
                            <article class="tour-package-card" style="display: flex; flex-direction: column; overflow: hidden; border: 1px solid rgba(120,113,108,0.18); background: rgba(255,255,255,0.96); box-shadow: 0 18px 32px rgba(15,23,42,0.14);">
                                <a href="{{ route('products.show', $package) }}" style="display: block; color: inherit; text-decoration: none;">
                                    <div class="tour-package-image-frame" style="position: relative;">
                                        @if ($package->image_url)
                                            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="tour-package-image" style="display: block; height: 16rem; width: 100%; object-fit: cover;">
                                        @else
                                            <div style="display: flex; height: 16rem; align-items: center; justify-content: center; background: linear-gradient(135deg, #60a5fa, #bfdbfe 40%, #fde68a); padding: 1rem; text-align: center;">
                                                <span style="font-family: 'Prata', serif; font-size: 1.5rem; line-height: 1.3; color: #1e3a8a;">{{ $package->name }}</span>
                                            </div>
                                        @endif
                                        <span style="position: absolute; left: 0.85rem; top: 0.85rem; background: rgba(255,255,255,0.95); padding: 0.38rem 0.65rem; font-size: 0.66rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #1d4ed8;">
                                            {{ $tripCode }}
                                        </span>
                                        @if ($discountBadge)
                                            <span style="position: absolute; right: 0.85rem; top: 0.85rem; background: #ffedd5; padding: 0.38rem 0.65rem; font-size: 0.66rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #c2410c;">
                                                {{ $discountBadge }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="padding: 1.2rem 1.2rem 1rem;">
                                        <p style="margin: 0; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: #78716c;">{{ $package->location }}</p>
                                        <h2 style="margin: 0.55rem 0 0; font-family: 'Oswald', sans-serif; font-size: 1.65rem; font-weight: 700; line-height: 1.08; color: #1f2937;">
                                            {{ $package->name }}
                                        </h2>
                                        <p style="margin: 0.75rem 0 0; font-size: 0.94rem; line-height: 1.75; color: #57534e;">
                                            {{ \Illuminate\Support\Str::limit($package->description ?: $package->summary ?: '', 150) }}
                                        </p>
                                    </div>
                                </a>
                                <div style="margin-top: auto; padding: 0 1.2rem 1.25rem;">
                                    <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; border-top: 1px solid rgba(120,113,108,0.16); padding-top: 1rem;">
                                        <div>
                                            <p style="margin: 0; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: #b45309;">Starting From</p>
                                            @if ($package->has_active_discount)
                                                <p style="margin: 0.25rem 0 0; font-size: 0.82rem; color: #78716c; text-decoration: line-through;">RM {{ number_format($originalPrice, 2) }}</p>
                                            @endif
                                            <p style="margin: 0.18rem 0 0; font-size: 1.55rem; font-weight: 700; line-height: 1; color: #0f4fb5;">RM {{ number_format($currentPrice, 2) }}</p>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <a href="{{ route('products.show', $package) }}" style="display: inline-flex; align-items: center; justify-content: center; border: 1px solid #1d4ed8; padding: 0.7rem 1rem; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #1d4ed8; text-decoration: none;">
                                                View Details
                                            </a>
                                            <a href="{{ route('booking.create', ['product_id' => $package->id]) }}" style="display: inline-flex; align-items: center; justify-content: center; background: #ff1d0d; padding: 0.7rem 1rem; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #ffffff; text-decoration: none;">
                                                Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @else
                <section class="mt-8" style="position: relative; left: 50%; width: min(1480px, calc(100vw - 3rem)); transform: translateX(-50%); border: 1px solid rgba(120,113,108,0.18); background: rgba(255,255,255,0.92); box-shadow: 0 18px 32px rgba(15,23,42,0.1); padding: 2rem;">
                    <h2 style="margin: 0; font-family: 'Prata', serif; font-size: 2rem; color: #1c1917;">No packages yet for {{ $tourPage['label'] }}</h2>
                    <p style="margin: 0.9rem 0 0; max-width: 42rem; font-size: 0.98rem; line-height: 1.8; color: #57534e;">
                        This page is ready. Once packages with matching duration are added in the admin dashboard, they will show here automatically.
                    </p>
                </section>
            @endif
        </div>
    </main>

       @include('/partials.footer')

</x-layouts.app>
