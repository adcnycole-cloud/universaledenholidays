<x-layouts.app title="Track Booking | Universal Eden Holidays">
    <style>
        .track-booking-shell {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.08), transparent 24rem),
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.08), transparent 22rem),
                linear-gradient(180deg, #fffdf8 0%, #f8fafc 100%);
        }

        .track-booking-shell::before,
        .track-booking-shell::after {
            content: '';
            position: absolute;
            top: 6rem;
            bottom: 5rem;
            width: min(18vw, 15rem);
            opacity: 0.18;
            pointer-events: none;
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            filter: blur(0.2px);
        }

        .track-booking-shell::before {
            left: -2rem;
            background-image:
                radial-gradient(circle at 30% 30%, rgba(120, 180, 150, 0.18) 0, rgba(120, 180, 150, 0.18) 18%, transparent 19%),
                radial-gradient(circle at 65% 45%, rgba(120, 180, 150, 0.15) 0, rgba(120, 180, 150, 0.15) 16%, transparent 17%),
                radial-gradient(circle at 45% 72%, rgba(120, 180, 150, 0.14) 0, rgba(120, 180, 150, 0.14) 14%, transparent 15%);
        }

        .track-booking-shell::after {
            right: -2rem;
            background-image:
                radial-gradient(circle at 62% 28%, rgba(148, 163, 184, 0.18) 0, rgba(148, 163, 184, 0.18) 18%, transparent 19%),
                radial-gradient(circle at 35% 52%, rgba(148, 163, 184, 0.14) 0, rgba(148, 163, 184, 0.14) 15%, transparent 16%),
                radial-gradient(circle at 58% 76%, rgba(148, 163, 184, 0.12) 0, rgba(148, 163, 184, 0.12) 13%, transparent 14%);
        }

        .track-booking-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
        }

        .track-booking-image {
            background-image:
                linear-gradient(180deg, rgba(12, 74, 110, 0.12) 0%, rgba(15, 23, 42, 0.2) 100%),
                url('{{ asset('track_bg.png') }}');
            background-size: cover;
            background-position: center;
        }

        .track-booking-step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.35rem;
            height: 3.35rem;
            border-radius: 999px;
            border: 2px solid rgba(251, 191, 36, 0.9);
            color: #fbbf24;
            font-size: 1.55rem;
            font-weight: 600;
            line-height: 1;
            background: rgba(5, 84, 55, 0.28);
        }

        .track-booking-step-badge--left {
            transform: translateX(20px);
        }

        .track-booking-step-badge--right {
            transform: translateX(-28px);
        }

        .track-booking-steps {
            display: grid;
            grid-template-columns: auto minmax(4rem, 1fr) auto minmax(4rem, 1fr) auto;
            align-items: center;
            column-gap: 1.5rem;
            width: min(100%, 36rem);
            margin: 0 auto;
        }

        .track-booking-step-line {
            height: 2px;
            width: 100%;
            background: rgba(251, 191, 36, 0.72);
        }

        .track-booking-step-labels {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.6rem;
            margin-top: 1rem;
            width: min(100%, 36rem);
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .track-booking-step-label--left {
            justify-self: start;
            text-align: left;
            transform: translateX(-10px);
        }

        .track-booking-step-label--center {
            justify-self: center;
            text-align: center;
        }

        .track-booking-step-label--right {
            justify-self: end;
            text-align: right;
            transform: translateX(18px);
        }

        .track-booking-step-panel {
            width: 94%;
        }

        @media (max-width: 767px) {
            .track-booking-image {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .track-booking-step-panel {
                width: 100%;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        @media (max-width: 1023px) {
            .track-booking-image {
                min-height: 24rem;
            }
        }

        @media (min-width: 1024px) {
            .track-booking-grid {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1.16fr);
            }

            .track-booking-step-panel {
                width: 92%;
            }
        }
    </style>

    <main class="track-booking-shell">
        <section class="mx-auto px-6 py-10 lg:px-10 lg:py-14" style="max-width: 96rem;">
            <div class="track-booking-card mx-auto mt-10 w-full overflow-hidden rounded-[1.25rem] border border-stone-200/80 bg-white lg:mt-14" style="max-width: 86rem;">
                <div class="track-booking-grid">
                    <div class="px-6 pb-12 pt-14 md:px-10 md:pb-14 md:pt-16 lg:px-12 lg:pb-16 lg:pt-20">
                        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-700">Booking Tracker</p>
                        <h1 class="mt-4 font-['Prata'] text-4xl leading-tight text-slate-900 md:text-5xl">Track Your Booking</h1>
                        <p class="mt-5 max-w-xl text-lg leading-8 text-stone-500">
                            Enter your Booking ID to view your booking status and continue securely to payment.
                        </p>

                        <form method="POST" action="{{ route('bookings.track.find') }}" class="mt-8 space-y-5">
                            @csrf

                            <div>
                                <label for="booking_reference" class="mb-2 block text-lg font-semibold text-slate-900">Booking ID</label>
                                <div class="flex items-center rounded-xl border border-stone-300 bg-white px-4 py-2.5 shadow-[inset_0_1px_2px_rgba(15,23,42,0.03)] transition focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-100">
                                    <input
                                        id="booking_reference"
                                        name="booking_reference"
                                        type="text"
                                        value="{{ old('booking_reference') }}"
                                        placeholder="UEH-ABC12345"
                                        class="w-full border-0 bg-transparent p-0 text-base text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-0"
                                        required
                                    >
                                </div>
                                @error('booking_reference')
                                    <p class="mt-3 text-sm font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-5 px-6 py-[1.05rem] transition hover:opacity-95"
                                style="background:#1f7f4d; color:#ffffff; border:2px solid #14683a; border-radius:0.6rem; box-shadow:0 8px 18px rgba(31,127,77,0.18), 0 0 0 3px rgba(45,138,88,0.28); font-size:1.02rem; font-weight:600; letter-spacing:0.01em;"
                            >
                                <span>Track Booking</span>
                                <span aria-hidden="true" style="font-size:1.9rem; font-weight:400; line-height:1;">&rarr;</span>
                            </button>

                            <div class="mt-2 mb-8 flex items-center justify-center gap-3 text-center text-sm text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="11" width="18" height="10" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <span>Secure payment powered by HitPay</span>
                            </div>
                        </form>

                        <div class="mt-16 flex w-full items-center gap-4 text-amber-300">
                            <div class="h-px flex-1 bg-amber-300"></div>
                            <span class="text-xl leading-none">✻</span>
                            <div class="h-px flex-1 bg-amber-300"></div>
                        </div>

                        <div class="mt-8 flex items-start gap-3 text-base leading-7 text-slate-500">
                            <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-700" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7h16v10H4z" />
                                    <path d="m4 8 8 6 8-6" />
                                </svg>
                            </span>
                            <p>Can't find your Booking ID? Check your confirmation email.</p>
                        </div>
                    </div>

                    <div class="track-booking-image relative flex min-h-[35rem] items-end px-3 pb-16 pt-3 md:px-4 md:pb-20 md:pt-4 lg:px-5 lg:pb-24 lg:pt-5">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/28 via-transparent to-transparent"></div>
                        <div class="track-booking-step-panel relative z-10 mx-auto mt-auto rounded-[1.5rem] px-8 py-4 text-white backdrop-blur-sm md:px-10 md:py-5" style="margin-bottom: 70px; background: linear-gradient(180deg, rgba(6, 71, 51, 0.84) 0%, rgba(6, 90, 63, 0.9) 100%); box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 18px 34px rgba(4, 53, 39, 0.22);">
                            <div class="track-booking-steps">
                                <div class="track-booking-step-badge track-booking-step-badge--left">1</div>
                                <div class="track-booking-step-line"></div>
                                <div class="track-booking-step-badge">2</div>
                                <div class="track-booking-step-line"></div>
                                <div class="track-booking-step-badge track-booking-step-badge--right">3</div>
                            </div>
                            <div class="track-booking-step-labels">
                                <p class="track-booking-step-label--left text-[1.1rem] font-semibold leading-7 text-white">Enter Booking ID</p>
                                <p class="track-booking-step-label--center text-[1.1rem] font-semibold leading-7 text-white">Review booking</p>
                                <p class="track-booking-step-label--right text-[1.1rem] font-semibold leading-7 text-white">Continue to payment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
