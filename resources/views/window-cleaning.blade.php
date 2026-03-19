@php
    $companyName = optional($siteSettings)->company_name ?: 'Clean Source AB';
    $phonePrimary = optional($siteSettings)->phone_primary ?: '070 741 37 72';
    $phoneSecondary = optional($siteSettings)->phone_secondary ?: null;
    $email = optional($siteSettings)->email ?: 'info@cleansource.se';
    $phonePrimaryHref = preg_replace('/[^\d+]/', '', $phonePrimary);

    $addressLine = trim(implode(', ', array_filter([
        optional($siteSettings)->address ?: 'Ångermannagatan 121',
        trim(implode(' ', array_filter([
            optional($siteSettings)->postal_code ?: '162 64',
            optional($siteSettings)->city ?: 'Vällingby',
        ]))),
    ])));

    $orgNumber = optional($siteSettings)->org_number ?: '556988-2722';
    $bankgiro = optional($siteSettings)->bankgiro ?: '540-1054';
    $swish = optional($siteSettings)->swish ?: '1234591558';
    $facebookUrl = 'https://www.facebook.com/profile.php?id=61558309301151#';
    $instagramUrl = 'https://www.instagram.com/cleansource_ab';
    $recoUrl = 'https://www.reco.se/clean-source-ab';

    $selectedAddons = collect(old('addon_ids', []))
        ->map(fn ($id) => (int) $id)
        ->all();

    $priceRanges = $service->priceRanges
        ->sortBy('sort_order')
        ->map(fn ($range) => [
            'min' => (int) $range->min_sqm,
            'max' => (int) $range->max_sqm,
            'price' => (int) $range->price,
        ])
        ->values();

    $addonData = $service->addons
        ->map(fn ($addon) => [
            'id' => (int) $addon->id,
            'name' => $addon->name,
            'price' => (int) $addon->price,
        ])
        ->values();

    $scopeLabels = [
        'inside' => 'Invändigt',
        'outside' => 'Utvändigt',
        'both' => 'In- och utvändigt',
    ];

    $privateMenu = [
        ['label' => 'Hemstädning', 'href' => route('services.private.show', 'hemstadning')],
        ['label' => 'Flyttstädning', 'href' => route('services.private.show', 'flyttstadning')],
        ['label' => 'Fönsterputsning', 'href' => route('window-cleaning')],
        ['label' => 'Byggstädning', 'href' => route('services.private.show', 'byggstadning')],
        ['label' => 'Storstädning', 'href' => route('services.private.show', 'storstadning')],
        ['label' => 'Visningsstädning', 'href' => route('services.private.show', 'visningsstadning')],
    ];

    $companyMenu = [
        ['label' => 'Butiksstädning', 'href' => route('services.company.show', 'butiksstadning')],
        ['label' => 'Flyttstädning', 'href' => route('services.company.show', 'flyttstadning')],
        ['label' => 'Storstädning', 'href' => route('services.company.show', 'storstadning')],
        ['label' => 'Fönsterputsning', 'href' => route('services.company.show', 'fonsterputsning')],
        ['label' => 'Byggstädning', 'href' => route('services.company.show', 'byggstadning')],
        ['label' => 'Kontorsstädning', 'href' => route('services.company.show', 'kontorsstadning')],
    ];
@endphp
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $service->name }} | {{ $companyName }}</title>
    <meta name="description" content="Boka {{ mb_strtolower($service->name) }} online. Välj antal fönster, tillägg, datum och tid.">

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">

    <style>
        .wc-page {
            background: var(--bg);
        }

        .wc-hero {
            padding-top: 72px;
            padding-bottom: 36px;
        }

        .wc-hero__grid {
            display: grid;
            gap: 28px;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            align-items: start;
        }

        .wc-hero-copy,
        .wc-card {
            background: #fff;
            border: 1px solid #e8dfd2;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(22, 32, 51, 0.08);
        }

        .wc-hero-copy {
            padding: 34px;
            background: linear-gradient(180deg, #ffffff, #faf7f1);
        }

        .wc-card {
            padding: 28px;
        }

        .wc-highlights {
            display: grid;
            gap: 12px;
            margin-top: 22px;
            margin-bottom: 0;
            padding: 0;
            list-style: none;
        }

        .wc-highlights li {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 14px 16px;
            color: #4e5a6d;
        }

        .wc-info-strip {
            margin-top: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .wc-info-strip span {
            display: inline-flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 999px;
            background: #f7efe3;
            border: 1px solid rgba(192, 163, 106, 0.24);
            color: #8a6c3b;
            font-size: 14px;
            font-weight: 600;
        }

        .wc-layout {
            display: grid;
            gap: 28px;
            grid-template-columns: minmax(0, 1.15fr) minmax(300px, 0.85fr);
            align-items: start;
        }

        .wc-card--form {
            padding: 30px;
        }

        .wc-card--summary {
            position: sticky;
            top: 112px;
            background: linear-gradient(180deg, #ffffff, #faf7f1);
        }

        .wc-alert {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        .wc-alert--success {
            background: #f6f0e7;
            color: #7a6138;
            border: 1px solid #dfcfb5;
        }

        .wc-alert--error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .wc-stepper {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }

        .wc-stepper__item {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 12px;
            background: #f8fafc;
            min-height: 72px;
        }

        .wc-stepper__item.is-active {
            background: #f7efe3;
            border-color: rgba(192, 163, 106, 0.38);
        }

        .wc-stepper__num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #c0a36a;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .wc-stepper__title {
            display: block;
            font-weight: 700;
            font-size: 14px;
            line-height: 1.3;
            color: #162033;
        }

        .wc-step {
            display: none;
        }

        .wc-step.is-active {
            display: block;
        }

        .wc-step h2 {
            margin-bottom: 8px;
            color: #162033;
        }

        .wc-step__lead {
            margin-bottom: 18px;
            color: #475569;
        }

        .wc-field-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wc-field-full {
            grid-column: 1 / -1;
        }

        .wc-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #162033;
        }

        .wc-input,
        .wc-textarea {
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            background: #fff;
            padding: 13px 14px;
            font: inherit;
            box-sizing: border-box;
        }

        .wc-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .wc-radio-grid,
        .wc-addon-grid,
        .wc-slot-grid,
        .wc-tips-grid {
            display: grid;
            gap: 12px;
        }

        .wc-radio-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .wc-addon-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: stretch;
            gap: 14px;
            margin-top: 6px;
        }

        .wc-tips-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-top: 22px;
        }

        .wc-tip-card {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .wc-tip-card h3 {
            margin-bottom: 8px;
            font-size: 16px;
            color: #162033;
        }

        .wc-tip-card p {
            margin-bottom: 0;
        }

        .wc-radio-card,
        .wc-addon-card {
            position: relative;
            display: block;
            width: 100%;
            min-height: 100%;
        }

        .wc-radio-card input,
        .wc-addon-card input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            margin: 0;
        }

        .wc-radio-card__box {
            display: block;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 16px;
            padding: 16px;
            background: #fff;
            transition: 0.2s ease;
            color: #162033;
        }

        .wc-addon-card__box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            min-height: 132px;
            height: 100%;
            box-sizing: border-box;
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 18px;
            padding: 18px;
            background: #fff;
            transition: 0.2s ease;
        }

        .wc-radio-card input:checked + .wc-radio-card__box,
        .wc-addon-card input:checked + .wc-addon-card__box {
            border-color: rgba(192, 163, 106, 0.42);
            background: #f7efe3;
            box-shadow: 0 10px 26px rgba(192, 163, 106, 0.12);
        }

        .wc-addon-card:hover .wc-addon-card__box {
            border-color: rgba(192, 163, 106, 0.28);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            transform: translateY(-1px);
        }

        .wc-addon-card__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .wc-addon-card__top strong:first-child {
            display: block;
            font-size: 17px;
            line-height: 1.35;
            color: #0f172a;
        }

        .wc-addon-card__top strong:last-child {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            flex-shrink: 0;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid rgba(15, 23, 42, 0.08);
            color: #0f172a;
            font-size: 14px;
            line-height: 1;
        }

        .wc-addon-card input:checked + .wc-addon-card__box .wc-addon-card__top strong:last-child {
            background: rgba(192, 163, 106, 0.12);
            border-color: rgba(192, 163, 106, 0.24);
            color: #8a6c3b;
        }

        .wc-addon-card__box .wc-note,
        .wc-addon-card__box small {
            display: block;
            margin-top: auto;
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        .wc-slot-status {
            margin: 10px 0 0;
            color: #475569;
        }

        .wc-slot-grid {
            margin-top: 16px;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .wc-slot-button {
            width: 100%;
        }

        .wc-slot-button.is-selected {
            outline: 2px solid rgba(192, 163, 106, 0.45);
        }

        .wc-summary-list {
            display: grid;
            gap: 12px;
            margin: 0;
        }

        .wc-summary-row {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 12px;
            border-bottom: 1px dashed rgba(15, 23, 42, 0.1);
        }

        .wc-summary-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .wc-summary-row strong {
            text-align: right;
            color: #162033;
        }

        .wc-summary-total {
            margin-top: 18px;
            padding: 18px;
            border-radius: 18px;
            background: linear-gradient(135deg, #c7ab78, #b6955f);
            color: #fff;
        }

        .wc-summary-total span {
            display: block;
            opacity: 0.9;
            margin-bottom: 6px;
        }

        .wc-summary-total strong {
            font-size: 28px;
            line-height: 1.1;
        }

        .wc-summary-note {
            margin-top: 14px;
            font-size: 14px;
            color: #64748b;
        }

        .wc-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .wc-actions__right {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        .wc-note {
            margin-top: 10px;
            color: #64748b;
            font-size: 14px;
        }

        .wc-confirm-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wc-confirm-item {
            background: #f8fafc;
            border-radius: 16px;
            padding: 14px 16px;
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        .wc-confirm-item span {
            display: block;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .wc-footer-inline {
            padding: 12px 0 46px;
        }

        .wc-footer-inline__box {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 20px;
            padding: 22px;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: var(--shadow);
        }

        @media (max-width: 1100px) {
            .wc-hero__grid,
            .wc-layout,
            .wc-field-grid,
            .wc-addon-grid,
            .wc-radio-grid,
            .wc-confirm-grid,
            .wc-tips-grid {
                grid-template-columns: 1fr;
            }

            .wc-card--summary {
                position: static;
            }

            .wc-stepper {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 760px) {
            .wc-hero {
                padding-top: 56px;
            }

            .wc-stepper {
                grid-template-columns: 1fr;
            }

            .wc-hero-copy,
            .wc-card,
            .wc-card--form,
            .wc-card--summary {
                padding: 22px;
                border-radius: 22px;
            }

            .wc-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .wc-actions__right {
                width: 100%;
                margin-left: 0;
            }

            .wc-actions__right .btn,
            .wc-actions .btn {
                width: 100%;
            }

            .wc-footer-inline__box {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="wc-page">
    <div class="site-topbar">
        <div class="container site-topbar__inner">
            <div class="site-topbar__left">
                <a href="tel:{{ $phonePrimaryHref }}">{{ $phonePrimary }}</a>
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </div>

            <div class="site-topbar__right">
                <a href="{{ $recoUrl }}" target="_blank" rel="noopener noreferrer">Reco</a>
                <a href="{{ route('home') }}#pricing">Pris</a>
            </div>
        </div>
    </div>

    <header class="site-header" id="top">
        <div class="container nav-wrap">
            <a class="brand" href="{{ route('home') }}" aria-label="{{ $companyName }} startsida">
                <img src="{{ asset('images/logo.png') }}" alt="{{ $companyName }}" class="brand-logo">
                <span>
                    <strong>{{ $companyName }}</strong>
                    <small>Städning i Stockholm</small>
                </span>
            </a>

            <nav class="site-nav" aria-label="Huvudmeny">
                <details class="nav-dropdown">
                    <summary>Städning</summary>
                    <div class="nav-dropdown__menu">
                        @foreach($privateMenu as $item)
                            <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </details>

                <details class="nav-dropdown">
                    <summary>Företag</summary>
                    <div class="nav-dropdown__menu">
                        @foreach($companyMenu as $item)
                            <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </details>

                <a href="{{ route('home') }}#about">Om oss</a>
                <a href="{{ route('home') }}#pricing">Pris</a>
                <a href="{{ route('home') }}#contact" class="btn btn-sm btn-primary nav-cta">Begär offert</a>
            </nav>
        </div>
    </header>

    <main id="main">
        <section class="wc-hero section">
            <div class="container wc-hero__grid">
                <div class="wc-hero-copy">
                    <span class="eyebrow">Separat tjänstesida</span>
                    <h1>Boka {{ mb_strtolower($service->name) }} online</h1>
                    <p class="lead">
                        Välj antal fönster, tillägg, datum och tid. Du får en tydlig prisöversikt direkt,
                        och vi bekräftar din bokning så snart som möjligt.
                    </p>

                    <ul class="wc-highlights">
                        <li>Priset räknas automatiskt från dina aktuella intervall i databasen</li>
                        <li>Lediga tider hämtas direkt från bokningsslottar</li>
                        <li>Vald tid låses när bokningen skickas in</li>
                        <li>Kund och admin får e-post efter skickad förfrågan</li>
                    </ul>

                    <div class="wc-info-strip">
                        <span>Separat bokningsflöde</span>
                        <span>Tydlig prisberäkning</span>
                        <span>Snabb återkoppling</span>
                    </div>
                </div>

                <aside class="wc-card wc-card--summary">
                    <h2>Snabb info</h2>
                    <div class="wc-summary-list">
                        <div class="wc-summary-row">
                            <span>Tjänst</span>
                            <strong>{{ $service->name }}</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Telefon</span>
                            <strong>{{ $phonePrimary }}</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>E-post</span>
                            <strong>{{ $email }}</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Beskrivning</span>
                            <strong>{{ $service->description ?: 'Professionell fönsterputsning med tydlig bokning.' }}</strong>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section" id="window-booking">
            <div class="container wc-layout">
                <div class="wc-card wc-card--form">
                    @if(session('success'))
                        <div class="wc-alert wc-alert--success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="wc-alert wc-alert--error">
                            <strong>Det finns fel i formuläret:</strong>
                            <ul style="margin: 8px 0 0 18px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="wc-stepper" id="wc-stepper" aria-label="Bokningssteg">
                        <div class="wc-stepper__item is-active" data-step-indicator="1">
                            <span class="wc-stepper__num">1</span>
                            <span class="wc-stepper__title">Fönster</span>
                        </div>
                        <div class="wc-stepper__item" data-step-indicator="2">
                            <span class="wc-stepper__num">2</span>
                            <span class="wc-stepper__title">Tillägg</span>
                        </div>
                        <div class="wc-stepper__item" data-step-indicator="3">
                            <span class="wc-stepper__num">3</span>
                            <span class="wc-stepper__title">Datum & tid</span>
                        </div>
                        <div class="wc-stepper__item" data-step-indicator="4">
                            <span class="wc-stepper__num">4</span>
                            <span class="wc-stepper__title">Kontakt</span>
                        </div>
                        <div class="wc-stepper__item" data-step-indicator="5">
                            <span class="wc-stepper__num">5</span>
                            <span class="wc-stepper__title">Bekräfta</span>
                        </div>
                    </div>

                    <form id="window-cleaning-form" method="POST" action="{{ route('window-cleaning.store') }}" novalidate>
                        @csrf

                        <input type="hidden" name="booking_slot_id" id="wc-booking-slot-id" value="{{ old('booking_slot_id') }}">
                        <input type="hidden" name="time_from" id="wc-time-from" value="{{ old('time_from') }}">

                        <section class="wc-step is-active" data-step="1">
                            <h2>Steg 1 — Fönster</h2>
                            <p class="wc-step__lead">Ange antal fönster och välj vilken typ av putsning du behöver.</p>

                            <div class="wc-field-grid">
                                <div>
                                    <label class="wc-label" for="wc-window-count">Antal fönster</label>
                                    <input
                                        class="wc-input"
                                        id="wc-window-count"
                                        type="number"
                                        name="window_count"
                                        min="1"
                                        max="100"
                                        step="1"
                                        value="{{ old('window_count', 10) }}"
                                        required
                                    >
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <label class="wc-label">Typ av putsning</label>
                                <div class="wc-radio-grid">
                                    @foreach($scopeLabels as $value => $label)
                                        <label class="wc-radio-card">
                                            <input
                                                type="radio"
                                                name="cleaning_scope"
                                                value="{{ $value }}"
                                                {{ old('cleaning_scope', 'both') === $value ? 'checked' : '' }}
                                                required
                                            >
                                            <span class="wc-radio-card__box">
                                                <strong>{{ $label }}</strong>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <section class="wc-step" data-step="2">
                            <h2>Steg 2 — Tillägg</h2>
                            <p class="wc-step__lead">Välj eventuella tillägg som ska ingå i uppdraget.</p>

                            @if($service->addons->isNotEmpty())
                                <div class="wc-addon-grid">
                                    @foreach($service->addons as $addon)
                                        <label class="wc-addon-card">
                                            <input
                                                type="checkbox"
                                                name="addon_ids[]"
                                                value="{{ $addon->id }}"
                                                {{ in_array((int) $addon->id, $selectedAddons, true) ? 'checked' : '' }}
                                            >
                                            <span class="wc-addon-card__box">
                                                <span class="wc-addon-card__top">
                                                    <strong>{{ $addon->name }}</strong>
                                                    <strong>{{ number_format($addon->price, 0, ',', ' ') }} kr</strong>
                                                </span>
                                                <small class="wc-note">Läggs till ovanpå grundpriset.</small>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p>Det finns inga aktiva tillägg för den här tjänsten just nu.</p>
                            @endif
                        </section>

                        <section class="wc-step" data-step="3">
                            <h2>Steg 3 — Datum och tid</h2>
                            <p class="wc-step__lead">Välj datum och därefter en ledig tid.</p>

                            <div class="wc-field-grid">
                                <div>
                                    <label class="wc-label" for="wc-booking-date">Datum</label>
                                    <input
                                        class="wc-input"
                                        id="wc-booking-date"
                                        type="date"
                                        name="booking_date"
                                        min="{{ now()->format('Y-m-d') }}"
                                        value="{{ old('booking_date') }}"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="wc-label" for="wc-selected-slot-display">Vald tid</label>
                                    <input
                                        class="wc-input"
                                        id="wc-selected-slot-display"
                                        type="text"
                                        value="{{ old('booking_date') && old('time_from') ? old('booking_date') . ' ' . old('time_from') : '' }}"
                                        readonly
                                        placeholder="Ingen tid vald ännu"
                                    >
                                </div>
                            </div>

                            <p class="wc-slot-status" id="wc-slot-status">Välj datum för att ladda lediga tider.</p>
                            <div class="wc-slot-grid" id="wc-slot-grid"></div>
                        </section>

                        <section class="wc-step" data-step="4">
                            <h2>Steg 4 — Kontaktuppgifter</h2>
                            <p class="wc-step__lead">Fyll i dina uppgifter så att vi kan bekräfta bokningen.</p>

                            <div class="wc-field-grid">
                                <div>
                                    <label class="wc-label" for="wc-customer-name">Namn</label>
                                    <input class="wc-input" id="wc-customer-name" type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                                </div>

                                <div>
                                    <label class="wc-label" for="wc-email">E-post</label>
                                    <input class="wc-input" id="wc-email" type="email" name="email" value="{{ old('email') }}" required>
                                </div>

                                <div>
                                    <label class="wc-label" for="wc-phone">Telefon</label>
                                    <input class="wc-input" id="wc-phone" type="text" name="phone" value="{{ old('phone') }}" required>
                                </div>

                                <div>
                                    <label class="wc-label" for="wc-personnummer">Personnummer</label>
                                    <input
                                        class="wc-input"
                                        id="wc-personnummer"
                                        type="text"
                                        name="personnummer"
                                        value="{{ old('personnummer') }}"
                                        placeholder="YYYYMMDDXXXX eller YYMMDDXXXX"
                                        required
                                    >
                                </div>

                                <div>
                                    <label class="wc-label" for="wc-address">Adress</label>
                                    <input class="wc-input" id="wc-address" type="text" name="address" value="{{ old('address') }}" required>
                                </div>

                                <div>
                                    <label class="wc-label" for="wc-postcode">Postnummer</label>
                                    <input class="wc-input" id="wc-postcode" type="text" name="postcode" value="{{ old('postcode') }}" required>
                                </div>

                                <div class="wc-field-full">
                                    <label class="wc-label" for="wc-message">Meddelande</label>
                                    <textarea class="wc-textarea" id="wc-message" name="message">{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </section>

                        <section class="wc-step" data-step="5">
                            <h2>Steg 5 — Bekräfta bokning</h2>
                            <p class="wc-step__lead">Kontrollera att allt stämmer innan du skickar bokningen.</p>

                            <div class="wc-confirm-grid">
                                <div class="wc-confirm-item">
                                    <span>Tjänst</span>
                                    <strong>{{ $service->name }}</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Antal fönster</span>
                                    <strong id="wc-confirm-window-count">-</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Typ av putsning</span>
                                    <strong id="wc-confirm-scope">-</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Datum</span>
                                    <strong id="wc-confirm-date">-</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Tid</span>
                                    <strong id="wc-confirm-time">-</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Tillägg</span>
                                    <strong id="wc-confirm-addons">Inga</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Kund</span>
                                    <strong id="wc-confirm-customer">-</strong>
                                </div>
                                <div class="wc-confirm-item">
                                    <span>Totalpris</span>
                                    <strong id="wc-confirm-total">0 kr</strong>
                                </div>
                            </div>

                            <p class="wc-note">
                                Genom att skicka bokningen godkänner du att vi kontaktar dig för bekräftelse och planering.
                            </p>
                        </section>

                        <div class="wc-actions">
                            <button type="button" class="btn btn-secondary" id="wc-prev" disabled>Tillbaka</button>

                            <div class="wc-actions__right">
                                <button type="button" class="btn btn-primary" id="wc-next">Nästa</button>
                                <button type="submit" class="btn btn-primary" id="wc-submit" hidden>Skicka bokning</button>
                            </div>
                        </div>
                    </form>
                </div>

                <aside class="wc-card wc-card--summary">
                    <h3>Sammanfattning</h3>

                    <div class="wc-summary-list">
                        <div class="wc-summary-row">
                            <span>Tjänst</span>
                            <strong>{{ $service->name }}</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Antal fönster</span>
                            <strong id="wc-summary-window-count">-</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Typ av putsning</span>
                            <strong id="wc-summary-scope">-</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Datum</span>
                            <strong id="wc-summary-date">-</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Tid</span>
                            <strong id="wc-summary-time">-</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Grundpris</span>
                            <strong id="wc-summary-base">0 kr</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Tillägg</span>
                            <strong id="wc-summary-addons-price">0 kr</strong>
                        </div>
                        <div class="wc-summary-row">
                            <span>Valda tillägg</span>
                            <strong id="wc-summary-addons-list">Inga</strong>
                        </div>
                    </div>

                    <div class="wc-summary-total">
                        <span>Totalt pris</span>
                        <strong id="wc-summary-total">0 kr</strong>
                    </div>

                    <p class="wc-summary-note">
                        Priset är baserat på nuvarande intervall och valda tillägg.
                    </p>
                </aside>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="wc-tips-grid">
                    <article class="wc-tip-card">
                        <h3>Förbered gärna innan besöket</h3>
                        <p>Se gärna till att vi kommer åt fönstren så smidigt som möjligt när uppdraget börjar.</p>
                    </article>
                    <article class="wc-tip-card">
                        <h3>Tydlig tidsbokning</h3>
                        <p>Du väljer en ledig tid direkt i flödet. Om en tid redan hunnit bokas uppdateras listan automatiskt.</p>
                    </article>
                    <article class="wc-tip-card">
                        <h3>Snabb återkoppling</h3>
                        <p>När du skickat in förfrågan får både du och admin ett mejl med sammanfattning av bokningen.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="wc-footer-inline">
            <div class="container">
                <div class="wc-footer-inline__box">
                    <div>
                        <strong>{{ $companyName }}</strong>
                        <p style="margin:8px 0 0;">Behöver du hjälp innan bokning? Hör gärna av dig så hjälper vi dig vidare.</p>
                    </div>

                    <div class="hero-actions" style="margin:0;">
                        <a href="tel:{{ $phonePrimaryHref }}" class="btn btn-secondary">{{ $phonePrimary }}</a>
                        <a href="{{ route('home') }}" class="btn btn-primary">Tillbaka till startsidan</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="brand footer-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ $companyName }}" class="brand-logo">
                    <span>
                        <strong>{{ $companyName }}</strong>
                        <small>Städning i Stockholm</small>
                    </span>
                </a>

                <p>Professionell städservice i Stockholm för privatpersoner och företag.</p>

                <div class="footer-contact-list">
                    <div><strong>Postadress:</strong> {{ $addressLine }}</div>
                    <div><strong>Org.nr:</strong> {{ $orgNumber }}</div>
                    <div><strong>Bankgiro:</strong> {{ $bankgiro }}</div>
                    <div><strong>Swish:</strong> {{ $swish }}</div>
                </div>

                <div class="footer-socials">
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.2-1.6 1.6-1.6H16.7V3.8c-.3 0-1.1-.1-2.1-.1-2.1 0-3.6 1.3-3.6 3.7V10H8v3h3V21h2.5Z" fill="currentColor"/>
                        </svg>
                    </a>

                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Zm0 2.2A1.8 1.8 0 0 0 5.2 7v10c0 1 .8 1.8 1.8 1.8h10c1 0 1.8-.8 1.8-1.8V7c0-1-.8-1.8-1.8-1.8H7Zm10.6 1.7a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8ZM12 7.2A4.8 4.8 0 1 1 7.2 12 4.8 4.8 0 0 1 12 7.2Zm0 2.2A2.6 2.6 0 1 0 14.6 12 2.6 2.6 0 0 0 12 9.4Z" fill="currentColor"/>
                        </svg>
                    </a>

                    <a href="{{ $recoUrl }}" target="_blank" rel="noopener noreferrer" class="footer-reco-link">Reco</a>
                </div>
            </div>

            <div>
                <h3>Privatpersoner</h3>
                <ul>
                    @foreach($privateMenu as $item)
                        <li><a href="{{ $item['href'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3>Företag</h3>
                <ul>
                    @foreach($companyMenu as $item)
                        <li><a href="{{ $item['href'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3>Övrigt</h3>
                <ul>
                    <li><a href="{{ route('home') }}#about">Om oss</a></li>
                    <li><a href="{{ route('home') }}#pricing">Pris</a></li>
                    <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                    <li><a href="{{ route('home') }}#contact">Kontakt</a></li>
                </ul>
            </div>
        </div>

        <div class="container footer-bottom">
            <small>© <span id="year"></span> {{ $companyName }}. Alla rättigheter förbehållna.</small>
        </div>
    </footer>

    <script>
        const yearEl = document.getElementById('year');
        if (yearEl) {
            yearEl.textContent = String(new Date().getFullYear());
        }

        window.windowCleaningConfig = {
            serviceSlug: @json($service->slug),
            priceRanges: @json($priceRanges),
            addons: @json($addonData),
            scopeLabels: @json($scopeLabels),
            scopeMultipliers: {
                inside: 1,
                outside: 1,
                both: 1.8
            },
            oldDate: @json(old('booking_date')),
            oldTimeFrom: @json(old('time_from')),
        };
    </script>

    <script>
        (function () {
            const config = window.windowCleaningConfig || {};
            const form = document.getElementById('window-cleaning-form');
            if (!form) return;

            const steps = [...document.querySelectorAll('.wc-step')];
            const indicators = [...document.querySelectorAll('[data-step-indicator]')];
            const prevBtn = document.getElementById('wc-prev');
            const nextBtn = document.getElementById('wc-next');
            const submitBtn = document.getElementById('wc-submit');

            const windowCountEl = document.getElementById('wc-window-count');
            const dateEl = document.getElementById('wc-booking-date');
            const bookingSlotIdEl = document.getElementById('wc-booking-slot-id');
            const timeFromEl = document.getElementById('wc-time-from');
            const selectedSlotDisplayEl = document.getElementById('wc-selected-slot-display');
            const slotGridEl = document.getElementById('wc-slot-grid');
            const slotStatusEl = document.getElementById('wc-slot-status');
            const personnummerEl = document.getElementById('wc-personnummer');

            const summaryWindowCount = document.getElementById('wc-summary-window-count');
            const summaryScope = document.getElementById('wc-summary-scope');
            const summaryDate = document.getElementById('wc-summary-date');
            const summaryTime = document.getElementById('wc-summary-time');
            const summaryBase = document.getElementById('wc-summary-base');
            const summaryAddonsPrice = document.getElementById('wc-summary-addons-price');
            const summaryAddonsList = document.getElementById('wc-summary-addons-list');
            const summaryTotal = document.getElementById('wc-summary-total');

            const confirmWindowCount = document.getElementById('wc-confirm-window-count');
            const confirmScope = document.getElementById('wc-confirm-scope');
            const confirmDate = document.getElementById('wc-confirm-date');
            const confirmTime = document.getElementById('wc-confirm-time');
            const confirmAddons = document.getElementById('wc-confirm-addons');
            const confirmCustomer = document.getElementById('wc-confirm-customer');
            const confirmTotal = document.getElementById('wc-confirm-total');

            let currentStep = 1;
            let selectedTimeTo = '';

            function getSelectedScope() {
                return form.querySelector('input[name="cleaning_scope"]:checked')?.value || '';
            }

            function getScopeLabel() {
                const scope = getSelectedScope();
                return config.scopeLabels?.[scope] || '-';
            }

            function getWindowCount() {
                const value = Number(windowCountEl?.value || 0);
                return value > 0 ? value : 0;
            }

            function getSelectedAddonInputs() {
                return [...form.querySelectorAll('input[name="addon_ids[]"]:checked')];
            }

            function getSelectedAddons() {
                const addonMap = new Map((config.addons || []).map((addon) => [String(addon.id), addon]));

                return getSelectedAddonInputs()
                    .map((input) => addonMap.get(String(input.value)))
                    .filter(Boolean);
            }

            function resolveBasePrice(windowCount) {
                const ranges = config.priceRanges || [];
                if (!windowCount || !ranges.length) return 0;

                for (const range of ranges) {
                    if (windowCount >= Number(range.min) && windowCount <= Number(range.max)) {
                        return Number(range.price || 0);
                    }
                }

                const lastRange = ranges[ranges.length - 1];
                if (lastRange && windowCount > Number(lastRange.max)) {
                    return Number(lastRange.price || 0);
                }

                return 0;
            }

            function getComputedPrices() {
                const count = getWindowCount();
                const base = resolveBasePrice(count);
                const scopeMultiplier = Number(config.scopeMultipliers?.[getSelectedScope()] || 1);
                const scopedBase = Math.round(base * scopeMultiplier);
                const addonsTotal = getSelectedAddons().reduce((sum, addon) => sum + Number(addon.price || 0), 0);

                return {
                    base,
                    scopedBase,
                    addonsTotal,
                    total: scopedBase + addonsTotal,
                };
            }

            function getSelectedTimeLabel() {
                if (!dateEl?.value || !timeFromEl?.value) {
                    return '-';
                }

                if (selectedTimeTo) {
                    return `${timeFromEl.value} - ${selectedTimeTo}`;
                }

                return timeFromEl.value;
            }

            function updateSummary() {
                const count = getWindowCount();
                const scopeLabel = getScopeLabel();
                const addons = getSelectedAddons();
                const prices = getComputedPrices();
                const customerName = form.querySelector('input[name="customer_name"]')?.value?.trim() || '-';

                const addonsNames = addons.length ? addons.map((addon) => addon.name).join(', ') : 'Inga';

                summaryWindowCount.textContent = count ? `${count}` : '-';
                summaryScope.textContent = scopeLabel;
                summaryDate.textContent = dateEl?.value || '-';
                summaryTime.textContent = getSelectedTimeLabel();
                summaryBase.textContent = `${prices.scopedBase} kr`;
                summaryAddonsPrice.textContent = `${prices.addonsTotal} kr`;
                summaryAddonsList.textContent = addonsNames;
                summaryTotal.textContent = `${prices.total} kr`;

                confirmWindowCount.textContent = count ? `${count}` : '-';
                confirmScope.textContent = scopeLabel;
                confirmDate.textContent = dateEl?.value || '-';
                confirmTime.textContent = getSelectedTimeLabel();
                confirmAddons.textContent = addonsNames;
                confirmCustomer.textContent = customerName;
                confirmTotal.textContent = `${prices.total} kr`;
            }

            function showStep(stepNumber) {
                currentStep = stepNumber;

                steps.forEach((step) => {
                    step.classList.toggle('is-active', Number(step.dataset.step) === stepNumber);
                });

                indicators.forEach((item) => {
                    item.classList.toggle('is-active', Number(item.dataset.stepIndicator) === stepNumber);
                });

                prevBtn.disabled = stepNumber === 1;
                nextBtn.hidden = stepNumber === steps.length;
                submitBtn.hidden = stepNumber !== steps.length;

                updateSummary();
            }

            function normalizePersonnummer(value = '') {
                return String(value).replace(/[^\d-]/g, '');
            }

            function validatePersonnummer() {
                if (!personnummerEl) return true;

                const value = normalizePersonnummer(personnummerEl.value);
                personnummerEl.value = value;
                personnummerEl.setCustomValidity('');

                if (!value) {
                    return true;
                }

                const isValid = /^\d{6,8}-?\d{4}$/.test(value);

                if (!isValid) {
                    personnummerEl.setCustomValidity('Ange ett giltigt personnummer i format YYYYMMDDXXXX eller YYMMDDXXXX.');
                    personnummerEl.reportValidity();
                    return false;
                }

                return true;
            }

            function getVisibleRequiredFields(stepNumber) {
                const step = steps.find((item) => Number(item.dataset.step) === stepNumber);
                if (!step) return [];

                return [...step.querySelectorAll('input, textarea, select')]
                    .filter((field) => !field.disabled);
            }

            function validateStep(stepNumber) {
                if (stepNumber === 3) {
                    if (!dateEl.value) {
                        dateEl.reportValidity();
                        return false;
                    }

                    if (!bookingSlotIdEl?.value) {
                        slotStatusEl.textContent = 'Välj en ledig tid innan du går vidare.';
                        slotStatusEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                }

                if (stepNumber === 4 && !validatePersonnummer()) {
                    return false;
                }

                const fields = getVisibleRequiredFields(stepNumber);

                for (const field of fields) {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        return false;
                    }
                }

                return true;
            }

            async function loadSlots(date, preselectTime = '') {
                if (!date) {
                    slotGridEl.innerHTML = '';
                    slotStatusEl.textContent = 'Välj datum för att ladda lediga tider.';
                    if (bookingSlotIdEl) bookingSlotIdEl.value = '';
                    timeFromEl.value = '';
                    selectedTimeTo = '';
                    selectedSlotDisplayEl.value = '';
                    updateSummary();
                    return;
                }

                slotGridEl.innerHTML = '';
                slotStatusEl.textContent = 'Hämtar lediga tider...';
                if (bookingSlotIdEl) bookingSlotIdEl.value = '';
                timeFromEl.value = '';
                selectedTimeTo = '';
                selectedSlotDisplayEl.value = '';
                updateSummary();

                try {
                    const response = await fetch(
                        `{{ route('booking-slots.available') }}?service=${encodeURIComponent(config.serviceSlug)}&date=${encodeURIComponent(date)}`,
                        {
                            headers: {
                                Accept: 'application/json',
                            },
                            credentials: 'same-origin',
                        }
                    );

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok || !data.ok) {
                        throw new Error(data.message || 'Kunde inte hämta lediga tider.');
                    }

                    const slots = data.slots || [];

                    if (!slots.length) {
                        slotStatusEl.textContent = 'Inga lediga tider för vald dag.';
                        return;
                    }

                    slotStatusEl.textContent = 'Välj en ledig tid:';

                    slots.forEach((slot) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'btn btn-secondary wc-slot-button';
                        button.textContent = slot.label;

                        const selectSlot = () => {
                            if (bookingSlotIdEl) bookingSlotIdEl.value = slot.id || '';
                            timeFromEl.value = slot.time_from || '';
                            selectedTimeTo = slot.time_to || '';
                            selectedSlotDisplayEl.value = `${slot.date} ${slot.label}`;

                            slotGridEl.querySelectorAll('button').forEach((btn) => {
                                btn.classList.remove('btn-primary', 'is-selected');
                                btn.classList.add('btn-secondary');
                            });

                            button.classList.remove('btn-secondary');
                            button.classList.add('btn-primary', 'is-selected');

                            slotStatusEl.textContent = `Vald tid: ${slot.label}`;
                            updateSummary();
                        };

                        button.addEventListener('click', selectSlot);
                        slotGridEl.appendChild(button);

                        if (preselectTime && slot.time_from === preselectTime) {
                            selectSlot();
                        }
                    });
                } catch (error) {
                    slotStatusEl.textContent = error.message || 'Kunde inte hämta lediga tider.';
                }
            }

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (!validateStep(currentStep)) return;
                showStep(Math.min(currentStep + 1, steps.length));
            });

            form.querySelectorAll('input, textarea').forEach((field) => {
                field.addEventListener('input', updateSummary);
                field.addEventListener('change', updateSummary);
            });

            dateEl.addEventListener('change', () => {
                loadSlots(dateEl.value);
            });

            personnummerEl?.addEventListener('blur', validatePersonnummer);

            form.addEventListener('submit', (event) => {
                const finalStepsValid = [1, 3, 4].every((stepNumber) => validateStep(stepNumber));

                if (!finalStepsValid) {
                    event.preventDefault();

                    if (!validateStep(1)) {
                        showStep(1);
                        return;
                    }

                    if (!validateStep(3)) {
                        showStep(3);
                        return;
                    }

                    showStep(4);
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Skickar bokning...';
            });

            updateSummary();
            showStep(@json($errors->any() ? 4 : 1));

            if (config.oldDate) {
                loadSlots(config.oldDate, config.oldTimeFrom || '');
            }
        })();
    </script>
</body>
</html>