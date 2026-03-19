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
    <title>Bokningsbekräftelse</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial, sans-serif; color:#111827; line-height:1.6;">
    <div style="max-width:680px; margin:0 auto; padding:32px 16px;">
        <div style="background:#ffffff; border:1px solid #e5e7eb; border-radius:20px; overflow:hidden;">
            <div style="background:linear-gradient(135deg, #0f766e, #115e59); color:#ffffff; padding:28px 24px;">
                <p style="margin:0 0 8px; font-size:13px; letter-spacing:0.08em; text-transform:uppercase; opacity:0.9;">
                    Clean Source AB
                </p>
                <h1 style="margin:0; font-size:26px; line-height:1.2;">Tack för din bokningsförfrågan</h1>
            </div>

            <div style="padding:24px;">
                <p style="margin-top:0;">Hej {{ $booking->customer_name }},</p>

                <p>
                    Vi har tagit emot din bokningsförfrågan för <strong>fönsterputsning</strong>.
                    Nedan ser du en sammanfattning av din bokning. Vårt team kontaktar dig snart
                    för att bekräfta uppdraget.
                </p>

                <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px; padding:18px 16px; margin:20px 0;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Tjänst</td>
                            <td style="padding:8px 0; text-align:right;"><strong>Fönsterputsning</strong></td>
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
                            <td style="padding:8px 0; color:#475569;">Antal fönster</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->window_count }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Typ av putsning</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ $scopeLabel }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#475569;">Adress</td>
                            <td style="padding:8px 0; text-align:right;"><strong>{{ $booking->address }}, {{ $booking->postcode }}</strong></td>
                        </tr>
                    </table>
                </div>

                <h3 style="margin:24px 0 12px; font-size:18px;">Valda tillägg</h3>

                @if(count($addons))
                    <ul style="margin:0 0 18px; padding-left:18px;">
                        @foreach($addons as $addon)
                            <li>{{ $addon['name'] ?? '-' }} — {{ number_format((int) ($addon['price'] ?? 0), 0, ',', ' ') }} kr</li>
                        @endforeach
                    </ul>
                @else
                    <p style="margin:0 0 18px;">Inga tillägg valda.</p>
                @endif

                <div style="background:#ecfeff; border:1px solid #99f6e4; border-radius:16px; padding:18px 16px; margin:20px 0;">
                    <p style="margin:0 0 6px; color:#0f766e;">Preliminärt pris</p>
                    <p style="margin:0; font-size:28px; font-weight:700; color:#0f172a;">
                        {{ number_format((int) $booking->quoted_price, 0, ',', ' ') }} kr
                    </p>
                </div>

                <p>
                    Om du behöver uppdatera något i bokningen, svara gärna på detta mejl eller kontakta oss direkt.
                </p>

                <p style="margin-bottom:0;">
                    Med vänliga hälsningar,<br>
                    <strong>Clean Source AB</strong>
                </p>
            </div>
        </div>
    </div>
</body>
</html>