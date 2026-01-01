<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment - GameDeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <header class="navbar">
    <div class="inner">
      <h1 class="logo"><a href="project.php">GameDeaL</a></h1>
      <div class="nav-actions">
        <a class="home" href="project.php">Home</a>
        <a class="login-link" href="auth/login.php">Login</a>
        <button class="cart-btn" id="cartBtn">🛒 Cart</button>
      </div>
    </div>
  </header>

  <main class="payment-page" style="padding:2rem;">
    <h2>Select payment method</h2>

    <form id="paymentForm">
      <label>
        <input type="radio" name="method" value="visa" checked>
        <i class="payment-method-icon fab fa-cc-visa" aria-hidden="true"></i>
        Pay with Visa / Card
      </label>
      <div id="cardFields" style="margin: .5rem 0 1rem 1.2rem;">
        <input type="text" id="cardNumber" placeholder="Card number" required maxlength="19" inputmode="numeric" autocomplete="cc-number" style="display:block;margin-bottom:.5rem;padding:.4rem;width:100%;max-width:360px;">
        <input type="text" id="cardName" placeholder="Name on card" required style="display:block;margin-bottom:.5rem;padding:.4rem;width:100%;max-width:360px;">
        <div style="display:flex;gap:.5rem;max-width:360px;">
          <input type="text" id="cardExp" placeholder="MM/YY" required maxlength="5" inputmode="numeric" style="flex:1;padding:.4rem;">
          <input type="text" id="cardCvv" placeholder="CVV" required maxlength="3" inputmode="numeric" pattern="\d{3}" autocomplete="cc-csc" style="width:90px;padding:.4rem;">
        </div>
      </div>

      <label style="display:block;margin-top:.5rem;">
        <input type="radio" name="method" value="paypal">
        <i class="payment-method-icon fab fa-paypal" aria-hidden="true"></i>
        Pay with PayPal
      </label>
      <div id="paypalFields" style="margin:.5rem 0 1rem 1.2rem;display:none;">
        <input type="email" id="paypalEmail" placeholder="PayPal email" style="padding:.4rem;width:100%;max-width:360px;">
      </div>

      <div style="margin-top:1rem;">
        <button type="submit" class="checkout-btn">Pay now</button>
        <a href="checkout.php" style="margin-left:1rem;">Back</a>
      </div>
    </form>

    <div id="paymentResult" style="margin-top:1rem;display:none;"></div>
  </main>

  <script src="assets/js/payment.js"></script>
</body>
</html>