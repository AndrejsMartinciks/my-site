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

        $groupLabel = ($page['group'] ?? null) === 'company' ? 'Företag' : 'Städning';
        $ctaPrimaryHref = route('home') . '#contact';
        $ctaPrimaryText = 'Begär offert';

        if (($page['group'] ?? null) === 'private' && str_contains(mb_strtolower($page['title']), 'fönsterputsning')) {
            $ctaPrimaryHref = route('window-cleaning');
            $ctaPrimaryText = 'Boka fönsterputsning';
        }

        $relatedLinks = $menuGroups[$page['group']] ?? [];

        $serviceSlug = request()->route('slug');
        $serviceImageRelativePath = 'images/services/' . $serviceSlug . '.jpg';
        $serviceSeoImage = file_exists(public_path($serviceImageRelativePath))
            ? asset($serviceImageRelativePath)
            : asset('images/logo.png');

        $serviceSeoDescription = \Illuminate\Support\Str::limit(
            \Illuminate\Support\Str::squish(
                strip_tags($page['lead'] ?? ($page['title'] . ' i Stockholm från Clean Source AB.'))
            ),
            160,
            ''
        );

        $breadcrumbGroupName = ($page['group'] ?? null) === 'company' ? 'Företag' : 'Privatpersoner';
    @endphp

    @include('partials.seo', [
        'siteSettings' => $siteSettings ?? null,
        'pageFaqs' => collect(),
        'seo' => [
            'title' => ($page['title'] ?? 'Tjänst') . ' | ' . ($siteSettings->company_name ?? 'Clean Source AB'),
            'description' => $serviceSeoDescription,
            'canonical' => url()->current(),
            'image' => $serviceSeoImage,
            'image_alt' => ($page['title'] ?? 'Tjänst') . ' i Stockholm',
            'og_type' => 'website',
            'schema_type' => 'service',
            'service_name' => $page['title'] ?? 'Tjänst',
            'service_type' => $page['title'] ?? 'Tjänst',
            'breadcrumbs' => [
                ['name' => 'Hem', 'url' => route('home')],
                ['name' => $breadcrumbGroupName, 'url' => route('home') . '#services'],
                ['name' => $page['title'] ?? 'Tjänst', 'url' => url()->current()],
            ],
        ],
    ])

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
        <section class="service-page-hero section">
            <div class="container">
                <div class="service-breadcrumbs">
                    <a href="{{ route('home') }}">Start</a>
                    <span>/</span>
                    <span>{{ $groupLabel }}</span>
                    <span>/</span>
                    <span>{{ $page['title'] }}</span>
                </div>

                <div class="service-page-hero__box">
                    <div class="service-page-hero__content">
                        <span class="eyebrow">{{ $page['eyebrow'] }}</span>
                        <h1>{{ $page['title'] }}</h1>
                        <p class="lead">{{ $page['lead'] }}</p>

                        <div class="hero-actions">
                            <a href="{{ $ctaPrimaryHref }}" class="btn btn-primary">{{ $ctaPrimaryText }}</a>
                            <a href="{{ route('home') }}#contact" class="btn btn-secondary">Kontakta oss</a>
                        </div>

                        <div class="service-chip-row">
                            <span>{{ ($page['group'] ?? null) === 'company' ? 'För företag' : 'För privatpersoner' }}</span>
                            <span>Stockholm</span>
                            <span>Snabb återkoppling</span>
                        </div>
                    </div>

                    <div class="service-page-hero__info">
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
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container service-page-layout">
                <div class="service-page-main">
                    <article class="service-page-card">
                        <h2>Om tjänsten</h2>

                        @foreach($page['intro'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </article>

                    <article class="service-page-card">
                        <h2>{{ $page['included_title'] }}</h2>
                        <ul class="check-list">
                            @foreach($page['included'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </article>

                    <article class="service-page-card">
                        <span class="eyebrow">Så går det till</span>
                        <h2>En tydlig process från start till utförande</h2>

                        <div class="service-process-grid">
                            <div class="service-process-step">
                                <strong>1. Kontakt</strong>
                                <p>Du skickar en förfrågan och berättar kort vad du behöver hjälp med.</p>
                            </div>

                            <div class="service-process-step">
                                <strong>2. Planering</strong>
                                <p>Vi går igenom upplägg, omfattning och föreslår en lösning som passar dig eller verksamheten.</p>
                            </div>

                            <div class="service-process-step">
                                <strong>3. Utförande</strong>
                                <p>Vi genomför tjänsten med fokus på tydlighet, kvalitet och ett professionellt resultat.</p>
                            </div>
                        </div>
                    </article>
                </div>

                <aside class="service-page-side">
                    <div class="service-page-card service-page-card--side">
                        <h3>Därför väljer kunder denna tjänst</h3>
                        <ul class="check-list">
                            @foreach($page['highlights'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="service-page-card service-page-card--side">
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
            <div class="container service-page-bottom">
                <div class="service-page-card">
                    <span class="eyebrow">Varför denna tjänst?</span>
                    <h2>{{ $page['why_title'] }}</h2>
                    <p>{{ $page['why_text'] }}</p>

                    <div class="hero-actions">
                        <a href="{{ $ctaPrimaryHref }}" class="btn btn-primary">{{ $ctaPrimaryText }}</a>
                        <a href="{{ route('home') }}#faq" class="btn btn-secondary">Vanliga frågor</a>
                    </div>
                </div>

                <div class="service-page-card">
                    <h3>{{ ($page['group'] ?? null) === 'company' ? 'Fler företagstjänster' : 'Fler tjänster för hemmet' }}</h3>

                    <ul class="service-links-list">
                        @foreach($relatedLinks as $item)
                            <li><a href="{{ $item['route'] }}">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="service-page-cta">
                    <div>
                        <span class="eyebrow">Nästa steg</span>
                        <h2>Vill du ha hjälp med {{ mb_strtolower($page['title']) }}?</h2>
                        <p>Kontakta oss så hjälper vi dig att hitta ett upplägg som passar dina behov.</p>
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