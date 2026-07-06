<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentReceiptMail;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingAccessController extends Controller
{
    public function showTrackingForm(): View
    {
        return view('booking.track');
    }

    public function findTrackingBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_reference' => ['required', 'string', 'max:50'],
        ]);

        $bookingReference = $this->normalizeBookingReference($validated['booking_reference']);
        $booking = Booking::where('booking_reference', $bookingReference)->first();

        if (! $booking) {
            return back()
                ->withErrors(['booking_reference' => 'We could not find a booking with that Booking ID.'])
                ->withInput();
        }

        return redirect()
            ->route('bookings.track.show', $booking->booking_reference)
            ->with('success', 'Booking found. Please review your booking details below.');
    }

    public function showTrackingDetails(string $bookingReference): View
    {
        $booking = $this->resolveBookingFromReference($bookingReference);

        return view('booking.track-details', [
            'booking' => $booking->load('product'),
        ]);
    }

    public function confirmAndContinueToPayment(string $bookingReference): RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($bookingReference);

        $updates = [];

        if ($booking->status === 'pending') {
            $updates['status'] = 'confirmed';
            $updates['confirmed_at'] = $booking->confirmed_at ?: now();
        }

        if ((float) $booking->amount_myr > 0) {
            $updates['payment_status'] = 'awaiting_payment';
        } elseif (($booking->payment_status ?? null) !== 'paid') {
            $updates['payment_status'] = 'not_required';
        }

        if ($updates !== []) {
            $booking->update($updates);
            $booking->refresh();
        }

        $confirmationEmailSent = $this->sendMailSafely(
            $booking->email,
            new BookingConfirmationMail($booking),
            'booking confirmation email',
            [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
            ],
        );

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('bookings.track.show', $booking->booking_reference)
                ->with('success', $confirmationEmailSent
                    ? 'Booking confirmed. This booking does not require payment.'
                    : 'Booking confirmed. This booking does not require payment, but we could not send the confirmation email right now.');
        }

        return $this->redirectToHitPayCheckout($booking);
    }

    public function showSandboxPaymentPage(string $bookingReference): View
    {
        $booking = $this->resolveBookingFromReference($bookingReference);

        if ((float) $booking->amount_myr > 0 && $booking->payment_status === 'awaiting_confirmation') {
            abort(403, 'Please confirm your booking details before continuing to payment.');
        }

        return view('booking.payment', [
            'booking' => $booking->load('product'),
            'isTrackingFlow' => true,
        ]);
    }

    public function handleHitPayRedirect(Request $request, string $bookingReference): RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($bookingReference);

        $status = $request->query('status', '');

        if ($status === 'completed') {
            $this->markBookingAsPaid($booking, ['status' => 'completed'], 'redirect');

            return redirect()
                ->route('bookings.track.show', $booking->booking_reference)
                ->with('success', 'Payment completed successfully. You can view your payment details and download your receipt.');
        }

        $booking->update([
            'payment_gateway_status' => $status ?: 'redirect_unpaid',
            'payment_gateway_payload' => $request->query(),
        ]);

        return redirect()
            ->route('bookings.track.form')
            ->withErrors(['booking_reference' => 'Payment was not completed. Please try again with your Booking ID.']);
    }

    public function handleHitPayCallback(Request $request): Response
    {
        $payload = $request->all();
        $paymentId = (string) ($payload['payment_id'] ?? '');

        if ($paymentId === '') {
            return response('missing payment id', 422);
        }

        $booking = Booking::where('payment_gateway_bill_id', $paymentId)->first();

        if (! $booking) {
            Log::warning('HitPay callback received with unknown payment id.', ['payment_id' => $paymentId]);

            return response('ok', 200);
        }

        if (! $this->isHitPaySignatureValid($request)) {
            Log::warning('HitPay callback signature invalid.', ['booking_id' => $booking->id, 'payment_id' => $paymentId]);

            return response('invalid signature', 422);
        }

        $status = strtolower((string) ($payload['status'] ?? ''));

        if ($status === 'completed') {
            $this->markBookingAsPaid($booking, $payload, 'callback');
        } else {
            $booking->update([
                'payment_gateway_status' => $payload['status'] ?? 'callback_unpaid',
                'payment_gateway_payload' => $payload,
            ]);
        }

        return response('ok', 200);
    }

    public function showReceipt(string $bookingReference): View
    {
        $booking = $this->resolveBookingFromReference($bookingReference)->load('product');

        abort_unless($booking->payment_status === 'paid' || $booking->invoice_number, 404);

        return view('booking.receipt', [
            'booking' => $booking,
        ]);
    }

    public function downloadReceiptPdf(string $bookingReference)
    {
        $booking = $this->resolveBookingFromReference($bookingReference)->load(['product', 'user']);

        abort_unless($booking->payment_status === 'paid' || $booking->invoice_number, 404);

        if (! $booking->invoice_number) {
            $this->issueInvoiceForBooking($booking);
            $booking->refresh();
        }

        return Pdf::loadView('admin.bookings.invoice-pdf', [
            'booking' => $booking,
        ])->setPaper('a4')->download('receipt-'.$booking->invoice_number_or_reference.'.pdf');
    }

    public function viewReceiptPdf(string $bookingReference)
    {
        $booking = $this->resolveBookingFromReference($bookingReference)->load(['product', 'user']);

        abort_unless($booking->payment_status === 'paid' || $booking->invoice_number, 404);

        if (! $booking->invoice_number) {
            $this->issueInvoiceForBooking($booking);
            $booking->refresh();
        }

        return Pdf::loadView('admin.bookings.invoice-pdf', [
            'booking' => $booking,
        ])->setPaper('a4')->stream('receipt-'.$booking->invoice_number_or_reference.'.pdf');
    }

    public function submitSandboxPayment(Request $request, string $bookingReference): RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($bookingReference);

        if ((float) $booking->amount_myr > 0 && $booking->payment_status === 'awaiting_confirmation') {
            return redirect()
                ->route('bookings.track.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'Please confirm your booking details before payment.']);
        }

        $request->validate([
            'sandbox_reference' => ['nullable', 'string', 'max:100'],
        ]);

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('bookings.track.show', $booking->booking_reference)
                ->with('success', 'This booking does not require a payment step.');
        }

        $updates = [
            'payment_status' => 'paid',
            'payment_submitted_at' => now(),
        ];

        if ($booking->status === 'pending') {
            $updates['status'] = 'confirmed';
            $updates['confirmed_at'] = $booking->confirmed_at ?: now();
        }

        $booking->update($updates);
        $booking->refresh();
        $this->issueInvoiceForBooking($booking);
        $booking->refresh();

        $receiptEmailSent = $this->sendMailSafely(
            $booking->email,
            new PaymentReceiptMail($booking),
            'payment receipt email',
            [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
            ],
        );

        return redirect()
            ->route('bookings.track.show', $booking->booking_reference)
            ->with('success', $receiptEmailSent
                ? 'Sandbox payment recorded successfully. A receipt has been sent to your email.'
                : 'Sandbox payment recorded successfully, but we could not send the receipt email right now.');
    }

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
            'isTrackingFlow' => false,
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
            'payment_status' => 'paid',
            'payment_submitted_at' => now(),
            'status' => $booking->status === 'pending' ? 'confirmed' : $booking->status,
            'confirmed_at' => in_array($booking->status, ['confirmed', 'completed'], true)
                ? $booking->confirmed_at
                : ($booking->confirmed_at ?: now()),
        ]);

        $booking->refresh();
        $this->issueInvoiceForBooking($booking);
        $booking->refresh();

        $receiptEmailSent = $this->sendMailSafely(
            $booking->email,
            new PaymentReceiptMail($booking),
            'payment receipt email',
            [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
            ],
        );

        return redirect()
            ->route('profile.bookings')
            ->with('success', $receiptEmailSent
                ? 'Sandbox payment submitted successfully. A payment receipt has been sent to your email.'
                : 'Sandbox payment submitted successfully, but we could not send the receipt email right now.');
    }

    private function resolveBookingFromReference(string $bookingReference): Booking
    {
        $booking = Booking::where('booking_reference', $this->normalizeBookingReference($bookingReference))->first();

        abort_unless($booking, 404);

        return $booking;
    }

    private function normalizeBookingReference(string $bookingReference): string
    {
        return strtoupper(trim($bookingReference));
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

    private function issueInvoiceForBooking(Booking $booking): void
    {
        if ($booking->invoice_number) {
            return;
        }

        $confirmedAt = $booking->confirmed_at ?: now();
        $invoiceNumber = 'UEH-INV-'.$confirmedAt->format('Ym').'-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT);

        $booking->update([
            'confirmed_at' => $confirmedAt,
            'invoice_number' => $invoiceNumber,
            'invoice_issued_at' => now(),
        ]);
    }

    private function redirectToHitPayCheckout(Booking $booking): RedirectResponse
    {
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.track.receipt.show', $booking->booking_reference);
        }

        $apiKey = (string) config('services.hitpay.api_key');
        $salt = (string) config('services.hitpay.salt');
        $baseUrl = rtrim((string) config('services.hitpay.base_url', 'https://api.sandbox.hitpay.com'), '/');

        if ($apiKey === '' || $salt === '') {
            return redirect()
                ->route('bookings.track.payment.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'HitPay is not configured yet. Please set HITPAY_API_KEY and HITPAY_SALT.']);
        }

        $webhookUrl = route('bookings.hitpay.callback');
        $redirectUrl = route('bookings.hitpay.redirect', $booking->booking_reference);

        // HitPay rejects localhost — use a placeholder webhook for local dev.
        // Payments still work: the redirect_url handles the success flow.
        if (str_contains($webhookUrl, 'localhost') || str_contains($webhookUrl, '127.0.0.1')) {
            $webhookUrl = 'https://example.com/hitpay-webhook';
        }

        try {
            $response = Http::asForm()
                ->withToken($apiKey)
                ->withOptions(['verify' => (bool) config('services.hitpay.verify_ssl', true)])
                ->withHeaders([
                    'X-Requested-With' => 'XMLHttpRequest',
                ])
                ->timeout(20)
                ->post($baseUrl.'/v1/payment-requests', [
                    'amount' => number_format((float) $booking->amount_myr, 2, '.', ''),
                    'currency' => strtoupper($booking->currency_code ?: 'MYR'),
                    'email' => $booking->email,
                    'name' => $booking->full_name,
                    'purpose' => 'Universal Eden Booking '.$booking->booking_reference,
                    'reference_number' => $booking->booking_reference,
                    'redirect_url' => $redirectUrl,
                    'webhook' => $webhookUrl,
                    'send_email' => false,
                ]);
        } catch (ConnectionException $exception) {
            Log::error('HitPay connection failed.', [
                'booking_id' => $booking->id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('bookings.track.form')
                ->withErrors(['booking_reference' => 'Unable to connect to HitPay right now. Please try again shortly.']);
        }

        if (! $response->successful()) {
            Log::error('HitPay payment request creation failed.', [
                'booking_id' => $booking->id,
                'status' => $response->status(),
                'body' => $response->json() ?: $response->body(),
            ]);

            return redirect()
                ->route('bookings.track.payment.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'Unable to start HitPay payment right now. Please try again.']);
        }

        $paymentData = $response->json();
        $paymentUrl = (string) ($paymentData['url'] ?? '');
        $paymentId = (string) ($paymentData['id'] ?? '');

        if ($paymentUrl === '' || $paymentId === '') {
            Log::error('HitPay response missing required payment fields.', [
                'booking_id' => $booking->id,
                'response' => $paymentData,
            ]);

            return redirect()
                ->route('bookings.track.payment.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'HitPay returned an invalid payment link. Please try again.']);
        }

        $booking->update([
            'payment_gateway' => 'hitpay',
            'payment_gateway_bill_id' => $paymentId,
            'payment_gateway_url' => $paymentUrl,
            'payment_gateway_status' => 'payment_request_created',
            'payment_gateway_payload' => $paymentData,
            'payment_status' => 'awaiting_payment',
        ]);

        return redirect()->away($paymentUrl);
    }

    private function markBookingAsPaid(Booking $booking, array $payload, string $source): void
    {
        if ($booking->payment_status !== 'paid') {
            $booking->update([
                'payment_status' => 'paid',
                'payment_submitted_at' => now(),
                'status' => $booking->status === 'pending' ? 'confirmed' : $booking->status,
                'confirmed_at' => in_array($booking->status, ['confirmed', 'completed'], true)
                    ? $booking->confirmed_at
                    : ($booking->confirmed_at ?: now()),
                'payment_gateway' => 'hitpay',
                'payment_gateway_status' => $payload['status'] ?? 'paid',
                'payment_gateway_paid_at' => $payload['paid_at'] ?? null,
                'payment_gateway_payload' => $payload,
            ]);

            $booking->refresh();
            $this->issueInvoiceForBooking($booking);
            $booking->refresh();

            $this->sendMailSafely(
                $booking->email,
                new PaymentReceiptMail($booking),
                'payment receipt email',
                [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'source' => $source,
                ],
            );
        } else {
            $booking->update([
                'payment_gateway_status' => $payload['state'] ?? $booking->payment_gateway_status,
                'payment_gateway_paid_at' => $payload['paid_at'] ?? $booking->payment_gateway_paid_at,
                'payment_gateway_payload' => $payload,
            ]);
        }

        Log::info('Booking payment marked as paid.', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'source' => $source,
        ]);
    }

    private function isHitPaySignatureValid(Request $request): bool
    {
        $salt = (string) config('services.hitpay.salt');

        if ($salt === '') {
            Log::warning('HitPay salt not configured — skipping webhook signature verification.');

            return true;
        }

        $receivedSignature = $request->header('X-HitPay-Signature', '');

        if ($receivedSignature === '') {
            return false;
        }

        $body = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $body, $salt);

        return hash_equals($expectedSignature, $receivedSignature);
    }

    private function sendMailSafely(string $email, object $mailable, string $mailType, array $context = []): bool
    {
        try {
            Mail::to($email)->send($mailable);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Unable to send '.$mailType.'.', $context + [
                'email' => $email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
