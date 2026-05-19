<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->invoice_number_or_reference }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }
        .header, .section, .totals {
            width: 100%;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 16px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0f4fb5;
        }
        .muted {
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f9fafb;
            font-size: 11px;
            text-transform: uppercase;
        }
        .total-row td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header card">
        <table>
            <tr>
                <td style="width: 58%; border: none;">
                    <div class="title">Universal Eden Holidays</div>
                    <div class="muted">Sabah Tours and Transport</div>
                    <div style="margin-top: 10px;">Kota Kinabalu, Sabah, Malaysia</div>
                    <div>info@universaledenholiday.com</div>
                    <div>+60 88 212 345</div>
                </td>
                <td style="width: 42%; border: none;">
                    <div style="font-size: 20px; font-weight: bold;">Invoice</div>
                    <div><strong>Invoice No:</strong> {{ $booking->invoice_number_or_reference }}</div>
                    <div><strong>Booking Ref:</strong> {{ $booking->booking_reference ?: 'N/A' }}</div>
                    <div><strong>Issued:</strong> {{ optional($booking->invoice_issued_at ?: $booking->confirmed_at ?: $booking->created_at)->format('d M Y') }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($booking->status) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="card" style="width: 50%; border: none;">
                    <strong>Billed To</strong><br>
                    {{ $booking->full_name }}<br>
                    {{ $booking->email }}<br>
                    {{ $booking->phone }}
                </td>
                <td class="card" style="width: 50%; border: none;">
                    <strong>Trip Details</strong><br>
                    Service: {{ ucfirst($booking->service_type) }}<br>
                    Package: {{ $booking->package_name }}<br>
                    Destination: {{ $booking->destination }}<br>
                    Travel Dates: {{ optional($booking->check_in_date)->format('d M Y') }} to {{ optional($booking->check_out_date)->format('d M Y') }}<br>
                    Pickup: {{ $booking->pickup_location ?: 'Not set' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section card">
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Guests</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Amount (MYR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $booking->package_name }} booking</td>
                    <td>{{ $booking->total_guests }}</td>
                    <td>{{ $booking->currency_code }}</td>
                    <td>{{ number_format((float) $booking->amount_display, 2) }}</td>
                    <td>{{ number_format((float) $booking->amount_myr, 2) }}</td>
                </tr>
                <tr>
                    <td>Malaysian guests</td>
                    <td>{{ $booking->malaysian_adults + $booking->malaysian_kids }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>International guests</td>
                    <td>{{ $booking->international_adults + $booking->international_kids }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>{{ $booking->currency_code }} {{ number_format((float) $booking->amount_display, 2) }}</td>
                    <td>MYR {{ number_format((float) $booking->amount_myr, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if ($booking->special_requests)
        <div class="section card">
            <strong>Special Requests</strong>
            <div style="margin-top: 8px;">{{ $booking->special_requests }}</div>
        </div>
    @endif

    <div class="totals muted">
        This invoice was automatically generated when the booking was confirmed and can be printed for customer records.
    </div>
</body>
</html>
