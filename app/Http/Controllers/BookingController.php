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
                    'description' => 'A quick urban getaway with hotel stay, breakfast, and a night tour.',
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
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
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
}
