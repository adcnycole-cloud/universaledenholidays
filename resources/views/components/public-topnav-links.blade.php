@props([
    'light' => false,
    'uppercaseDropdownLabels' => false,
])

@php
    $linkClass = 'main-nav-link whitespace-nowrap'.($light ? ' is-light' : '');
    $toggleClass = $linkClass.' tours-menu-toggle';
    $tourMenuLabel = $uppercaseDropdownLabels ? 'TOUR PACKAGES' : 'Tour Packages';
    $aboutMenuLabel = $uppercaseDropdownLabels ? 'ABOUT US' : 'About Us';
    $isHomeRoute = request()->routeIs('home');
    $isTransportRoute = request()->routeIs('transport.index', 'products.show') && (request()->routeIs('transport.index') || (isset($product) && ($product->category ?? null) === 'transport'));
    $isTourRoute = request()->routeIs('tours.show');
    $isAboutRoute = request()->routeIs('about-us', 'reviews.*', 'blog.*', 'legal.*', 'payment-options');
    $isTrackRoute = request()->routeIs('bookings.track.*');

    $tourLinks = [
        ['label' => 'Day Trip', 'route' => route('tours.show', 'day-trip')],
        ['label' => '2D1N Trip', 'route' => route('tours.show', '2d1n-trip')],
        ['label' => '3D2N Trip', 'route' => route('tours.show', '3d2n-trip')],
        ['label' => '4D3N Trip', 'route' => route('tours.show', '4d3n-trip')],
    ];

    $aboutLinks = [
        ['label' => 'About Us', 'route' => route('about-us')],
        ['label' => 'Customer Reviews', 'route' => route('reviews.index')],
        ['label' => 'Travel Blog', 'route' => route('blog.index')],
        ['label' => 'Terms and Condition', 'route' => route('legal.terms-and-conditions')],
        ['label' => 'Payment Options', 'route' => route('payment-options')],
    ];
@endphp

<a href="{{ route('home') }}" class="{{ $linkClass }}{{ $isHomeRoute ? ' is-active' : '' }}" @if($isHomeRoute) aria-current="page" @endif>Home</a>
<a href="{{ route('transport.index') }}" class="{{ $linkClass }}{{ $isTransportRoute ? ' is-active' : '' }}" @if($isTransportRoute) aria-current="page" @endif>Transport</a>
<div class="tours-menu" data-tours-menu>
    <button type="button" class="{{ $toggleClass }}{{ $isTourRoute ? ' is-active' : '' }}" data-tours-toggle aria-expanded="false" @if($isTourRoute) aria-current="page" @endif style="display: inline-flex; align-items: center; gap: 0.28rem;">
        <span>{{ $tourMenuLabel }}</span>
        <span aria-hidden="true" style="font-size: 0.72rem; line-height: 1;">&#9662;</span>
    </button>
    <div class="tours-menu-panel">
        @foreach ($tourLinks as $tourLink)
            <a href="{{ $tourLink['route'] }}" class="tours-menu-link">{{ $tourLink['label'] }}</a>
        @endforeach
    </div>
</div>
<div class="tours-menu" data-tours-menu>
    <button type="button" class="{{ $toggleClass }}{{ $isAboutRoute ? ' is-active' : '' }}" data-tours-toggle aria-expanded="false" @if($isAboutRoute) aria-current="page" @endif style="display: inline-flex; align-items: center; gap: 0.28rem;">
        <span>{{ $aboutMenuLabel }}</span>
        <span aria-hidden="true" style="font-size: 0.72rem; line-height: 1;">&#9662;</span>
    </button>
    <div class="tours-menu-panel">
        @foreach ($aboutLinks as $aboutLink)
            <a href="{{ $aboutLink['route'] }}" class="tours-menu-link">{{ $aboutLink['label'] }}</a>
        @endforeach
    </div>
</div>
<a href="{{ route('bookings.track.form') }}" class="{{ $linkClass }}{{ $isTrackRoute ? ' is-active' : '' }}" @if($isTrackRoute) aria-current="page" @endif>Track Booking</a>
