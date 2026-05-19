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

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('bookings.track.show', $booking->booking_reference)
                ->with('success', 'Booking found. This booking does not require payment.');
        }

        $sendConfirmationEmail = false;
        $updates = [];

        if ($booking->status === 'pending') {
            $updates['status'] = 'confirmed';
            $updates['confirmed_at'] = $booking->confirmed_at ?: now();
            $sendConfirmationEmail = true;
        }

        if ($booking->payment_status === 'awaiting_confirmation') {
            $updates['payment_status'] = 'awaiting_payment';
            $sendConfirmationEmail = true;
        }

        if ($updates !== []) {
            $booking->update($updates);
            $booking->refresh();
        }

        if ($sendConfirmationEmail) {
            Mail::to($booking->email)->send(new BookingConfirmationMail($booking));
        }

        return $this->redirectToBillplzCheckout($booking);
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

        Mail::to($booking->email)->send(new BookingConfirmationMail($booking));

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('bookings.track.show', $booking->booking_reference)
                ->with('success', 'Booking confirmed. This booking does not require payment.');
        }

        return $this->redirectToBillplzCheckout($booking);
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

    public function handleBillplzRedirect(Request $request, string $bookingReference): RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($bookingReference);

        $payload = $this->extractBillplzPayload($request);
        $paidFlag = strtolower((string) ($payload['paid'] ?? 'false'));
        $isPaid = in_array($paidFlag, ['true', '1'], true);

        if ($isPaid) {
            $this->markBookingAsPaid($booking, $payload, 'redirect');

            return redirect()
                ->route('bookings.track.receipt.show', $booking->booking_reference)
                ->with('success', 'Payment completed successfully. Your receipt is ready.');
        }

        $booking->update([
            'payment_gateway_status' => $payload['state'] ?? 'redirect_unpaid',
            'payment_gateway_payload' => $payload,
        ]);

        return redirect()
            ->route('bookings.track.form')
            ->withErrors(['booking_reference' => 'Payment was not completed. Please try again with your Booking ID.']);
    }

    public function handleBillplzCallback(Request $request): Response
    {
        $payload = $this->extractBillplzPayload($request);
        $billId = (string) ($payload['id'] ?? '');

        if ($billId === '') {
            return response('missing bill id', 422);
        }

        $booking = Booking::where('payment_gateway_bill_id', $billId)->first();

        if (! $booking) {
            Log::warning('Billplz callback received with unknown bill id.', ['bill_id' => $billId]);

            return response('ok', 200);
        }

        if (! $this->isBillplzSignatureValid($request, $payload)) {
            Log::warning('Billplz callback signature invalid.', ['booking_id' => $booking->id, 'bill_id' => $billId]);

            return response('invalid signature', 422);
        }

        $paidFlag = strtolower((string) ($payload['paid'] ?? 'false'));
        $isPaid = in_array($paidFlag, ['true', '1'], true);

        if ($isPaid) {
            $this->markBookingAsPaid($booking, $payload, 'callback');
        } else {
            $booking->update([
                'payment_gateway_status' => $payload['state'] ?? 'callback_unpaid',
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

        Mail::to($booking->email)->send(new PaymentReceiptMail($booking));

        return redirect()
            ->route('bookings.track.show', $booking->booking_reference)
            ->with('success', 'Sandbox payment recorded successfully. A receipt has been sent to your email.');
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

        Mail::to($booking->email)->send(new PaymentReceiptMail($booking));

        return redirect()
            ->route('profile.bookings')
            ->with('success', 'Sandbox payment submitted successfully. A payment receipt has been sent to your email.');
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

    private function redirectToBillplzCheckout(Booking $booking): RedirectResponse
    {
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.track.receipt.show', $booking->booking_reference);
        }

        $apiKey = (string) config('services.billplz.api_key');
        $collectionId = (string) config('services.billplz.collection_id');
        $baseUrl = rtrim((string) config('services.billplz.base_url', 'https://www.billplz-sandbox.com'), '/');
        $verifySsl = (bool) config('services.billplz.verify_ssl', true);

        if ($apiKey === '' || $collectionId === '') {
            return redirect()
                ->route('bookings.track.payment.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'Billplz sandbox is not configured yet. Please set BILLPLZ_API_KEY and BILLPLZ_COLLECTION_ID.']);
        }

        try {
            $response = Http::asForm()
                ->withBasicAuth($apiKey, '')
                ->withOptions(['verify' => $verifySsl])
                ->timeout(20)
                ->post($baseUrl.'/api/v3/bills', [
                    'collection_id' => $collectionId,
                    'email' => $booking->email,
                    'name' => $booking->full_name,
                    'amount' => (int) round((float) $booking->amount_myr * 100),
                    'description' => 'Universal Eden Booking '.$booking->booking_reference,
                    'reference_1_label' => 'Booking ID',
                    'reference_1' => $booking->booking_reference,
                    'callback_url' => route('bookings.billplz.callback'),
                    'redirect_url' => route('bookings.billplz.redirect', $booking->booking_reference),
                ]);
        } catch (ConnectionException $exception) {
            Log::error('Billplz connection failed.', [
                'booking_id' => $booking->id,
                'verify_ssl' => $verifySsl,
                'message' => $exception->getMessage(),
            ]);

            $hint = $verifySsl
                ? 'SSL verification failed while connecting to Billplz sandbox. For local testing, set BILLPLZ_VERIFY_SSL=false in your .env and clear config cache.'
                : 'Unable to connect to Billplz sandbox right now. Please try again shortly.';

            return redirect()
                ->route('bookings.track.form')
                ->withErrors(['booking_reference' => $hint]);
        }

        if (! $response->successful()) {
            Log::error('Billplz bill creation failed.', [
                'booking_id' => $booking->id,
                'status' => $response->status(),
                'body' => $response->json() ?: $response->body(),
            ]);

            return redirect()
                ->route('bookings.track.payment.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'Unable to start Billplz payment right now. Please try again.']);
        }

        $billData = $response->json();
        $billUrl = (string) ($billData['url'] ?? '');
        $billId = (string) ($billData['id'] ?? '');

        if ($billUrl === '' || $billId === '') {
            Log::error('Billplz response missing required bill fields.', [
                'booking_id' => $booking->id,
                'response' => $billData,
            ]);

            return redirect()
                ->route('bookings.track.payment.show', $booking->booking_reference)
                ->withErrors(['booking_reference' => 'Billplz returned an invalid payment link. Please try again.']);
        }

        $booking->update([
            'payment_gateway' => 'billplz',
            'payment_gateway_bill_id' => $billId,
            'payment_gateway_url' => $billUrl,
            'payment_gateway_status' => 'bill_created',
            'payment_gateway_payload' => $billData,
            'payment_status' => 'awaiting_payment',
        ]);

        return redirect()->away($billUrl);
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
                'payment_gateway' => 'billplz',
                'payment_gateway_status' => $payload['state'] ?? 'paid',
                'payment_gateway_paid_at' => $payload['paid_at'] ?? null,
                'payment_gateway_payload' => $payload,
            ]);

            $booking->refresh();
            $this->issueInvoiceForBooking($booking);
            $booking->refresh();

            Mail::to($booking->email)->send(new PaymentReceiptMail($booking));
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

    private function extractBillplzPayload(Request $request): array
    {
        $billplz = $request->input('billplz', []);

        return [
            'id' => $request->input('id') ?? $request->input('billplz.id') ?? ($billplz['id'] ?? null),
            'paid' => $request->input('paid') ?? $request->input('billplz.paid') ?? ($billplz['paid'] ?? null),
            'paid_at' => $request->input('paid_at') ?? $request->input('billplz.paid_at') ?? ($billplz['paid_at'] ?? null),
            'state' => $request->input('state') ?? $request->input('billplz.state') ?? ($billplz['state'] ?? null),
            'x_signature' => $request->input('x_signature') ?? $request->input('billplz.x_signature') ?? ($billplz['x_signature'] ?? null),
            'raw' => $request->all(),
        ];
    }

    private function isBillplzSignatureValid(Request $request, array $payload): bool
    {
        $xSignatureKey = (string) config('services.billplz.x_signature');

        if ($xSignatureKey === '') {
            return true;
        }

        $id = (string) ($payload['id'] ?? '');
        $paid = (string) ($payload['paid'] ?? '');
        $paidAt = (string) ($payload['paid_at'] ?? '');
        $receivedSignature = (string) ($payload['x_signature'] ?? '');

        if ($id === '' || $receivedSignature === '') {
            return false;
        }

        $signingString = 'billplzid'.$id.'|billplzpaid_at'.$paidAt.'|billplzpaid'.$paid;
        $expectedSignature = hash_hmac('sha256', $signingString, $xSignatureKey);

        return hash_equals($expectedSignature, $receivedSignature);
    }
}
