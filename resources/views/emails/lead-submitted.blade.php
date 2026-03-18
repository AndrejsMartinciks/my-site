<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Ny lead</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #222;">
    <h2>Ny förfrågan från webbplatsen</h2>

    <p><strong>Namn:</strong> {{ $lead['name'] }}</p>
    <p><strong>E-post:</strong> {{ $lead['email'] }}</p>
    <p><strong>Telefon:</strong> {{ $lead['phone'] }}</p>
    <p><strong>Tjänst:</strong> {{ $lead['service'] }}</p>

    <p><strong>Meddelande:</strong><br>
        {!! nl2br(e($lead['message'] ?? '—')) !!}
    </p>

    @if(!empty($lead['calculator_summary']))
        <p><strong>Prisberäkning från kalkylatorn:</strong><br>
            {!! nl2br(e($lead['calculator_summary'])) !!}
        </p>
    @endif

    <p><strong>Skickad:</strong> {{ $lead['created_at'] }}</p>
</body>
</html>