<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Bokningsbekräftelse</title>
</head>
<body style="font-family: Arial, sans-serif; color:#111827; line-height:1.6;">
    <h2>Tack för din bokningsförfrågan</h2>

    <p>Hej {{ $booking->customer_name }},</p>

    <p>
        Vi har tagit emot din bokningsförfrågan för fönsterputsning.
        Vårt team kontaktar dig så snart som möjligt för att bekräfta uppdraget.
    </p>

    <p><strong>Datum:</strong> {{ optional($booking->booking_date)->format('Y-m-d') }}</p>
    <p><strong>Tid:</strong> {{ \Illuminate\Support\Str::substr($booking->time_from, 0, 5) }}–{{ \Illuminate\Support\Str::substr($booking->time_to, 0, 5) }}</p>
    <p><strong>Preliminärt pris:</strong> {{ number_format((int) $booking->quoted_price, 0, ',', ' ') }} kr</p>

    <p>Med vänliga hälsningar,<br>Clean Source AB</p>
</body>
</html>