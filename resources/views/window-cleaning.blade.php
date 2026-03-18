@php
    $companyName = optional($siteSettings)->company_name ?: 'CleanS AB';
    $phonePrimary = optional($siteSettings)->phone_primary ?: '070 741 37 72';
    $email = optional($siteSettings)->email ?: 'info@cleansource.se';
    $phonePrimaryHref = preg_replace('/[^\d+]/', '', $phonePrimary);
@endphp

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $service->name }} | {{ $companyName }}</title>
    <meta name="description" content="Boka fönsterputsning online hos {{ $companyName }}.">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">

    <style>
        .wc-page { background:#f5f8fc; min-height:100vh; }
        .wc-header { background:#fff; border-bottom:1px solid #e5e7eb; position:sticky; top:0; z-index:30; }
        .wc-header-inner { max-width:1200px; margin:0 auto; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
        .wc-brand { text-decoration:none; color:#0f172a; font-weight:800; font-size:20px; }
        .wc-nav { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
        .wc-shell { max-width:1200px; margin:0 auto; padding:28px 20px 60px; }
        .wc-hero { background:linear-gradient(135deg, #0891b2 0%, #38bdf8 100%); color:#fff; border-radius:28px; padding:32px; display:grid; grid-template-columns:1.25fr 0.75fr; gap:24px; margin-bottom:24px; }
        .wc-hero h1 { margin:0 0 12px; font-size:42px; line-height:1.1; }
        .wc-hero p { margin:0; font-size:18px; line-height:1.7; }
        .wc-hero-card { background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.28); border-radius:22px; padding:22px; }
        .wc-layout { display:grid; grid-template-columns:minmax(0, 1.2fr) 380px; gap:24px; align-items:start; }
        .wc-card { background:#fff; border:1px solid #e2e8f0; border-radius:24px; padding:24px; box-shadow:0 20px 40px rgba(15,23,42,.05); }
        .wc-card + .wc-card { margin-top:20px; }
        .wc-steps { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px; }
        .wc-step-chip { min-width:40px; border-radius:999px; padding:10px 14px; font-weight:700; border:1px solid #cbd5e1; background:#f8fafc; color:#334155; }
        .wc-step-chip.active { background:#0ea5e9; color:#fff; border-color:#0ea5e9; }
        .wc-step-panel { display:none; }
        .wc-step-panel.active { display:block; }
        .wc-title { font-size:28px; margin:0 0 8px; }
        .wc-muted { color:#64748b; }
        .wc-grid { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:16px; }
        .wc-field { display:flex; flex-direction:column; gap:8px; }
        .wc-field.full { grid-column:1 / -1; }
        .wc-field label, .wc-label { font-weight:700; color:#0f172a; }
        .wc-input, .wc-textarea { width:100%; box-sizing:border-box; padding:14px 16px; border-radius:16px; border:1px solid #dbe3ee; background:#fff; font-size:16px; }
        .wc-textarea { min-height:120px; resize:vertical; }
        .wc-choice-grid, .wc-addon-grid, .wc-slot-grid { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:14px; }
        .wc-choice, .wc-addon, .wc-slot { position:relative; }
        .wc-choice input, .wc-addon input, .wc-slot input { position:absolute; opacity:0; pointer-events:none; }
        .wc-choice span, .wc-addon span, .wc-slot span { display:block; border:2px solid #dbe3ee; border-radius:18px; padding:18px 16px; text-align:center; font-weight:700; background:#fff; transition:.2s ease; cursor:pointer; }
        .wc-choice input:checked + span, .wc-addon input:checked + span, .wc-slot input:checked + span { border-color:#0ea5e9; background:#eff8ff; color:#0c4a6e; }
        .wc-slot.disabled span { opacity:.45; cursor:not-allowed; background:#f8fafc; }
        .wc-actions { display:flex; gap:12px; justify-content:space-between; margin-top:24px; }
        .wc-actions .btn { min-width:140px; }
        .wc-summary-row { display:flex; justify-content:space-between; gap:16px; padding:12px 0; border-bottom:1px solid #eef2f7; }
        .wc-summary-row:last-of-type { border-bottom:0; }
        .wc-total { margin-top:18px; padding:18px; background:#eff8ff; color:#0c4a6e; border-radius:20px; }
        .wc-total strong { display:block; font-size:34px; margin-top:6px; }
        .wc-alert { padding:16px 18px; border-radius:16px; margin-bottom:18px; }
        .wc-alert-success { background:#ecfdf5; border:1px solid #86efac; color:#166534; }
        .wc-alert-error { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; }
        .wc-small { font-size:14px; }
        .wc-confirm-box { background:#f8fafc; border:1px dashed #cbd5e1; border-radius:18px; padding:18px; }
        @media (max-width: 980px) { .wc-hero, .wc-layout { grid-template-columns:1fr; } }
        @media (max-width: 720px) { .wc-grid, .wc-choice-grid, .wc-addon-grid, .wc-slot-grid { grid-template-columns:1fr; } .wc-hero h1 { font-size:34px; } }
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
            <h1>Boka fönsterputsning online</h1>
            <p>
                Välj antal fönster, tillägg, datum och tid. Systemet räknar priset direkt,
                blockerar upptagna tider och skickar bekräftelse via e-post.
            </p>
        </div>
        <aside class="wc-hero-card">
            <ul>
                <li>Separat bokningsflöde för fönsterputs</li>
                <li>Automatisk prisräkning från databasen</li>
                <li>Blockering av upptagna tider</li>
                <li>E-post till kund och admin</li>
            </ul>
        </aside>
    </section>

    @if(session('success'))
        <div class="wc-alert wc-alert-success">{{ session('success') }}</div>
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

    <form method="POST" action="{{ route('window-cleaning.store') }}" id="window-cleaning-form">
        @csrf

        <div class="wc-layout">
            <div class="wc-card">
                <div class="wc-steps">
                    <div class="wc-step-chip active" data-step-chip="1">1. Fönster</div>
                    <div class="wc-step-chip" data-step-chip="2">2. Tillägg</div>
                    <div class="wc-step-chip" data-step-chip="3">3. Datum & tid</div>
                    <div class="wc-step-chip" data-step-chip="4">4. Kontakt</div>
                    <div class="wc-step-chip" data-step-chip="5">5. Bekräfta</div>
                </div>

                <section class="wc-step-panel active" data-step-panel="1">
                    <h2 class="wc-title">Steg 1 — Fönster</h2>
                    <p class="wc-muted">Ange antal fönster och vilken typ av putsning du vill boka.</p>

                    <div class="wc-grid" style="margin-top:18px;">
                        <div class="wc-field">
                            <label for="window_count">Antal fönster</label>
                            <input id="window_count" class="wc-input" type="number" name="window_count" min="1" max="100" value="{{ old('window_count', 6) }}" required>
                        </div>
                    </div>

                    <div style="margin-top:22px;">
                        <div class="wc-label" style="margin-bottom:10px;">Typ av putsning</div>
                        <div class="wc-choice-grid">
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
                </section>

                <section class="wc-step-panel" data-step-panel="2">
                    <h2 class="wc-title">Steg 2 — Tillägg</h2>
                    <p class="wc-muted">Välj eventuella tilläggstjänster.</p>

                    <div class="wc-addon-grid" style="margin-top:18px;">
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
                </section>

                <section class="wc-step-panel" data-step-panel="3">
                    <h2 class="wc-title">Steg 3 — Datum och tid</h2>
                    <p class="wc-muted">Lediga tider visas automatiskt för valt datum.</p>

                    <div class="wc-grid" style="margin-top:18px;">
                        <div class="wc-field">
                            <label for="booking_date">Datum</label>
                            <input id="booking_date" class="wc-input" type="date" name="booking_date" min="{{ now()->format('Y-m-d') }}" value="{{ old('booking_date') }}" required>
                        </div>

                        <div class="wc-field">
                            <label>Vald tid</label>
                            <input id="selected_time_display" class="wc-input" type="text" value="{{ old('time_from') }}" readonly placeholder="Ingen tid vald än">
                            <input id="time_from" type="hidden" name="time_from" value="{{ old('time_from') }}" required>
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <div class="wc-label" style="margin-bottom:10px;">Lediga tider</div>
                        <div id="slot-grid" class="wc-slot-grid"></div>
                        <p id="slot-help" class="wc-muted wc-small" style="margin-top:12px;">Välj datum för att ladda lediga tider.</p>
                    </div>
                </section>

                <section class="wc-step-panel" data-step-panel="4">
                    <h2 class="wc-title">Steg 4 — Kontaktuppgifter</h2>
                    <p class="wc-muted">Fyll i dina uppgifter så att vi kan bekräfta bokningen.</p>

                    <div class="wc-grid" style="margin-top:18px;">
                        <div class="wc-field">
                            <label for="customer_name">Namn</label>
                            <input id="customer_name" class="wc-input" type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                        </div>

                        <div class="wc-field">
                            <label for="email">E-post</label>
                            <input id="email" class="wc-input" type="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="wc-field">
                            <label for="phone">Telefon</label>
                            <input id="phone" class="wc-input" type="text" name="phone" value="{{ old('phone') }}" required>
                        </div>

                        <div class="wc-field">
                            <label for="personnummer">Personnummer</label>
                            <input id="personnummer" class="wc-input" type="text" name="personnummer" value="{{ old('personnummer') }}" placeholder="YYYYMMDDXXXX" required>
                        </div>

                        <div class="wc-field full">
                            <label for="address">Adress</label>
                            <input id="address" class="wc-input" type="text" name="address" value="{{ old('address') }}" placeholder="Gatuadress" required>
                        </div>

                        <div class="wc-field">
                            <label for="postcode">Postnummer</label>
                            <input id="postcode" class="wc-input" type="text" name="postcode" value="{{ old('postcode') }}" placeholder="12345" required>
                        </div>

                        <div class="wc-field full">
                            <label for="message">Meddelande</label>
                            <textarea id="message" class="wc-textarea" name="message" placeholder="Portkod, våning, övrigt">{{ old('message') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="wc-step-panel" data-step-panel="5">
                    <h2 class="wc-title">Steg 5 — Bekräfta bokning</h2>
                    <p class="wc-muted">Kontrollera att allt stämmer innan du skickar bokningen.</p>

                    <div class="wc-confirm-box" style="margin-top:18px;">
                        <div class="wc-summary-row"><span>Tjänst</span><strong>{{ $service->name }}</strong></div>
                        <div class="wc-summary-row"><span>Antal fönster</span><strong id="confirm-window-count">-</strong></div>
                        <div class="wc-summary-row"><span>Typ av putsning</span><strong id="confirm-scope">-</strong></div>
                        <div class="wc-summary-row"><span>Datum</span><strong id="confirm-date">-</strong></div>
                        <div class="wc-summary-row"><span>Tid</span><strong id="confirm-time">-</strong></div>
                        <div class="wc-summary-row"><span>Tillägg</span><strong id="confirm-addons">Inga</strong></div>
                        <div class="wc-summary-row"><span>Kund</span><strong id="confirm-customer">-</strong></div>
                    </div>
                </section>

                <div class="wc-actions">
                    <button type="button" class="btn btn-secondary" id="prev-step" style="visibility:hidden;">Tillbaka</button>
                    <button type="button" class="btn btn-primary" id="next-step">Nästa</button>
                    <button type="submit" class="btn btn-primary" id="submit-booking" style="display:none;">Skicka bokning</button>
                </div>
            </div>

            <aside class="wc-card">
                <h3>Sammanfattning</h3>
                <div class="wc-summary-row"><span>Tjänst</span><strong>{{ $service->name }}</strong></div>
                <div class="wc-summary-row"><span>Antal fönster</span><strong id="summary-window-count">-</strong></div>
                <div class="wc-summary-row"><span>Typ av putsning</span><strong id="summary-scope">-</strong></div>
                <div class="wc-summary-row"><span>Datum</span><strong id="summary-date">-</strong></div>
                <div class="wc-summary-row"><span>Tid</span><strong id="summary-time">-</strong></div>
                <div class="wc-summary-row"><span>Grundpris</span><strong id="summary-base-price">0 kr</strong></div>
                <div class="wc-summary-row"><span>Tillägg</span><strong id="summary-addons-price">0 kr</strong></div>
                <div class="wc-summary-row"><span>Valda tillägg</span><strong id="summary-addons-list">Inga</strong></div>
                <div class="wc-total">
                    Totalt pris
                    <strong id="summary-total-price">0 kr</strong>
                    <div class="wc-small">Pris efter dina nuvarande intervall.</div>
                </div>
            </aside>
        </div>
    </form>
</main>

<script>
    const calculatorData = @json($calculatorData);
    const serviceName = @json($service->name);
    const availableSlotsUrl = @json(route('booking-slots.available'));

    const stepChips = [...document.querySelectorAll('[data-step-chip]')];
    const stepPanels = [...document.querySelectorAll('[data-step-panel]')];
    const prevStepBtn = document.getElementById('prev-step');
    const nextStepBtn = document.getElementById('next-step');
    const submitBtn = document.getElementById('submit-booking');

    const form = document.getElementById('window-cleaning-form');
    const windowCountInput = document.getElementById('window_count');
    const bookingDateInput = document.getElementById('booking_date');
    const timeFromInput = document.getElementById('time_from');
    const selectedTimeDisplay = document.getElementById('selected_time_display');
    const slotGrid = document.getElementById('slot-grid');
    const slotHelp = document.getElementById('slot-help');
    const scopeInputs = [...document.querySelectorAll('input[name="cleaning_scope"]')];
    const addonInputs = [...document.querySelectorAll('input[name="addon_ids[]"]')];

    const summaryWindowCount = document.getElementById('summary-window-count');
    const summaryScope = document.getElementById('summary-scope');
    const summaryDate = document.getElementById('summary-date');
    const summaryTime = document.getElementById('summary-time');
    const summaryBasePrice = document.getElementById('summary-base-price');
    const summaryAddonsPrice = document.getElementById('summary-addons-price');
    const summaryAddonsList = document.getElementById('summary-addons-list');
    const summaryTotalPrice = document.getElementById('summary-total-price');

    const confirmWindowCount = document.getElementById('confirm-window-count');
    const confirmScope = document.getElementById('confirm-scope');
    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmAddons = document.getElementById('confirm-addons');
    const confirmCustomer = document.getElementById('confirm-customer');

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

    let currentStep = {{ $errors->any() ? 4 : 1 }};

    function showStep(step) {
        currentStep = step;

        stepChips.forEach((chip, index) => chip.classList.toggle('active', index + 1 === step));
        stepPanels.forEach((panel) => panel.classList.toggle('active', Number(panel.dataset.stepPanel) === step));

        prevStepBtn.style.visibility = step === 1 ? 'hidden' : 'visible';
        nextStepBtn.style.display = step === 5 ? 'none' : 'inline-flex';
        submitBtn.style.display = step === 5 ? 'inline-flex' : 'none';

        if (step === 5) {
            fillConfirmStep();
        }
    }

    function parseRanges(rangeString) {
        if (!rangeString) return [];

        return rangeString.split('|').map(item => {
            const [rangePart, pricePart] = item.split(':').map(s => s.trim());
            if (!rangePart || !pricePart) return null;
            const [min, max] = rangePart.split('-').map(Number);
            return { min, max, price: Number(pricePart) };
        }).filter(Boolean);
    }

    function resolveBasePrice(windowCount, serviceConfig) {
        if (!serviceConfig || !serviceConfig.ranges) return 0;
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
        const selected = scopeInputs.find(input => input.checked);
        return selected ? selected.value : 'inside';
    }

    function getSelectedAddons() {
        return addonInputs
            .filter(input => input.checked)
            .map(input => ({
                id: input.value,
                name: input.dataset.addonName,
                price: Number(input.dataset.addonPrice || 0),
            }));
    }

    function updateSummary() {
        const serviceConfig = calculatorData.services[serviceName] || null;
        const windowCount = Number(windowCountInput.value || 0);
        const scope = getSelectedScope();
        const multiplier = scopeMultipliers[scope] || 1;
        const basePrice = Math.round(resolveBasePrice(windowCount, serviceConfig) * multiplier);
        const addons = getSelectedAddons();
        const addonsTotal = addons.reduce((sum, addon) => sum + addon.price, 0);
        const total = basePrice + addonsTotal;

        summaryWindowCount.textContent = windowCount ? `${windowCount} st` : '-';
        summaryScope.textContent = scopeLabels[scope] || '-';
        summaryDate.textContent = bookingDateInput.value || '-';
        summaryTime.textContent = timeFromInput.value || '-';
        summaryBasePrice.textContent = formatPrice(basePrice);
        summaryAddonsPrice.textContent = formatPrice(addonsTotal);
        summaryAddonsList.textContent = addons.length ? addons.map(addon => addon.name).join(', ') : 'Inga';
        summaryTotalPrice.textContent = formatPrice(total);
    }

    function fillConfirmStep() {
        confirmWindowCount.textContent = summaryWindowCount.textContent;
        confirmScope.textContent = summaryScope.textContent;
        confirmDate.textContent = summaryDate.textContent;
        confirmTime.textContent = summaryTime.textContent;
        confirmAddons.textContent = summaryAddonsList.textContent;
        confirmCustomer.textContent = document.getElementById('customer_name').value || '-';
    }

    function validateStep(step) {
        if (step === 1) {
            if (!windowCountInput.value || Number(windowCountInput.value) < 1) {
                alert('Ange antal fönster.');
                return false;
            }
            if (!getSelectedScope()) {
                alert('Välj typ av putsning.');
                return false;
            }
        }

        if (step === 3) {
            if (!bookingDateInput.value) {
                alert('Välj datum.');
                return false;
            }
            if (!timeFromInput.value) {
                alert('Välj ledig tid.');
                return false;
            }
        }

        if (step === 4) {
            const requiredIds = ['customer_name', 'email', 'phone', 'personnummer', 'address', 'postcode'];
            const isMissing = requiredIds.some(id => !document.getElementById(id).value.trim());
            if (isMissing) {
                alert('Fyll i alla obligatoriska kontaktuppgifter.');
                return false;
            }
        }

        return true;
    }

    async function loadSlots() {
        const date = bookingDateInput.value;
        slotGrid.innerHTML = '';
        timeFromInput.value = '';
        selectedTimeDisplay.value = '';
        updateSummary();

        if (!date) {
            slotHelp.textContent = 'Välj datum för att ladda lediga tider.';
            return;
        }

        slotHelp.textContent = 'Laddar lediga tider...';

        try {
            const response = await fetch(`${availableSlotsUrl}?date=${encodeURIComponent(date)}&duration=120`, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                throw new Error('Failed to load slots');
            }

            const data = await response.json();
            const slots = data.slots || [];

            if (!slots.length) {
                slotHelp.textContent = 'Inga tider hittades för valt datum.';
                return;
            }

            slots.forEach(slot => {
                const wrapper = document.createElement('label');
                wrapper.className = `wc-slot ${slot.available ? '' : 'disabled'}`;

                const input = document.createElement('input');
                input.type = 'radio';
                input.name = 'visual_slot';
                input.value = slot.time;
                input.disabled = !slot.available;
                if (timeFromInput.value === slot.time) {
                    input.checked = true;
                }

                input.addEventListener('change', () => {
                    timeFromInput.value = slot.time;
                    selectedTimeDisplay.value = `${slot.time}–${slot.end_time}`;
                    updateSummary();
                });

                const span = document.createElement('span');
                span.innerHTML = `${slot.time}–${slot.end_time}<br><small>${slot.available ? 'Ledig' : 'Upptagen'}</small>`;

                wrapper.appendChild(input);
                wrapper.appendChild(span);
                slotGrid.appendChild(wrapper);
            });

            slotHelp.textContent = 'Välj en ledig tid.';
        } catch (error) {
            slotHelp.textContent = 'Kunde inte ladda lediga tider just nu.';
        }
    }

    windowCountInput.addEventListener('input', updateSummary);
    scopeInputs.forEach(input => input.addEventListener('change', updateSummary));
    addonInputs.forEach(input => input.addEventListener('change', updateSummary));
    bookingDateInput.addEventListener('change', loadSlots);

    prevStepBtn.addEventListener('click', () => {
        if (currentStep > 1) showStep(currentStep - 1);
    });

    nextStepBtn.addEventListener('click', async () => {
        if (!validateStep(currentStep)) return;
        if (currentStep < 5) showStep(currentStep + 1);
    });

    form.addEventListener('submit', (event) => {
        if (!validateStep(5)) {
            event.preventDefault();
        }
    });

    showStep(currentStep);
    updateSummary();

    if (bookingDateInput.value) {
        loadSlots().then(() => {
            if (timeFromInput.value) {
                selectedTimeDisplay.value = timeFromInput.value;
                updateSummary();
            }
        });
    }
</script>
</body>
</html>