<?php
session_start();

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['postLoginRedirect'] = 'checkout.php';
    header('Location: auth/login.php');
    exit;
}

// Remove item if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_index'])) {
    $removeIndex = intval($_POST['remove_index']);
    if (isset($_SESSION['cart'][$removeIndex])) {
        unset($_SESSION['cart'][$removeIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
    }
    header('Location: checkout.php'); // Refresh page
    exit;
}

// Check for empty cart
if (empty($_SESSION['cart'])) {
    header('Location: project2.php');
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * ($item['quantity'] ?? 1);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - GameDeal</title>
<link rel="stylesheet" href="assets/css/checkout.css">
<style>
/* Minimal style for remove icon */
.remove-item-btn {
    background: none;
    border: none;
    color: #ff0000;
    font-weight: bold;
    font-size: 20px;
    cursor: pointer;
    margin-left: 10px;
}

.remove-item-btn:hover {
    color: #cc0000;
}
</style>
</head>
<body>

<header class="navbar">
  <div class="inner">
    <h1 class="logo"><a href="project.php">GameDeaL</a></h1>
  </div>
</header>

<main class="checkout-page">
    <h2>Review your order</h2>

    <ul class="checkout-items">
    <?php foreach ($cart as $index => $item): ?>
        <li>
            <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            <div class="item-meta">
                <div class="title"><?= htmlspecialchars($item['title']) ?></div>
                <div class="price-qty">
                    $<?= number_format($item['price'],2) ?> — Qty: <?= $item['quantity'] ?? 1 ?>
                </div>
            </div>
            <!-- REMOVE ICON -->
            <form method="post" style="display:inline;">
                <input type="hidden" name="remove_index" value="<?= $index ?>">
                <button type="submit" class="remove-item-btn" title="Remove item">&times;</button>
            </form>
        </li>
    <?php endforeach; ?>
    </ul>

    <div class="checkout-summary">
        Total: $<?= number_format($total,2) ?>
    </div>

    <form method="post" action="payment.php">
        <input type="hidden" name="total" value="<?= $total ?>">
        <button type="submit" class="checkout-btn">Proceed to Payment</button>
    </form>

    <a href="project2.php" class="continue-shopping">Continue shopping</a>
</main>

</body>
</html>
