const navToggle = document.querySelector('[data-nav-toggle]');
const siteNav = document.querySelector('#site-nav');
const navLinks = document.querySelectorAll('#site-nav a');
const form = document.querySelector('#booking-form');
const statusEl = document.querySelector('#form-status');
const yearEl = document.querySelector('#year');

function setStatus(message = '', type = '', options = {}) {
  if (!statusEl) return;

  const { scroll = false } = options;

  statusEl.textContent = message;
  statusEl.className = 'form-status';

  if (type) {
    statusEl.classList.add(type);
  }

  if (scroll && message) {
    statusEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
}

if (yearEl) {
  yearEl.textContent = String(new Date().getFullYear());
}

if (navToggle && siteNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = siteNav.classList.toggle('is-open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });

  navLinks.forEach((link) => {
    link.addEventListener('click', () => {
      siteNav.classList.remove('is-open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });
}

async function submitToServer(formData) {
  const token = formData.get('_token');

  const response = await fetch('/contact', {
    method: 'POST',
    headers: {
      Accept: 'application/json',
      'X-CSRF-TOKEN': token,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: formData,
    credentials: 'same-origin',
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    if (response.status === 419) {
      throw new Error('Sessionen har uppdaterats. Ladda om sidan och försök igen.');
    }

    if (response.status === 422 && data.errors) {
      const firstError = Object.values(data.errors).flat()[0];
      throw new Error(firstError || 'Kontrollera formuläret och försök igen.');
    }

    if (response.status === 409) {
      throw new Error(data.message || 'Den valda tiden är inte längre tillgänglig. Välj en annan ledig tid.');
    }

    throw new Error(data.message || 'Kunde inte skicka formuläret till servern.');
  }

  return data;
}

function setCookie(name, value, days) {
  const expires = new Date(Date.now() + days * 864e5).toUTCString();
  document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
}

function getCookie(name) {
  return document.cookie
    .split('; ')
    .find((row) => row.startsWith(`${name}=`))
    ?.split('=')[1];
}

function runDeferredScripts(category) {
  const scripts = document.querySelectorAll(`script[type="text/plain"][data-cookie-category="${category}"]`);

  scripts.forEach((script) => {
    const newScript = document.createElement('script');

    if (script.dataset.src) {
      newScript.src = script.dataset.src;
      newScript.async = true;
    } else {
      newScript.textContent = script.textContent;
    }

    document.head.appendChild(newScript);
    script.remove();
  });
}

function applyCookieConsent(consent) {
  if (consent === 'all') {
    runDeferredScripts('analytics');
  }
}

function updateCookieBannerOffset(banner) {
  if (!banner) return;
  const offset = banner.offsetHeight + 24;
  document.documentElement.style.setProperty('--cookie-banner-offset', `${offset}px`);
}

document.addEventListener('DOMContentLoaded', () => {
  const banner = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('cookie-accept');
  const necessaryBtn = document.getElementById('cookie-necessary');

  if (!banner || !acceptBtn || !necessaryBtn) return;

  const savedConsent = getCookie('cookie_consent');

  if (!savedConsent) {
    banner.hidden = false;
    updateCookieBannerOffset(banner);
    document.body.classList.add('has-cookie-banner');
  } else {
    applyCookieConsent(decodeURIComponent(savedConsent));
    document.body.classList.remove('has-cookie-banner');
  }

  acceptBtn.addEventListener('click', () => {
    setCookie('cookie_consent', 'all', 180);
    banner.hidden = true;
    document.body.classList.remove('has-cookie-banner');
    applyCookieConsent('all');
  });

  necessaryBtn.addEventListener('click', () => {
    setCookie('cookie_consent', 'necessary', 180);
    banner.hidden = true;
    document.body.classList.remove('has-cookie-banner');
  });

  window.addEventListener('resize', () => {
    if (!banner.hidden) {
      updateCookieBannerOffset(banner);
    }
  });
});

(function initCalculator() {
  const calcData = window.calcData || { services: {} };

  const serviceEl = document.getElementById('calc-service');
  const sqmEl = document.getElementById('calc-sqm');
  const frequencyWrap = document.getElementById('calc-frequency-wrap');
  const frequencyList = document.getElementById('calc-frequency-list');
  const supplementsWrap = document.getElementById('calc-supplements-wrap');
  const supplementsList = document.getElementById('calc-supplements-list');
  const bookingWrap = document.getElementById('calc-booking-wrap');
  const bookingDateEl = document.getElementById('calc-booking-date');
  const bookingStatusEl = document.getElementById('calc-booking-status');
  const bookingSlotsEl = document.getElementById('calc-booking-slots');

  const totalEl = document.getElementById('calc-total-price');
  const messageEl = document.getElementById('calc-message');
  const applyBtn = document.getElementById('calc-apply');

  const summaryService = document.getElementById('calc-summary-service');
  const summarySqm = document.getElementById('calc-summary-sqm');
  const summaryFrequencyRow = document.getElementById('calc-summary-frequency-row');
  const summaryFrequency = document.getElementById('calc-summary-frequency');
  const summarySupplements = document.getElementById('calc-summary-supplements');
  const summarySlotRow = document.getElementById('calc-summary-slot-row');
  const summarySlot = document.getElementById('calc-summary-slot');

  const formServiceEl = document.getElementById('contact-service');
  const calculatorSummaryInput = document.getElementById('calculator-summary-input');
  const bookingSlotIdInput = document.getElementById('booking-slot-id-input');
  const bookingDateInput = document.getElementById('booking-date-input');
  const bookingTimeFromInput = document.getElementById('booking-time-from-input');
  const bookingTimeToInput = document.getElementById('booking-time-to-input');
  const bookingCustomerFields = document.getElementById('booking-customer-fields');
  const personnummerInput = document.getElementById('booking-personnummer');
  const addressInput = document.getElementById('booking-address');

  if (!serviceEl || !sqmEl || !totalEl || !applyBtn) return;

  const submitBtn = form?.querySelector('button[type="submit"]');
  const submitBtnDefaultText = submitBtn ? submitBtn.textContent.trim() : 'Skicka förfrågan';

  let selectedBookingSlot = null;
  let isSubmitting = false;

  function normalizeText(value = '') {
    return String(value)
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .trim()
      .toLowerCase();
  }

  function slugify(value = '') {
    return normalizeText(value)
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }

  function isBookingService(serviceName = '') {
    return slugify(serviceName) === 'engangsstadning';
  }

  function getServiceData(serviceName) {
    return calcData.services?.[serviceName] || null;
  }

  function normalizePersonnummerValue(value = '') {
    return String(value).replace(/[^\d]/g, '');
  }

  function formatPersonnummerForDisplay(value = '') {
    const digits = normalizePersonnummerValue(value);

    if (digits.length <= 6) {
      return digits;
    }

    if (digits.length <= 10) {
      return `${digits.slice(0, 6)}-${digits.slice(6, 10)}`;
    }

    return `${digits.slice(0, 8)}-${digits.slice(8, 12)}`;
  }

  function clearPersonnummerValidity() {
    if (personnummerInput) {
      personnummerInput.setCustomValidity('');
    }
  }

  function showPersonnummerValidity(message) {
    if (!personnummerInput) return;

    personnummerInput.setCustomValidity(message);
    personnummerInput.reportValidity();
  }

  function isValidSwedishPersonnummer(value = '') {
    const digits = normalizePersonnummerValue(value);
    const isLocalHost = ['localhost', '127.0.0.1'].includes(window.location.hostname);

    if (isLocalHost) {
      return [10, 12].includes(digits.length);
    }

    if (![10, 12].includes(digits.length)) {
      return false;
    }

    const tenDigits = digits.length === 12 ? digits.slice(-10) : digits;
    let sum = 0;

    for (let i = 0; i < tenDigits.length; i += 1) {
      let number = Number(tenDigits[i]);

      if (i % 2 === 0) {
        number *= 2;

        if (number > 9) {
          number -= 9;
        }
      }

      sum += number;
    }

    return sum % 10 === 0;
  }

  function setSubmitState(loading) {
    if (!submitBtn) return;

    submitBtn.disabled = loading;
    submitBtn.classList.toggle('is-loading', loading);
    submitBtn.textContent = loading ? 'Skickar...' : submitBtnDefaultText;

    if (form) {
      form.setAttribute('aria-busy', loading ? 'true' : 'false');
    }
  }

  function clearBookingHiddenInputs() {
    if (bookingSlotIdInput) bookingSlotIdInput.value = '';
    if (bookingDateInput) bookingDateInput.value = '';
    if (bookingTimeFromInput) bookingTimeFromInput.value = '';
    if (bookingTimeToInput) bookingTimeToInput.value = '';
  }

  function resetBookingState({ clearDate = false } = {}) {
    selectedBookingSlot = null;
    clearBookingHiddenInputs();

    if (bookingSlotsEl) {
      bookingSlotsEl.innerHTML = '';
    }

    if (bookingStatusEl) {
      bookingStatusEl.textContent = '';
    }

    if (summarySlotRow) {
      summarySlotRow.hidden = true;
    }

    if (summarySlot) {
      summarySlot.textContent = '-';
    }

    if (clearDate && bookingDateEl) {
      bookingDateEl.value = '';
    }
  }

  function toggleBookingCustomerFields(serviceName = '') {
    const bookingMode = isBookingService(serviceName);

    if (bookingCustomerFields) {
      bookingCustomerFields.hidden = !bookingMode;
    }

    if (personnummerInput) {
      personnummerInput.required = bookingMode;

      if (!bookingMode) {
        personnummerInput.value = '';
        clearPersonnummerValidity();
      }
    }

    if (addressInput) {
      addressInput.required = bookingMode;

      if (!bookingMode) {
        addressInput.value = '';
      }
    }
  }

  function parseRanges(rangeString = '') {
    return String(rangeString)
      .split('|')
      .map((item) => item.trim())
      .filter(Boolean)
      .map((item) => {
        const match = item.match(/(\d+)\s*-\s*(\d+)\s*:\s*(\d+)/);

        if (!match) return null;

        return {
          min: Number(match[1]),
          max: Number(match[2]),
          price: Number(match[3]),
        };
      })
      .filter(Boolean);
  }

  function getPriceFromRanges(rangeString, sqm) {
    const ranges = parseRanges(rangeString);
    const found = ranges.find((range) => sqm >= range.min && sqm <= range.max);

    return found ? found.price : null;
  }

  function getSelectedFrequency() {
    const checked = document.querySelector('input[name="calc-frequency"]:checked');
    return checked ? checked.value : '';
  }

  function getSelectedSupplementItems() {
    return [...document.querySelectorAll('.calc-supplement:checked')].map((el) => ({
      name: el.dataset.name || '',
      price: Number(el.dataset.price || 0),
    }));
  }

  function getSupplementsTotal() {
    return getSelectedSupplementItems().reduce((sum, item) => sum + item.price, 0);
  }

  function renderFrequencyOptions(serviceName) {
    if (!frequencyWrap || !frequencyList) return;

    const service = getServiceData(serviceName);
    frequencyList.innerHTML = '';

    if (!service || service.type !== 'frequency' || !service.frequencies) {
      frequencyWrap.hidden = true;
      return;
    }

    const entries = Object.keys(service.frequencies);

    if (!entries.length) {
      frequencyWrap.hidden = true;
      return;
    }

    entries.forEach((frequency, index) => {
      const label = document.createElement('label');
      label.className = 'calc-radio';

      const input = document.createElement('input');
      input.type = 'radio';
      input.name = 'calc-frequency';
      input.value = frequency;
      input.checked = index === 0;

      const span = document.createElement('span');
      span.textContent = frequency;

      label.appendChild(input);
      label.appendChild(span);
      frequencyList.appendChild(label);
    });

    frequencyWrap.hidden = false;
  }

  function renderSupplements(serviceName) {
    if (!supplementsWrap || !supplementsList) return;

    const service = getServiceData(serviceName);
    supplementsList.innerHTML = '';

    if (!service || !service.supplements || !service.supplements.length) {
      supplementsWrap.hidden = true;
      return;
    }

    service.supplements.forEach((supplement) => {
      const label = document.createElement('label');
      label.className = 'calc-checkbox';

      const input = document.createElement('input');
      input.type = 'checkbox';
      input.className = 'calc-supplement';
      input.dataset.name = supplement.name;
      input.dataset.price = String(supplement.price || 0);

      const span = document.createElement('span');
      span.textContent = `${supplement.name} (+${supplement.price} kr)`;

      label.appendChild(input);
      label.appendChild(span);
      supplementsList.appendChild(label);
    });

    supplementsWrap.hidden = false;
  }

  function renderBookingSection(serviceName) {
    if (!bookingWrap) return;

    const bookingMode = isBookingService(serviceName);
    bookingWrap.hidden = !bookingMode;

    if (!bookingMode) {
      resetBookingState({ clearDate: true });
    }
  }

  function getCurrentPrice() {
    const serviceName = serviceEl.value;
    const sqm = Number(sqmEl.value);
    const service = getServiceData(serviceName);

    if (!serviceName || !service || !sqm || sqm <= 0) {
      return null;
    }

    if (service.type === 'frequency') {
      const selectedFrequency = getSelectedFrequency();

      if (!selectedFrequency || !service.frequencies?.[selectedFrequency]) {
        return null;
      }

      return getPriceFromRanges(service.frequencies[selectedFrequency], sqm);
    }

    return getPriceFromRanges(service.ranges, sqm);
  }

  function buildCalculatorSummary() {
    const serviceName = serviceEl.value || '-';
    const sqm = sqmEl.value ? `${sqmEl.value} m²` : '-';
    const frequency = getSelectedFrequency();
    const supplements = getSelectedSupplementItems();
    const supplementsText = supplements.length
      ? supplements.map((item) => item.name).join(', ')
      : 'Inga tillägg';

    const priceText = totalEl ? totalEl.textContent : '- kr';

    const lines = [
      'Prisberäknare:',
      `Tjänst: ${serviceName}`,
      `Kvadratmeter: ${sqm}`,
    ];

    if (frequency) {
      lines.push(`Frekvens: ${frequency}`);
    }

    lines.push(`Tillägg: ${supplementsText}`);

    if (isBookingService(serviceName) && selectedBookingSlot) {
      lines.push(`Bokad tid: ${selectedBookingSlot.date} ${selectedBookingSlot.time_from}-${selectedBookingSlot.time_to}`);
    }

    lines.push(`Pris: ${priceText}`);

    return lines.join('\n');
  }

  function syncSummary() {
    const serviceName = serviceEl.value;
    const sqm = sqmEl.value;
    const frequency = getSelectedFrequency();
    const supplements = getSelectedSupplementItems().map((item) => item.name);

    if (summaryService) {
      summaryService.textContent = serviceName || '-';
    }

    if (summarySqm) {
      summarySqm.textContent = sqm ? `${sqm} m²` : '-';
    }

    if (summaryFrequencyRow) {
      summaryFrequencyRow.hidden = !frequency;
    }

    if (summaryFrequency) {
      summaryFrequency.textContent = frequency || '-';
    }

    if (summarySupplements) {
      summarySupplements.textContent = supplements.length ? supplements.join(', ') : 'Inga tillägg';
    }

    if (summarySlotRow) {
      summarySlotRow.hidden = !selectedBookingSlot;
    }

    if (summarySlot) {
      summarySlot.textContent = selectedBookingSlot
        ? `${selectedBookingSlot.date} ${selectedBookingSlot.time_from}-${selectedBookingSlot.time_to}`
        : '-';
    }
  }

  function updateApplyButtonState(totalPrice) {
    const serviceName = serviceEl.value;
    const sqm = Number(sqmEl.value);

    if (!serviceName || !sqm || sqm <= 0 || !totalPrice) {
      applyBtn.disabled = true;
      return;
    }

    if (isBookingService(serviceName) && !selectedBookingSlot) {
      applyBtn.disabled = true;
      return;
    }

    applyBtn.disabled = false;
  }

  function updateCalculator() {
    const serviceName = serviceEl.value;
    const totalBase = getCurrentPrice();
    const supplementsTotal = getSupplementsTotal();

    syncSummary();

    if (!serviceName) {
      totalEl.textContent = '- kr';
      messageEl.textContent = 'Välj en tjänst för att börja.';
      updateApplyButtonState(null);

      if (calculatorSummaryInput) {
        calculatorSummaryInput.value = '';
      }

      return;
    }

    if (!sqmEl.value || Number(sqmEl.value) <= 0) {
      totalEl.textContent = '- kr';
      messageEl.textContent = 'Ange kvadratmeter för att se pris.';
      updateApplyButtonState(null);

      if (calculatorSummaryInput) {
        calculatorSummaryInput.value = '';
      }

      return;
    }

    if (totalBase === null) {
      totalEl.textContent = '- kr';
      messageEl.textContent = 'Vi kunde inte hitta ett pris för den valda storleken.';
      updateApplyButtonState(null);

      if (calculatorSummaryInput) {
        calculatorSummaryInput.value = '';
      }

      return;
    }

    const total = totalBase + supplementsTotal;
    totalEl.textContent = `${total} kr`;

    if (isBookingService(serviceName) && !selectedBookingSlot) {
      messageEl.textContent = 'Välj datum och därefter en ledig tid för att fortsätta.';
    } else {
      messageEl.textContent = 'Priset är klart. Du kan nu använda uppgifterna i bokningsformuläret.';
    }

    updateApplyButtonState(total);

    if (calculatorSummaryInput) {
      calculatorSummaryInput.value = buildCalculatorSummary();
    }
  }

  async function loadAvailableBookingSlots(date) {
    if (!bookingStatusEl || !bookingSlotsEl) return;

    const serviceName = serviceEl.value;

    if (!isBookingService(serviceName) || !date) {
      resetBookingState();
      updateCalculator();
      return;
    }

    resetBookingState();
    bookingStatusEl.textContent = 'Hämtar lediga tider...';

    try {
      const response = await fetch(
        `/booking-slots/available?service=${encodeURIComponent(slugify(serviceName))}&date=${encodeURIComponent(date)}`,
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
        bookingStatusEl.textContent = 'Inga lediga tider för vald dag.';
        updateCalculator();
        return;
      }

      bookingStatusEl.textContent = 'Välj en ledig tid:';

      slots.forEach((slot) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn btn-secondary';
        button.textContent = slot.label;

        button.addEventListener('click', () => {
          selectedBookingSlot = slot;

          bookingSlotsEl.querySelectorAll('button').forEach((btn) => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
          });

          button.classList.remove('btn-secondary');
          button.classList.add('btn-primary');

          if (bookingSlotIdInput) bookingSlotIdInput.value = slot.id || '';
          if (bookingDateInput) bookingDateInput.value = slot.date || '';
          if (bookingTimeFromInput) bookingTimeFromInput.value = slot.time_from || '';
          if (bookingTimeToInput) bookingTimeToInput.value = slot.time_to || '';

          bookingStatusEl.textContent = `Vald tid: ${slot.label}`;
          updateCalculator();
        });

        bookingSlotsEl.appendChild(button);
      });
    } catch (error) {
      bookingStatusEl.textContent = error.message || 'Kunde inte hämta lediga tider.';
      updateCalculator();
    }
  }

  function focusFirstInvalidField() {
    const invalidField = form?.querySelector(':invalid');

    if (invalidField && typeof invalidField.focus === 'function') {
      invalidField.focus();
    }
  }

  function focusContactForm(serviceName = '') {
    const contactSection = document.getElementById('contact');

    if (contactSection) {
      contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    window.setTimeout(() => {
      if (isBookingService(serviceName)) {
        personnummerInput?.focus();
      } else {
        form?.querySelector('input[name="name"]')?.focus();
      }
    }, 350);
  }

  function resetCalculatorForm() {
    serviceEl.value = '';
    sqmEl.value = '';

    if (frequencyList) {
      frequencyList.innerHTML = '';
    }

    if (supplementsList) {
      supplementsList.innerHTML = '';
    }

    if (frequencyWrap) {
      frequencyWrap.hidden = true;
    }

    if (supplementsWrap) {
      supplementsWrap.hidden = true;
    }

    if (bookingWrap) {
      bookingWrap.hidden = true;
    }

    resetBookingState({ clearDate: true });

    if (summaryService) summaryService.textContent = '-';
    if (summarySqm) summarySqm.textContent = '-';
    if (summaryFrequency) summaryFrequency.textContent = '-';
    if (summarySupplements) summarySupplements.textContent = 'Inga tillägg';
    if (summaryFrequencyRow) summaryFrequencyRow.hidden = true;
    if (summarySlotRow) summarySlotRow.hidden = true;

    totalEl.textContent = '- kr';
    messageEl.textContent = 'Välj en tjänst för att börja.';
    applyBtn.disabled = true;

    if (calculatorSummaryInput) {
      calculatorSummaryInput.value = '';
    }
  }

  serviceEl.addEventListener('change', () => {
    const serviceName = serviceEl.value;

    renderFrequencyOptions(serviceName);
    renderSupplements(serviceName);
    renderBookingSection(serviceName);

    updateCalculator();
  });

  sqmEl.addEventListener('input', updateCalculator);

  if (frequencyList) {
    frequencyList.addEventListener('change', updateCalculator);
  }

  if (supplementsList) {
    supplementsList.addEventListener('change', updateCalculator);
  }

  if (bookingDateEl) {
    bookingDateEl.addEventListener('change', () => {
      const selectedDate = bookingDateEl.value;
      loadAvailableBookingSlots(selectedDate);
    });
  }

  if (formServiceEl) {
    formServiceEl.addEventListener('change', () => {
      toggleBookingCustomerFields(formServiceEl.value);
    });
  }

  if (personnummerInput) {
    personnummerInput.addEventListener('input', () => {
      clearPersonnummerValidity();
    });

    personnummerInput.addEventListener('blur', () => {
      clearPersonnummerValidity();

      const formattedValue = formatPersonnummerForDisplay(personnummerInput.value);
      personnummerInput.value = formattedValue;

      if (!formattedValue) {
        return;
      }

      if (!isValidSwedishPersonnummer(formattedValue)) {
        showPersonnummerValidity('Ange ett giltigt personnummer i format YYYYMMDDXXXX eller YYMMDDXXXX.');
      }
    });
  }

  applyBtn.addEventListener('click', () => {
    const serviceName = serviceEl.value;
    const sqm = Number(sqmEl.value);
    const totalPrice = getCurrentPrice();

    if (!serviceName || !sqm || sqm <= 0 || totalPrice === null) {
      messageEl.textContent = 'Fyll i tjänst och kvadratmeter först.';
      return;
    }

    if (isBookingService(serviceName) && !selectedBookingSlot) {
      messageEl.textContent = 'Välj datum och ledig tid innan du går vidare.';
      return;
    }

    if (formServiceEl) {
      formServiceEl.value = serviceName;
      toggleBookingCustomerFields(serviceName);
    }

    if (calculatorSummaryInput) {
      calculatorSummaryInput.value = buildCalculatorSummary();
    }

    focusContactForm(serviceName);
  });

  resetCalculatorForm();
  toggleBookingCustomerFields(formServiceEl ? formServiceEl.value : '');

  if (form) {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      if (isSubmitting) {
        return;
      }

      clearPersonnummerValidity();
      setStatus('', '');

      const selectedService = String(formServiceEl?.value || form.querySelector('select[name="service"]')?.value || '').trim();
      const currentBookingDate = bookingDateInput?.value || '';
      const currentBookingLabel = selectedBookingSlot
        ? `${selectedBookingSlot.date} ${selectedBookingSlot.time_from}-${selectedBookingSlot.time_to}`
        : '';

      if (!form.reportValidity()) {
        focusFirstInvalidField();
        setStatus('Kontrollera formuläret och fyll i alla obligatoriska fält korrekt.', 'is-error', { scroll: true });
        return;
      }

      if (isBookingService(selectedService)) {
        const rawPersonnummer = personnummerInput?.value || '';
        const normalizedPersonnummer = normalizePersonnummerValue(rawPersonnummer);

        if (personnummerInput) {
          personnummerInput.value = normalizedPersonnummer;
        }

        if (!String(bookingSlotIdInput?.value || '').trim()) {
          setStatus('Välj först datum och en ledig tid i prisberäknaren.', 'is-error', { scroll: true });
          bookingDateEl?.focus();
          return;
        }

        if (!normalizedPersonnummer) {
          const message = 'Personnummer är obligatoriskt för RUT-avdrag.';
          setStatus(message, 'is-error', { scroll: true });
          showPersonnummerValidity(message);
          personnummerInput?.focus();
          return;
        }

        if (!isValidSwedishPersonnummer(normalizedPersonnummer)) {
          const message = 'Ange ett giltigt personnummer i format YYYYMMDDXXXX eller YYMMDDXXXX.';
          setStatus(message, 'is-error', { scroll: true });
          showPersonnummerValidity(message);
          personnummerInput?.focus();
          return;
        }

        if (!String(addressInput?.value || '').trim()) {
          setStatus('Fyll i adress för att fortsätta bokningen.', 'is-error', { scroll: true });
          addressInput?.focus();
          return;
        }
      }

      const formData = new FormData(form);

      if (calculatorSummaryInput && calculatorSummaryInput.value) {
        formData.set('calculator_summary', calculatorSummaryInput.value);
      }

      isSubmitting = true;
      setSubmitState(true);

      try {
        const wasBookingService = isBookingService(selectedService);

        await submitToServer(formData);

        form.reset();
        toggleBookingCustomerFields('');

        if (formServiceEl) {
          formServiceEl.value = '';
        }

        resetCalculatorForm();

        if (wasBookingService) {
          const successText = currentBookingLabel
            ? `Tack! Din bokningsförfrågan har skickats för ${currentBookingLabel}. Vi bekräftar tiden så snart som möjligt.`
            : 'Tack! Din bokningsförfrågan har skickats. Vi bekräftar tiden så snart som möjligt.';

          setStatus(successText, 'is-success', { scroll: true });
        } else {
          setStatus(
            'Tack! Din förfrågan har skickats. Vi återkommer så snart som möjligt.',
            'is-success',
            { scroll: true }
          );
        }
      } catch (error) {
        const message = error.message || 'Kunde inte skicka formuläret till servern.';
        setStatus(message, 'is-error', { scroll: true });

        if (
          isBookingService(selectedService) &&
          currentBookingDate &&
          /ledig|tillgänglig|bookad|slot|tid/i.test(message)
        ) {
          await loadAvailableBookingSlots(currentBookingDate);
        }
      } finally {
        isSubmitting = false;
        setSubmitState(false);
      }
    });
  }
})();