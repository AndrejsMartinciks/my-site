<!DOCTYPE html>
<html lang="sv">
<head>
  @php
  $companyName = $siteSettings->company_name ?? 'CleanS AB';
  $phonePrimary = $siteSettings->phone_primary ?? '+46707413772';
  $phoneSecondary = $siteSettings->phone_secondary ?? '+468838538';
  $email = $siteSettings->email ?? 'info@cleansource.se';
  $addressLine = trim(implode(', ', array_filter([
      $siteSettings->address ?? 'Ångermannagatan 121',
      trim(implode(' ', array_filter([
          $siteSettings->postal_code ?? '162 64',
          $siteSettings->city ?? 'Vällingby',
      ]))),
  ])));
  $orgNumber = $siteSettings->org_number ?? '556988-2722';

  $heroEyebrow = $siteSettings->hero_eyebrow ?? 'Hemstädning • Flyttstädning • Fönsterputs';
  $heroTitle = $siteSettings->hero_title ?? 'Rent hemma, mer tid över för livet.';
  $heroText = $siteSettings->hero_text ?? 'Vi hjälper hushåll i Stockholm med noggrann städning, tydliga priser och snabb återkoppling.';
  $heroPrimaryButtonText = $siteSettings->hero_primary_button_text ?? 'Få kostnadsfri offert';
  $heroSecondaryButtonText = $siteSettings->hero_secondary_button_text ?? 'Se våra tjänster';

  $heroPoints = array_values(array_filter([
      $siteSettings->hero_point_1 ?? '50% RUT-avdrag direkt på fakturan',
      $siteSettings->hero_point_2 ?? 'Ansvarsförsäkring och kvalitetssäkring',
      $siteSettings->hero_point_3 ?? 'Flexibla tider i hela Stockholm',
  ]));

  $heroBadges = array_values(array_filter([
      $siteSettings->hero_badge_1 ?? 'Försäkrade uppdrag',
      $siteSettings->hero_badge_2 ?? 'Miljövänliga produkter',
      $siteSettings->hero_badge_3 ?? 'Trygga rutiner',
  ]));

  $trustEyebrow = $siteSettings->trust_eyebrow ?? 'Därför väljer kunder oss';
  $trustTitle = $siteSettings->trust_title ?? 'En enkel process med hög trygghet';

  $trustItems = [
      [
          'icon' => '✓',
          'title' => $siteSettings->trust_item_1_title ?? 'Försäkring',
          'text' => $siteSettings->trust_item_1_text ?? 'Våra uppdrag utförs med ansvarsförsäkring för extra trygghet i ditt hem.',
      ],
      [
          'icon' => 'R',
          'title' => $siteSettings->trust_item_2_title ?? 'RUT-avdrag',
          'text' => $siteSettings->trust_item_2_text ?? 'Vi hanterar administrationen så att du bara betalar din reducerade kostnad.',
      ],
      [
          'icon' => '★',
          'title' => $siteSettings->trust_item_3_title ?? 'Utbildad personal',
          'text' => $siteSettings->trust_item_3_text ?? 'Vårt team följer tydliga checklistor och kvalitetsrutiner för varje tjänst.',
      ],
      [
          'icon' => '24',
          'title' => $siteSettings->trust_item_4_title ?? 'Snabb kontakt',
          'text' => $siteSettings->trust_item_4_text ?? 'Vi återkopplar snabbt på offertförfrågningar och hjälper dig hitta rätt upplägg.',
      ],
  ];

  $aboutEyebrow = $siteSettings->about_eyebrow ?? ('Om ' . $companyName);
  $aboutTitle = $siteSettings->about_title ?? 'Vi bygger långsiktigt förtroende i varje uppdrag';
  $aboutText1 = $siteSettings->about_text_1 ?? 'Att släppa in någon i sitt hem kräver trygghet. Därför arbetar vi med tydliga rutiner, god kommunikation och ett servicetänk som gör det enkelt att bli återkommande kund.';
  $aboutText2 = $siteSettings->about_text_2 ?? 'Vi kombinerar erfarenhet med moderna arbetsmetoder och miljömedvetna produkter. Målet är att du ska känna att allt fungerar smidigt från första kontakt till avslutat uppdrag.';
  $aboutCheckTitle = $siteSettings->about_check_title ?? 'Så arbetar vi';
  $aboutChecks = array_values(array_filter([
      $siteSettings->about_check_1 ?? 'Behovsanalys innan start',
      $siteSettings->about_check_2 ?? 'Fast kontaktperson för återkommande kunder',
      $siteSettings->about_check_3 ?? 'Dokumenterade checklistor per tjänst',
      $siteSettings->about_check_4 ?? 'Flexibel bokning och tydlig kommunikation',
  ]));

  $rutEyebrow = $siteSettings->rut_eyebrow ?? 'RUT-avdrag för städning';
  $rutTitle = $siteSettings->rut_title ?? 'Du betalar bara halva arbetskostnaden';
  $rutText1 = $siteSettings->rut_text_1 ?? 'Som privatperson kan du i många fall använda RUT-avdrag för hushållsnära tjänster. Det innebär att halva arbetskostnaden dras direkt på fakturan, medan vi sköter administrationen.';
  $rutText2 = $siteSettings->rut_text_2 ?? 'På så sätt blir professionell städning mer tillgänglig och du får ett tydligt pris redan från början.';

  $footerText = $siteSettings->footer_text ?? 'Modern, trygg och tydlig städservice för privatpersoner i Stockholm.';

  $seoTitle = $siteSettings->seo_title ?? ($companyName . ' | Hemstädning i Stockholm');
  $seoDescription = $siteSettings->seo_description ?? 'Professionell hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning i Stockholm. Transparenta priser, RUT-avdrag och snabb bokning.';

  $hemstadningService = $services->first(function ($service) {
      return \Illuminate\Support\Str::lower($service->name) === 'hemstädning';
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
@endphp

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ $seoTitle }}</title>
  <meta name="description" content="{{ $seoDescription }}" />
  <meta name="theme-color" content="#0f766e" />
  <link rel="canonical" href="{{ url('/') }}">

  <link rel="icon" href="{{ asset('images/favicon.ico') }}" sizes="any">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ filemtime(public_path('css/styles.css')) }}">

  @verbatim
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "CleanS AB",
    "image": "https://cleans.se/favicon-32x32.png",
    "description": "Städföretag i Stockholm med hemstädning, storstädning, flyttstädning, byggstädning och fönsterputsning.",
    "telephone": "+46707413772",
    "email": "info@cleansource.se",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Ångermannagatan 121",
      "addressLocality": "Vällingby",
      "postalCode": "162 64",
      "addressCountry": "SE"
    },
    "areaServed": "Stockholm",
    "priceRange": "249-289 SEK"
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

  <header class="site-header" id="top">
    <div class="container nav-wrap">
      <a class="brand" href="#top" aria-label="{{ $companyName }} startsida">
        <img src="{{ asset('images/logo.png') }}" alt="{{ $companyName }}" class="brand-logo">
        <span>
          <strong>{{ $companyName }}</strong>
          <small>Trygg städning i Stockholm</small>
        </span>
      </a>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
        <span></span>
        <span></span>
        <span></span>
        <span class="sr-only">Öppna meny</span>
      </button>

      <nav id="site-nav" class="site-nav" aria-label="Huvudmeny">
        <a href="#services">Tjänster</a>
        <a href="#pricing">Priser</a>
        <a href="#rut">RUT-avdrag</a>
        <a href="#about">Om oss</a>
        <a href="#faq">FAQ</a>
        <a href="#contact" class="btn btn-sm btn-primary">Boka nu</a>
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
            <a href="#services" class="btn btn-secondary">{{ $heroSecondaryButtonText }}</a>
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
      <h2>Professionell städservice för hem och flytt</h2>
      <p>Vi erbjuder flexibla upplägg för både regelbunden städning och engångsuppdrag.</p>
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

<section class="section section-soft">
  <div class="container">
    <div class="quote-box" style="max-width: 100%;">
      <span class="eyebrow">Fönsterputsning</span>
      <h2>Boka professionell fönsterputsning</h2>
      <p>
        Behöver du hjälp med rena fönster hemma eller inför en flytt?
        Gå till vår separata sida för Fönsterputsning med egen bokning och prislogik.
      </p>

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
        <a class="brand footer-brand" href="#top">
          <img src="{{ asset('images/logo.png') }}" alt="{{ $companyName }}" class="brand-logo">
          <span>
            <strong>{{ $companyName }}</strong>
            <small>Städning i Stockholm</small>
          </span>
        </a>
        <p>{{ $footerText }}</p>
      </div>

      <div>
        <h3>Snabblänkar</h3>
        <ul>
          <li><a href="#services">Tjänster</a></li>
          <li><a href="#pricing">Priser</a></li>
          <li><a href="#rut">RUT-avdrag</a></li>
          <li><a href="#contact">Kontakt</a></li>
        </ul>
      </div>

      <div>
        <h3>Kontakt</h3>
        <ul>
          <li><a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}">{{ $phonePrimary }}</a></li>
          @if($phoneSecondary)
            <li><a href="tel:{{ preg_replace('/\s+/', '', $phoneSecondary) }}">{{ $phoneSecondary }}</a></li>
          @endif
          <li><a href="mailto:{{ $email }}">{{ $email }}</a></li>
          <li>{{ $addressLine }}</li>
          <li>Org.nr: {{ $orgNumber }}</li>
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