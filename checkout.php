<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout - GameDeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="navbar">
    <div class="inner">
      <h1 class="logo"><a href="project.html">GameDeaL</a></h1>
      <div class="nav-actions">
        <a class="home" href="project.html">Home</a>
        <button class="cart-btn" id="cartBtn">🛒 Cart</button>
      </div>
    </div>
  </header>

  <main class="checkout-page" style="padding:2rem;">
    <h2>Review your order</h2>
    <ul class="checkout-items"></ul>
    <div class="checkout-summary">
      <p>Total: <span class="checkout-total">$0.00</span></p>
    </div>

    <div style="margin-top:1rem;">
      <button id="proceedPayment" class="checkout-btn">Proceed to Payment</button>
      <a href="project2.html" style="margin-left:1rem;">Continue shopping</a>
    </div>
  </main>

  <script src="assets/js/checkout.js"></script>
</body>
</html>