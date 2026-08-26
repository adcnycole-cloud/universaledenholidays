@php
    $tourLinks = [
        ['label' => 'Day Trip', 'route' => route('tours.show', 'day-trip'), 'active' => request()->routeIs('tours.show') && request()->route('tourType') === 'day-trip'],
        ['label' => '2D1N Trip', 'route' => route('tours.show', '2d1n-trip'), 'active' => request()->routeIs('tours.show') && request()->route('tourType') === '2d1n-trip'],
        ['label' => '3D2N Trip', 'route' => route('tours.show', '3d2n-trip'), 'active' => request()->routeIs('tours.show') && request()->route('tourType') === '3d2n-trip'],
        ['label' => '4D3N Trip', 'route' => route('tours.show', '4d3n-trip'), 'active' => request()->routeIs('tours.show') && request()->route('tourType') === '4d3n-trip'],
        ['label' => 'All Packages', 'route' => route('tours.index'), 'active' => request()->routeIs('tours.index')],
    ];

    $aboutLinks = [
        ['label' => 'About Us', 'route' => route('about-us'), 'active' => request()->routeIs('about-us')],
        ['label' => 'Customer Reviews', 'route' => route('reviews.index'), 'active' => request()->routeIs('reviews.index')],
        ['label' => 'Travel Blog', 'route' => route('blog.index'), 'active' => request()->routeIs('blog.index', 'blog.show')],
        ['label' => 'Terms and Condition', 'route' => route('legal.terms-and-conditions'), 'active' => request()->routeIs('legal.terms-and-conditions')],
        ['label' => 'Payment Options', 'route' => route('payment-options'), 'active' => request()->routeIs('payment-options')],
    ];

    $isTourActive = request()->routeIs('tours.*');
    $isAboutActive = request()->routeIs('about-us', 'reviews.*', 'blog.*', 'legal.*', 'payment-options');
@endphp

<nav class="mobile-nav" aria-label="Mobile navigation">
    <a href="{{ route('home') }}" class="mobile-nav-link js-site-nav-link{{ request()->routeIs('home') ? ' is-active' : '' }}">Home</a>
    <a href="{{ route('transport.index') }}" class="mobile-nav-link js-site-nav-link{{ request()->routeIs('transport.index') ? ' is-active' : '' }}">Transport</a>

    <div class="mobile-nav-group">
        <button type="button" class="mobile-nav-toggle" aria-expanded="{{ $isTourActive ? 'true' : 'false' }}" aria-controls="mn-tour-packages" data-mobile-nav-toggle>
            <span>Tour Packages</span>
            <svg class="mobile-nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
        </button>
        <div class="mobile-nav-submenu{{ $isTourActive ? ' is-open' : '' }}" id="mn-tour-packages">
            <div class="mobile-nav-submenu-inner">
                @foreach ($tourLinks as $tourLink)
                    <a href="{{ $tourLink['route'] }}" class="mobile-nav-sublink js-site-nav-link{{ $tourLink['active'] ? ' is-active' : '' }}">{{ $tourLink['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mobile-nav-group">
        <button type="button" class="mobile-nav-toggle" aria-expanded="{{ $isAboutActive ? 'true' : 'false' }}" aria-controls="mn-about" data-mobile-nav-toggle>
            <span>About Us</span>
            <svg class="mobile-nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"></path></svg>
        </button>
        <div class="mobile-nav-submenu{{ $isAboutActive ? ' is-open' : '' }}" id="mn-about">
            <div class="mobile-nav-submenu-inner">
                @foreach ($aboutLinks as $aboutLink)
                    <a href="{{ $aboutLink['route'] }}" class="mobile-nav-sublink js-site-nav-link{{ $aboutLink['active'] ? ' is-active' : '' }}">{{ $aboutLink['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <a href="{{ route('bookings.track.form') }}" class="mobile-nav-link js-site-nav-link{{ request()->routeIs('bookings.track.*') ? ' is-active' : '' }}">Track Booking</a>

    <a href="{{ route('booking.create') }}" class="mobile-nav-cta">Book Now</a>
</nav>
