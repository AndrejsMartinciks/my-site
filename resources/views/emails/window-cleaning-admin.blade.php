@php
    $addons = is_array($booking->addons) ? $booking->addons : [];
    $summary = json_decode($booking->calculator_summary ?? '[]', true) ?: [];

    $scopeLabel = $summary['cleaning_scope'] ?? match ($booking->cleaning_scope) {
        'inside' => 'Invändigt',
        'outside' => 'Utvändigt',
        'both' => 'In- och utvändigt',
        default => $booking->cleaning_scope,
    };

    $bookingDate = optional($booking->booking_date)->format('Y-m-d');
    $timeFrom = \Illuminate\Support\Str::substr((string) $booking->time_from, 0, 5);
    $timeTo = \Illuminate\Support\Str::substr((string) $booking->time_to, 0, 5);
@endphp
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Ny bokning för fönsterputsning</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial, sans-serif; color:#111827; line-height:1.6;">
    <div style="max-width:760px; margin:0 auto; padding:32px 16px;">
        <div style="background:#ffffff; border:1px solid #e5e7eb; border-radius:20px; overflow:hidden;">
            <div style="background:#0f172a; color:#ffffff; padding:24px;">
                <p style="margin:0 0 8px; font-size:13px; letter-spacing:0.08em; text-transform:uppercase; opacity:0.9;">
                    Admin notification
                </p>
                <h1 style="margin:0; font-size:24px; line-height:1.2;">Ny bokning för fönsterputsning</h1>
            </div>

            <div style="padding:24px;">
                <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px; padding:18px 16px; margin-bottom:20px;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Boknings-ID</td>
                            <td style="padding:8px 0; text-align:right;"><strong>#{{ $booking->id }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Status</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->status }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Datum</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ $bookingDate }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Tid</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ $timeFrom }}–{{ $timeTo }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Pris</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ number_format((int) $booking->quoted_price, 0, ',', ' ') }} kr</strong></td>
                        </tr>
                    </table>
                </div>

                <h3 style="margin:0 0 12px;">Kunduppgifter</h3>
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:24px;">
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Kund</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->customer_name }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">E-post</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->email }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Telefon</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->phone }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Adress</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->address }}, {{ $booking->postcode }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Personnummer sista 4</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->personnummer_last4 }}</strong></td>
                    </tr>
                </table>

                <h3 style="margin:0 0 12px;">Bokningsdetaljer</h3>
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:24px;">
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Tjänst</td>
                        <td style="padding:8px 0; text-align:right;"><strong>Fönsterputsning</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Antal fönster</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->window_count }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Typ av putsning</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $scopeLabel }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; color:#475569;">Booking slot ID</td>
                        <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->booking_slot_id }}</strong></td>
                    </tr>
                </table>

                <h3 style="margin:0 0 12px;">Tillägg</h3>
                @if(count($addons))
                    <ul style="margin:0 0 24px; padding-left:18px;">
                        @foreach($addons as $addon)
                            <li>{{ $addon['name'] ?? '-' }} — {{ number_format((int) ($addon['price'] ?? 0), 0, ',', ' ') }} kr</li>
                        @endforeach
                    </ul>
                @else
                    <p style="margin:0 0 24px;">Inga tillägg valda.</p>
                @endif

                @if(!empty($summary['message']))
                    <h3 style="margin:0 0 12px;">Meddelande från kund</h3>
                    <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px; padding:16px; margin-bottom:24px;">
                        {{ $summary['message'] }}
                    </div>
                @elseif(!empty($booking->message))
                    <h3 style="margin:0 0 12px;">Meddelande från kund</h3>
                    <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px; padding:16px; margin-bottom:24px;">
                        {{ $booking->message }}
                    </div>
                @endif

                <h3 style="margin:0 0 12px;">Calculator summary</h3>
                <pre style="background:#0f172a; color:#e2e8f0; padding:16px; border-radius:16px; overflow:auto; font-size:13px;">{{ json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>
</body>
</html>