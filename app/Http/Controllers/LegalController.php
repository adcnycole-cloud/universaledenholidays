<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function privacyPolicy(): View
    {
        return view('legal.privacy-policy', [
            'title' => 'Privacy Policy | Universal Eden Holidays',
            'metaDescription' => 'Read the Privacy Policy of Universal Eden Holidays Sdn. Bhd. to understand how we collect, use, and protect your personal data in accordance with PDPA.',
        ]);
    }

    public function termsAndConditions(): View
    {
        return view('legal.terms-and-conditions', [
            'title' => 'Terms & Conditions | Universal Eden Holidays',
            'metaDescription' => 'Review the Terms and Conditions of Universal Eden Holidays Sdn. Bhd. governing the use of our services and booking arrangements.',
        ]);
    }

    public function refundCancellationPolicy(): View
    {
        return view('legal.refund-cancellation-policy', [
            'title' => 'Refund & Cancellation Policy | Universal Eden Holidays',
            'metaDescription' => 'Learn about the refund and cancellation policy of Universal Eden Holidays Sdn. Bhd. for all tour packages and transport bookings.',
        ]);
    }

    public function carRentalTerms(): View
    {
        return view('legal.car-rental-terms', [
            'title' => 'Car Rental Terms & Conditions | Universal Eden Holidays',
            'metaDescription' => 'Read the Car Rental Terms and Conditions of Universal Eden Holidays Sdn. Bhd. covering eligibility, damage policy, prohibited uses, and renter responsibilities.',
        ]);
    }
}
