{{--
    Shared site footer partial.
    Include this in all public-facing pages:
        @include('partials.footer')
--}}

<style>
    .site-footer {
        position: relative;
        overflow: hidden;
        margin-top: 0;
        color: rgba(226, 232, 240, 0.9);
        background:
            linear-gradient(135deg, rgba(6, 19, 38, 0.97), rgba(12, 28, 52, 0.96)),
            url('{{ asset('background.png') }}') center center / cover no-repeat;
    }

    .site-footer::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at left top, rgba(61, 182, 138, 0.12), transparent 34%),
            radial-gradient(circle at right bottom, rgba(61, 182, 138, 0.08), transparent 28%);
        pointer-events: none;
    }

    .site-footer::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg, rgba(82, 232, 179, 0.22), rgba(82, 232, 179, 0)) top / 100% 2px no-repeat;
        pointer-events: none;
    }

    .site-footer-shell {
        position: relative;
        z-index: 1;
        width: min(100%, 1520px);
        margin: 0 auto;
        padding: 3rem 2rem 1.1rem;
    }

    .site-footer-main {
        display: grid;
        grid-template-columns: minmax(0, 2.15fr) minmax(0, 0.62fr) minmax(0, 0.62fr) minmax(0, 0.82fr);
        gap: 2.6rem;
        align-items: start;
    }

    .site-footer-brand {
        max-width: 44rem;
        padding-right: 3rem;
        border-right: 1px solid rgba(255, 255, 255, 0.16);
    }

    .site-footer-brandmark {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .site-footer-brandmark img {
        height: 1.5rem;
        width: auto;
        object-fit: contain;
    }

    .site-footer-brandmark-divider {
        width: 1px;
        height: 1.9rem;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 999px;
    }

    .site-footer-brandmark h3 {
        margin: 0;
        font-family: "Playfair Display", Georgia, serif;
        font-size: 1.45rem;
        line-height: 1.1;
        color: #f8fafc;
    }

    .site-footer-copy {
        margin-top: 1.35rem;
        max-width: 38rem;
        color: rgba(226, 232, 240, 0.82);
        font-size: 0.86rem;
        line-height: 1.75;
    }

    .site-footer-socials {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.35rem;
    }

    .site-footer-social {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 0.7rem;
        color: #f8fafc;
        background: rgba(255, 255, 255, 0.04);
        transition: transform 0.2s ease, border-color 0.2s ease, background-color 0.2s ease;
    }

    .site-footer-social svg {
        width: 1.1rem;
        height: 1.1rem;
    }

    .site-footer-social:hover {
        transform: translateY(-2px);
        border-color: rgba(82, 232, 179, 0.42);
        background: rgba(82, 232, 179, 0.08);
    }

    .site-footer-col-title,
    .site-footer-newsletter-title {
        margin: 0;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #ffffff;
    }

    .site-footer-col-title::after,
    .site-footer-newsletter-title::after {
        content: "";
        display: block;
        width: 4rem;
        height: 3px;
        margin-top: 0.7rem;
        border-radius: 999px;
        background: linear-gradient(90deg, #4fd8a5, #59c8b2);
    }

    .site-footer-links,
    .site-footer-contact-list {
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
        margin-top: 1.2rem;
    }

    .site-footer-links a,
    .site-footer-contact-item,
    .site-footer-contact-item a {
        color: rgba(226, 232, 240, 0.78);
        font-size: 0.86rem;
        line-height: 1.5;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .site-footer-links a:hover,
    .site-footer-contact-item a:hover {
        color: #ffffff;
    }

    .site-footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
    }

    .site-footer-contact-wrap {
        max-width: 22rem;
    }

    .site-footer-contact-icon {
        flex: 0 0 auto;
        width: 1.5rem;
        height: 1.5rem;
        color: #2fcf98;
        margin-top: 0.05rem;
    }

    .site-footer-bottom {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-top: 2.6rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
    }

    .site-footer-bottom p,
    .site-footer-bottom a {
        margin: 0;
        color: rgba(226, 232, 240, 0.74);
        font-size: 0.84rem;
        text-decoration: none;
    }

    .site-footer-bottom a:hover {
        color: #ffffff;
    }

    .site-footer-bottom-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .site-footer-bottom-links a + a {
        position: relative;
        padding-left: 1.5rem;
    }

    .site-footer-bottom-links a + a::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        width: 1px;
        height: 1.4rem;
        background: rgba(255, 255, 255, 0.18);
        transform: translateY(-50%);
    }

    @media (max-width: 1180px) {
        .site-footer-main {
            grid-template-columns: minmax(0, 1.15fr) repeat(2, minmax(0, 1fr));
        }

        .site-footer-contact-wrap {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 900px) {
        .site-footer-shell {
            padding: 2.4rem 1.25rem 1rem;
        }

        .site-footer-main {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .site-footer-brand {
            max-width: none;
            padding-right: 0;
            border-right: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
            padding-bottom: 1.35rem;
        }

        .site-footer-contact-wrap {
            grid-column: auto;
        }

        .site-footer-bottom {
            flex-direction: column;
            align-items: flex-start;
            margin-top: 2rem;
        }
    }

    @media (max-width: 640px) {
        .site-footer-brandmark {
            align-items: flex-start;
            flex-direction: column;
        }

        .site-footer-brandmark h3 {
            font-size: 1.25rem;
        }

        .site-footer-copy,
        .site-footer-links a,
        .site-footer-contact-item,
        .site-footer-contact-item a {
            font-size: 0.82rem;
        }

        .site-footer-bottom-links {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .site-footer-bottom-links a + a {
            padding-left: 0;
        }

        .site-footer-bottom-links a + a::before {
            display: none;
        }
    }
</style>

<footer class="site-footer">
    <div class="site-footer-shell">
        <div class="site-footer-main">
            <div class="site-footer-brand">
                <div class="site-footer-brandmark">
                    <img
                        src="{{ asset('images/ue white.png') }}"
                        alt="Universal Eden Holidays Logo"
                    >
                    <span class="site-footer-brandmark-divider" aria-hidden="true"></span>
                    <h3>Universal Eden Holidays</h3>
                </div>

                <p class="site-footer-copy">
                    Travel planning for Sabah made easier with transport services, holiday packages, and practical booking support in one place.
                </p>

                <div class="site-footer-socials">
                    <a class="site-footer-social" href="https://www.facebook.com/universal.edenholidays" target="_blank" rel="noreferrer" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M13.5 8.5V6.8c0-.8.5-1.3 1.4-1.3H17V2.1c-.4-.1-1.6-.1-3-.1-3 0-5 1.8-5 5.1v1.4H6v3.7h3v9.8h4.5v-9.8h3.1l.5-3.7h-3.6Z"/></svg>
                    </a>
                    <a class="site-footer-social" href="https://www.instagram.com/ue.holidays/?hl=en" target="_blank" rel="noreferrer" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="4"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.4" cy="6.6" r="1"></circle></svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="site-footer-col-title">Explore</h4>
                <div class="site-footer-links">
                    <a href="{{ route('transport.index') }}">Transport</a>
                    <a href="{{ route('tours.show', 'day-trip') }}">Packages</a>
                    <a href="{{ route('blog.index') }}">Travel Blog</a>
                </div>
            </div>

            <div>
                <h4 class="site-footer-col-title">Company</h4>
                <div class="site-footer-links">
                    <a href="{{ route('about-us') }}">About Us</a>
                    <a href="{{ route('bookings.track.form') }}">Track Your Bookings</a>
                    @auth
                        <a href="{{ route('profile.show') }}">My Profile</a>
                    @else
                        <a href="{{ route('login') }}">Admin Login</a>
                    @endauth
                </div>
            </div>

            <div class="site-footer-contact-wrap">
                <h4 class="site-footer-col-title">Contact</h4>
                <div class="site-footer-contact-list">
                    <div class="site-footer-contact-item">
                        <svg class="site-footer-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z"></path><path d="m5 7 7 6 7-6"></path></svg>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=uniedenholidays@gmail.com" target="_blank" rel="noreferrer">uniedenholidays@gmail.com</a>
                    </div>
                    <div class="site-footer-contact-item">
                        <svg class="site-footer-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.8l-2 2a16 16 0 0 0 6.1 6.1l2-2a2 2 0 0 1 1.8-.6l3 .5a2 2 0 0 1 1.7 2Z"></path></svg>
                        <a href="tel:+60103869077">+60 10-386 9077</a>
                    </div>
                    <div class="site-footer-contact-item">
                        <svg class="site-footer-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 21s-6-5.4-6-10a6 6 0 1 1 12 0c0 4.6-6 10-6 10Z"></path><circle cx="12" cy="11" r="2.4"></circle></svg>
                        <span>Kota Kinabalu, Sabah, Malaysia</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="site-footer-bottom">
            <p>&copy; {{ now()->year }} Universal Eden Holidays. All rights reserved.</p>
            <div class="site-footer-bottom-links">
                <a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('legal.terms-and-conditions') }}">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
