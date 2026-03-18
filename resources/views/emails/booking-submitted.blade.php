<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Ny bokning</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #222;">
    <h2>Ny bokning från webbplatsen</h2>

    <p><strong>Tjänst:</strong> {{ $booking->service?->name ?? 'Engångsstädning' }}</p>
    <p><strong>Datum:</strong> {{ $bookingDateFormatted }}</p>
    <p><strong>Tid:</strong> {{ $timeLabel }}</p>

    <hr>

    <p><strong>För &amp; Efternamn:</strong> {{ $booking->customer_name }}</p>
    <p><strong>Personnummer:</strong> {{ $maskedPersonnummer }}</p>
    <p><strong>Adress:</strong> {{ $booking->address }}</p>
    <p><strong>Telefonnummer:</strong> {{ $booking->phone }}</p>
    <p><strong>E-post:</strong> {{ $booking->email }}</p>

    @if(!empty($booking->calculator_summary))
        <hr>
        <p><strong>Prisberäkning från kalkylatorn:</strong><br>
            {!! nl2br(e($booking->calculator_summary)) !!}
        </p>
    @endif

    @if(!empty($booking->manager_note))
        <hr>
        <p><strong>Intern anteckning:</strong><br>
            {!! nl2br(e($booking->manager_note)) !!}
        </p>
    @endif

    <hr>
    <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
    <p><strong>Skickad:</strong> {{ $booking->created_at?->format('Y-m-d H:i:s') ?? '—' }}</p>
</body>
</html>