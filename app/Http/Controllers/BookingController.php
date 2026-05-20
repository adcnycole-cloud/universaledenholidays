<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('booking.index', [
            'packages' => [
                [
                    'name' => 'Beach Escape',
                    'location' => 'Langkawi, Malaysia',
                    'price' => '$320',
                    'description' => 'Three nights by the sea with breakfast and airport pickup included.',
                ],
                [
                    'name' => 'City Lights',
                    'location' => 'Singapore',
                    'price' => '$460',
                    'description' => 'A quick urban getaway with hotel stay, breakfast, and an evening city experience.',
                ],
                [
                    'name' => 'Highland Retreat',
                    'location' => 'Cameron Highlands, Malaysia',
                    'price' => '$280',
                    'description' => 'Fresh mountain air, tea estate visits, and a cozy two-night stay.',
                ],
            ],
            'bookings' => Schema::hasTable('bookings')
                ? Booking::latest()->take(5)->get()
                : collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email:rfc,dns'],
            'phone' => $this->phoneRules(),
            'destination' => ['required', 'string', 'max:255'],
            'package_name' => ['required', 'string', 'max:255'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:20'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        Booking::create($validated + ['status' => 'pending']);

        return to_route('booking.index')->with('success', 'Your booking request has been received. We will contact you shortly.');
    }

    private function phoneRules(): array
    {
        return [
            'required',
            'string',
            'max:25',
            function (string $attribute, mixed $value, \Closure $fail) {
                $phone = trim((string) $value);

                if (! preg_match('/^\+[0-9][0-9\s\-()]{7,24}$/', $phone)) {
                    $fail('Please enter a valid phone number with a country code, for example +60 12-345 6789.');

                    return;
                }

                $digitsOnly = preg_replace('/\D+/', '', $phone) ?? '';
                $digitCount = strlen($digitsOnly);

                if ($digitCount < 8 || $digitCount > 15) {
                    $fail('Please enter a valid phone number between 8 and 15 digits.');

                    return;
                }

                if (preg_match('/^(\d)\1+$/', $digitsOnly) === 1) {
                    $fail('Please enter a real phone number, not a repeated-digit number.');

                    return;
                }

                $supportedPatterns = [
                    '60' => '/^(?:60)(?:1\d{8,9}|[3-9]\d{7,8})$/',
                    '65' => '/^(?:65)(?:[3689]\d{7})$/',
                    '82' => '/^(?:82)(?:1\d{8,9}|[2-6]\d{7,9})$/',
                    '1' => '/^(?:1)(?:[2-9]\d{2}[2-9]\d{6})$/',
                    '86' => '/^(?:86)(?:1[3-9]\d{9})$/',
                ];

                $matchesSupportedCountry = collect($supportedPatterns)
                    ->contains(fn ($pattern) => preg_match($pattern, $digitsOnly) === 1);

                if (! $matchesSupportedCountry) {
                    $fail('Please enter a valid phone number with a supported country code and prefix.');
                }
            },
        ];
    }
}
