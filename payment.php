<?php
session_start();
include 'includes/db-connect.php';

// Enable mysqli error reporting during development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['postLoginRedirect'] = 'checkout.php';
    header('Location: auth/login.php');
    exit;
}

// Check for empty cart
if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    header('Location: project2.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$cart    = $_SESSION['cart'];
$total   = 0.0;

// Calculate total
foreach ($cart as $item) {
    $price = isset($item['price']) ? (float)$item['price'] : 0.0;
    $qty   = isset($item['quantity']) ? (int)$item['quantity'] : 1;
    $total += $price * $qty;
}

$paymentSuccess = false;
$errorMessage   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? 'visa';

    try {
        $connect->begin_transaction();

        // Create order (no status column; uncomment if you have it)
        // $stmtOrder = $connect->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'paid')");
        $stmtOrder = $connect->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmtOrder->bind_param("id", $user_id, $total);
        $stmtOrder->execute();
        $order_id = $stmtOrder->insert_id;
        $stmtOrder->close();

        // Prepare order_items insert
        $stmtItem = $connect->prepare(
            "INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)"
        );

        foreach ($cart as $item) {
            $price    = isset($item['price']) ? (float)$item['price'] : 0.0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;

            // Prefer product id from cart; fallback to lookup by title+price
            if (isset($item['id'])) {
                $product_id = (int)$item['id'];
            } else {
                // Fallback lookup
                $title = isset($item['title']) ? $item['title'] : '';
                if ($title === '') {
                    // Skip invalid item
                    continue;
                }
                $stmtFind = $connect->prepare("SELECT id FROM products WHERE title = ? AND price = ?");
                $stmtFind->bind_param("sd", $title, $price);
                $stmtFind->execute();
                $resFind = $stmtFind->get_result();
                if ($row = $resFind->fetch_assoc()) {
                    $product_id = (int)$row['id'];
                } else {
                    // If not found, skip this item
                    $stmtFind->close();
                    continue;
                }
                $stmtFind->close();
            }

            $stmtItem->bind_param("iidi", $order_id, $product_id, $price, $quantity);
            $stmtItem->execute();
        }
        $stmtItem->close();

        // Insert payment (no status column; uncomment if you have it)
        // $stmtPay = $connect->prepare("INSERT INTO payments (order_id, method, amount, status) VALUES (?, ?, ?, 'success')");
        $stmtPay = $connect->prepare("INSERT INTO payments (order_id, method, amount) VALUES (?, ?, ?)");
        $stmtPay->bind_param("isd", $order_id, $method, $total);
        $stmtPay->execute();
        $stmtPay->close();

        // Clear latest cart items from DB (optional—kept from your old flow)
        $stmtCart = $connect->prepare("SELECT id FROM carts WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmtCart->bind_param("i", $user_id);
        $stmtCart->execute();
        $resCart = $stmtCart->get_result();
        if ($rowCart = $resCart->fetch_assoc()) {
            $cart_id = (int)$rowCart['id'];
            $stmtClear = $connect->prepare("DELETE FROM carts_items WHERE cart_id = ?");
            $stmtClear->bind_param("i", $cart_id);
            $stmtClear->execute();
            $stmtClear->close();
        }
        $stmtCart->close();

        $connect->commit();// Clear session cart and show success
        unset($_SESSION['cart']);
        $paymentSuccess = true;

    } catch (Throwable $e) {
        if ($connect->errno === 0) {
            // If transaction open, rollback
            $connect->rollback();
        }
        $errorMessage = "Payment error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment - GameDeal</title>
  <link rel="stylesheet" href="assets/css/payment.css">
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
    <h2>Select Payment Method</h2>

    <?php if (!empty($errorMessage)): ?>
      <div class="error-message" style="margin:1rem 0;padding:1rem;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;">
        <?= $errorMessage ?>
      </div>
    <?php endif; ?>

    <?php if ($paymentSuccess): ?>
      <div class="success-message" style="margin:1rem 0;padding:1rem;background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
        ✅ Your payment has been completed successfully!
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <label>
        <input type="radio" name="method" value="visa" checked>
        <i class="fab fa-cc-visa"></i> Pay with Visa / Card
      </label>
      <br>
      <label>
        <input type="radio" name="method" value="paypal">
        <i class="fab fa-paypal"></i> Pay with PayPal
      </label>
      <br><br>
      <button type="submit" class="checkout-btn">Pay $<?= number_format($total,2) ?></button>
      <a href="checkout.php" style="margin-left:1rem;">Back to Checkout</a>
    </form>

    <h3 style="margin-top:2rem;">Order Summary</h3>
    <ul>
      <?php foreach ($cart as $item): ?>
        <li>
          <?= htmlspecialchars($item['title'] ?? 'Item') ?>
          — $<?= number_format((float)($item['price'] ?? 0),2) ?>
          × <?= (int)($item['quantity'] ?? 1) ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: $<?= number_format($total,2) ?></strong></p>
  </main>
</body>
</html>