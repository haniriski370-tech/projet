
<?php
session_start();
include 'includes/db-connect.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['postLoginRedirect'] = 'checkout.php';
    header('Location: auth/login.php');
    exit;
}

// Check for empty cart
if (empty($_SESSION['cart'])) {
    header('Location: project2.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total = 0;

// Calculate total
foreach ($cart as $item) {
    $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
}

// Process payment on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? 'visa';

    // Create order
    $stmt = $connect->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'paid')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order items
    $stmtItem = $connect->prepare("INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        if (!isset($item['id'])) continue;

        $product_id = intval($item['id']);
        $price = floatval($item['price']);
        $quantity = intval($item['quantity'] ?? 1);

        $stmtItem->bind_param("iidi", $order_id, $product_id, $price, $quantity);
        $stmtItem->execute();
    }
    $stmtItem->close();

    // Insert payment record
    $stmtPay = $connect->prepare("INSERT INTO payments (order_id, method, amount, status) VALUES (?, ?, ?, 'success')");
    $stmtPay->bind_param("isd", $order_id, $method, $total);
    $stmtPay->execute();
    $stmtPay->close();

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect
    header('Location: project.php?payment=success');
    exit;
}
?>