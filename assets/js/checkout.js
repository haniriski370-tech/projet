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

  proceedBtn.addEventListener('click', () => {
    // save a snapshot of checkout items and total
    localStorage.setItem('checkoutItems', JSON.stringify(cart));
    localStorage.setItem('checkoutTotal', totalEl.textContent);
    // navigate to payment selection
    window.location.href = 'payment.html';
  });
});