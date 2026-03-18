const navToggle = document.querySelector('[data-nav-toggle]');
const siteNav = document.querySelector('#site-nav');
const navLinks = document.querySelectorAll('#site-nav a');
const form = document.querySelector('#booking-form');
const statusEl = document.querySelector('#form-status');
const yearEl = document.querySelector('#year');

function setStatus(message = '', type = '') {
  if (!statusEl) return;

  statusEl.textContent = message;
  statusEl.className = 'form-status';

  if (type) {
    statusEl.classList.add(type);
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

    if (response.status === 422 && data?.errors) {
      const firstValidationError = Object.values(data.errors).flat()[0];
      throw new Error(firstValidationError || 'Kontrollera formuläret och försök igen.');
    }

    throw new Error(data.message || data.error || 'Något gick fel när formuläret skickades.');
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

const calcData = window.calcData || { services: {} };

function parsePriceRanges(rangeString) {
  if (!rangeString) return [];

  return rangeString.split('|').map((item) => {
    const [rangePart, pricePart] = item.split(':').map((v) => v.trim());
    const [min, max] = rangePart.split('-').map((v) => Number(v.trim()));

    return {
      min,
      max,
      price: Number(pricePart),
    };
  });
}

function getPriceFromRanges(rangeString, sqm) {
  const ranges = parsePriceRanges(rangeString);
  const match = ranges.find((item) => sqm >= item.min && sqm <= item.max);

  return match ? match.price : null;
}

function formatPrice(value) {
  return new Intl.NumberFormat('sv-SE').format(value);
}

document.addEventListener('DOMContentLoaded', () => {
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

  const bookingForm = document.getElementById('booking-form');
  const bookingServiceSelect = bookingForm?.querySelector('select[name="service"]');

  const bookingCustomerFields = document.getElementById('booking-customer-fields');
  const bookingSlotIdInput = document.getElementById('booking-slot-id-input');
  const bookingDateInput = document.getElementById('booking-date-input');
  const bookingTimeFromInput = document.getElementById('booking-time-from-input');
  const bookingTimeToInput = document.getElementById('booking-time-to-input');

  const bookingPersonnummerInput = document.getElementById('booking-personnummer');
  const bookingAddressInput = document.getElementById('booking-address');

  const totalEl = document.getElementById('calc-total-price');
  const messageEl = document.getElementById('calc-message');
  const applyBtn = document.getElementById('calc-apply');

  const summaryService = document.getElementById('calc-summary-service');
  const summarySqm = document.getElementById('calc-summary-sqm');
  const summaryFrequencyRow = document.getElementById('calc-summary-frequency-row');
  const summaryFrequency = document.getElementById('calc-summary-frequency');
  const summarySupplements = document.getElementById('calc-summary-supplements');

  const bookingServiceName = 'Engångsstädning';
  const bookingServiceSlug = 'engangsstadning';

  let selectedBookingSlot = null;

  function getSelectedFrequency() {
    const checked = document.querySelector('input[name="calc-frequency"]:checked');
    return checked ? checked.value : '';
  }

  function isBookingService(serviceName) {
    return serviceName === bookingServiceName;
  }

  function normalizePersonnummerValue(value) {
    return (value || '').replace(/[^\d]/g, '');
  }

  function formatPersonnummerForDisplay(value) {
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
    if (bookingPersonnummerInput) {
      bookingPersonnummerInput.setCustomValidity('');
    }
  }

  function showPersonnummerValidity(message) {
    if (!bookingPersonnummerInput) return;

    bookingPersonnummerInput.setCustomValidity(message);
    bookingPersonnummerInput.reportValidity();
  }

  function isValidSwedishPersonnummer(value) {
  const digits = normalizePersonnummerValue(value);

  // На локальной разработке разрешаем любой номер длиной 10 или 12 цифр
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

  function toggleBookingCustomerFields(serviceName) {
    const isBooking = isBookingService(serviceName);

    if (bookingCustomerFields) {
      bookingCustomerFields.hidden = !isBooking;
    }

    if (bookingPersonnummerInput) {
      bookingPersonnummerInput.required = isBooking;
    }

    if (bookingAddressInput) {
      bookingAddressInput.required = isBooking;
    }

    if (!isBooking) {
      if (bookingSlotIdInput) bookingSlotIdInput.value = '';
      if (bookingDateInput) bookingDateInput.value = '';
      if (bookingTimeFromInput) bookingTimeFromInput.value = '';
      if (bookingTimeToInput) bookingTimeToInput.value = '';
    }

    clearPersonnummerValidity();
  }

  function resetBookingState(options = {}) {
    const { clearDate = false } = options;

    selectedBookingSlot = null;

    if (bookingSlotsEl) {
      bookingSlotsEl.innerHTML = '';
    }

    if (bookingStatusEl) {
      bookingStatusEl.textContent = '';
    }

    if (clearDate && bookingDateEl) {
      bookingDateEl.value = '';
    }

    if (bookingSlotIdInput) bookingSlotIdInput.value = '';
    if (bookingDateInput) bookingDateInput.value = '';
    if (bookingTimeFromInput) bookingTimeFromInput.value = '';
    if (bookingTimeToInput) bookingTimeToInput.value = '';
  }

  function renderFrequencies(serviceName) {
    if (!frequencyList || !frequencyWrap) return;

    const service = calcData.services[serviceName];
    const selectedFrequency = getSelectedFrequency();

    frequencyList.innerHTML = '';

    if (!service || service.type !== 'frequency' || !service.frequencies) {
      frequencyWrap.hidden = true;
      return;
    }

    const frequencyNames = Object.keys(service.frequencies);

    if (!frequencyNames.length) {
      frequencyWrap.hidden = true;
      return;
    }

    frequencyNames.forEach((frequencyName) => {
      const label = document.createElement('label');
      label.className = 'calc-radio';

      const isChecked = selectedFrequency === frequencyName ? 'checked' : '';

      label.innerHTML = `
        <input type="radio" name="calc-frequency" value="${frequencyName}" ${isChecked}>
        <span>${frequencyName}</span>
      `;

      frequencyList.appendChild(label);
    });

    frequencyWrap.hidden = false;
  }

  async function loadAvailableBookingSlots(date) {
    if (!bookingStatusEl || !bookingSlotsEl) return;

    resetBookingState();

    bookingStatusEl.textContent = 'Laddar lediga tider...';

    try {
      const response = await fetch(
        `/booking-slots/available?service=${encodeURIComponent(bookingServiceSlug)}&date=${encodeURIComponent(date)}`,
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

          if (applyBtn) {
            applyBtn.disabled = false;
          }

          updateCalculator();
        });

        bookingSlotsEl.appendChild(button);
      });
    } catch (error) {
      bookingStatusEl.textContent = error.message || 'Kunde inte hämta lediga tider.';
    }
  }

  function renderSupplements(serviceName) {
    if (!supplementsList || !supplementsWrap) return;

    const service = calcData.services[serviceName];
    const selectedSupplements = new Set(
      [...document.querySelectorAll('.calc-supplement:checked')].map((el) => el.dataset.name)
    );

    supplementsList.innerHTML = '';

    if (!service || !service.supplements || !service.supplements.length) {
      supplementsWrap.hidden = true;
      return;
    }

    service.supplements.forEach((supplement) => {
      const label = document.createElement('label');
      label.className = 'calc-checkbox';

      const isChecked = selectedSupplements.has(supplement.name) ? 'checked' : '';

      label.innerHTML = `
        <input
          type="checkbox"
          class="calc-supplement"
          data-price="${supplement.price}"
          data-name="${supplement.name}"
          ${isChecked}
        >
        <span>${supplement.name}</span>
      `;

      supplementsList.appendChild(label);
    });

    supplementsWrap.hidden = false;
  }

  function updateCalculator() {
    if (!serviceEl || !sqmEl || !totalEl) return;

    const serviceName = serviceEl.value;
    const sqm = Number(sqmEl.value || 0);
    const service = calcData.services[serviceName];

    if (summaryService) summaryService.textContent = serviceName || '-';
    if (summarySqm) summarySqm.textContent = sqm > 0 ? `${sqm} m²` : '-';
    if (summaryFrequency) summaryFrequency.textContent = '-';
    if (summarySupplements) summarySupplements.textContent = '-';
    if (summaryFrequencyRow) summaryFrequencyRow.hidden = true;
    if (messageEl) messageEl.textContent = '';
    if (applyBtn) applyBtn.disabled = true;

    if (!serviceName) {
      totalEl.textContent = '- kr';

      if (frequencyWrap) frequencyWrap.hidden = true;
      if (supplementsWrap) supplementsWrap.hidden = true;
      if (bookingWrap) bookingWrap.hidden = true;
      if (frequencyList) frequencyList.innerHTML = '';

      resetBookingState({ clearDate: true });
      toggleBookingCustomerFields('');

      return;
    }

    renderFrequencies(serviceName);
    renderSupplements(serviceName);

    if (isBookingService(serviceName)) {
      if (frequencyWrap) frequencyWrap.hidden = true;
      if (bookingWrap) bookingWrap.hidden = false;

      totalEl.textContent = 'Pris enligt offert';

      if (selectedBookingSlot) {
        if (messageEl) {
          messageEl.textContent = `Vald tid: ${selectedBookingSlot.date} ${selectedBookingSlot.label}`;
        }

        if (applyBtn) {
          applyBtn.disabled = false;
        }
      } else {
        if (messageEl) {
          messageEl.textContent = 'Välj datum och därefter en ledig tid.';
        }

        if (applyBtn) {
          applyBtn.disabled = true;
        }
      }

      toggleBookingCustomerFields(serviceName);
      return;
    }

    if (bookingWrap) bookingWrap.hidden = true;

    resetBookingState({ clearDate: true });
    toggleBookingCustomerFields('');

    let basePrice = null;
    let frequencyText = '';

    if (service.type === 'frequency') {
      frequencyText = getSelectedFrequency();

      if (summaryFrequencyRow) summaryFrequencyRow.hidden = false;
      if (summaryFrequency) summaryFrequency.textContent = frequencyText || '-';

      if (!frequencyText) {
        totalEl.textContent = '- kr';
        if (messageEl) messageEl.textContent = 'Välj hur ofta tjänsten ska utföras.';
        return;
      }

      if (sqm <= 0) {
        totalEl.textContent = '- kr';
        if (messageEl) messageEl.textContent = 'Ange antal kvadratmeter.';
        return;
      }

      basePrice = getPriceFromRanges(service.frequencies[frequencyText], sqm);
    } else {
      if (frequencyWrap) frequencyWrap.hidden = true;

      if (sqm <= 0) {
        totalEl.textContent = '- kr';
        if (messageEl) messageEl.textContent = 'Ange antal kvadratmeter.';
        return;
      }

      basePrice = getPriceFromRanges(service.ranges, sqm);
    }

    if (basePrice === null) {
      totalEl.textContent = '- kr';
      if (messageEl) messageEl.textContent = 'Kontakta oss för personlig offert för denna storlek.';
      return;
    }

    const checkedSupplements = [...document.querySelectorAll('.calc-supplement:checked')];
    const supplementsTotal = checkedSupplements.reduce((sum, el) => sum + Number(el.dataset.price || 0), 0);
    const supplementsNames = checkedSupplements.map((el) => el.dataset.name);

    const total = basePrice + supplementsTotal;

    totalEl.textContent = `${formatPrice(total)} kr`;

    if (summarySupplements) {
      summarySupplements.textContent = supplementsNames.length ? supplementsNames.join(', ') : '-';
    }

    if (applyBtn) {
      applyBtn.disabled = false;
    }
  }

  if (serviceEl) {
    serviceEl.addEventListener('change', updateCalculator);
  }

  if (sqmEl) {
    sqmEl.addEventListener('input', updateCalculator);
  }

  bookingPersonnummerInput?.addEventListener('input', () => {
    clearPersonnummerValidity();

    const cursorAtEnd =
      bookingPersonnummerInput.selectionStart === bookingPersonnummerInput.value.length;

    const formatted = formatPersonnummerForDisplay(bookingPersonnummerInput.value);

    bookingPersonnummerInput.value = formatted;

    if (cursorAtEnd) {
      const pos = bookingPersonnummerInput.value.length;
      bookingPersonnummerInput.setSelectionRange(pos, pos);
    }
  });

  bookingPersonnummerInput?.addEventListener('blur', () => {
    clearPersonnummerValidity();

    const value = bookingPersonnummerInput.value;

    if (!value) {
      return;
    }

    if (!isValidSwedishPersonnummer(value)) {
      showPersonnummerValidity('Ange ett giltigt personnummer i format YYYYMMDD-XXXX eller YYMMDD-XXXX.');
    }
  });

  bookingServiceSelect?.addEventListener('change', () => {
    toggleBookingCustomerFields(bookingServiceSelect.value || '');
  });

  bookingDateEl?.addEventListener('change', async () => {
    const serviceName = serviceEl?.value || '';
    const date = bookingDateEl.value;

    if (!isBookingService(serviceName)) {
      return;
    }

    if (!date) {
      resetBookingState();
      updateCalculator();
      return;
    }

    await loadAvailableBookingSlots(date);
    updateCalculator();
  });

  document.addEventListener('change', (event) => {
    if (event.target.matches('input[name="calc-frequency"], .calc-supplement')) {
      updateCalculator();
    }
  });

  if (applyBtn) {
    applyBtn.addEventListener('click', () => {
      const serviceName = serviceEl?.value || '';
      const sqm = sqmEl?.value || '';
      const frequency = getSelectedFrequency();
      const total = totalEl?.textContent || '- kr';
      const supplements = [...document.querySelectorAll('.calc-supplement:checked')].map((el) => el.dataset.name);

      const bookingService = document.querySelector('#booking-form select[name="service"]');
      const bookingCalculatorSummary = document.querySelector('#booking-form input[name="calculator_summary"]');

      if (!serviceName) {
        return;
      }

      if (bookingService) {
        bookingService.value = serviceName;
      }

      if (isBookingService(serviceName)) {
        if (!selectedBookingSlot) {
          if (messageEl) {
            messageEl.textContent = 'Välj först en ledig tid.';
          }

          return;
        }

        if (bookingSlotIdInput) bookingSlotIdInput.value = selectedBookingSlot.id || '';
        if (bookingDateInput) bookingDateInput.value = selectedBookingSlot.date || '';
        if (bookingTimeFromInput) bookingTimeFromInput.value = selectedBookingSlot.time_from || '';
        if (bookingTimeToInput) bookingTimeToInput.value = selectedBookingSlot.time_to || '';
      } else {
        if (bookingSlotIdInput) bookingSlotIdInput.value = '';
        if (bookingDateInput) bookingDateInput.value = '';
        if (bookingTimeFromInput) bookingTimeFromInput.value = '';
        if (bookingTimeToInput) bookingTimeToInput.value = '';
      }

      if (bookingCalculatorSummary) {
        const lines = [
          'Prisberäknare:',
          `Tjänst: ${serviceName || '-'}`,
          `Kvadratmeter: ${sqm ? `${sqm} m²` : '-'}`,
          `Hur ofta: ${frequency || '-'}`,
          `Datum: ${selectedBookingSlot?.date || '-'}`,
          `Tid: ${selectedBookingSlot?.label || '-'}`,
          `Tillägg: ${supplements.length ? supplements.join(', ') : '-'}`,
          `Beräknat pris: ${total}`,
        ];

        bookingCalculatorSummary.value = lines.join('\n');
      }

      toggleBookingCustomerFields(serviceName);

      const contactSection = document.getElementById('contact');

      if (contactSection) {
        contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      setTimeout(() => {
        if (isBookingService(serviceName)) {
          bookingPersonnummerInput?.focus();
        } else {
          const nameField = document.querySelector('#booking-form input[name="name"]');
          nameField?.focus();
        }
      }, 350);
    });
  }

  if (form) {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      clearPersonnummerValidity();
      setStatus('', '');

      const selectedService = form.querySelector('select[name="service"]')?.value || '';

      if (isBookingService(selectedService)) {
        const rawPersonnummer = bookingPersonnummerInput?.value || '';
        const normalizedPersonnummer = normalizePersonnummerValue(rawPersonnummer);

        if (bookingPersonnummerInput) {
          bookingPersonnummerInput.value = normalizedPersonnummer;
        }

        if (!bookingSlotIdInput?.value) {
          setStatus('Välj först datum och en ledig tid i kalkylatorn.', 'is-error');
          return;
        }

        if (!normalizedPersonnummer) {
          const message = 'Personnummer är obligatoriskt för RUT-avdrag.';
          setStatus(message, 'is-error');
          showPersonnummerValidity(message);
          bookingPersonnummerInput?.focus();
          return;
        }

        if (!isValidSwedishPersonnummer(normalizedPersonnummer)) {
          const message = 'Ange ett giltigt personnummer i format YYYYMMDDXXXX eller YYMMDDXXXX.';
          setStatus(message, 'is-error');
          showPersonnummerValidity(message);
          bookingPersonnummerInput?.focus();
          return;
        }
      }

      if (!form.reportValidity()) {
        setStatus('Kontrollera formuläret och fyll i alla obligatoriska fält korrekt.', 'is-error');
        return;
      }

      const formData = new FormData(form);

      try {
        await submitToServer(formData);

        form.reset();
        resetBookingState({ clearDate: true });
        toggleBookingCustomerFields('');

        if (bookingWrap) {
          bookingWrap.hidden = true;
        }

        updateCalculator();

        setStatus('Tack! Din förfrågan har skickats. Vi återkommer så snart som möjligt.', 'is-success');
      } catch (error) {
        setStatus(error.message || 'Kunde inte skicka formuläret till servern.', 'is-error');
      }
    });
  }

  toggleBookingCustomerFields(bookingServiceSelect?.value || '');
  updateCalculator();
});