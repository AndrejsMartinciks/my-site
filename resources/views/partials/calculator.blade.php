@php
    $calculatorServices = $services->filter(function ($service) {
        if ($service->slug === 'engangsstadning') {
            return true;
        }

        if ($service->pricing_mode === 'frequency') {
            return $service->frequencies->contains(function ($frequency) {
                return $frequency->priceRanges->isNotEmpty();
            });
        }

        return $service->priceRanges->isNotEmpty();
    })->values();
@endphp

<section id="calculator" class="price-calculator section section-soft">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Prisberäknare</span>
      <h2>Räkna ut ditt pris direkt</h2>
      <p>Välj tjänst, ange kvadratmeter och se pris direkt. Du kan sedan skicka vidare uppgifterna till bokningsformuläret.</p>
    </div>

    <div class="calc-shell">
      <div class="calc-main">
        <div class="calc-panel">
          <div class="calc-panel-head">
            <h3>Välj tjänst och storlek</h3>
            <p>Fyll i uppgifterna nedan för att få ett uppskattat pris direkt.</p>
          </div>

          <div class="calc-fields">
            <div class="calc-field">
              <label for="calc-service">Typ av tjänst</label>
              <select id="calc-service" class="calc-input">
                <option value="">Välj tjänst</option>

                @forelse($calculatorServices as $service)
                  <option value="{{ $service->name }}">{{ $service->name }}</option>
                @empty
                  <option value="Hemstädning">Hemstädning</option>
                  <option value="Storstädning">Storstädning</option>
                  <option value="Flyttstädning">Flyttstädning</option>
                  <option value="Visningsstädning">Visningsstädning</option>
                @endforelse
              </select>
            </div>

            <div class="calc-field">
              <label for="calc-sqm">Kvadratmeter</label>
              <div class="calc-input-wrap">
                <input id="calc-sqm" class="calc-input" type="number" min="1" step="1" placeholder="Ange m²">
                <span class="calc-suffix">m²</span>
              </div>
            </div>
          </div>

          <div id="calc-frequency-wrap" class="calc-box" hidden>
  <h4>Hur ofta</h4>
  <div id="calc-frequency-list" class="calc-radio-list"></div>
</div>

          <div id="calc-supplements-wrap" class="calc-box" hidden>
            <h4>Alternativa tillägg</h4>
            <div id="calc-supplements-list" class="calc-checkbox-list"></div>
          </div>
        </div>

<div id="calc-booking-wrap" class="calc-box" hidden>
  <h4>Välj datum och tid</h4>

  <div class="calc-field">
    <label for="calc-booking-date">Datum</label>
    <div class="calc-input-wrap">
      <input
        id="calc-booking-date"
        class="calc-input"
        type="date"
        min="{{ now()->format('Y-m-d') }}"
      >
    </div>
  </div>

  <p id="calc-booking-status" class="calc-message"></p>

  <div id="calc-booking-slots" class="calc-radio-list"></div>
</div>

        <aside class="calc-summary-card">
          <h3>Sammanfattning</h3>

          <div class="calc-summary-row">
            <span>Tjänst</span>
            <strong id="calc-summary-service">-</strong>
          </div>

          <div class="calc-summary-row">
            <span>Kvadratmeter</span>
            <strong id="calc-summary-sqm">-</strong>
          </div>

          <div class="calc-summary-row" id="calc-summary-frequency-row" hidden>
            <span>Hur ofta</span>
            <strong id="calc-summary-frequency">-</strong>
          </div>

          <div class="calc-summary-row">
            <span>Tillägg</span>
            <strong id="calc-summary-supplements">-</strong>
          </div>

          <div class="calc-total-box">
            <span>Totalt pris</span>
            <strong id="calc-total-price">- kr</strong>
            <small>*Pris efter RUT-avdrag</small>
          </div>

          <p id="calc-message" class="calc-message"></p>

          <div class="calc-actions">
            <button type="button" id="calc-apply" class="btn btn-primary full" disabled>
              Använd i bokningsformuläret
            </button>
            <a href="#contact" class="btn btn-secondary full">Gå till kontaktformuläret</a>
          </div>
        </aside>
      </div>
    </div>
  </div>
</section>