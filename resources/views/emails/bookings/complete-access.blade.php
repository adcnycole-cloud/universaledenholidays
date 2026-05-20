<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Complete your booking account</title>
    </head>
    <body style="margin: 0; background: #f5f5f4; font-family: Arial, sans-serif; color: #1c1917;">
        <div style="max-width: 640px; margin: 0 auto; padding: 32px 20px;">
            <div style="border-radius: 24px; background: #ffffff; padding: 32px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);">
                <p style="margin: 0 0 12px; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: #b45309;">
                    Universal Eden Holidays
                </p>
                <h1 style="margin: 0; font-size: 28px; line-height: 1.2; color: #0f172a;">
                    Complete your booking access
                </h1>
                <p style="margin: 18px 0 0; font-size: 15px; line-height: 1.8; color: #44403c;">
                    Hi {{ $booking->full_name }}, your booking reference is <strong>{{ $booking->booking_reference }}</strong>.
                    Use the secure link below to connect this booking to your profile, and continue to payment.
                </p>

                <div style="margin-top: 24px; border-radius: 18px; background: #f8fafc; padding: 18px 20px;">
                    <p style="margin: 0; font-size: 14px; line-height: 1.8;"><strong>Package:</strong> {{ $booking->package_name }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Travel dates:</strong> {{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }}</p>
                    <p style="margin: 6px 0 0; font-size: 14px; line-height: 1.8;"><strong>Amount:</strong> {{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</p>
                </div>

                <div style="margin-top: 28px;">
                    <a href="{{ $accessUrl }}" style="display: inline-block; border-radius: 999px; background: #0f766e; padding: 14px 24px; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700;">
                        Complete account and continue
                    </a>
                </div>

                <p style="margin: 14px 0 0; font-size: 13px; line-height: 1.7; color: #57534e;">
                    Or visit our website directly:
                    <a href="{{ config('app.url') }}" style="color: #0f766e; text-decoration: underline;">{{ config('app.url') }}</a>
                </p>

                <p style="margin: 20px 0 0; font-size: 13px; line-height: 1.7; color: #57534e;">
                    This access link expires in 24 hours and can only be used for this booking.
                </p>
            </div>
        </div>
    </body>
</html>
