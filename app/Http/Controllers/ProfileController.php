<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->load(['bookings.product']);

        return view('profile.show', [
            'user' => $user,
            'currencies' => [
                'MYR' => 'Malaysian Ringgit',
                'KRW' => 'Korean Won',
                'USD' => 'US Dollar',
                'SGD' => 'Singapore Dollar',
                'CNY' => 'Chinese Yuan',
            ],
        ]);
    }

    public function bookings(Request $request): View
    {
        $user = $request->user()->load(['bookings.product']);

        return view('profile.bookings', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'preferred_currency' => ['required', 'in:MYR,KRW,USD,SGD,CNY'],
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
