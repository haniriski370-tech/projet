document.addEventListener('DOMContentLoaded', () => {
  const list = document.querySelector('.checkout-items');
  const totalEl = document.querySelector('.checkout-total');
  const proceedBtn = document.getElementById('proceedPayment');

  const cart = JSON.parse(localStorage.getItem('cartItems') || '[]');

  function render() {
    list.innerHTML = '';
    let total = 0;
    if (cart.length === 0) {
      list.innerHTML = '<li>Your cart is empty</li>';
    }
    cart.forEach(i => {
      total += i.price;
      const li = document.createElement('li');
      li.style.padding = '.6rem 0';
      li.innerHTML = `<strong>${i.title}</strong> — $${i.price.toFixed(2)}`;
      list.appendChild(li);
    });
    totalEl.textContent = `$${total.toFixed(2)}`;
  }

  render();

  function showToast(message, timeout = 1200) {
    let t = document.querySelector('.toast');
    if (!t) {
      t = document.createElement('div');
      t.className = 'toast';
      t.setAttribute('role', 'alert');
      t.setAttribute('aria-live', 'assertive');
      document.body.appendChild(t);
    }
    t.textContent = message;
    t.classList.add('show');
    clearTimeout(t._hideTimer);
    t._hideTimer = setTimeout(() => t.classList.remove('show'), timeout + 200);
  }

  proceedBtn.addEventListener('click', () => {
    // require login before proceeding
    const currentUser = localStorage.getItem('currentUser');
    if (!currentUser) {
      // save desired destination and come back after login
      localStorage.setItem('postLoginRedirect', 'checkout.php');
      showToast('Please log in to continue to checkout', 1000);
      setTimeout(() => window.location.href = 'auth/login.php', 1050);
      return;
    }
    // save a snapshot of checkout items and total
    localStorage.setItem('checkoutItems', JSON.stringify(cart));
    localStorage.setItem('checkoutTotal', totalEl.textContent);
    // navigate to payment selection
    window.location.href = 'payment.php';
  });
});