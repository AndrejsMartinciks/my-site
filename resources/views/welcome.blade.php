@extends('layouts.app')

@section('content')
  <a class="skip-link" href="#main">Hoppa till innehåll</a>

  <header class="site-header" id="top">
    <div class="container nav-wrap">
      <a class="brand" href="#top" aria-label="Clean Source AB startsida">
        <img src="{{ asset('images/logo.png') }}" alt="Clean Source AB" class="brand-logo">
        <span>
          <strong>Clean Source AB</strong>
          <small>Trygg städning i Stockholm</small>
        </span>
      </a>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
        <span></span><span></span><span></span>
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
          <span class="eyebrow">Hemstädning • Flyttstädning • Fönsterputs</span>
          <h1>Rent hemma, mer tid över för livet.</h1>
          <p class="lead">Clean Source AB hjälper hushåll i Stockholm med noggrann städning, tydliga priser och snabb återkoppling. Vi arbetar med professionella rutiner, försäkring och fokus på trygg service.</p>
          <div class="hero-actions">
            <a href="#contact" class="btn btn-primary">Få kostnadsfri offert</a>
            <a href="#services" class="btn btn-secondary">Se våra tjänster</a>
          </div>
          <ul class="hero-points" aria-label="Våra styrkor">
            <li>50% RUT-avdrag direkt på fakturan</li>
            <li>Ansvarsförsäkring och kvalitetssäkring</li>
            <li>Flexibla tider i hela Stockholm</li>
          </ul>
        </div>

        <aside class="hero-card" aria-label="Snabb info">
          <div class="stat-grid">
            <article><strong>15+</strong><span>års erfarenhet</span></article>
            <article><strong>24h</strong><span>svarstid på vardagar</span></article>
            <article><strong>249 kr</strong><span>från / timme efter RUT</span></article>
            <article><strong>100%</strong><span>fokus på nöjda kunder</span></article>
          </div>
          <div class="hero-badge-list">
            <span>Försäkrade uppdrag</span>
            <span>Miljövänliga produkter</span>
            <span>Trygga rutiner</span>
          </div>
        </aside>
      </div>
    </section>

    <section class="trust section section-soft">
      <div class="container">
        <div class="section-head compact">
          <span class="eyebrow">Därför väljer kunder oss</span>
          <h2>En enkel process med hög trygghet</h2>
        </div>
        <div class="feature-grid cols-4">
          <article class="feature-card"><div class="icon">✓</div><h3>Försäkring</h3><p>Våra uppdrag utförs med ansvarsförsäkring för extra trygghet i ditt hem.</p></article>
          <article class="feature-card"><div class="icon">R</div><h3>RUT-avdrag</h3><p>Vi hanterar administrationen så att du bara betalar din reducerade kostnad.</p></article>
          <article class="feature-card"><div class="icon">★</div><h3>Utbildad personal</h3><p>Vårt team följer tydliga checklistor och kvalitetsrutiner för varje tjänst.</p></article>
          <article class="feature-card"><div class="icon">24</div><h3>Snabb kontakt</h3><p>Vi återkopplar snabbt på offertförfrågningar och hjälper dig hitta rätt upplägg.</p></article>
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
          <article class="service-card"><h3>Hemstädning</h3><p>Återkommande hemstädning med fast upplägg varje vecka, varannan vecka eller mer sällan.</p><ul><li>Kök, badrum och alla ytor</li><li>Dammsugning och våttorkning</li><li>Flexibla besökstider</li></ul></article>
          <article class="service-card"><h3>Flyttstädning</h3><p>Grundlig slutstädning inför besiktning med fokus på detaljer, glasytor och vitvaror.</p><ul><li>Checklista för flytt</li><li>Material ingår</li><li>Perfekt för privatpersoner</li></ul></article>
          <article class="service-card"><h3>Storstädning</h3><p>När hemmet behöver en ordentlig genomgång ger vi extra tid åt svåråtkomliga ytor.</p><ul><li>Djupare rengöring</li><li>Perfekt inför säsongsbyte</li><li>Anpassas efter behov</li></ul></article>
          <article class="service-card"><h3>Fönsterputsning</h3><p>Rena fönster med ett jämnt och hållbart resultat för bostäder och mindre lokaler.</p><ul><li>Invändigt och utvändigt</li><li>Erfaret team</li><li>Bättre ljusinsläpp direkt</li></ul></article>
          <article class="service-card"><h3>Byggstädning</h3><p>Efter renovering eller byggprojekt ser vi till att damm, rester och smuts försvinner.</p><ul><li>Finputs före inflytt</li><li>Dammreducering</li><li>Säker arbetsmetod</li></ul></article>
          <article class="service-card"><h3>Flytthjälp & packning</h3><p>Vi kan kombinera städning med praktisk hjälp före eller efter flytten för en smidigare process.</p><ul><li>Packhjälp</li><li>Bärhjälp</li><li>Samordning med städning</li></ul></article>
        </div>
      </div>
    </section>

    <section class="pricing section section-soft" id="pricing">
      <div class="container pricing-grid">
        <div>
          <div class="section-head left">
            <span class="eyebrow">Priser för hemstädning</span>
            <h2>Tydliga timpriser efter RUT-avdrag</h2>
            <p>Priset påverkas av bostadens storlek, intervall och om du vill ha tilläggstjänster.</p>
          </div>
          <div class="price-stack">
            <article class="price-card featured"><span>Varje vecka</span><strong>249 kr / timme</strong><small>Vårt bästa pris</small></article>
            <article class="price-card"><span>Varannan vecka</span><strong>259 kr / timme</strong></article>
            <article class="price-card"><span>Var tredje vecka</span><strong>269 kr / timme</strong></article>
            <article class="price-card"><span>Var fjärde vecka</span><strong>289 kr / timme</strong></article>
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
          <span class="eyebrow">Om Clean Source AB</span>
          <h2>Vi bygger långsiktigt förtroende i varje uppdrag</h2>
          <p>Att släppa in någon i sitt hem kräver trygghet. Därför arbetar vi med tydliga rutiner, god kommunikation и т.д.</p>
        </div>
        <div class="check-panel">
          <h3>Så arbetar vi</h3>
          <ul class="check-list">
            <li>Behovsanalys innan start</li>
            <li>Fast kontaktperson</li>
            <li>Dokumenterade checklistor</li>
            <li>Flexibel bokning</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="rut section section-soft" id="rut">
      <div class="container split-grid">
        <div class="rut-visual" aria-hidden="true"><div class="rut-circle"><span>50%</span><small>RUT-avdrag</small></div></div>
        <div>
          <span class="eyebrow">RUT-avdrag för städning</span>
          <h2>Du betalar bara halva arbetskostnaden</h2>
          <p>Halva arbetskostnaden dras direkt på fakturan, medan vi sköter administrationen.</p>
        </div>
      </div>
    </section>

    <section class="testimonials section">
      <div class="container">
        <div class="section-head"><span class="eyebrow">Kundomdömen</span><h2>Vad våra kunder uppskattar</h2></div>
        <div class="testimonial-grid">
          <blockquote class="testimonial-card"><p>"Snabb offert, trevligt bemötande и т.д."</p><footer>Anna, Bromma</footer></blockquote>
          <blockquote class="testimonial-card"><p>"Flyttstädningen gick smidigt..."</p><footer>Johan, Solna</footer></blockquote>
          <blockquote class="testimonial-card"><p>"Bra kommunikation hela vägen..."</p><footer>Elin, Hägersten</footer></blockquote>
        </div>
      </div>
    </section>

    <section class="contact section section-soft" id="contact">
      <div class="container contact-grid">
        <div>
          <span class="eyebrow">Boka eller bli uppringd</span>
          <h2>Skicka en förfrågan så kontaktar vi dig</h2>
          <p>Fyll i formuläret så återkommer vi under våra öppettider.</p>
          <div class="contact-cards">
            <article><strong>Mobil</strong><a href="tel:+46707413772">070 741 37 72</a></article>
            <article><strong>Växel</strong><a href="tel:+468838538">08-838-538</a></article>
            <article><strong>E-post</strong><a href="mailto:info@cleansource.se">info@cleansource.se</a></article>
            <article><strong>Adress</strong><p>Ångermannagatan 121, 162 64 Vällingby</p></article>
          </div>
        </div>

        <form class="booking-form" action="{{ route('order.send') }}" method="POST" id="booking-form">
          @csrf
          @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
          @endif
          <div class="form-row">
            <label>Namn <input type="text" name="name" required /></label>
            <label>E-post <input type="email" name="email" required /></label>
          </div>
          <div class="form-row">
            <label>Telefon <input type="tel" name="phone" required /></label>
            <label>Tjänst 
              <select name="service" required>
                <option value="">Välj tjänst</option>
                <option>Hemstädning</option>
                <option>Flyttstädning</option>
                <option>Storstädning</option>
                <option>Fönsterputsning</option>
                <option>Byggstädning</option>
                <option>Flytthjälp</option>
              </select>
            </label>
          </div>
          <label>Meddelande <textarea name="message" rows="5"></textarea></label>
          <button type="submit" class="btn btn-primary full">Skicka förfrågan</button>
        </form>
      </div>
    </section>

    <section class="faq section" id="faq">
      <div class="container">
        <div class="section-head"><span class="eyebrow">Vanliga frågor</span><h2>Svar på det kunder oftast undrar</h2></div>
        <div class="faq-list">
          <details><summary>Tar ni med eget städmaterial?</summary><p>Ja, vi tar med allt som behövs.</p></details>
          <details><summary>Hur fungerar RUT-avdraget?</summary><p>Vi redovisar det direkt på fakturan.</p></details>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <a class="brand footer-brand" href="#top">
          <img src="{{ asset('images/logo.png') }}" alt="Clean Source AB" class="brand-logo">
          <span><strong>Clean Source AB</strong><small>Städning i Stockholm</small></span>
        </a>
        <p>Modern, trygg och tydlig städservice för privatpersoner i Stockholm.</p>
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
          <li><a href="tel:+46707413772">070 741 37 72</a></li>
          <li><a href="mailto:info@cleansource.se">info@cleansource.se</a></li>
          <li>Org.nr: 556988-2722</li>
        </ul>
      </div>
    </div>
    <div class="container footer-bottom">
      <small>© {{ date('Y') }} Clean Source AB. Alla rättigheter förbehållna.</small>
    </div>
  </footer>
@endsection