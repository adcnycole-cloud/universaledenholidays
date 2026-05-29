<?php

namespace App\Console\Commands;

use App\Mail\BookingPaymentReminderMail;
use App\Mail\BookingTripReminderMail;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Send payment and trip reminder emails for upcoming bookings.';

    public function handle(): int
    {
        $today = now()->startOfDay();
        $tripReminderDate = $today->copy()->addDays(3)->toDateString();
        $paymentReminderDate = $today->copy()->addDays(7)->toDateString();

        $paymentReminderSent = 0;
        $tripReminderSent = 0;

        Booking::query()
            ->whereDate('check_in_date', $paymentReminderDate)
            ->where('amount_myr', '>', 0)
            ->whereIn('payment_status', ['awaiting_confirmation', 'awaiting_payment'])
            ->where('status', '!=', 'cancelled')
            ->whereNull('payment_reminder_sent_at')
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use (&$paymentReminderSent) {
                foreach ($bookings as $booking) {
                    if ($this->sendReminderMail(
                        $booking,
                        new BookingPaymentReminderMail($booking),
                        'payment reminder'
                    )) {
                        $booking->forceFill(['payment_reminder_sent_at' => now()])->save();
                        $paymentReminderSent++;
                    }
                }
            });

        Booking::query()
            ->whereDate('check_in_date', $tripReminderDate)
            ->where('status', '!=', 'cancelled')
            ->whereNull('trip_reminder_sent_at')
            ->orderBy('id')
            ->chunkById(100, function ($bookings) use (&$tripReminderSent) {
                foreach ($bookings as $booking) {
                    if ($this->sendReminderMail(
                        $booking,
                        new BookingTripReminderMail($booking),
                        'trip reminder'
                    )) {
                        $booking->forceFill(['trip_reminder_sent_at' => now()])->save();
                        $tripReminderSent++;
                    }
                }
            });

        $this->info(sprintf(
            'Booking reminders completed. Payment reminders sent: %d, Trip reminders sent: %d',
            $paymentReminderSent,
            $tripReminderSent,
        ));

        return self::SUCCESS;
    }

    private function sendReminderMail(Booking $booking, object $mailable, string $mailType): bool
    {
        try {
            Mail::to($booking->email)->send($mailable);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Failed to send '.$mailType.'.', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'email' => $booking->email,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
