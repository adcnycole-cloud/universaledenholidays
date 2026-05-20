<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Booking Invoice</title>
    </head>
    <body style="margin: 0; background: #f5f5f4; font-family: Arial, sans-serif; color: #1c1917;">
        <div style="max-width: 640px; margin: 0 auto; padding: 32px 20px;">
            <div style="border-radius: 24px; background: #ffffff; padding: 32px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);">
                <p style="margin: 0 0 12px; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: #0f766e;">
                    Universal Eden Holidays
                </p>
                <h1 style="margin: 0; font-size: 28px; line-height: 1.2; color: #0f172a;">Your booking invoice</h1>
                <p style="margin: 18px 0 0; font-size: 15px; line-height: 1.8; color: #44403c;">
                    Your booking has been confirmed. We attached your invoice PDF for Booking ID <strong>{{ $booking->booking_reference }}</strong>.
                </p>

                <div style="margin-top: 24px; border-radius: 18px; background: #f8fafc; padding: 18px 20px;">
                    <p style="margin: 0; font-size: 14px; line-height: 1.8;"><strong>Package:</strong> {{ $booking->package_name }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Invoice No:</strong> {{ $booking->invoice_number ?: 'Pending' }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Travel Dates:</strong> {{ optional($booking->check_in_date)->format('d M Y') }} to {{ optional($booking->check_out_date)->format('d M Y') }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Total:</strong> {{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                </div>

                <p style="margin: 24px 0 0; font-size: 14px; line-height: 1.8; color: #57534e;">
                    Please keep this invoice for your records. If you need any changes or help with your trip, reply to this email and our team will assist you.
                </p>
            </div>
        </div>
    </body>
</html>
