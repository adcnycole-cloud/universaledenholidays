<?php

namespace App\Console\Commands;

use App\Mail\BookingPaymentReminderMail;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBookingPaymentReminders extends Command
{
    protected $signature = 'bookings:send-payment-reminders';

    protected $description = 'Send payment reminder emails three days before check-in for unpaid bookings';

    public function handle(): int
    {
        $travelDate = now()->addDays(3)->toDateString();

        $bookings = Booking::query()
            ->whereDate('check_in_date', $travelDate)
            ->where('status', '!=', 'cancelled')
            ->whereNotIn('payment_status', ['payment_submitted', 'not_required'])
            ->whereNull('payment_reminder_sent_at')
            ->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->email)->send(new BookingPaymentReminderMail(
                $booking,
                route('bookings.lookup.show', ['booking_reference' => $booking->booking_reference]),
            ));

            $booking->update([
                'payment_reminder_sent_at' => now(),
            ]);
        }

        $this->info('Sent '.$bookings->count().' payment reminder(s).');

        return self::SUCCESS;
    }
}
