@php
    $companyName = optional($siteSettings)->company_name ?: 'CleanS AB';
    $phonePrimary = optional($siteSettings)->phone_primary ?: '070 741 37 72';
    $phoneSecondary = optional($siteSettings)->phone_secondary ?: '08-838-538';
    $email = optional($siteSettings)->email ?: 'info@cleansource.se';
    $address = optional($siteSettings)->address ?: 'Ångermannagatan 121';
    $postalCode = optional($siteSettings)->postal_code ?: '162 64';
    $city = optional($siteSettings)->city ?: 'Vällingby';
    $orgNumber = optional($siteSettings)->org_number ?: '556988-2722';

    $heroEyebrow = optional($siteSettings)->hero_eyebrow ?: 'Hemstädning • Flyttstädning • Fönsterputs';
    $heroTitle = optional($siteSettings)->hero_title ?: 'Rent hemma, mer tid över för livet.';
    $heroText = optional($siteSettings)->hero_text ?: 'CleanS AB hjälper hushåll i Stockholm med noggrann städning, tydliga priser och snabb återkoppling. Vi arbetar med professionella rutiner, försäkring och fokus på trygg service.';
    $heroPrimaryButtonText = optional($siteSettings)->hero_primary_button_text ?: 'Få kostnadsfri offert';
    $heroSecondaryButtonText = optional($siteSettings)->hero_secondary_button_text ?: 'Se våra tjänster';

    $seoTitle = optional($siteSettings)->seo_title ?: $companyName . ' | Hemstädning i Stockholm';
    $seoDescription = optional($siteSettings)->seo_description ?: 'Professionell hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning i Stockholm. Transparenta priser, RUT-avdrag och snabb bokning.';

    $fullAddress = trim($address . ', ' . $postalCode . ' ' . $city);

    $phonePrimaryHref = preg_replace('/[^\d+]/', '', $phonePrimary);
    $phoneSecondaryHref = preg_replace('/[^\d+]/', '', $phoneSecondary);
@endphp

<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ $siteSettings->seo_title?? 'CleanS AB | Hemstädning i Stockholm' }}</title>
  <meta name="description" content="{{ $siteSettings->seo_description?? 'Professionell städning i Stockholm.' }}">
  <meta name="theme-color" content="#0f766e" />
  <link rel="canonical" href="https://cleans.se/">

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
        <a href="{{ route('window-cleaning') }}" class="nav-highlight">Fönsterputsning</a>
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
    <a href="{{ route('window-cleaning') }}" class="btn btn-outline-white">Boka fönsterputs</a>
</div>
          <ul class="hero-points" aria-label="Våra styrkor">
            <li>50% RUT-avdrag direkt på fakturan</li>
            <li>Ansvarsförsäkring och kvalitetssäkring</li>
            <li>Flexibla tider i hela Stockholm</li>
          </ul>
        </div>

        <aside class="hero-card" aria-label="Snabb info">
          <div class="stat-grid">
            <article>
              <strong>15+</strong>
              <span>års erfarenhet</span>
            </article>
            <article>
              <strong>24h</strong>
              <span>svarstid på vardagar</span>
            </article>
            <article>
              <strong>249 kr</strong>
              <span>från / timme efter RUT</span>
            </article>
            <article>
              <strong>100%</strong>
              <span>fokus på nöjda kunder</span>
            </article>
          </div>
          <div class="hero-badge-list">
            <span>Försäkrade uppdrag</span>
            <span>Miljövänliga produkter</span>
            <span>Trygga rutiner</span>
          </div>
        </aside>
      </div>
    </section>

@include('partials.calculator')

    <section class="trust section section-soft">
      <div class="container">
        <div class="section-head compact">
          <span class="eyebrow">Därför väljer kunder oss</span>
          <h2>En enkel process med hög trygghet</h2>
        </div>
        <div class="feature-grid cols-4">
          <article class="feature-card">
            <div class="icon">✓</div>
            <h3>Försäkring</h3>
            <p>Våra uppdrag utförs med ansvarsförsäkring för extra trygghet i ditt hem.</p>
          </article>
          <article class="feature-card">
            <div class="icon">R</div>
            <h3>RUT-avdrag</h3>
            <p>Vi hanterar administrationen så att du bara betalar din reducerade kostnad.</p>
          </article>
          <article class="feature-card">
            <div class="icon">★</div>
            <h3>Utbildad personal</h3>
            <p>Vårt team följer tydliga checklistor och kvalitetsrutiner för varje tjänst.</p>
          </article>
          <article class="feature-card">
            <div class="icon">24</div>
            <h3>Snabb kontakt</h3>
            <p>Vi återkopplar snabbt på offertförfrågningar och hjälper dig hitta rätt upplägg.</p>
          </article>
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
      @forelse($services as $service)
        <article class="service-card">
          <h3>{{ $service->name }}</h3>
          <p>{{ $service->description }}</p>

          <ul>
            @if($service->pricing_mode === 'frequency')
              <li>Återkommande upplägg</li>
              <li>Flera intervall och prisnivåer</li>
              <li>Kan kombineras med tillägg</li>
            @else
              <li>Engångsuppdrag</li>
              <li>Pris efter bostad och behov</li>
              <li>Kan kombineras med tillägg</li>
            @endif
          </ul>
        </article>
      @empty
        <article class="service-card">
          <h3>Hemstädning</h3>
          <p>Återkommande hemstädning med fast upplägg varje vecka, varannan vecka eller mer sällan.</p>
          <ul>
            <li>Kök, badrum och alla ytor</li>
            <li>Dammsugning och våttorkning</li>
            <li>Flexibla besökstider</li>
          </ul>
        </article>
        <article class="service-card">
          <h3>Flyttstädning</h3>
          <p>Grundlig slutstädning inför besiktning med fokus på detaljer, glasytor och vitvaror.</p>
          <ul>
            <li>Checklista för flytt</li>
            <li>Material ingår</li>
            <li>Perfekt för privatpersoner</li>
          </ul>
        </article>
        <article class="service-card">
          <h3>Storstädning</h3>
          <p>När hemmet behöver en ordentlig genomgång ger vi extra tid åt svåråtkomliga ytor.</p>
          <ul>
            <li>Djupare rengöring</li>
            <li>Perfekt inför säsongsbyte</li>
            <li>Anpassas efter behov</li>
          </ul>
        </article>
      @endforelse
    </div>
  </div>
</section>

    @php
    $pricingService = $services->firstWhere('pricing_mode', 'frequency') ?? $services->first();
@endphp

<section class="pricing section section-soft" id="pricing">
  <div class="container pricing-grid">
    <div>
      <div class="section-head left">
        <span class="eyebrow">
          Priser för {{ $pricingService?->name ?? 'hemstädning' }}
        </span>
        <h2>Tydliga prisnivåer efter RUT-avdrag</h2>
        <p>
          Priset påverkas av bostadens storlek, intervall och om du vill ha tilläggstjänster.
          Här är våra vanligaste nivåer.
        </p>
      </div>

      <div class="price-stack">
        @if($pricingService && $pricingService->pricing_mode === 'frequency')
          @forelse($pricingService->frequencies as $frequency)
            @php
                $fromPrice = $frequency->priceRanges->min('price');
                $rangeCount = $frequency->priceRanges->count();
            @endphp

            <article class="price-card {{ $loop->first ? 'featured' : '' }}">
              <span>{{ $frequency->name }}</span>

              @if(!is_null($fromPrice))
                <strong>Från {{ number_format((float) $fromPrice, 0, ',', ' ') }} kr</strong>
              @else
                <strong>Pris saknas</strong>
              @endif

              @if($loop->first)
                <small>Vanligt val</small>
              @elseif($rangeCount > 0)
                <small>{{ $rangeCount }} prisnivåer efter storlek</small>
              @endif
            </article>
          @empty
            <article class="price-card featured">
              <span>Varje vecka</span>
              <strong>249 kr / timme</strong>
              <small>Vårt bästa pris</small>
            </article>
            <article class="price-card">
              <span>Varannan vecka</span>
              <strong>259 kr / timme</strong>
            </article>
            <article class="price-card">
              <span>Var tredje vecka</span>
              <strong>269 kr / timme</strong>
            </article>
            <article class="price-card">
              <span>Var fjärde vecka</span>
              <strong>289 kr / timme</strong>
            </article>
          @endforelse

        @elseif($pricingService)
          @forelse($pricingService->priceRanges as $range)
            <article class="price-card {{ $loop->first ? 'featured' : '' }}">
              <span>{{ $range->min_sqm }}–{{ $range->max_sqm }} m²</span>
              <strong>{{ number_format((float) $range->price, 0, ',', ' ') }} kr</strong>

              @if($loop->first)
                <small>Prisnivå efter storlek</small>
              @endif
            </article>
          @empty
            <article class="price-card featured">
              <span>Varje vecka</span>
              <strong>249 kr / timme</strong>
              <small>Vårt bästa pris</small>
            </article>
            <article class="price-card">
              <span>Varannan vecka</span>
              <strong>259 kr / timme</strong>
            </article>
            <article class="price-card">
              <span>Var tredje vecka</span>
              <strong>269 kr / timme</strong>
            </article>
            <article class="price-card">
              <span>Var fjärde vecka</span>
              <strong>289 kr / timme</strong>
            </article>
          @endforelse

        @else
          <article class="price-card featured">
            <span>Varje vecka</span>
            <strong>249 kr / timme</strong>
            <small>Vårt bästa pris</small>
          </article>
          <article class="price-card">
            <span>Varannan vecka</span>
            <strong>259 kr / timme</strong>
          </article>
          <article class="price-card">
            <span>Var tredje vecka</span>
            <strong>269 kr / timme</strong>
          </article>
          <article class="price-card">
            <span>Var fjärde vecka</span>
            <strong>289 kr / timme</strong>
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
          <span class="eyebrow">Om CleanS AB</span>
          <h2>Vi bygger långsiktigt förtroende i varje uppdrag</h2>
          <p>Att släppa in någon i sitt hem kräver trygghet. Därför arbetar vi med tydliga rutiner, god kommunikation och ett servicetänk som gör det enkelt att bli återkommande kund.</p>
          <p>Vi kombinerar erfarenhet med moderna arbetsmetoder och miljömedvetna produkter. Målet är att du ska känna att allt fungerar smidigt från första kontakt till avslutat uppdrag.</p>
        </div>
        <div class="check-panel">
          <h3>Så arbetar vi</h3>
          <ul class="check-list">
            <li>Behovsanalys innan start</li>
            <li>Fast kontaktperson för återkommande kunder</li>
            <li>Dokumenterade checklistor per tjänst</li>
            <li>Flexibel bokning och tydlig kommunikation</li>
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
          <span class="eyebrow">RUT-avdrag för städning</span>
          <h2>Du betalar bara halva arbetskostnaden</h2>
          <p>Som privatperson kan du i många fall använda RUT-avdrag för hushållsnära tjänster. Det innebär att halva arbetskostnaden dras direkt på fakturan, medan vi sköter administrationen.</p>
          <p>På så sätt blir professionell städning mer tillgänglig och du får ett tydligt pris redan från början.</p>
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
    @foreach($testimonials as $testimonial)
        <div class="testimonial-card">
            <p>"{{ $testimonial->content }}"</p>
            <strong>{{ $testimonial->name }}</strong>
            @if($testimonial->city)
                <span>{{ $testimonial->city }}</span>
            @endif
        </div>
    @endforeach
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
    <a href="tel:{{ $phonePrimaryHref }}">{{ $phonePrimary }}</a>
  </article>
  <article>
    <strong>Växel</strong>
    <a href="tel:{{ $phoneSecondaryHref }}">{{ $phoneSecondary }}</a>
  </article>
  <article>
    <strong>E-post</strong>
    <a href="mailto:{{ $email }}">{{ $email }}</a>
  </article>
  <article>
    <strong>Adress</strong>
    <p>{{ $fullAddress }}</p>
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

<section id="faq" class="faq section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">FAQ</span>
      <h2>Vanliga frågor</h2>
    </div>

    <section class="faq section" id="faq">
    <div class="faq-list">
      @forelse($faqs as $faq)
        <details>
          <summary>{{ $faq->question }}</summary>
          <p>{{ $faq->answer }}</p>
        </details>
      @empty
        <details>
          <summary>{{ $faq->question }}</summary>
          <p>{{ $faq->answer }}</p>
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
<p>Modern, trygg och tydlig städservice för privatpersoner i Stockholm.</p>
      </div>

      <div>
        <h3>Snabblänkar</h3>
        <ul>
  <li><a href="tel:{{ $phonePrimaryHref }}">{{ $phonePrimary }}</a></li>
  <li><a href="tel:{{ $phoneSecondaryHref }}">{{ $phoneSecondary }}</a></li>
  <li><a href="mailto:{{ $email }}">{{ $email }}</a></li>
  <li>{{ $fullAddress }}</li>
  <li>Org.nr: {{ $orgNumber }}</li>
</ul>
      </div>

      <div>
  <h3>Kontakt</h3>
  <ul>
    <li><a href="tel:{{ preg_replace('/\D+/', '', $siteSettings->phone_primary ?? '') }}">{{ $siteSettings->phone_primary ?? '070 741 37 72' }}</a></li>
    <li><a href="tel:{{ preg_replace('/\D+/', '', $siteSettings->phone_secondary ?? '') }}">{{ $siteSettings->phone_secondary ?? '08-838-538' }}</a></li>
    <li><a href="mailto:{{ $siteSettings->email ?? 'info@cleansource.se' }}">{{ $siteSettings->email ?? 'info@cleansource.se' }}</a></li>
    <li>{{ $siteSettings->address ?? 'Ångermannagatan 121' }}, {{ $siteSettings->postal_code ?? '162 64' }} {{ $siteSettings->city ?? 'Vällingby' }}</li>
    <li>Org.nr: {{ $siteSettings->org_number ?? '556988-2722' }}</li>
  </ul>
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