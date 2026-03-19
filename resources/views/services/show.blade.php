<!DOCTYPE html>
<html lang="sv">
<head>
    @php
        $companyName = $siteSettings->company_name ?? 'Clean Source AB';
        $phonePrimary = $siteSettings->phone_primary ?? '+46707413772';
        $email = $siteSettings->email ?? 'info@cleansource.se';
        $addressLine = trim(implode(', ', array_filter([
            $siteSettings->address ?? 'Ångermannagatan 121',
            trim(implode(' ', array_filter([
                $siteSettings->postal_code ?? '162 64',
                $siteSettings->city ?? 'Vällingby',
            ]))),
        ])));

        $groupLabel = ($page['group'] ?? null) === 'company' ? 'Företag' : 'Städning för hemmet';
        $ctaPrimaryHref = route('home') . '#contact';
        $ctaPrimaryText = 'Begär offert';

        if (($page['group'] ?? null) === 'private' && str_contains(mb_strtolower($page['title']), 'fönsterputsning')) {
            $ctaPrimaryHref = route('window-cleaning');
            $ctaPrimaryText = 'Boka fönsterputsning';
        }

        $relatedLinks = $menuGroups[$page['group']] ?? [];
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page['title'] }} | {{ $companyName }}</title>
    <meta name="description" content="{{ $page['lead'] }}">

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">
</head>
<body>
    <div class="site-topbar">
        <div class="container site-topbar__inner">
            <div class="site-topbar__left">
                <a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}">{{ $phonePrimary }}</a>
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </div>

            <div class="site-topbar__right">
                <a href="{{ route('home') }}#faq">FAQ</a>
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

            <nav class="site-nav service-top-nav" aria-label="Huvudmeny">
                <a href="{{ route('home') }}">Start</a>
                <a href="{{ route('home') }}#services">Tjänster</a>
                <a href="{{ route('home') }}#pricing">Pris</a>
                <a href="{{ route('home') }}#contact" class="btn btn-sm btn-primary nav-cta">Begär offert</a>
            </nav>
        </div>
    </header>

    <main id="main">
        <section class="service-hero section">
            <div class="container">
                <div class="service-breadcrumbs">
                    <a href="{{ route('home') }}">Start</a>
                    <span>/</span>
                    <span>{{ $groupLabel }}</span>
                    <span>/</span>
                    <span>{{ $page['title'] }}</span>
                </div>

                <div class="service-hero-grid">
                    <div class="service-hero-copy">
                        <span class="eyebrow">{{ $page['eyebrow'] }}</span>
                        <h1>{{ $page['title'] }}</h1>
                        <p class="lead">{{ $page['lead'] }}</p>

                        <div class="service-hero-actions">
                            <a href="{{ $ctaPrimaryHref }}" class="btn btn-primary">{{ $ctaPrimaryText }}</a>
                            <a href="{{ route('home') }}#contact" class="btn btn-secondary">Kontakta oss</a>
                        </div>

                        <div class="service-chip-row">
                            <span>{{ ($page['group'] ?? null) === 'company' ? 'För företag' : 'För privatpersoner' }}</span>
                            <span>Stockholm</span>
                            <span>Snabb återkoppling</span>
                        </div>
                    </div>

                    <aside class="service-hero-card">
                        <h3>Snabb överblick</h3>

                        <div class="service-summary-list">
                            <div class="service-summary-row">
                                <span>Tjänst</span>
                                <strong>{{ $page['title'] }}</strong>
                            </div>

                            <div class="service-summary-row">
                                <span>Målgrupp</span>
                                <strong>{{ ($page['group'] ?? null) === 'company' ? 'Företag' : 'Privatpersoner' }}</strong>
                            </div>

                            <div class="service-summary-row">
                                <span>Område</span>
                                <strong>Stockholm</strong>
                            </div>

                            <div class="service-summary-row">
                                <span>Telefon</span>
                                <strong>{{ $phonePrimary }}</strong>
                            </div>

                            <div class="service-summary-row">
                                <span>E-post</span>
                                <strong>{{ $email }}</strong>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container service-content-grid">
                <div class="service-main-column">
                    <div class="service-content-card">
                        <h2>Om tjänsten</h2>

                        @foreach($page['intro'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <div class="service-content-card">
                        <h2>{{ $page['included_title'] }}</h2>
                        <ul class="check-list">
                            @foreach($page['included'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="service-process-card">
                        <span class="eyebrow">Så går det till</span>
                        <h2>En enkel och tydlig process</h2>

                        <div class="service-process-grid">
                            <article>
                                <strong>1. Kontakt</strong>
                                <p>Du hör av dig till oss via formulär eller telefon och berättar vad du behöver hjälp med.</p>
                            </article>

                            <article>
                                <strong>2. Upplägg</strong>
                                <p>Vi föreslår ett upplägg som passar din bostad, lokal eller verksamhet och går igenom önskemål.</p>
                            </article>

                            <article>
                                <strong>3. Utförande</strong>
                                <p>Vi planerar tjänsten och genomför uppdraget med fokus på kvalitet, tydlighet och trygg kommunikation.</p>
                            </article>
                        </div>
                    </div>
                </div>

                <aside class="service-side-column">
                    <div class="service-side-card">
                        <h3>Därför väljer kunder denna tjänst</h3>
                        <ul class="check-list">
                            @foreach($page['highlights'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="service-side-card">
                        <h3>Kontakt</h3>

                        <div class="service-sidebar-meta">
                            <strong>Telefon</strong>
                            <p><a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}">{{ $phonePrimary }}</a></p>

                            <strong>E-post</strong>
                            <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>

                            <strong>Adress</strong>
                            <p>{{ $addressLine }}</p>
                        </div>

                        <div class="service-side-actions">
                            <a href="{{ $ctaPrimaryHref }}" class="btn btn-primary full">{{ $ctaPrimaryText }}</a>
                            <a href="{{ route('home') }}#contact" class="btn btn-secondary full">Skicka förfrågan</a>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section section-soft">
            <div class="container service-bottom-grid">
                <div class="quote-box">
                    <span class="eyebrow">Varför denna tjänst?</span>
                    <h2>{{ $page['why_title'] }}</h2>
                    <p>{{ $page['why_text'] }}</p>

                    <div class="hero-actions">
                        <a href="{{ $ctaPrimaryHref }}" class="btn btn-primary">{{ $ctaPrimaryText }}</a>
                        <a href="{{ route('home') }}#faq" class="btn btn-secondary">Vanliga frågor</a>
                    </div>
                </div>

                <div class="service-links-panel">
                    <h3>{{ ($page['group'] ?? null) === 'company' ? 'Fler företagstjänster' : 'Fler tjänster för hemmet' }}</h3>
                    <ul>
                        @foreach($relatedLinks as $item)
                            <li><a href="{{ $item['route'] }}">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="service-cta-banner">
                    <div>
                        <span class="eyebrow">Nästa steg</span>
                        <h2>Vill du ha hjälp med {{ mb_strtolower($page['title']) }}?</h2>
                        <p>Kontakta oss så hjälper vi dig att hitta ett upplägg som passar just dina behov.</p>
                    </div>

                    <div class="hero-actions">
                        <a href="{{ $ctaPrimaryHref }}" class="btn btn-primary">{{ $ctaPrimaryText }}</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Till startsidan</a>
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
            </div>

            <div>
                <h3>Privatpersoner</h3>
                <ul>
                    @foreach($menuGroups['private'] as $item)
                        <li><a href="{{ $item['route'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3>Företag</h3>
                <ul>
                    @foreach($menuGroups['company'] as $item)
                        <li><a href="{{ $item['route'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3>Kontakt</h3>
                <ul>
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}">{{ $phonePrimary }}</a></li>
                    <li><a href="mailto:{{ $email }}">{{ $email }}</a></li>
                    <li>{{ $addressLine }}</li>
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
    </script>
</body>
</html>