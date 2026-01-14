<?php
session_start();
include 'includes/db-connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GameDeal - Shop</title>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
.product-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 8px;
}
</style>
</head>

<body>

<header class="navbar">
  <div class="inner">
    <h1 class="logo"><a href="project.php">GameDeaL</a></h1>
    <div class="nav-actions">
      <a class="home" href="project.php">Home</a>
      <a class="login-link" href="auth/login.php">Login</a>
      <button class="cart-btn" id="cartBtn">
        🛒 Cart <span id="cartCount">0</span>
      </button>
    </div>
  </div>
</header>

<section class="products">
  <h2>All Products</h2>
  <div class="product-all">

<?php
$result = $connect->query("SELECT * FROM products ORDER BY id DESC");
while ($p = $result->fetch_assoc()):
?>

<div class="product-card">
  <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
  <h3><?= htmlspecialchars($p['title']) ?></h3>
  <p class="price">$<?= number_format($p['price'], 2) ?></p>

  <!-- ✅ MATCHES CART SYSTEM -->
  <button class="cart-button"
    data-title="<?= htmlspecialchars($p['title']) ?>"
    data-price="<?= $p['price'] ?>"
    data-img="<?= htmlspecialchars($p['image_url']) ?>"
  >
    <span class="add-to-cart">ADD TO CART</span>
    <span class="added">ADDED</span>
    <i class="fas fa-shopping-cart"></i>
    <i class="fas fa-box"></i>
  </button>
</div>

<?php endwhile; ?>

  </div>
</section>

<footer>
  <p class="price">© 2025 GameDeaL. All rights reserved.</p>
</footer>

<!-- CART SIDEBAR -->
<aside class="cart-sidebar" id="cartSidebar">
  <div class="cart-sidebar-header">
    <h3>Your Cart</h3>
    <button id="closeCart" class="close-cart">
  <i class="fas fa-times"></i>
</button>

    
  </div>

  <ul class="cart-items"></ul>

  <div class="cart-footer">
    <div class="total">
      Total: <span class="total-amount">$0.00</span>
    </div>
    <a class="checkout-btn" href="checkout.php">Checkout</a>
  </div>
</aside>

<!-- ✅ ONLY ONE SCRIPT -->
<script src="assets/js/cart-animation.js"></script>

</body>
</html>
