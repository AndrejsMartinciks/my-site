<!DOCTYPE html>
<html lang="sv">
<head>
  @php
    use Illuminate\Support\Str;

    $companyName = $siteSettings->company_name ?? 'Clean Source AB';
    $phonePrimary = $siteSettings->phone_primary ?? '+46707413772';
    $phoneSecondary = $siteSettings->phone_secondary ?? null;
    $email = $siteSettings->email ?? 'info@cleansource.se';
    $addressLine = trim(implode(', ', array_filter([
        $siteSettings->address ?? 'Ångermannagatan 121',
        trim(implode(' ', array_filter([
            $siteSettings->postal_code ?? '162 64',
            $siteSettings->city ?? 'Vällingby',
        ]))),
    ])));
    $orgNumber = $siteSettings->org_number ?? '556988-2722';

    $bankgiro = '540-1054';
    $swish = '1234591558';
    $facebookUrl = 'https://www.facebook.com/profile.php?id=61558309301151#';
    $instagramUrl = 'https://www.instagram.com/cleansource_ab?fbclid=IwY2xjawQo8LJleHRuA2FlbQIxMABicmlkETA4eHUzZGxBeUpiZkxBNzg3c3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHpOtXqviDqCw-KsJtrM-uqETaJcA4CwDl-_QvPLo8rSUKXChC4uCJo2eAOwU_aem_vhHTjVSzh9vhKuK3ae77RQ';
    $recoUrl = 'https://www.reco.se/clean-source-ab#3102144';

    $heroEyebrow = $siteSettings->hero_eyebrow ?? 'Städning för hem & företag i Stockholm';
    $heroTitle = $siteSettings->hero_title ?? 'Trygg och noggrann städservice med tydlig bokning.';
    $heroText = $siteSettings->hero_text ?? 'Clean Source AB hjälper privatpersoner och företag i Stockholm med städning, fönsterputsning och flyttrelaterade tjänster. Tydliga priser, snabb återkoppling och flexibla upplägg.';
    $heroPrimaryButtonText = $siteSettings->hero_primary_button_text ?? 'Räkna ut pris';
    $heroSecondaryButtonText = $siteSettings->hero_secondary_button_text ?? 'Begär offert';

    $heroPoints = array_values(array_filter([
        $siteSettings->hero_point_1 ?? '50% RUT-avdrag direkt på fakturan',
        $siteSettings->hero_point_2 ?? 'Ansvarsförsäkring och kvalitetssäkring',
        $siteSettings->hero_point_3 ?? 'Flexibla tider i hela Stockholm',
    ]));

    $heroBadges = array_values(array_filter([
        $siteSettings->hero_badge_1 ?? 'För hem & företag',
        $siteSettings->hero_badge_2 ?? 'Tydliga priser',
        $siteSettings->hero_badge_3 ?? 'Snabb återkoppling',
    ]));

    $trustEyebrow = $siteSettings->trust_eyebrow ?? 'Därför väljer kunder oss';
    $trustTitle = $siteSettings->trust_title ?? 'En modern och trygg städpartner';

    $trustItems = [
        [
            'icon' => '✓',
            'title' => $siteSettings->trust_item_1_title ?? 'Tryggt',
            'text' => $siteSettings->trust_item_1_text ?? 'Vi arbetar med tydliga rutiner, ansvarsförsäkring och personlig kontakt genom hela processen.',
        ],
        [
            'icon' => '★',
            'title' => $siteSettings->trust_item_2_title ?? 'Kvalitet',
            'text' => $siteSettings->trust_item_2_text ?? 'Vårt team arbetar noggrant och strukturerat för ett jämnt resultat i varje uppdrag.',
        ],
        [
            'icon' => '24',
            'title' => $siteSettings->trust_item_3_title ?? 'Flexibelt',
            'text' => $siteSettings->trust_item_3_text ?? 'Boka enstaka uppdrag eller återkommande städning — vi anpassar upplägget efter ditt behov.',
        ],
        [
            'icon' => 'R',
            'title' => $siteSettings->trust_item_4_title ?? 'RUT-avdrag',
            'text' => $siteSettings->trust_item_4_text ?? 'Vi hjälper dig med korrekt underlag så att din hushållsnära tjänst blir enkel att hantera.',
        ],
    ];

    $aboutEyebrow = $siteSettings->about_eyebrow ?? ('Om ' . $companyName);
    $aboutTitle = $siteSettings->about_title ?? 'Städning med fokus på kvalitet, trygghet och enkel kommunikation';
    $aboutText1 = $siteSettings->about_text_1 ?? 'Vi hjälper kunder i Stockholm med städtjänster för både hem och företag. Vår ambition är att göra bokning, kontakt och utförande så smidigt som möjligt.';
    $aboutText2 = $siteSettings->about_text_2 ?? 'Med tydliga rutiner, ett vänligt bemötande och noggrant utförda uppdrag vill vi vara en pålitlig partner när du behöver professionell städning.';
    $aboutCheckTitle = $siteSettings->about_check_title ?? 'Så arbetar vi';
    $aboutChecks = array_values(array_filter([
        $siteSettings->about_check_1 ?? 'Tydlig offert och snabb återkoppling',
        $siteSettings->about_check_2 ?? 'Anpassade upplägg för privat & företag',
        $siteSettings->about_check_3 ?? 'Noggrann planering inför varje uppdrag',
        $siteSettings->about_check_4 ?? 'Enkel kontakt före, under och efter bokning',
    ]));

    $rutEyebrow = $siteSettings->rut_eyebrow ?? 'RUT-avdrag för privatpersoner';
    $rutTitle = $siteSettings->rut_title ?? 'Du betalar bara halva arbetskostnaden';
    $rutText1 = $siteSettings->rut_text_1 ?? 'För många hushållsnära tjänster kan du använda RUT-avdrag, vilket innebär att arbetskostnaden reduceras direkt på fakturan.';
    $rutText2 = $siteSettings->rut_text_2 ?? 'Vi arbetar med tydlig information kring bokning och pris så att du enkelt ser vad som gäller för din tjänst.';

    $footerText = $siteSettings->footer_text ?? 'Professionell städservice i Stockholm för privatpersoner och företag.';

    $seoTitle = $siteSettings->seo_title ?? ($companyName . ' | Städning i Stockholm');
    $seoDescription = $siteSettings->seo_description ?? 'Professionell hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning i Stockholm. Tydliga priser och snabb bokning.';

    $hemstadningService = $services->first(function ($service) {
        return Str::lower($service->name) === 'hemstädning';
    });

    if (!$hemstadningService) {
        $hemstadningService = $services->first(function ($service) {
            return $service->pricing_mode === 'frequency';
        });
    }

    $priceCards = collect();

    if ($hemstadningService && $hemstadningService->pricing_mode === 'frequency') {
        $priceCards = $hemstadningService->frequencies
            ->map(function ($frequency) {
                $lowestRange = $frequency->priceRanges->sortBy('price')->first();

                return [
                    'label' => $frequency->name,
                    'price' => $lowestRange?->price,
                ];
            })
            ->filter(fn ($item) => !is_null($item['price']))
            ->values();
    }

    $serviceIntro = $services->take(6);
    $displayTestimonials = $testimonials->take(6);
    $displayFaqs = $faqs->take(8);

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

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ $seoTitle }}</title>
  <meta name="description" content="{{ $seoDescription }}" />
  <meta name="theme-color" content="#1f2b4d" />
  <link rel="canonical" href="{{ url('/') }}">

  <link rel="icon" href="{{ asset('images/favicon.ico') }}" sizes="any">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">

  @verbatim
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Clean Source AB",
    "image": "https://cleans.se/favicon-32x32.png",
    "description": "Städföretag i Stockholm med hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning.",
    "telephone": "+46707413772",
    "email": "info@cleansource.se",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Ångermannagatan 121",
      "addressLocality": "Vällingby",
      "postalCode": "162 64",
      "addressCountry": "SE"
    },
    "areaServed": "Stockholm"
  }
  </script>
  @endverbatim
</head>
<body>
  <div class="cookie-banner" id="cookie-banner" hidden>
    <div class="cookie-banner__content">
      <div class="cookie-banner__text">
        <strong>Vi använder cookies</strong>
        <p>
          Vi använder nödvändiga cookies för att webbplatsen ska fungera och, om du samtycker,
          även analyscookies för att förbättra webbplatsen.
          <a href="/cookie-policy">Läs mer</a>
        </p>
      </div>

      <div class="cookie-banner__actions">
        <button type="button" class="btn btn-secondary" id="cookie-necessary">
          Endast nödvändiga
        </button>
        <button type="button" class="btn btn-primary" id="cookie-accept">
          Acceptera alla
        </button>
      </div>
    </div>
  </div>

  <a class="skip-link" href="#main">Hoppa till innehåll</a>

  <div class="site-topbar">
    <div class="container site-topbar__inner">
      <div class="site-topbar__left">
        <a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}">{{ $phonePrimary }}</a>
        <a href="mailto:{{ $email }}">{{ $email }}</a>
      </div>

      <div class="site-topbar__right">
        <a href="{{ $recoUrl }}" target="_blank" rel="noopener noreferrer">Reco</a>
        <a href="#pricing">Pris</a>
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

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
        <span></span>
        <span></span>
        <span></span>
        <span class="sr-only">Öppna meny</span>
      </button>

      <nav id="site-nav" class="site-nav" aria-label="Huvudmeny">
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

        <a href="#about">Om oss</a>
        <a href="#pricing">Pris</a>
        <a href="#contact" class="btn btn-sm btn-primary nav-cta">Begär offert</a>
      </nav>
    </div>
  </header>

  <main id="main">
    <section class="hero section">
      <div class="container hero-grid">
        <div class="hero-copy">
          <span class="eyebrow">{{ $heroEyebrow }}</span>
          <h1>{{ $heroTitle }}</h1>
          <p class="lead">{{ $heroText }}</p>

          <div class="hero-actions">
            <a href="#calculator" class="btn btn-primary">{{ $heroPrimaryButtonText }}</a>
            <a href="#contact" class="btn btn-secondary">{{ $heroSecondaryButtonText }}</a>
          </div>

          <ul class="hero-points" aria-label="Våra styrkor">
            @foreach($heroPoints as $point)
              <li>{{ $point }}</li>
            @endforeach
          </ul>
        </div>

        <aside class="hero-card" aria-label="Snabb info">
          <div class="stat-grid">
            <article>
              <strong>{{ $services->count() }}+</strong>
              <span>aktiva tjänster</span>
            </article>
            <article>
              <strong>24h</strong>
              <span>svarstid på vardagar</span>
            </article>
            <article>
              <strong>
                @if($priceCards->isNotEmpty())
                  {{ $priceCards->min('price') }} kr
                @else
                  249 kr
                @endif
              </strong>
              <span>från / timme efter RUT</span>
            </article>
            <article>
              <strong>{{ max($displayTestimonials->count(), 3) }}+</strong>
              <span>kundomdömen</span>
            </article>
          </div>

          <div class="hero-badge-list">
            @foreach($heroBadges as $badge)
              <span>{{ $badge }}</span>
            @endforeach
          </div>
        </aside>
      </div>
    </section>

    @include('partials.calculator')

    <section class="trust section section-soft">
      <div class="container">
        <div class="section-head compact">
          <span class="eyebrow">{{ $trustEyebrow }}</span>
          <h2>{{ $trustTitle }}</h2>
        </div>

        <div class="feature-grid cols-4">
          @foreach($trustItems as $item)
            <article class="feature-card">
              <div class="icon">{{ $item['icon'] }}</div>
              <h3>{{ $item['title'] }}</h3>
              <p>{{ $item['text'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    <section class="services section" id="services">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Våra tjänster</span>
          <h2>Städning för privatpersoner och företag</h2>
          <p>Vi erbjuder flexibla upplägg för både regelbunden städning och engångsuppdrag i hela Stockholm.</p>
        </div>

        <div class="service-grid">
          @forelse($services->take(6) as $service)
            <article class="service-card">
              <h3>{{ $service->name }}</h3>

              <p>
                {{ $service->description ?: 'Professionell städservice anpassad efter dina behov och önskemål.' }}
              </p>

              <ul>
                @if($service->pricing_mode === 'frequency')
                  <li>Flexibla intervaller och återkommande upplägg</li>
                  <li>Pris beräknas efter storlek och frekvens</li>
                  <li>Passar för löpande hemstädning</li>
                @else
                  <li>Tydliga prisintervall utifrån bostadens storlek</li>
                  <li>Kan kombineras med tilläggstjänster</li>
                  <li>Snabb offert och enkel bokning</li>
                @endif
              </ul>

              @if($service->slug === 'fonsterputsning')
                <a href="{{ route('window-cleaning') }}" class="btn btn-secondary btn-sm">
                  Till Fönsterputsning
                </a>
              @endif
            </article>
          @empty
            <article class="service-card">
              <h3>Tjänster uppdateras</h3>
              <p>Inga aktiva tjänster hittades just nu. Lägg till eller aktivera tjänster i adminpanelen.</p>
            </article>
          @endforelse
        </div>
      </div>
    </section>

    <section class="reco-section section section-soft">
      <div class="container reco-shell">
        <div class="reco-copy">
          <span class="eyebrow">Reco</span>
          <h2>Se vad våra kunder säger om oss</h2>
          <p>Vi har lagt till en direktlänk till vår profilsida på Reco så att nya besökare enkelt kan läsa omdömen och få en snabbare bild av vårt arbete.</p>
        </div>

        <div class="reco-card">
          <strong>Clean Source AB på Reco</strong>
          <p>Läs recensioner, omdömen och se hur kunder upplever vårt arbete.</p>
          <a href="{{ $recoUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
            Öppna Reco
          </a>
        </div>
      </div>
    </section>

    <section class="section section-highlight">
      <div class="container">
        <div class="promo-panel">
          <div>
            <span class="eyebrow">Fönsterputsning</span>
            <h2>Boka professionell fönsterputsning online</h2>
            <p>
              Behöver du hjälp med rena fönster hemma eller inför en flytt?
              Gå till vår separata sida för Fönsterputsning med egen bokning och prislogik.
            </p>
          </div>

          <div class="hero-actions">
            <a href="{{ route('window-cleaning') }}" class="btn btn-primary">
              Gå till Fönsterputsning
            </a>
            <a href="#contact" class="btn btn-secondary">
              Kontakta oss först
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="pricing section section-soft" id="pricing">
      <div class="container pricing-grid">
        <div>
          <div class="section-head left">
            <span class="eyebrow">Priser för hemstädning</span>
            <h2>Tydliga timpriser efter RUT-avdrag</h2>
            <p>Priset påverkas av bostadens storlek, intervall och om du vill ha tilläggstjänster. Här är våra vanligaste nivåer.</p>
          </div>

          <div class="price-stack">
            @if($priceCards->isNotEmpty())
              @foreach($priceCards as $index => $card)
                <article class="price-card {{ $index === 0 ? 'featured' : '' }}">
                  <span>{{ $card['label'] }}</span>
                  <strong>{{ $card['price'] }} kr / timme</strong>
                  @if($index === 0)
                    <small>Vårt bästa pris</small>
                  @endif
                </article>
              @endforeach
            @else
              <article class="price-card featured">
                <span>Priser uppdateras</span>
                <strong>Kontakta oss för offert</strong>
                <small>Data saknas i admin</small>
              </article>
            @endif
          </div>
        </div>

        <aside class="quote-box">
          <h3>Vad ingår i priset?</h3>
          <ul>
            <li>Moms och RUT-avdrag redovisat</li>
            <li>Professionellt städmaterial</li>
            <li>Planering efter dina önskemål</li>
            <li>Snabb offert inför start</li>
          </ul>
          <a href="#contact" class="btn btn-primary full">Be om personlig offert</a>
        </aside>
      </div>
    </section>

    <section class="about section" id="about">
      <div class="container split-grid">
        <div>
          <span class="eyebrow">{{ $aboutEyebrow }}</span>
          <h2>{{ $aboutTitle }}</h2>
          <p>{{ $aboutText1 }}</p>
          <p>{{ $aboutText2 }}</p>
        </div>

        <div class="check-panel">
          <h3>{{ $aboutCheckTitle }}</h3>
          <ul class="check-list">
            @foreach($aboutChecks as $check)
              <li>{{ $check }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </section>

    <section class="rut section section-soft" id="rut">
      <div class="container split-grid">
        <div class="rut-visual" aria-hidden="true">
          <div class="rut-circle">
            <span>50%</span>
            <small>RUT-avdrag</small>
          </div>
        </div>

        <div>
          <span class="eyebrow">{{ $rutEyebrow }}</span>
          <h2>{{ $rutTitle }}</h2>
          <p>{{ $rutText1 }}</p>
          <p>{{ $rutText2 }}</p>
        </div>
      </div>
    </section>

    <section class="testimonials section">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Kundomdömen</span>
          <h2>Vad våra kunder uppskattar</h2>
        </div>

        <div class="testimonial-grid">
          @forelse($displayTestimonials as $testimonial)
            <blockquote class="testimonial-card">
              <p>"{{ $testimonial->quote }}"</p>
              <footer>
                {{ $testimonial->name }}
                @if(!empty($testimonial->location))
                  , {{ $testimonial->location }}
                @endif
              </footer>
            </blockquote>
          @empty
            <blockquote class="testimonial-card">
              <p>"Snabb offert, trevligt bemötande och mycket noggrann städning."</p>
              <footer>Kundomdömen uppdateras</footer>
            </blockquote>
          @endforelse
        </div>
      </div>
    </section>

    <section class="contact section section-soft" id="contact">
      <div class="container contact-grid">
        <div>
          <span class="eyebrow">Boka eller bli uppringd</span>
          <h2>Skicka en förfrågan så kontaktar vi dig</h2>
          <p>Fyll i formuläret så återkommer vi med offert eller förslag på upplägg under våra öppettider.</p>

          <div class="contact-cards">
            <article>
              <strong>Mobil</strong>
              <a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}">{{ $phonePrimary }}</a>
            </article>

            @if($phoneSecondary)
              <article>
                <strong>Växel</strong>
                <a href="tel:{{ preg_replace('/\s+/', '', $phoneSecondary) }}">{{ $phoneSecondary }}</a>
              </article>
            @endif

            <article>
              <strong>E-post</strong>
              <a href="mailto:{{ $email }}">{{ $email }}</a>
            </article>

            <article>
              <strong>Adress</strong>
              <p>{{ $addressLine }}</p>
            </article>
          </div>
        </div>

        <form class="booking-form" id="booking-form" method="POST" action="{{ route('contact.store') }}" novalidate>
          @csrf

          <input type="hidden" name="calculator_summary" id="calculator-summary-input">
          <input type="hidden" name="booking_slot_id" id="booking-slot-id-input">
          <input type="hidden" name="booking_date" id="booking-date-input">
          <input type="hidden" name="booking_time_from" id="booking-time-from-input">
          <input type="hidden" name="booking_time_to" id="booking-time-to-input">

          <div class="form-row">
            <label>
              Namn
              <input type="text" name="name" autocomplete="name" required />
            </label>

            <label>
              E-post
              <input type="email" name="email" autocomplete="email" required />
            </label>
          </div>

          <div class="form-row">
            <label>
              Telefon
              <input type="tel" name="phone" autocomplete="tel" required />
            </label>

            <label>
              Tjänst
              <select name="service" id="contact-service" required>
                <option value="">Välj tjänst</option>
                @foreach($services as $service)
                  <option value="{{ $service->name }}">{{ $service->name }}</option>
                @endforeach
              </select>
            </label>
          </div>

          <div id="booking-customer-fields" hidden>
            <div class="form-row">
              <label>
                Personnummer
                <input
                  type="text"
                  name="personnummer"
                  id="booking-personnummer"
                  inputmode="numeric"
                  autocomplete="off"
                  placeholder="YYYYMMDDXXXX eller YYMMDDXXXX"
                />
              </label>

              <label>
                Adress
                <input
                  type="text"
                  name="address"
                  id="booking-address"
                  autocomplete="street-address"
                  placeholder="Gatuadress och lägenhetsnummer"
                />
              </label>
            </div>

            <p class="form-note">
              För bokning med RUT-avdrag behöver vi personnummer och adress.
            </p>
          </div>

          <label>
            Meddelande
            <textarea
              name="message"
              rows="5"
              placeholder="Beskriv bostad, önskat datum eller vad du behöver hjälp med."
            ></textarea>
          </label>

          <button type="submit" class="btn btn-primary full">Skicka förfrågan</button>
          <p class="form-status" id="form-status" aria-live="polite"></p>
        </form>
      </div>
    </section>

    <section class="faq section" id="faq">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Vanliga frågor</span>
          <h2>Svar på det kunder oftast undrar</h2>
        </div>

        <div class="faq-list">
          @forelse($displayFaqs as $faq)
            <details>
              <summary>{{ $faq->question }}</summary>
              <p>{{ $faq->answer }}</p>
            </details>
          @empty
            <details open>
              <summary>Vanliga frågor uppdateras</summary>
              <p>Lägg till aktiva frågor i adminpanelen så visas de här automatiskt.</p>
            </details>
          @endforelse
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

        <p>{{ $footerText }}</p>

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
          <li><a href="#about">Om oss</a></li>
          <li><a href="#pricing">Pris</a></li>
          <li><a href="#faq">FAQ</a></li>
          <li><a href="#contact">Kontakt</a></li>
        </ul>
      </div>
    </div>

    <div class="container footer-bottom">
      <small>© <span id="year"></span> {{ $companyName }}. Alla rättigheter förbehållna.</small>
    </div>
  </footer>

  <script>
    window.calcData = @json($calculatorData);
  </script>
  <script src="{{ asset('js/script.js') }}?v={{ filemtime(public_path('js/script.js')) }}"></script>
</body>
</html>