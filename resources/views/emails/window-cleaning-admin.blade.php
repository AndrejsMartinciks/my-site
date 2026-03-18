@php
    $addons = is_array($booking->addons) ? $booking->addons : [];
    $summary = json_decode($booking->calculator_summary ?? '[]', true) ?: [];
@endphp
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Ny bokning</title>
</head>
<body style="font-family: Arial, sans-serif; color:#111827; line-height:1.6;">
    <h2>Ny bokning för fönsterputsning</h2>

    <p><strong>Boknings-ID:</strong> #{{ $booking->id }}</p>
    <p><strong>Kund:</strong> {{ $booking->customer_name }}</p>
    <p><strong>E-post:</strong> {{ $booking->email }}</p>
    <p><strong>Telefon:</strong> {{ $booking->phone }}</p>
    <p><strong>Adress:</strong> {{ $booking->address }}, {{ $booking->postcode }}</p>
    <p><strong>Personnummer sista 4:</strong> {{ $booking->personnummer_last4 }}</p>

    <hr>

    <p><strong>Datum:</strong> {{ optional($booking->booking_date)->format('Y-m-d') }}</p>
    <p><strong>Tid:</strong> {{ \Illuminate\Support\Str::substr($booking->time_from, 0, 5) }}–{{ \Illuminate\Support\Str::substr($booking->time_to, 0, 5) }}</p>
    <p><strong>Antal fönster:</strong> {{ $booking->window_count }}</p>
    <p><strong>Typ:</strong> {{ $summary['cleaning_scope'] ?? $booking->cleaning_scope }}</p>
    <p><strong>Pris:</strong> {{ number_format((int) $booking->quoted_price, 0, ',', ' ') }} kr</p>

    <h3>Tillägg</h3>
    @if(count($addons))
        <ul>
            @foreach($addons as $addon)
                <li>{{ $addon['name'] ?? '-' }} — {{ number_format((int) ($addon['price'] ?? 0), 0, ',', ' ') }} kr</li>
            @endforeach
        </ul>
    @else
        <p>Inga tillägg valda.</p>
    @endif

    <h3>Calculator summary</h3>
    <pre style="background:#f3f4f6; padding:12px; border-radius:8px; overflow:auto;">{{ json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
</body>
</html>