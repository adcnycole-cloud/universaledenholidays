<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingAccessController extends Controller
{
    public function showSetupForm(string $token): View
    {
        $booking = $this->resolveBookingFromToken($token);

        $existingUser = User::where('email', $booking->email)->first();

        return view('booking.complete-access', [
            'booking' => $booking,
            'token' => $token,
            'existingUser' => $existingUser,
        ]);
    }

    public function completeSetup(Request $request, string $token): RedirectResponse
    {
        $booking = $this->resolveBookingFromToken($token);
        $existingUser = User::where('email', $booking->email)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $existingUser;

        if ($user) {
            $user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?: $booking->phone,
                'password' => $validated['password'],
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $booking->email,
                'phone' => $validated['phone'] ?: $booking->phone,
                'password' => $validated['password'],
                'role' => 'customer',
                'preferred_currency' => $booking->currency_code ?: 'MYR',
                'email_verified_at' => now(),
            ]);
        }

        $booking->update([
            'user_id' => $user->id,
            'full_name' => $validated['name'],
            'phone' => $validated['phone'] ?: $booking->phone,
            'account_setup_token' => null,
            'account_setup_expires_at' => null,
            'account_setup_completed_at' => now(),
            'payment_status' => $booking->amount_myr > 0 ? 'awaiting_payment' : 'not_required',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('profile.bookings')
                ->with('success', 'Your account is ready and your booking is now linked to your profile.');
        }

        return redirect()
            ->route('bookings.payment.show', $booking)
            ->with('success', 'Your account is ready. Please review your booking and continue with payment.');
    }

    public function showPaymentPage(Request $request, Booking $booking): View
    {
        abort_unless($request->user() && $booking->user_id === $request->user()->id, 403);

        return view('booking.payment', [
            'booking' => $booking->load('product'),
        ]);
    }

    public function submitPayment(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($request->user() && $booking->user_id === $request->user()->id, 403);

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('profile.bookings')
                ->with('success', 'This booking does not require a payment step.');
        }

        $booking->update([
            'payment_status' => 'payment_submitted',
            'payment_submitted_at' => now(),
        ]);

        return redirect()
            ->route('profile.bookings')
            ->with('success', 'Your payment step has been recorded. Our Sabah team will verify it and follow up shortly.');
    }

    private function resolveBookingFromToken(string $token): Booking
    {
        $booking = Booking::where('account_setup_token', hash('sha256', $token))->first();

        abort_unless(
            $booking
            && $booking->account_setup_expires_at
            && $booking->account_setup_expires_at->isFuture(),
            404
        );

        return $booking;
    }
}
