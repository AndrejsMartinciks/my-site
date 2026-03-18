@php
    $companyName = optional($siteSettings)->company_name ?: 'CleanS AB';
    $phonePrimary = optional($siteSettings)->phone_primary ?: '070 741 37 72';
    $email = optional($siteSettings)->email ?: 'info@cleansource.se';
    $phonePrimaryHref = preg_replace('/[^\d+]/', '', $phonePrimary);

    $timeOptions = [
        '08:00', '09:00', '10:00', '11:00', '12:00',
        '13:00', '14:00', '15:00', '16:00', '17:00',
    ];
@endphp

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} | {{ $companyName }}</title>
    <meta name="description" content="Boka fönsterputsning online hos {{ $companyName }}.">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">

    <style>
        .wc-page {
            background: #f6f8fb;
            min-height: 100vh;
            padding-bottom: 64px;
        }

        .wc-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .wc-header-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .wc-brand {
            font-weight: 800;
            font-size: 20px;
            color: #0f172a;
            text-decoration: none;
        }

        .wc-nav {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .wc-nav a {
            text-decoration: none;
        }

        .wc-shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 20px 0;
        }

        .wc-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            color: #fff;
            border-radius: 24px;
            padding: 32px;
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        .wc-hero h1 {
            font-size: 42px;
            line-height: 1.1;
            margin: 0 0 12px;
        }

        .wc-hero p {
            margin: 0;
            font-size: 18px;
            max-width: 760px;
            opacity: 0.98;
        }

        .wc-hero-card {
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            padding: 20px;
            align-self: stretch;
        }

        .wc-hero-card ul {
            margin: 0;
            padding-left: 18px;
        }

        .wc-hero-card li + li {
            margin-top: 8px;
        }

        .wc-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) 380px;
            gap: 24px;
        }

        .wc-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        }

        .wc-card + .wc-card {
            margin-top: 20px;
        }

        .wc-card h2,
        .wc-card h3 {
            margin-top: 0;
        }

        .wc-section-title {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .wc-muted {
            color: #64748b;
        }

        .wc-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .wc-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .wc-field.full {
            grid-column: 1 / -1;
        }

        .wc-field label,
        .wc-label {
            font-weight: 700;
            color: #0f172a;
        }

        .wc-input,
        .wc-select,
        .wc-textarea {
            width: 100%;
            border: 1px solid #dbe3ee;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 16px;
            background: #fff;
            box-sizing: border-box;
        }

        .wc-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .wc-scope-grid,
        .wc-addon-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .wc-choice,
        .wc-addon {
            position: relative;
        }

        .wc-choice input,
        .wc-addon input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .wc-choice span,
        .wc-addon span {
            display: block;
            border: 2px solid #dbe3ee;
            border-radius: 18px;
            padding: 18px 16px;
            text-align: center;
            font-weight: 700;
            background: #fff;
            transition: 0.2s ease;
            cursor: pointer;
        }

        .wc-choice input:checked + span,
        .wc-addon input:checked + span {
            border-color: #38bdf8;
            background: #eff8ff;
            color: #0c4a6e;
        }

        .wc-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .wc-summary-row:last-of-type {
            border-bottom: 0;
        }

        .wc-total {
            margin-top: 16px;
            padding: 18px;
            border-radius: 18px;
            background: #eff8ff;
            color: #0c4a6e;
        }

        .wc-total strong {
            display: block;
            font-size: 34px;
            line-height: 1;
            margin-top: 6px;
        }

        .wc-submit {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 16px 18px;
            font-size: 16px;
            font-weight: 800;
            background: #0ea5e9;
            color: #fff;
            cursor: pointer;
        }

        .wc-alert {
            padding: 16px 18px;
            border-radius: 16px;
            margin-bottom: 18px;
        }

        .wc-alert-success {
            background: #ecfdf5;
            border: 1px solid #86efac;
            color: #166534;
        }

        .wc-alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .wc-small {
            font-size: 14px;
        }

        @media (max-width: 980px) {
            .wc-hero,
            .wc-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .wc-fields,
            .wc-scope-grid,
            .wc-addon-grid {
                grid-template-columns: 1fr;
            }

            .wc-hero h1 {
                font-size: 34px;
            }
        }
    </style>
</head>
<body class="wc-page">
    <header class="wc-header">
        <div class="wc-header-inner">
            <a href="{{ route('home') }}" class="wc-brand">{{ $companyName }}</a>

            <nav class="wc-nav">
                <a href="{{ route('home') }}#services" class="btn btn-secondary">Tjänster</a>
                <a href="{{ route('home') }}#pricing" class="btn btn-secondary">Priser</a>
                <a href="tel:{{ $phonePrimaryHref }}" class="btn btn-secondary">{{ $phonePrimary }}</a>
                <a href="{{ route('window-cleaning') }}" class="btn btn-primary">Boka fönsterputs</a>
            </nav>
        </div>
    </header>

    <main class="wc-shell">
        <section class="wc-hero">
            <div>
                <div class="eyebrow" style="color:#e0f2fe;">Onlinebokning</div>
                <h1>Fönsterputsning med separat bokningssida</h1>
                <p>
                    Välj antal fönster, typ av putsning, tillägg, datum och tid.
                    Priset räknas ut direkt och bokningen sparas i databasen.
                </p>
            </div>

            <aside class="wc-hero-card">
                <ul>
                    <li>Pris räknas direkt utifrån dina prisintervall i databasen</li>
                    <li>Tillägg hämtas från service_addons</li>
                    <li>Bokningen sparas i bookings och visas i Filament</li>
                </ul>
            </aside>
        </section>

        @if(session('success'))
            <div class="wc-alert wc-alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="wc-alert wc-alert-error">
                <strong>Det finns fel i formuläret:</strong>
                <ul style="margin:10px 0 0 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('window-cleaning.store') }}">
            @csrf

            <div class="wc-grid">
                <div>
                    <section class="wc-card">
                        <h2 class="wc-section-title">{{ $service->name }}</h2>
                        <p class="wc-muted">
                            Ange antal fönster och välj om du vill ha putsning invändigt, utvändigt eller båda sidor.
                        </p>

                        <div class="wc-fields" style="margin-top:20px;">
                            <div class="wc-field">
                                <label for="window_count">Antal fönster</label>
                                <input
                                    id="window_count"
                                    class="wc-input"
                                    type="number"
                                    name="window_count"
                                    min="1"
                                    max="100"
                                    value="{{ old('window_count', 6) }}"
                                    required
                                >
                            </div>

                            <div class="wc-field">
                                <label for="booking_date">Datum</label>
                                <input
                                    id="booking_date"
                                    class="wc-input"
                                    type="date"
                                    name="booking_date"
                                    min="{{ now()->format('Y-m-d') }}"
                                    value="{{ old('booking_date') }}"
                                    required
                                >
                            </div>
                        </div>

                        <div style="margin-top:22px;">
                            <div class="wc-label" style="margin-bottom:10px;">Typ av putsning</div>
                            <div class="wc-scope-grid">
                                <label class="wc-choice">
                                    <input type="radio" name="cleaning_scope" value="inside" {{ old('cleaning_scope', 'inside') === 'inside' ? 'checked' : '' }}>
                                    <span>Invändigt</span>
                                </label>

                                <label class="wc-choice">
                                    <input type="radio" name="cleaning_scope" value="outside" {{ old('cleaning_scope') === 'outside' ? 'checked' : '' }}>
                                    <span>Utvändigt</span>
                                </label>

                                <label class="wc-choice">
                                    <input type="radio" name="cleaning_scope" value="both" {{ old('cleaning_scope') === 'both' ? 'checked' : '' }}>
                                    <span>In- och utvändigt</span>
                                </label>
                            </div>
                        </div>

                        <div style="margin-top:22px;">
                            <div class="wc-label" style="margin-bottom:10px;">Tilläggstjänster</div>
                            <div class="wc-addon-grid">
                                @foreach($service->addons as $addon)
                                    <label class="wc-addon">
                                        <input
                                            type="checkbox"
                                            name="addon_ids[]"
                                            value="{{ $addon->id }}"
                                            data-addon-name="{{ $addon->name }}"
                                            data-addon-price="{{ $addon->price }}"
                                            {{ in_array($addon->id, old('addon_ids', [])) ? 'checked' : '' }}
                                        >
                                        <span>
                                            {{ $addon->name }}<br>
                                            <small>{{ number_format($addon->price, 0, ',', ' ') }} kr</small>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="wc-fields" style="margin-top:22px;">
                            <div class="wc-field">
                                <label for="time_from">Tid</label>
                                <select id="time_from" class="wc-select" name="time_from" required>
                                    <option value="">Välj tid</option>
                                    @foreach($timeOptions as $time)
                                        <option value="{{ $time }}" {{ old('time_from') === $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="wc-field">
                                <label for="postcode">Postnummer</label>
                                <input
                                    id="postcode"
                                    class="wc-input"
                                    type="text"
                                    name="postcode"
                                    value="{{ old('postcode') }}"
                                    placeholder="12345"
                                    required
                                >
                            </div>
                        </div>
                    </section>

                    <section class="wc-card">
                        <h3>Dina uppgifter</h3>

                        <div class="wc-fields">
                            <div class="wc-field">
                                <label for="customer_name">Namn</label>
                                <input
                                    id="customer_name"
                                    class="wc-input"
                                    type="text"
                                    name="customer_name"
                                    value="{{ old('customer_name') }}"
                                    required
                                >
                            </div>

                            <div class="wc-field">
                                <label for="email">E-post</label>
                                <input
                                    id="email"
                                    class="wc-input"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                >
                            </div>

                            <div class="wc-field">
                                <label for="phone">Telefon</label>
                                <input
                                    id="phone"
                                    class="wc-input"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                >
                            </div>

                            <div class="wc-field">
                                <label for="personnummer">Personnummer</label>
                                <input
                                    id="personnummer"
                                    class="wc-input"
                                    type="text"
                                    name="personnummer"
                                    value="{{ old('personnummer') }}"
                                    placeholder="YYYYMMDDXXXX"
                                    required
                                >
                            </div>

                            <div class="wc-field full">
                                <label for="address">Adress</label>
                                <input
                                    id="address"
                                    class="wc-input"
                                    type="text"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="Gatuadress"
                                    required
                                >
                            </div>

                            <div class="wc-field full">
                                <label for="message">Meddelande</label>
                                <textarea
                                    id="message"
                                    class="wc-textarea"
                                    name="message"
                                    placeholder="Exempel: portkod, våning, särskilda önskemål">{{ old('message') }}</textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="wc-card">
                    <h3>Sammanfattning</h3>

                    <div class="wc-summary-row">
                        <span>Tjänst</span>
                        <strong>{{ $service->name }}</strong>
                    </div>

                    <div class="wc-summary-row">
                        <span>Antal fönster</span>
                        <strong id="summary-window-count">-</strong>
                    </div>

                    <div class="wc-summary-row">
                        <span>Typ av putsning</span>
                        <strong id="summary-scope">-</strong>
                    </div>

                    <div class="wc-summary-row">
                        <span>Grundpris</span>
                        <strong id="summary-base-price">0 kr</strong>
                    </div>

                    <div class="wc-summary-row">
                        <span>Tillägg</span>
                        <strong id="summary-addons-price">0 kr</strong>
                    </div>

                    <div class="wc-summary-row">
                        <span>Valda tillägg</span>
                        <strong id="summary-addons-list">Inga</strong>
                    </div>

                    <div class="wc-total">
                        Totalt pris
                        <strong id="summary-total-price">0 kr</strong>
                        <div class="wc-small">Pris efter RUT enligt dina nuvarande intervall.</div>
                    </div>

                    <div style="margin-top:20px;">
                        <button type="submit" class="wc-submit">Skicka bokning</button>
                    </div>

                    <p class="wc-muted wc-small" style="margin-top:14px;">
                        Bokningen sparas i adminpanelen. Priset räknas om på servern vid submit.
                    </p>
                </aside>
            </div>
        </form>
    </main>

    <script>
        const calculatorData = @json($calculatorData);
        const serviceName = @json($service->name);

        const windowCountInput = document.getElementById('window_count');
        const scopeInputs = document.querySelectorAll('input[name="cleaning_scope"]');
        const addonInputs = document.querySelectorAll('input[name="addon_ids[]"]');

        const summaryWindowCount = document.getElementById('summary-window-count');
        const summaryScope = document.getElementById('summary-scope');
        const summaryBasePrice = document.getElementById('summary-base-price');
        const summaryAddonsPrice = document.getElementById('summary-addons-price');
        const summaryAddonsList = document.getElementById('summary-addons-list');
        const summaryTotalPrice = document.getElementById('summary-total-price');

        const scopeLabels = {
            inside: 'Invändigt',
            outside: 'Utvändigt',
            both: 'In- och utvändigt',
        };

        const scopeMultipliers = {
            inside: 1.0,
            outside: 1.0,
            both: 1.8,
        };

        function parseRanges(rangeString) {
            if (!rangeString) return [];

            return rangeString.split('|').map(item => {
                const [rangePart, pricePart] = item.split(':').map(s => s.trim());
                if (!rangePart || !pricePart) return null;

                const [min, max] = rangePart.split('-').map(Number);

                return {
                    min,
                    max,
                    price: Number(pricePart),
                };
            }).filter(Boolean);
        }

        function resolveBasePrice(windowCount, serviceConfig) {
            if (!serviceConfig || !serviceConfig.ranges) {
                return 0;
            }

            const ranges = parseRanges(serviceConfig.ranges);

            for (const range of ranges) {
                if (windowCount >= range.min && windowCount <= range.max) {
                    return range.price;
                }
            }

            if (ranges.length && windowCount > ranges[ranges.length - 1].max) {
                return ranges[ranges.length - 1].price;
            }

            return 0;
        }

        function formatPrice(value) {
            return new Intl.NumberFormat('sv-SE').format(value) + ' kr';
        }

        function getSelectedScope() {
            const selected = [...scopeInputs].find(input => input.checked);
            return selected ? selected.value : 'inside';
        }

        function getSelectedAddons() {
            return [...addonInputs]
                .filter(input => input.checked)
                .map(input => ({
                    name: input.dataset.addonName,
                    price: Number(input.dataset.addonPrice || 0),
                }));
        }

        function updateSummary() {
            const serviceConfig = calculatorData.services[serviceName] || null;
            const windowCount = Number(windowCountInput.value || 0);
            const scope = getSelectedScope();
            const multiplier = scopeMultipliers[scope] || 1;

            const rawBasePrice = resolveBasePrice(windowCount, serviceConfig);
            const adjustedBasePrice = Math.round(rawBasePrice * multiplier);

            const selectedAddons = getSelectedAddons();
            const addonsTotal = selectedAddons.reduce((sum, addon) => sum + addon.price, 0);
            const total = adjustedBasePrice + addonsTotal;

            summaryWindowCount.textContent = windowCount ? `${windowCount} st` : '-';
            summaryScope.textContent = scopeLabels[scope] || '-';
            summaryBasePrice.textContent = formatPrice(adjustedBasePrice);
            summaryAddonsPrice.textContent = formatPrice(addonsTotal);
            summaryAddonsList.textContent = selectedAddons.length
                ? selectedAddons.map(addon => addon.name).join(', ')
                : 'Inga';
            summaryTotalPrice.textContent = formatPrice(total);
        }

        windowCountInput.addEventListener('input', updateSummary);
        scopeInputs.forEach(input => input.addEventListener('change', updateSummary));
        addonInputs.forEach(input => input.addEventListener('change', updateSummary));

        updateSummary();
    </script>
</body>
</html>