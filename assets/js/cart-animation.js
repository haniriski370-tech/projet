document.addEventListener('DOMContentLoaded', () => {
  const cartSidebar = document.getElementById('cartSidebar');
  const headerCartBtn = document.getElementById('cartBtn');
  const cartCount = document.getElementById('cartCount');
  const cartItemsList = document.querySelector('.cart-items');
  const totalAmountEl = document.querySelector('.total-amount');
  const closeCart = document.getElementById('closeCart');

  let cart = [];

  async function loadCart() {
    try {
      const res = await fetch('get_cart.php');
      cart = await res.json();
      renderCart();
    } catch (e) {
      console.error(e);
    }
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
        <img src="${item.img}">
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

  /* ✅ EVENT DELEGATION — الحل الحقيقي */
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.cart-button');
    if (!btn) return;

    // Animation
    btn.classList.add('clicked');
    setTimeout(() => btn.classList.remove('clicked'), 1600);

    const card = btn.closest('.product-card');
    if (!card) return;

    const title = card.querySelector('h3')?.innerText || '';
    const price = parseFloat(
      card.querySelector('.price')?.innerText.replace(/[^\d.]/g, '')
    ) || 0;
    const img = card.querySelector('img')?.src || '';

    const formData = new FormData();
    formData.append('title', title);
    formData.append('price', price);
    formData.append('img', img);

    try {
      await fetch('add_to_cart.php', { method: 'POST', body: formData });
      await loadCart();
    } catch (err) {
      console.error(err);
    }
  });

  // Remove item
  if (cartItemsList) {
    cartItemsList.addEventListener('click', async (e) => {
      if (e.target.classList.contains('remove')) {
        const idx = e.target.dataset.index;
        await fetch(`add_to_cart.php?index=${idx}`, { method: 'DELETE' });
        loadCart();
      }
    });
  }

  function openCart() {
    cartSidebar?.classList.add('open');
  }
  function closeCartFn() {
    cartSidebar?.classList.remove('open');
  }

  headerCartBtn?.addEventListener('click', () =>
    cartSidebar.classList.toggle('open')
  );
  closeCart?.addEventListener('click', closeCartFn);

  loadCart();
});
