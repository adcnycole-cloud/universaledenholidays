<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class BookingAccessController extends Controller
{
    public function showLookupForm(Request $request): View
    {
        return view('booking.access', [
            'bookingReference' => old('booking_reference', $request->query('booking_reference', '')),
            'email' => old('email', $request->query('email', '')),
        ]);
    }

    public function lookup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_reference' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $booking = $this->resolveBookingFromReferenceAndEmail(
            $validated['booking_reference'],
            $validated['email'],
        );

        self::grantGuestAccess($request, $booking);

        return redirect()
            ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
            ->with('success', 'Booking found. You can now trace the status and continue with payment.');
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
            ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
            ->with('success', 'Your account is ready. Please review your booking and continue with payment.');
    }

    public function showPaymentPage(Request $request, string $reference): View|RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($reference);
        $redirect = $this->ensureBookingAccess($request, $booking);

        if ($redirect) {
            return $redirect;
        }

        return view('booking.payment', [
            'booking' => $booking->load('product'),
        ]);
    }

    public function submitPayment(Request $request, string $reference): RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($reference);
        $redirect = $this->ensureBookingAccess($request, $booking);

        if ($redirect) {
            return $redirect;
        }

        if ((float) $booking->amount_myr <= 0) {
            return redirect()
                ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
                ->with('success', 'This booking does not require a payment step.');
        }

        if ($booking->billplz_paid) {
            return redirect()
                ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
                ->with('success', 'This booking is already marked as paid.');
        }

        if ($booking->billplz_bill_url && $booking->billplz_state !== 'paid') {
            $booking->update([
                'payment_status' => 'payment_pending',
            ]);

            return redirect()->away($booking->billplz_bill_url);
        }

        try {
            $bill = $this->createBillplzBill($booking);
        } catch (Throwable $exception) {
            Log::error('Unable to create Billplz bill.', [
                'booking_reference' => $booking->booking_reference,
                'error' => $exception->getMessage(),
            ]);

            $errorMessage = str_contains(strtolower($exception->getMessage()), 'ssl certificate problem')
                ? 'Billplz SSL verification failed on this machine. Set BILLPLZ_VERIFY_SSL=false for local testing, then run php artisan config:clear.'
                : 'Unable to start Billplz payment right now. Please try again in a moment.';

            return redirect()
                ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
                ->withErrors(['payment' => $errorMessage]);
        }

        $booking->update([
            'payment_status' => 'payment_pending',
            'billplz_bill_id' => $bill['id'],
            'billplz_bill_url' => $bill['url'],
            'billplz_state' => $bill['state'] ?? 'due',
            'billplz_paid' => (bool) ($bill['paid'] ?? false),
            'billplz_last_payload' => $bill,
        ]);

        return redirect()->away($bill['url']);
    }

    public function handleBillplzRedirect(Request $request, string $reference): RedirectResponse
    {
        $booking = $this->resolveBookingFromReference($reference);
        $redirect = $this->ensureBookingAccess($request, $booking);

        if ($redirect) {
            return $redirect;
        }

        $payload = $request->query('billplz', []);

        if (! is_array($payload)) {
            return redirect()
                ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
                ->withErrors(['payment' => 'Invalid payment response received from Billplz.']);
        }

        $providedSignature = (string) ($payload['x_signature'] ?? '');
        unset($payload['x_signature']);

        if (! $this->verifyBillplzSignature(['billplz' => $payload], $providedSignature)) {
            return redirect()
                ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
                ->withErrors(['payment' => 'Billplz signature verification failed for this return URL.']);
        }

        $this->syncBookingPaymentStatusFromPayload($booking, $payload);

        if ($booking->fresh()->billplz_paid) {
            return redirect()
                ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
                ->with('success', 'Payment successful. Your booking has been marked as paid.');
        }

        return redirect()
            ->route('bookings.payment.show', ['reference' => $booking->booking_reference])
            ->withErrors(['payment' => 'Payment is not completed yet. You can continue payment using the same Billplz link.']);
    }

    public function handleBillplzCallback(Request $request): Response
    {
        $payload = $request->all();
        $providedSignature = (string) ($payload['x_signature'] ?? '');
        unset($payload['x_signature']);

        if (! $this->verifyBillplzSignature($payload, $providedSignature)) {
            Log::warning('Billplz callback signature verification failed.', [
                'bill_id' => $payload['id'] ?? null,
            ]);

            return response('invalid signature', 400);
        }

        $billId = (string) ($payload['id'] ?? '');

        if ($billId === '') {
            return response('ok', 200);
        }

        $booking = Booking::where('billplz_bill_id', $billId)->first();

        if (! $booking) {
            Log::warning('Billplz callback received for unknown bill id.', [
                'bill_id' => $billId,
            ]);

            return response('ok', 200);
        }

        $this->syncBookingPaymentStatusFromPayload($booking, $payload);

        return response('ok', 200);
    }

    private function createBillplzBill(Booking $booking): array
    {
        $config = config('services.billplz');
        $apiKey = trim((string) ($config['api_key'] ?? ''));
        $collectionId = trim((string) ($config['collection_id'] ?? ''));

        if ($apiKey === '' || $collectionId === '') {
            throw new RuntimeException('Billplz credentials are not configured.');
        }

        $isSandbox = (bool) ($config['sandbox'] ?? true);
        $defaultBaseUrl = $isSandbox ? 'https://www.billplz-sandbox.com' : 'https://www.billplz.com';
        $configuredBaseUrl = trim((string) ($config['base_url'] ?? ''));
        $baseUrl = rtrim($configuredBaseUrl !== '' ? $configuredBaseUrl : $defaultBaseUrl, '/');
        $verifySsl = (bool) ($config['verify_ssl'] ?? true);
        $amountInSen = max(1, (int) round(((float) $booking->amount_myr) * 100));

        $payload = [
            'collection_id' => $collectionId,
            'email' => $booking->email,
            'name' => $booking->full_name,
            'amount' => $amountInSen,
            'description' => 'Booking '.$booking->booking_reference.' - '.$booking->package_name,
            'callback_url' => route('bookings.payment.callback'),
            'redirect_url' => route('bookings.payment.return', ['reference' => $booking->booking_reference]),
            'reference_1_label' => 'Booking Ref',
            'reference_1' => $booking->booking_reference,
            'reference_2_label' => 'Package',
            'reference_2' => Str::limit($booking->package_name ?: 'Package', 120, ''),
            'deliver' => false,
        ];

        if (! empty($booking->phone)) {
            $payload['mobile'] = $booking->phone;
        }

        $http = Http::asForm();

        if (! $verifySsl) {
            $http = $http->withoutVerifying();
        }

        $response = $http
            ->withBasicAuth($apiKey, '')
            ->timeout(20)
            ->post($baseUrl.'/api/v3/bills', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Billplz bill creation failed: '.$response->body());
        }

        $bill = $response->json();

        if (! is_array($bill) || empty($bill['id']) || empty($bill['url'])) {
            throw new RuntimeException('Billplz bill creation returned an invalid response.');
        }

        return $bill;
    }

    private function verifyBillplzSignature(array $payload, string $providedSignature): bool
    {
        $xSignatureKey = trim((string) config('services.billplz.x_signature_key', ''));

        if ($xSignatureKey === '') {
            return true;
        }

        if ($providedSignature === '') {
            return false;
        }

        $sourceElements = $this->buildBillplzSignatureElements($payload);

        if ($sourceElements === []) {
            return false;
        }

        natcasesort($sourceElements);
        $source = implode('|', $sourceElements);
        $calculated = hash_hmac('sha256', $source, $xSignatureKey);

        return hash_equals($calculated, $providedSignature);
    }

    private function buildBillplzSignatureElements(array $payload, string $prefix = ''): array
    {
        $elements = [];

        foreach ($payload as $key => $value) {
            if ($key === 'x_signature') {
                continue;
            }

            $fullKey = $prefix.(string) $key;

            if (is_array($value)) {
                $elements = array_merge($elements, $this->buildBillplzSignatureElements($value, $fullKey));

                continue;
            }

            $elements[] = $fullKey.(string) ($value ?? '');
        }

        return $elements;
    }

    private function syncBookingPaymentStatusFromPayload(Booking $booking, array $payload): void
    {
        $paid = filter_var($payload['paid'] ?? false, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $paid = $paid ?? false;

        $state = (string) ($payload['state'] ?? ($paid ? 'paid' : 'due'));
        $paidAt = $this->parseBillplzDate($payload['paid_at'] ?? null);

        $updates = [
            'billplz_state' => $state,
            'billplz_paid' => $paid,
            'billplz_paid_at' => $paid ? ($paidAt ?: now()) : null,
            'billplz_transaction_id' => $payload['transaction_id'] ?? null,
            'billplz_transaction_status' => $payload['transaction_status'] ?? null,
            'billplz_last_payload' => $payload,
        ];

        if ($paid) {
            $updates['payment_status'] = 'paid';
            $updates['payment_submitted_at'] = $booking->payment_submitted_at ?: now();
        } elseif ($booking->payment_status !== 'paid') {
            $updates['payment_status'] = 'payment_pending';
        }

        $booking->update($updates);
    }

    private function parseBillplzDate(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }
    }

    public static function grantGuestAccess(Request $request, Booking $booking): void
    {
        $allowedBookings = $request->session()->get('booking_access', []);
        $allowedBookings[$booking->booking_reference] = strtolower($booking->email);

        $request->session()->put('booking_access', $allowedBookings);
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

    private function resolveBookingFromReference(string $reference): Booking
    {
        $booking = Booking::where('booking_reference', Str::upper(trim($reference)))->first();

        abort_unless($booking, 404);

        return $booking;
    }

    private function resolveBookingFromReferenceAndEmail(string $reference, string $email): Booking
    {
        $booking = Booking::where('booking_reference', Str::upper(trim($reference)))
            ->whereRaw('lower(email) = ?', [strtolower(trim($email))])
            ->first();

        if (! $booking) {
            throw ValidationException::withMessages([
                'booking_reference' => 'We could not match that Booking ID and email address.',
            ]);
        }

        return $booking;
    }

    private function ensureBookingAccess(Request $request, Booking $booking): ?RedirectResponse
    {
        if ($request->user() && $booking->user_id === $request->user()->id) {
            return null;
        }

        if (strcasecmp((string) $request->query('email', ''), $booking->email) === 0) {
            self::grantGuestAccess($request, $booking);

            return null;
        }

        $allowedBookings = $request->session()->get('booking_access', []);

        if (($allowedBookings[$booking->booking_reference] ?? null) === strtolower($booking->email)) {
            return null;
        }

        return redirect()
            ->route('bookings.lookup.show', ['booking_reference' => $booking->booking_reference])
            ->withErrors(['booking_reference' => 'Enter your Booking ID and booking email to continue.']);
    }
}
