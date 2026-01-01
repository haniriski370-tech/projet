document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('paymentForm');
  const cardFields = document.getElementById('cardFields');
  const paypalFields = document.getElementById('paypalFields');
  const result = document.getElementById('paymentResult');

  // toggle fields
  form.method.forEach(r => r.addEventListener('change', () => {
    if (form.method.value === 'visa') {
      cardFields.style.display = 'block';
      paypalFields.style.display = 'none';
    } else {
      cardFields.style.display = 'none';
      paypalFields.style.display = 'block';
    }
  }));

  // format card number: auto-insert dash after every 4 digits, allow max 16 digits
  const cardInput = document.getElementById('cardNumber');
  if (cardInput) {
    const formatCard = (value) => {
      // keep only digits
      const digits = value.replace(/\D/g, '').slice(0, 16); // limit to 16 digits
      // group into 4s
      const parts = [];
      for (let i = 0; i < digits.length; i += 4) parts.push(digits.substring(i, i + 4));
      return parts.join('-');
    };

    cardInput.addEventListener('input', (e) => {
      const selectionStart = cardInput.selectionStart;
      const oldValue = cardInput.value;
      const newValue = formatCard(oldValue);
      cardInput.value = newValue;

      // try to keep caret position reasonable after formatting
      let newPos = selectionStart;
      // if a dash was inserted before the caret, move forward
      const diff = newValue.length - oldValue.length;
      if (diff > 0) newPos = selectionStart + diff;
      if (newPos > newValue.length) newPos = newValue.length;
      cardInput.setSelectionRange(newPos, newPos);
    });

    // also handle paste: format pasted value
    cardInput.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
      cardInput.value = formatCard(pasted);
    });
  }

  // format expiry MM/YY: auto-insert '/' after 2 digits, limit to MMYY (4 digits)
  const expInput = document.getElementById('cardExp');
  if (expInput) {
    const formatExp = (value) => {
      const digits = value.replace(/\D/g, '').slice(0, 4); // max 4 digits
      if (digits.length <= 2) return digits;
      return digits.slice(0, 2) + '/' + digits.slice(2);
    };

    expInput.addEventListener('input', () => {
      const selStart = expInput.selectionStart;
      const oldValue = expInput.value;
      const newValue = formatExp(oldValue);
      expInput.value = newValue;

      // adjust caret
      let newPos = selStart;
      const diff = newValue.length - oldValue.length;
      if (diff > 0) newPos = selStart + diff;
      if (newPos > newValue.length) newPos = newValue.length;
      expInput.setSelectionRange(newPos, newPos);
    });

    expInput.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
      expInput.value = formatExp(pasted);
    });
  }

  // CVV: enforce digits only, max 3 chars, prevent negative
  const cvvInput = document.getElementById('cardCvv');
  if (cvvInput) {
    cvvInput.addEventListener('input', () => {
      const digits = cvvInput.value.replace(/\D/g, '').slice(0, 3);
      cvvInput.value = digits;
    });
    cvvInput.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
      cvvInput.value = pasted.replace(/\D/g, '').slice(0, 3);
    });
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    // Normally here you'd call your payment gateway API.
    // For demo: accept input and clear cart
    const checkoutItems = JSON.parse(localStorage.getItem('checkoutItems') || '[]');
    const total = localStorage.getItem('checkoutTotal') || '$0.00';

    // validation for card method (visa)
      if (form.method.value === 'visa') {
      const cardExpRaw = document.getElementById('cardExp')?.value || '';
      const digits = cardExpRaw.replace(/\D/g, '');
      if (digits.length !== 4) {
        result.style.display = 'block';
        result.innerHTML = '<strong style="color:#c00">Invalid expiry date. Use MM/YY.</strong>';
        return;
      }
      const mm = parseInt(digits.slice(0, 2), 10);
      const yy = parseInt(digits.slice(2), 10);
      // enforce ranges: mm 01-12, yy 25-45 (no negatives)
      if (isNaN(mm) || mm < 1 || mm > 12) {
        result.style.display = 'block';
        result.innerHTML = '<strong style="color:#c00">Expiry month must be between 01 and 12.</strong>';
        return;
      }
      if (isNaN(yy) || yy < 25 || yy > 45) {
        result.style.display = 'block';
        result.innerHTML = '<strong style="color:#c00">Expiry year must be between 25 and 45.</strong>';
        return;
      }
      // validate CVV (3 digits)
      const cvvVal = document.getElementById('cardCvv')?.value || '';
      if (!/^\d{3}$/.test(cvvVal)) {
        result.style.display = 'block';
        result.innerHTML = '<strong style="color:#c00">CVV must be exactly 3 digits.</strong>';
        return;
      }
    }

    // simulate success
    result.style.display = 'block';
    result.innerHTML = `<strong>Payment successful</strong><p>Amount charged: ${total}</p><p>Items: ${checkoutItems.length}</p>`;

    // clear cart data
    localStorage.removeItem('cartItems');
    localStorage.removeItem('checkoutItems');
    localStorage.removeItem('checkoutTotal');

    // update header badge on pages if user navigates back; optional redirect after short delay
    setTimeout(() => {
      window.location.href = 'project.php';
    }, 2200);
  });
});