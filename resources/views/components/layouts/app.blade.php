<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Universal Eden Holidays' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|prata:400|oswald:400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@400;900&family=Playfair+Display:wght@400;700;900&family=Cinzel:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-stone-50 text-stone-900" data-form-persist-success="{{ session('success') ? 'true' : 'false' }}">
        <style>
            :root {
                --app-header-offset: 0px;
                --admin-sidebar-width: 16rem;
            }

            .js-app-header {
                position: fixed;
                inset: 0 0 auto 0;
                z-index: 180;
                isolation: isolate;
            }

            .app-toast-stack {
                position: fixed;
                top: calc(var(--app-header-offset, 0px) + 0.75rem);
                right: 1rem;
                z-index: 320;
                display: flex;
                width: min(28rem, calc(100vw - 2rem));
                flex-direction: column;
                gap: 0.75rem;
                pointer-events: none;
            }

            .app-toast {
                pointer-events: auto;
                border-radius: 1.25rem;
                border: 1px solid;
                padding: 1rem 1rem 1rem 1.1rem;
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
                backdrop-filter: blur(14px);
                transition: opacity 0.25s ease, transform 0.25s ease;
            }

            .app-toast.is-hiding {
                opacity: 0;
                transform: translateY(-10px);
            }

            @media (max-width: 640px) {
                .app-toast-stack {
                    top: calc(var(--app-header-offset, 0px) + 0.5rem);
                    right: 0.75rem;
                    left: 0.75rem;
                    width: auto;
                }
            }

            .admin-shell {
                min-width: 0;
            }

            .site-nav-details > summary::-webkit-details-marker {
                display: none;
            }

            .site-nav-details[open] .site-nav-toggle-icon {
                transform: rotate(90deg);
            }

            .site-nav-panel {
                position: absolute;
                top: calc(100% + 0.9rem);
                right: 0;
                width: min(22rem, calc(100vw - 2rem));
                border: 1px solid #d6d3d1;
                border-radius: 1.5rem;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 22px 44px rgba(15, 23, 42, 0.16);
                backdrop-filter: blur(18px);
            }

            .site-nav-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                border-radius: 1rem;
                padding: 0.85rem 1rem;
                font-size: 1rem;
                font-weight: 600;
                color: #44403c;
                transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
            }

            .site-nav-link:hover {
                background: #f0f9ff;
                color: #0369a1;
                transform: translateX(-2px);
            }

            .tours-menu {
                position: relative;
                display: inline-flex;
                align-items: center;
            }

            .tours-menu.is-open .tours-menu-panel {
                opacity: 1;
                visibility: visible;
                transform: translate(-50%, 0);
                pointer-events: auto;
            }

            .tours-menu-panel {
                position: absolute;
                top: calc(100% + 0.6rem);
                left: 50%;
                min-width: 10.5rem;
                padding: 0.5rem;
                border: 1px solid rgba(69, 84, 153, 0.16);
                border-radius: 1rem;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 18px 36px rgba(15, 23, 42, 0.18);
                opacity: 0;
                visibility: hidden;
                transform: translate(-50%, 0.5rem);
                transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
                pointer-events: none;
                z-index: 220;
            }

            .tours-menu-link {
                display: block;
                border-radius: 0.7rem;
                padding: 0.55rem 0.8rem;
                font-size: 0.95rem;
                font-weight: 600;
                color: #455499;
                text-decoration: none;
                white-space: nowrap;
                transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
            }

            .tours-menu-link:hover {
                background: rgba(69, 84, 153, 0.12);
                color: #2f3b7c;
                transform: translateX(2px);
            }

            .main-nav-link {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                border-radius: 999px;
                background: transparent;
                padding: 0.38rem 0.7rem;
                font: inherit;
                cursor: pointer;
                transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
            }

            .main-nav-link:hover {
                background: rgba(255, 255, 255, 0.14);
                color: #ffffff;
                transform: translateY(-1px);
            }

            .main-nav-link.is-light:hover {
                background: rgba(69, 84, 153, 0.1);
                color: #455499;
            }

            @media (min-width: 768px) {
                .admin-shell.with-sidebar {
                    position: relative;
                    margin-left: var(--admin-sidebar-width);
                    width: calc(100% - var(--admin-sidebar-width));
                }

                .admin-shell.with-sidebar::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    left: 0;
                    width: 1px;
                    background: #e7e5e4;
                    pointer-events: none;
                }
            }
        </style>
        @php($isAdminRoute = request()->routeIs('admin.*'))
        @php($hideHeader = $isAdminRoute || request()->routeIs('login', 'admin.login'))
        @php($currencyOptions = ['MYR', 'KRW', 'USD', 'SGD', 'CNY'])
        @php($adminNavBase = 'rounded-full border px-4 py-2 transition')
        @php($adminNavActive = $adminNavBase.' border-emerald-200 bg-emerald-50 text-emerald-700')
        @php($adminNavIdle = $adminNavBase.' border-stone-200 text-stone-700 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700')
        <div class="flex min-h-screen flex-col bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.08),_transparent_30%),linear-gradient(180deg,_#fffdf9,_#f8fafc)]">
            @unless ($hideHeader)
                <header class="js-app-header shadow-[0_10px_24px_rgba(15,23,42,0.08)] {{ $isAdminRoute ? 'border-b border-emerald-200 bg-white' : 'bg-white' }} ">
                    <div class="{{ $isAdminRoute ? 'grid w-full grid-cols-[1fr_auto_1fr] items-center px-6 lg:px-10' : 'flex w-full items-center justify-between px-6 lg:px-10' }}" style="height: 70px; padding-top: 0; padding-bottom: 0;">
                        @if ($isAdminRoute)
                            <div class="justify-self-start" aria-hidden="true"></div>
                        @else
                            <a href="{{ route('home') }}" class="flex items-center gap-3" style="position: relative; left: 0.25rem;">
                                <img src="{{ asset('images/ue blue logo.png') }}" alt="Universal Eden Logo" class="w-auto " style="height: 2rem;">
                                <img src="{{ asset('images/Malaysia Truly Asia logo 2026.png') }}" alt="Malaysia Truly Asia Logo" class="w-auto" style="display: block; height: 4rem; margin: 0; padding: 0;">
                                <span class="font-['Prata'] text-xl text-stone-900">Universal Eden Holidays</span>
                            </a>
                        @endif

                        @if ($isAdminRoute)
                            <p class="hidden md:block text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Admin Workspace</p>
                        @else
                            <nav class="hidden">
                                <div class="flex items-center gap-5 font-semibold uppercase text-stone-700 xl:gap-6" style="font-size: 0.58rem; letter-spacing: 0.1em;">
                                    <a href="{{ route('home') }}" class="main-nav-link is-light whitespace-nowrap">Home</a>
                                    <a href="{{ route('home') }}#transport" class="main-nav-link is-light whitespace-nowrap">Transport</a>
                                    <a href="{{ route('home') }}#packages-showcase" class="main-nav-link is-light whitespace-nowrap">Packages</a>
                                    <div class="tours-menu" data-tours-menu>
                                        <button type="button" class="main-nav-link is-light tours-menu-toggle whitespace-nowrap" data-tours-toggle aria-expanded="false" style="display: inline-flex; align-items: center; gap: 0.28rem;">
                                            <span>Tours</span>
                                            <span aria-hidden="true" style="font-size: 0.72rem; line-height: 1;">&#9662;</span>
                                        </button>
                                        <div class="tours-menu-panel" style="border-radius: 0;">
                                            <a href="{{ route('tours.show', 'day-trip') }}" class="tours-menu-link" style="border-radius: 0;">Day Trip</a>
                                            <a href="{{ route('tours.show', '2d1n-trip') }}" class="tours-menu-link" style="border-radius: 0;">2D1N Trip</a>
                                            <a href="{{ route('tours.show', '3d2n-trip') }}" class="tours-menu-link" style="border-radius: 0;">3D2N Trip</a>
                                            <a href="{{ route('tours.show', '4d3n-trip') }}" class="tours-menu-link" style="border-radius: 0;">4D3N Trip</a>
                                        </div>
                                    </div>
                                    <a href="{{ route('blog.index') }}" class="main-nav-link is-light whitespace-nowrap">Blog</a>
                                    <a href="{{ route('home') }}#about-us" class="main-nav-link is-light whitespace-nowrap">About Us</a>
                                    <a href="{{ route('bookings.track.form') }}" class="main-nav-link is-light whitespace-nowrap">Track Booking</a>
                                </div>
                            </nav>
                        @endif

                        <div class="flex items-center gap-3 {{ $isAdminRoute ? 'w-full justify-end justify-self-stretch' : '' }}">
                            @if (! $isAdminRoute)
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-stone-500">Currency:</span>
                                    <div class="relative inline-flex items-center">
                                        <select id="currency-selector" class="appearance-none rounded-full border border-stone-200 bg-white px-3 py-1.5 pr-8 text-sm font-semibold text-stone-700 outline-none" style="min-width: 4.75rem;">
                                            @foreach ($currencyOptions as $code)
                                                <option value="{{ $code }}" @selected((auth()->user()->preferred_currency ?? 'MYR') === $code)>{{ $code }}</option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute top-1/2 -translate-y-1/2 text-[10px] leading-none text-stone-400" style="right: 0.55rem;">▼</span>
                                    </div>
                                </div>


                            @endif
                            @auth
                                @if (! $isAdminRoute)
                                    @if (auth()->user()->isAdmin())
                                        <details class="relative">
                                            <summary class="flex h-10 cursor-pointer list-none items-center gap-2 rounded-full border border-emerald-300 bg-emerald-50 px-3 text-emerald-700 transition hover:border-emerald-400 hover:bg-emerald-100">
                                                <span class="text-xs font-bold uppercase tracking-[0.12em]">Admin</span>
                                                <span class="sr-only">Open admin session menu</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M4 6h16" />
                                                    <path d="M4 12h16" />
                                                    <path d="M4 18h16" />
                                                </svg>
                                            </summary>
                                            <div class="absolute right-0 z-50 mt-2 w-64 overflow-hidden rounded-2xl border border-emerald-200 bg-white p-3 shadow-xl">
                                                <a href="{{ route('admin.dashboard') }}" class="mt-3 block rounded-xl border border-stone-200 px-3 py-2 text-sm font-medium text-stone-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Dashboard</a>
                                                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                                    @csrf
                                                    <button type="submit" class="w-full rounded-xl border border-stone-200 px-3 py-2 text-left text-sm font-medium text-stone-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">Logout</button>
                                                </form>
                                            </div>
                                        </details>
                                    @else
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-700 transition hover:border-stone-400 hover:bg-stone-100">Logout</button>
                                        </form>
                                    @endif
                                @endif
                            @else
                            @endauth
                        </div>
                    </div>
                    @if (! $isAdminRoute)
                        <div class="hidden md:block" style="background: #455499;">
                            <nav class="w-full px-6 lg:px-10" style="padding-top: 1rem; padding-bottom: 1rem; background: #455499;">
                                <div class="flex items-center justify-center gap-8 font-semibold uppercase text-white xl:gap-10" style="font-size: 0.9rem; letter-spacing: 0.12em;">
                                    <a href="{{ route('home') }}" class="main-nav-link whitespace-nowrap">Home</a>
                                    <a href="{{ route('home') }}#transport" class="main-nav-link whitespace-nowrap">Transport</a>
                                    <a href="{{ route('home') }}#packages-showcase" class="main-nav-link whitespace-nowrap">Packages</a>
                                    <div class="tours-menu" data-tours-menu>
                                        <button type="button" class="main-nav-link tours-menu-toggle whitespace-nowrap" data-tours-toggle aria-expanded="false" style="display: inline-flex; align-items: center; gap: 0.28rem;">
                                            <span>TOURS</span>
                                            <span aria-hidden="true" style="font-size: 0.72rem; line-height: 1;">&#9662;</span>
                                        </button>
                                        <div class="tours-menu-panel" style="border-radius: 0;">
                                            <a href="{{ route('tours.show', 'day-trip') }}" class="tours-menu-link" style="border-radius: 0;">Day Trip</a>
                                            <a href="{{ route('tours.show', '2d1n-trip') }}" class="tours-menu-link" style="border-radius: 0;">2D1N Trip</a>
                                            <a href="{{ route('tours.show', '3d2n-trip') }}" class="tours-menu-link" style="border-radius: 0;">3D2N Trip</a>
                                            <a href="{{ route('tours.show', '4d3n-trip') }}" class="tours-menu-link" style="border-radius: 0;">4D3N Trip</a>
                                        </div>
                                    </div>
                                    <a href="{{ route('blog.index') }}" class="main-nav-link whitespace-nowrap">Blog</a>
                                    <a href="{{ route('home') }}#about-us" class="main-nav-link whitespace-nowrap">About Us</a>
                                    <a href="{{ route('bookings.track.form') }}" class="main-nav-link whitespace-nowrap">Track Booking</a>
                                </div>
                            </nav>
                        </div>
                    @endif
                </header>
            @endunless

            @if (session('success') || $errors->any())
                <div class="app-toast-stack" aria-live="polite" aria-atomic="true">
                    @if (session('success'))
                        <div class="app-toast js-app-toast border-emerald-200 bg-emerald-50/95 text-emerald-800">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                    <span class="text-sm font-bold">✓</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold">Success</p>
                                    <p class="mt-1 text-sm leading-6">{{ session('success') }}</p>
                                </div>
                                <button type="button" class="js-app-toast-close inline-flex h-8 w-8 items-center justify-center rounded-full text-emerald-700/70 transition hover:bg-emerald-100 hover:text-emerald-900" aria-label="Dismiss notification">
                                    <span class="text-lg leading-none">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="app-toast js-app-toast border-rose-200 bg-rose-50/95 text-rose-800">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                                    <span class="text-sm font-bold">!</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold">Please check this</p>
                                    <p class="mt-1 text-sm leading-6">{{ $errors->first() }}</p>
                                </div>
                                <button type="button" class="js-app-toast-close inline-flex h-8 w-8 items-center justify-center rounded-full text-rose-700/70 transition hover:bg-rose-100 hover:text-rose-900" aria-label="Dismiss notification">
                                    <span class="text-lg leading-none">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($isAdminRoute)
                <x-admin-sidebar />
            @endif

            <div class="admin-shell {{ $isAdminRoute ? 'with-sidebar flex min-h-screen flex-col' : 'flex flex-1 flex-col' }}" style="{{ $hideHeader ? '' : 'padding-top: var(--app-header-offset, 0px);' }}">
                {{ $slot }}

                @if ($isAdminRoute)
                    <footer class="mt-auto border-t border-stone-200/80 bg-white/70">
                        <div class="mx-auto max-w-[1700px] px-6 py-4 text-center text-xs font-medium uppercase tracking-[0.18em] text-stone-500 lg:px-10">
                            Copyright 2026 by universaledenholidays @ Adcey
                        </div>
                    </footer>
                @endif
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const root = document.documentElement;
                const header = document.querySelector('.js-app-header');
                const toasts = Array.from(document.querySelectorAll('.js-app-toast'));
                const siteNavDetails = Array.from(document.querySelectorAll('.js-site-nav'));
                const toursMenus = Array.from(document.querySelectorAll('[data-tours-menu]'));
                const anchorOffsetExtra = 0;
                const anchorOffsetOverrides = {
                    '#popular-picks': 24,
                };

                const updateHeaderOffset = () => {
                    root.style.setProperty('--app-header-offset', `${header?.offsetHeight ?? 0}px`);
                };

                const scrollToHashTarget = (hash, behavior = 'auto') => {
                    if (!hash || hash === '#') {
                        return;
                    }

                    const target = document.querySelector(hash);

                    if (!target) {
                        return;
                    }

                    const headerOffset = header?.offsetHeight ?? 0;
                    const targetTop = target.getBoundingClientRect().top + window.scrollY;
                    const sectionOffset = anchorOffsetOverrides[hash] ?? anchorOffsetExtra;
                    const scrollTop = Math.max(0, targetTop - headerOffset - sectionOffset);

                    window.scrollTo({
                        top: scrollTop,
                        behavior,
                    });
                };

                updateHeaderOffset();
                window.addEventListener('resize', updateHeaderOffset);

                document.querySelectorAll('a[href*="#"]').forEach((link) => {
                    link.addEventListener('click', (event) => {
                        const url = new URL(link.href, window.location.href);

                        if (!url.hash || url.pathname !== window.location.pathname || url.origin !== window.location.origin) {
                            return;
                        }

                        const target = document.querySelector(url.hash);

                        if (!target) {
                            return;
                        }

                        event.preventDefault();
                        window.history.pushState({}, '', url.hash);
                        scrollToHashTarget(url.hash, 'smooth');
                    });
                });

                window.addEventListener('hashchange', () => {
                    scrollToHashTarget(window.location.hash, 'auto');
                });

                if (window.location.hash) {
                    window.setTimeout(() => {
                        scrollToHashTarget(window.location.hash, 'auto');
                    }, 0);
                }

                const closeSiteNavs = (activeNav = null) => {
                    siteNavDetails.forEach((details) => {
                        if (details !== activeNav) {
                            details.removeAttribute('open');
                        }
                    });
                };

                const closeToursMenus = (activeMenu = null) => {
                    toursMenus.forEach((menu) => {
                        const toggle = menu.querySelector('[data-tours-toggle]');

                        if (menu !== activeMenu) {
                            menu.classList.remove('is-open');
                            toggle?.setAttribute('aria-expanded', 'false');
                        }
                    });
                };

                siteNavDetails.forEach((details) => {
                    details.addEventListener('toggle', () => {
                        if (details.open) {
                            closeSiteNavs(details);
                        }
                    });
                });

                document.addEventListener('click', (event) => {
                    const target = event.target;

                    if (!(target instanceof Node)) {
                        return;
                    }

                    if (siteNavDetails.some((details) => details.contains(target))) {
                        return;
                    }

                    closeSiteNavs();
                });

                document.querySelectorAll('.js-site-nav-link').forEach((link) => {
                    link.addEventListener('click', () => {
                        closeSiteNavs();
                    });
                });

                toursMenus.forEach((menu) => {
                    const toggle = menu.querySelector('[data-tours-toggle]');
                    const links = menu.querySelectorAll('.tours-menu-link');

                    toggle?.addEventListener('click', (event) => {
                        event.preventDefault();
                        const willOpen = !menu.classList.contains('is-open');

                        closeToursMenus();
                        menu.classList.toggle('is-open', willOpen);
                        toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                    });

                    links.forEach((link) => {
                        link.addEventListener('click', () => {
                            closeToursMenus();
                        });
                    });
                });

                document.addEventListener('click', (event) => {
                    const target = event.target;

                    if (!(target instanceof Node)) {
                        return;
                    }

                    if (toursMenus.some((menu) => menu.contains(target))) {
                        return;
                    }

                    closeToursMenus();
                });

                if (!toasts.length) {
                    return;
                }

                const closeToast = (toast) => {
                    if (!toast || toast.dataset.closed === 'true') {
                        return;
                    }

                    toast.dataset.closed = 'true';
                    toast.classList.add('is-hiding');

                    window.setTimeout(() => {
                        toast.remove();
                    }, 250);
                };

                toasts.forEach((toast) => {
                    const closeButton = toast.querySelector('.js-app-toast-close');
                    closeButton?.addEventListener('click', () => closeToast(toast));

                    window.setTimeout(() => {
                        closeToast(toast);
                    }, 5000);
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const storagePrefix = 'ueh-form-draft:';
                const successFlag = document.body.dataset.formPersistSuccess === 'true';
                const lastSubmittedKeyStorage = `${storagePrefix}last-submitted`;
                const lastSubmittedKey = window.sessionStorage.getItem(lastSubmittedKeyStorage);

                const readStoredValue = (key) => {
                    try {
                        const rawValue = window.localStorage.getItem(key);

                        return rawValue ? JSON.parse(rawValue) : null;
                    } catch (error) {
                        return null;
                    }
                };

                const writeStoredValue = (key, value) => {
                    try {
                        window.localStorage.setItem(key, JSON.stringify(value));
                    } catch (error) {
                        // Ignore localStorage quota and privacy-mode failures.
                    }
                };

                const removeStoredValue = (key) => {
                    try {
                        window.localStorage.removeItem(key);
                    } catch (error) {
                        // Ignore localStorage failures.
                    }
                };

                const getPersistKey = (form) => {
                    const explicitKey = form.dataset.formPersist;

                    if (explicitKey) {
                        return `${storagePrefix}${explicitKey}`;
                    }

                    return `${storagePrefix}${form.getAttribute('action') || form.id || 'form'}`;
                };

                const formHasMeaningfulValues = (form) => Array.from(form.elements).some((field) => {
                    if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement)) {
                        return false;
                    }

                    if (['file', 'submit', 'button', 'reset', 'hidden'].includes(field.type)) {
                        return false;
                    }

                    if (field instanceof HTMLInputElement && ['checkbox', 'radio'].includes(field.type)) {
                        return field.checked;
                    }

                    return String(field.value || '').trim() !== '';
                });

                if (successFlag && lastSubmittedKey) {
                    removeStoredValue(lastSubmittedKey);
                    window.sessionStorage.removeItem(lastSubmittedKeyStorage);
                }

                document.querySelectorAll('form[data-form-persist]').forEach((form) => {
                    const persistKey = getPersistKey(form);
                    const storedDraft = readStoredValue(persistKey);
                    let restoredDraft = false;

                    if (storedDraft && typeof storedDraft === 'object') {
                        Array.from(form.elements).forEach((field) => {
                            if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement)) {
                                return;
                            }

                            if (['file', 'submit', 'button', 'reset', 'hidden'].includes(field.type)) {
                                return;
                            }

                            const fieldKey = field.name || field.id;

                            if (!fieldKey || !(fieldKey in storedDraft)) {
                                return;
                            }

                            const savedValue = storedDraft[fieldKey];

                            if (field instanceof HTMLInputElement && field.type === 'checkbox') {
                                field.checked = Array.isArray(savedValue)
                                    ? savedValue.includes(field.value)
                                    : Boolean(savedValue);
                                restoredDraft = true;
                                return;
                            }

                            if (field instanceof HTMLInputElement && field.type === 'radio') {
                                field.checked = savedValue === field.value;
                                restoredDraft = true;
                                return;
                            }

                            field.value = savedValue ?? '';
                            restoredDraft = true;
                        });
                    }

                    const persistDraft = () => {
                        const payload = {};

                        Array.from(form.elements).forEach((field) => {
                            if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement || field instanceof HTMLSelectElement)) {
                                return;
                            }

                            if (['file', 'submit', 'button', 'reset', 'hidden'].includes(field.type)) {
                                return;
                            }

                            const fieldKey = field.name || field.id;

                            if (!fieldKey) {
                                return;
                            }

                            if (field instanceof HTMLInputElement && field.type === 'checkbox') {
                                if (!Array.isArray(payload[fieldKey])) {
                                    payload[fieldKey] = [];
                                }

                                if (field.checked) {
                                    payload[fieldKey].push(field.value);
                                }

                                return;
                            }

                            if (field instanceof HTMLInputElement && field.type === 'radio') {
                                if (field.checked) {
                                    payload[fieldKey] = field.value;
                                }

                                return;
                            }

                            payload[fieldKey] = field.value;
                        });

                        if (!formHasMeaningfulValues(form)) {
                            removeStoredValue(persistKey);
                            return;
                        }

                        writeStoredValue(persistKey, payload);
                    };

                    form.addEventListener('input', persistDraft);
                    form.addEventListener('change', persistDraft);
                    form.addEventListener('submit', () => {
                        persistDraft();
                        window.sessionStorage.setItem(lastSubmittedKeyStorage, persistKey);
                    });
                    form.addEventListener('reset', () => {
                        window.setTimeout(() => removeStoredValue(persistKey), 0);
                    });

                    if (restoredDraft) {
                        form.dataset.draftRestored = 'true';

                        const parentDetails = form.closest('details');
                        if (parentDetails) {
                            parentDetails.open = true;
                        }

                        document.dispatchEvent(new CustomEvent('codex:form-draft-restored', {
                            detail: {
                                formId: form.id || null,
                                persistKey,
                            },
                        }));
                    }
                });
            });
        </script>
    </body>
</html>


