<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Payment Receipt</title>
    </head>
    <body style="margin: 0; background: #f5f5f4; font-family: Arial, sans-serif; color: #1c1917;">
        <div style="max-width: 640px; margin: 0 auto; padding: 32px 20px;">
            <div style="border-radius: 24px; background: #ffffff; padding: 32px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);">
                <p style="margin: 0 0 12px; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: #b45309;">
                    Universal Eden Holidays
                </p>
                <h1 style="margin: 0; font-size: 28px; line-height: 1.2; color: #0f172a;">Payment receipt</h1>
                <p style="margin: 18px 0 0; font-size: 15px; line-height: 1.8; color: #44403c;">
                    We recorded your payment for Booking ID <strong>{{ $booking->booking_reference }}</strong>.
                </p>

                <div style="margin-top: 24px; border-radius: 18px; background: #f8fafc; padding: 18px 20px;">
                    <p style="margin: 0; font-size: 14px; line-height: 1.8;"><strong>Package:</strong> {{ $booking->package_name }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Booking ID:</strong> {{ $booking->booking_reference }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Receipt / Invoice:</strong> {{ $booking->invoice_number ?: 'Pending' }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Amount Paid:</strong> {{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Payment Date:</strong> {{ optional($booking->payment_submitted_at)->format('d M Y H:i') }}</p>
                </div>

                <div style="margin-top: 28px;">
                    <a href="{{ route('bookings.track.receipt.show', $booking->booking_reference) }}" style="display: inline-block; border-radius: 999px; background: #0f766e; padding: 14px 24px; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; margin-right: 8px;">
                        View Receipt
                    </a>
                    <a href="{{ route('bookings.track.receipt.pdf', $booking->booking_reference) }}" style="display: inline-block; border-radius: 999px; background: #1f2937; padding: 14px 24px; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700;">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
