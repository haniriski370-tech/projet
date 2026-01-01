document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.cart-button');
  const cartSidebar = document.getElementById('cartSidebar');
  const headerCartBtn = document.getElementById('cartBtn');
  const cartCount = document.getElementById('cartCount');
  const cartItemsList = document.querySelector('.cart-items');
  const totalAmountEl = document.querySelector('.total-amount');
  const closeCart = document.getElementById('closeCart');

  let cart = JSON.parse(localStorage.getItem('cartItems') || '[]');

  function saveCart() {
    localStorage.setItem('cartItems', JSON.stringify(cart));
  }

  function renderCart() {
    if (!cartItemsList) return;
    cartItemsList.innerHTML = '';
    let total = 0;
    if (cart.length === 0) {
      cartItemsList.innerHTML = '<li class="empty">Cart is empty</li>';
    }
    cart.forEach((item, idx) => {
      total += item.price;
      const li = document.createElement('li');
      li.className = 'cart-item';
      li.innerHTML = `
        <img src="${item.img}" alt="">
        <div class="meta">
          <div class="title">${item.title}</div>
          <div class="price">$${item.price.toFixed(2)}</div>
        </div>
        <button class="remove" data-index="${idx}">Remove</button>
      `;
      cartItemsList.appendChild(li);
    });
    if (totalAmountEl) totalAmountEl.textContent = `$${total.toFixed(2)}`;
    if (cartCount) cartCount.textContent = cart.length;
  }

  // add/remove item handlers in sidebar
  if (cartItemsList) {
    cartItemsList.addEventListener('click', (e) => {
      if (e.target.classList.contains('remove')) {
        const idx = Number(e.target.dataset.index);
        if (!Number.isNaN(idx)) {
          cart.splice(idx, 1);
          saveCart();
          renderCart();
        }
      }
    });
  }

  // Cart button click: animate + add item
  buttons.forEach(btn => {
    btn.addEventListener('click', (ev) => {
      btn.classList.add('clicked');
      setTimeout(() => btn.classList.remove('clicked'), 1700);

      const card = btn.closest('.product-card');
      if (!card) return;
      const title = card.querySelector('h3')?.innerText?.trim() || 'Product';
      const priceText = card.querySelector('.price')?.innerText || '0';
      const price = parseFloat(priceText.replace(/[^0-9.-]+/g, '')) || 0;
      const img = card.querySelector('img')?.src || '';

      cart.push({ title, price, img });
      saveCart();
      renderCart();
    });
  });

  // open/close sidebar behavior
  function openCart() {
    if (cartSidebar) cartSidebar.classList.add('open');
  }
  function closeCartFn() {
    if (cartSidebar) cartSidebar.classList.remove('open');
  }

  if (headerCartBtn) {
    headerCartBtn.addEventListener('mouseenter', openCart);
    headerCartBtn.addEventListener('focus', openCart);
    headerCartBtn.addEventListener('click', () => {
      if (cartSidebar) cartSidebar.classList.toggle('open');
    });
    headerCartBtn.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!cartSidebar.matches(':hover')) closeCartFn();
      }, 200);
    });
  }

  if (cartSidebar) {
    cartSidebar.addEventListener('mouseleave', closeCartFn);
    // intercept checkout link in sidebar: require login (use delegation + toast)
    cartSidebar.addEventListener('click', (e) => {
      const link = e.target.closest && e.target.closest('a.checkout-btn');
      if (!link) return;
      const currentUser = localStorage.getItem('currentUser');
      if (!currentUser) {
        e.preventDefault();
        localStorage.setItem('postLoginRedirect', 'checkout.php');
          showToast('Please log in to place your order', 1000, '.products');
          setTimeout(() => window.location.href = 'auth/login.php', 1050);
      }
    });
  }

  // small toast helper
   function showToast(msg, timeout = 1200, targetSelector) {
      let t = document.querySelector('.toast');
      if (!t) {
        t = document.createElement('div');
        t.className = 'toast';
        t.setAttribute('role', 'alert');
        t.setAttribute('aria-live', 'assertive');
        document.body.appendChild(t);
      }
      // reset inline positioning
      t.style.left = '';
      t.style.top = '';
      t.style.transform = '';

      if (targetSelector) {
        const target = document.querySelector(targetSelector);
        if (target) {
          const rect = target.getBoundingClientRect();
          const left = rect.left + rect.width / 2;
          const top = rect.top + 8; // small offset from top of section
          t.style.left = `${left}px`;
          t.style.top = `${top}px`;
          t.style.transform = 'translate(-50%, 0)';
        }
      }

      t.textContent = msg;
      t.classList.add('show');
      clearTimeout(t._hideTimer);
      t._hideTimer = setTimeout(() => t.classList.remove('show'), timeout + 200);
   }

  if (closeCart) closeCart.addEventListener('click', closeCartFn);

  renderCart();
});