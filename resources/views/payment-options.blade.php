<x-layouts.app title="Payment Options | Universal Eden Holidays">
    <main class="payments-page min-h-[calc(100vh-var(--app-header-offset,0px))]">
        <section class="payments-shell">
            <header class="payments-intro">
                <span class="payments-kicker">Booking With Confidence</span>
                <h1>Simple, Secure Payment Options</h1>
                <p>Choose the payment method that suits your travel plans. Our team is here to make every step clear and easy.</p>
            </header>

            <section class="payments-assurance" aria-label="Payment assurance">
                <div><span>✓</span><strong>Secure Checkout</strong><small>Your payment details are handled carefully.</small></div>
                <div><span>✓</span><strong>Flexible Choices</strong><small>Select the payment method that works for you.</small></div>
                <div><span>✓</span><strong>Helpful Support</strong><small>Our team can guide you before you confirm.</small></div>
            </section>

            <section class="payments-options" aria-labelledby="payment-options-title">
                <div class="payments-section-heading">
                    <div><span class="payments-kicker">Payment Methods</span><h2 id="payment-options-title">Choose How You Would Like to Pay</h2></div>
                    <p>All available options are shown below. Review the details, then continue with your preferred method during booking.</p>
                </div>

                <div class="payments-grid">
                    @foreach ($paymentOptions as $option)
                        <article class="payment-card">
                            <div class="payment-card-top">
                                <span class="payment-number">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="payment-icon" aria-hidden="true">{{ ['⌁', '◈', '◉', '▣'][$loop->index % 4] }}</span>
                            </div>
                            <span class="payment-badge">{{ $option['badge'] }}</span>
                            <h3>{{ $option['title'] }}</h3>
                            <p>{{ $option['description'] }}</p>
                            <ul>
                                @foreach ($option['notes'] as $note)
                                    <li>{{ $note }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="payments-help">
                <div><span class="payments-kicker">Need A Hand?</span><h2>We Are Here to Help With Your Booking</h2><p>If you have a question about payment, contact our team before confirming your reservation.</p></div>
                <a href="{{ route('booking.create') }}">Start Your Booking <span aria-hidden="true">→</span></a>
            </section>
        </section>
    </main>

    <style>
        .payments-page { background: #faf7f0; color: #132c4e; }.payments-shell { width: min(100% - 2rem, 1120px); margin: 0 auto; padding: 2.5rem 0 4rem; }.payments-intro { text-align: center; }.payments-kicker { color: #24953a; font-size: .66rem; font-weight: 800; letter-spacing: .22em; text-transform: uppercase; }.payments-intro h1 { margin-top: .4rem; color: #132c4e; font-size: clamp(1.8rem, 3.3vw, 2.5rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; }.payments-intro p { max-width: 42rem; margin: .55rem auto 0; color: #788496; font-size: .8rem; line-height: 1.6; }
        .payments-assurance { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; margin-top: 1.35rem; padding: 1.25rem 1.6rem; border: 1px solid #e5e7e5; border-radius: .9rem; background: #fff; box-shadow: 0 8px 20px rgba(23, 44, 73, .08); }.payments-assurance div { display: grid; grid-template-columns: 2.25rem 1fr; column-gap: .65rem; align-items: center; }.payments-assurance span { display: flex; grid-row: span 2; width: 2rem; height: 2rem; align-items: center; justify-content: center; border-radius: 50%; background: #e5f4e8; color: #24953a; font-size: 1rem; font-weight: 900; }.payments-assurance strong { color: #1b3353; font-size: .82rem; }.payments-assurance small { color: #748195; font-size: .63rem; line-height: 1.35; }
        .payments-options { margin-top: 2.2rem; }.payments-section-heading { display: flex; align-items: end; justify-content: space-between; gap: 2rem; }.payments-section-heading h2 { margin-top: .35rem; color: #173151; font-size: 1.35rem; font-weight: 800; }.payments-section-heading > p { max-width: 24rem; color: #748195; font-size: .72rem; line-height: 1.5; text-align: right; }.payments-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .8rem; margin-top: .85rem; }.payment-card { min-height: 15.5rem; padding: 1rem; border: 1px solid #e1e7e3; border-radius: .8rem; background: #fff; box-shadow: 0 5px 13px rgba(23, 44, 73, .06); transition: transform .2s ease, box-shadow .2s ease; }.payment-card:hover { transform: translateY(-3px); box-shadow: 0 11px 20px rgba(23, 44, 73, .1); }.payment-card-top { display: flex; align-items: center; justify-content: space-between; }.payment-number { color: #a3afb0; font-size: .65rem; font-weight: 800; letter-spacing: .14em; }.payment-icon { display: flex; width: 2rem; height: 2rem; align-items: center; justify-content: center; border-radius: .45rem; background: #e6f4e9; color: #25983a; font-size: 1.15rem; }.payment-badge { display: inline-block; margin-top: .8rem; border-radius: .25rem; background: #e5f4e8; color: #248337; padding: .2rem .4rem; font-size: .58rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }.payment-card h3 { margin-top: .5rem; color: #193352; font-size: 1.05rem; font-weight: 800; }.payment-card p { margin-top: .35rem; color: #6e7b8e; font-size: .7rem; line-height: 1.5; }.payment-card ul { display: grid; gap: .28rem; margin-top: .75rem; }.payment-card li { display: flex; gap: .4rem; color: #526276; font-size: .67rem; line-height: 1.35; }.payment-card li::before { content: '✓'; color: #25983a; font-weight: 900; }.payments-help { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; margin-top: 2rem; padding: 1.35rem 1.5rem; border-radius: .8rem; background: #163254; }.payments-help h2 { margin-top: .3rem; color: #fff; font-size: 1.2rem; font-weight: 800; }.payments-help p { margin-top: .3rem; color: #c6d2df; font-size: .72rem; }.payments-help a { display: inline-flex; flex: 0 0 auto; align-items: center; gap: .45rem; border-radius: .35rem; background: #35a846; color: #fff; padding: .7rem 1rem; font-size: .72rem; font-weight: 800; text-decoration: none; }
        @media (max-width: 700px) { .payments-assurance { grid-template-columns: 1fr; }.payments-section-heading { display: block; }.payments-section-heading > p { margin-top: .5rem; text-align: left; }.payments-grid { grid-template-columns: 1fr; }.payments-help { align-items: flex-start; flex-direction: column; } }.payments-help .payments-kicker { color: #7de08c; } @media (max-width: 560px) { .payments-shell { width: min(100% - 1.2rem, 1120px); padding-top: 2rem; }.payments-assurance { padding: 1rem; } }
    </style>

    @include('partials.footer')
</x-layouts.app>
