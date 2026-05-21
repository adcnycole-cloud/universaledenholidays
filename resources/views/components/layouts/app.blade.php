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
    <body class="min-h-screen bg-stone-50 text-stone-900">
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
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.08),_transparent_30%),linear-gradient(180deg,_#fffdf9,_#f8fafc)]">
            @unless ($hideHeader)
                <header class="js-app-header border-b shadow-[0_10px_24px_rgba(15,23,42,0.08)] {{ $isAdminRoute ? 'border-emerald-200 bg-white' : 'border-stone-200 bg-white' }} ">
                    <div class="{{ $isAdminRoute ? 'grid w-full grid-cols-[1fr_auto_1fr] items-center px-6 py-3 lg:px-10' : 'flex w-full items-center justify-between px-6 py-3 lg:px-10' }}">
                        @if ($isAdminRoute)
                            <div class="justify-self-start" aria-hidden="true"></div>
                        @else
                            <a href="{{ route('home') }}" class="flex items-center gap-3" style="position: relative; left: 1rem;">
                                <img src="{{ asset('images/ue_logo.jpg') }}" alt="Universal Eden Logo" class="h-11 w-11 rounded-full object-cover">
                                <span class="font-['Prata'] text-xl text-stone-900">Universal Eden Holidays</span>
                            </a>
                        @endif

                        @if ($isAdminRoute)
                            <p class="hidden md:block text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Admin Workspace</p>
                        @else
                            <nav class="hidden items-center gap-6 text-sm font-medium text-stone-600 md:flex">
                                <a href="{{ route('home') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 bg-white text-stone-600 transition hover:border-sky-200 hover:text-sky-700" aria-label="Home">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 10.5 12 3l9 7.5" />
                                        <path d="M5 9.5V21h14V9.5" />
                                        <path d="M9 21v-6h6v6" />
                                    </svg>
                                </a>
                                <a href="{{ route('home') }}#transport" class="transition hover:text-sky-700">Transport</a>
                                <a href="{{ route('home') }}#packages-showcase" class="transition hover:text-sky-700">Packages</a>
                                <a href="{{ route('home') }}#testimonials" class="transition hover:text-sky-700">Testimonials</a>
                                <a href="{{ route('bookings.track.form') }}" class="transition hover:text-sky-700">Track Booking</a>
                                <a href="{{ route('home') }}#about-us" class="transition hover:text-sky-700">About Us</a>
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

            <div class="admin-shell {{ $isAdminRoute ? 'with-sidebar' : '' }}" style="{{ $hideHeader ? '' : 'padding-top: var(--app-header-offset, 0px);' }}">
                {{ $slot }}

                @if ($isAdminRoute)
                    <footer class="border-t border-stone-200/80 bg-white/70">
                        <div class="mx-auto max-w-[1700px] px-6 py-4 text-center text-xs font-medium uppercase tracking-[0.18em] text-stone-500 lg:px-10">
                            Copyright by universaledenholidays @ Adcey
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

                const updateHeaderOffset = () => {
                    root.style.setProperty('--app-header-offset', `${header?.offsetHeight ?? 0}px`);
                };

                updateHeaderOffset();
                window.addEventListener('resize', updateHeaderOffset);

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
    </body>
</html>
