@php
    // Put your logo at: public/images/invoice-logo.png
    $logoRelativePath = 'images/invoice-logo.png';
    $logoAbsolutePath = public_path($logoRelativePath);
    $logoSrc = file_exists($logoAbsolutePath) ? 'file:///' . str_replace('\\', '/', $logoAbsolutePath) : null;
    $issuedDate = $booking->invoice_issued_at ?: $booking->confirmed_at ?: $booking->created_at;
    $customerName = $booking->full_name ?: 'Customer';
    $address = $booking->pickup_location ?: 'N/A';
    $phone = $booking->phone ?: 'N/A';
    $packageLine = trim(($booking->package_name ?: 'Package').' - '.($booking->destination ?: 'Destination'));
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->invoice_number_or_reference }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.35;
            color: #111827;
        }

        .page {
            width: 100%;
        }

        .logo-section {
            margin-bottom: 8px;
        }

        .logo {
            max-height: 60px;
            width: auto;
            margin-bottom: 4px;
        }

        .company-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 2px 0;
            color: #0f172a;
        }

        .company-info {
            font-size: 9px;
            line-height: 1.4;
            color: #374151;
            margin: 4px 0 0 0;
        }

        .office-section {
            margin-bottom: 6px;
            font-size: 10px;
        }

        .office-label {
            font-weight: 700;
            width: 90px;
            display: inline-block;
            vertical-align: top;
        }

        .office-content {
            display: inline-block;
            width: calc(100% - 95px);
            vertical-align: top;
            line-height: 1.4;
        }

        .invoice-header {
            border-top: 2px solid #111827;
            border-bottom: 2px solid #111827;
            padding: 6px 8px;
            margin: 6px 0;
            display: table;
            width: 100%;
        }

        .invoice-header-col {
            display: table-cell;
            font-size: 11px;
        }

        .invoice-header-title {
            font-size: 16px;
            font-weight: 700;
            width: 100px;
        }

        .invoice-header-center {
            flex: 1;
            padding-left: 20px;
        }

        .invoice-header-right {
            width: 160px;
            text-align: right;
        }

        .billed-section {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }

        .billed-left {
            display: table-cell;
            width: 65%;
            vertical-align: top;
            padding-right: 8px;
            font-size: 10px;
        }

        .billed-right {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            font-size: 10px;
        }

        .billed-row {
            margin-bottom: 2px;
            display: table;
            width: 100%;
        }

        .billed-label {
            display: table-cell;
            width: 70px;
            font-weight: 600;
        }

        .billed-value {
            display: table-cell;
            padding-left: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9px;
        }

        thead th {
            background: #f9fafb;
            border: 1px solid #111827;
            color: #111827;
            font-weight: 700;
            text-transform: uppercase;
            padding: 5px 4px;
        }

        tbody td {
            border: 1px solid #111827;
            padding: 4px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 8px;
            width: 100%;
            display: table;
        }

        .footer-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-right: 8px;
        }

        .footer-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .bank-info {
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .totals-box {
            border: 1px solid #111827;
            width: 130px;
            text-align: right;
            font-weight: 700;
            padding: 4px 6px;
            font-size: 10px;
            margin-bottom: -1px;
        }

        .totals-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2px;
        }

        .totals-label {
            font-weight: 600;
            font-size: 9px;
        }

        .footer-note {
            font-size: 8px;
            color: #4b5563;
            margin-top: 4px;
        }

        .signatures {
            margin-top: 10px;
            width: 100%;
            display: table;
            border-top: 1px solid #111827;
            padding-top: 6px;
        }

        .sig-col {
            display: table-cell;
            width: 33.33%;
            font-size: 9px;
            color: #374151;
            padding-right: 8px;
        }

        .sig-line {
            border-bottom: 1px solid #111827;
            height: 22px;
            margin-bottom: 3px;
        }
    </style>
</head>

<body>
<<<<<<< Updated upstream
    @php
        $tripDuration = $booking->product?->duration ?: 'Custom duration';
        preg_match('/(\d+)\s*day/i', (string) $tripDuration, $durationMatches);
        $durationDays = isset($durationMatches[1]) ? (int) $durationMatches[1] : null;
        $travelStart = $booking->check_in_date;
        $travelEnd = $durationDays && $travelStart
            ? $travelStart->copy()->addDays($durationDays)
            : $booking->check_out_date;
    @endphp
    <div class="header card">
        <table>
            <tr>
                <td style="width: 58%; border: none;">
                    <div class="title">Universal Eden Holidays</div>
                    <div class="muted">Sabah Packages and Transport</div>
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
                    Trip Duration: {{ $tripDuration }}<br>
                    Travel Dates: {{ optional($travelStart)->format('d M Y') }} to {{ optional($travelEnd)->format('d M Y') }}{{ $tripDuration ? ' ('.$tripDuration.')' : '' }}<br>
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
=======
    <div class="page">
        <div class="logo-section">
            @if ($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo" class="logo">
            @else
                <h1 class="company-title">UNIVERSAL EDEN HOLIDAYS</h1>
            @endif
>>>>>>> Stashed changes
        </div>

        <div class="company-info">
            VAN &amp; COACH RENTAL | TRANSPORT SERVICES | TOUR SERVICES (KPK/LN: 10621)<br>
            Jalan Kalansanan, Batu 6, Tuaran By Pass, 88450 Kota Kinabalu, Sabah<br>
            Tel: 088-388920 | HP: 0168122921 | Email: uniedenholidays@gmail.com
        </div>

        <div class="office-section">
            <span class="office-label"><strong>HEAD OFFICE</strong></span>
            <span class="office-content">: VAN &amp; COACH RENTAL | TRANSPORT SERVICES | TOUR SERVICES (KPK/LN: 10621)<br>Kota Kinabalu, Sabah &nbsp; Tel: 088-388920 &nbsp; HP: 0168122921</span>
        </div>

        <div class="office-section">
            <span class="office-label"><strong>BRANCH [ EHHQ ]</strong></span>
            <span class="office-content">: Jalan Kalansanan, Batu 6, Tuaran By Pass, 88450 Kota Kinabalu, Sabah<br>Email: uniedenholidays@gmail.com</span>
        </div>

        <div class="invoice-header">
            <div class="invoice-header-col invoice-header-title"><strong>INVOICE</strong></div>
            <div class="invoice-header-col invoice-header-center"><strong>No :</strong> {{ $booking->invoice_number_or_reference }}</div>
            <div class="invoice-header-col invoice-header-right"><strong>Date :</strong> {{ optional($issuedDate)->format('d.m.Y') }}</div>
        </div>

        <div class="billed-section">
            <div class="billed-left">
                <div class="billed-row">
                    <div class="billed-label">To</div>
                    <div class="billed-value">{{ $customerName }}</div>
                </div>
                <div class="billed-row">
                    <div class="billed-label">Address</div>
                    <div class="billed-value">{{ substr($address, 0, 40) }}</div>
                </div>
                <div class="billed-row">
                    <div class="billed-label">Contact No</div>
                    <div class="billed-value">{{ $phone }}</div>
                </div>
            </div>
            <div class="billed-right">
                <div class="billed-row">
                    <div class="billed-label">Ref No</div>
                    <div class="billed-value">{{ $booking->booking_reference ?: '' }}</div>
                </div>
                <div class="billed-row">
                    <div class="billed-label">Page</div>
                    <div class="billed-value">1</div>
                </div>
            </div>
        </div>

        <div style="margin: 6px 0;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px" class="text-center">No</th>
                        <th>Description</th>
                        <th style="width: 130px" class="text-right">Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td style="text-transform: uppercase;">{{ $packageLine }}</td>
                        <td class="text-right">{{ number_format((float) $booking->amount_myr, 2) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="padding-left: 20px; font-size: 9px;">Travel date: {{ optional($booking->check_in_date)->format('d M Y') }} to {{ optional($booking->check_out_date)->format('d M Y') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="padding-left: 20px; font-size: 9px;">Guests: {{ $booking->total_guests }} traveler{{ $booking->total_guests === 1 ? '' : 's' }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="footer-left">
                <div class="bank-info">"6000 0023 0002 0022" - Alliance Bank</div>
                <div class="bank-info">Universal Eden Holidays Sdn Bhd</div>
                <div style="font-size: 9px; margin-top: 3px;">
                    <strong>RINGGIT MALAYSIA :</strong> {{ number_format((float) $booking->amount_myr, 2) }} ONLY
                </div>
                <div class="footer-note">This is a computer-generated invoice for customer records.</div>
            </div>
            <div class="footer-right">
                <div class="totals-wrap">
                    <div class="totals-label">Total Amount :</div>
                    <div class="totals-box">{{ number_format((float) $booking->amount_myr, 2) }}</div>
                </div>
                <div class="totals-wrap">
                    <div class="totals-label">Rounding :</div>
                    <div class="totals-box">-</div>
                </div>
                <div class="totals-wrap">
                    <div class="totals-label"><strong>Grand Total :</strong></div>
                    <div class="totals-box"><strong>{{ number_format((float) $booking->amount_myr, 2) }}</strong></div>
                </div>
            </div>
        </div>

        <div class="signatures">
            <div class="sig-col">
                <div class="sig-line"></div>
                <strong>Issued By</strong>
                <br>Name : SYSTEM
            </div>
            <div class="sig-col">
                <div class="sig-line"></div>
                <strong>Approved By</strong>
                <br>Name : MANAGEMENT
            </div>
            <div class="sig-col" style="padding-right: 0;">
                <div class="sig-line"></div>
                <strong>Received By</strong>
                <br>Name :
            </div>
        </div>
    </div>
</body>

</html>
