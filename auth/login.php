
<?php
session_start();
include '../includes/db-connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = "Email and password are required.";
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = $connect->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // إذا كنت تستخدم password_hash() استعمل password_verify
            // if (!password_verify($password, $user['password'])) { ... }
            if ($password === $user['password']) { // نصي مؤقت
                $_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['username'];
$_SESSION['role'] = $user['role']; // إضافة الدور في الجلسة

if ($user['role'] === 'admin') {
    $_SESSION['admin_logged_in'] = true;
    header("Location: ../admin/index.php");
    exit;
}


                // تحميل السلة للمستخدم العادي
                $user_id = $_SESSION['user_id'];
                $sqlCart = "SELECT p.title, p.price, p.image_url as img, ci.quantity 
                            FROM carts c 
                            JOIN carts_items ci ON c.id = ci.cart_id 
                            JOIN products p ON ci.product_id = p.id 
                            WHERE c.user_id = $user_id 
                            ORDER BY c.created_at DESC 
                            LIMIT 1";
                $resultCart = $connect->query($sqlCart);
                $cart = [];
                if ($resultCart) {
                    while ($row = $resultCart->fetch_assoc()) {
                        for ($i = 0; $i < $row['quantity']; $i++) {
                            $cart[] = [
                                'title' => $row['title'], 
                                'price' => $row['price'], 
                                'img' => $row['image_url']
                            ];
                        }
                    }
                }
                $_SESSION['cart'] = $cart;

                // إعادة التوجيه
                if (isset($_SESSION['postLoginRedirect'])) {
                    $redirect = $_SESSION['postLoginRedirect'];
                    unset($_SESSION['postLoginRedirect']);
                    header("Location: ../$redirect");
                } else {
                    header("Location: ../project.php");
                }
                exit;
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Account not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - GameDeal</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
  <header class="navbar">
    <div class="inner">
      <h1 class="logo"><a href="../project.php">GameDeaL</a></h1>
      <div class="nav-actions">
        <a class="home" href="../project.php">Home</a>
        <a class="home" href="signup.php" style="margin-left:12px">Sign up</a>
      </div>
    </div>
  </header>

  <main class="auth-page">
    <div class="auth-card">
      <h2>Log in</h2>
      <?php if (!empty($message)): ?>
        <div class="auth-message" style="color:#c00; margin-bottom:10px;">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>
      <form id="loginForm" method="POST">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required placeholder="you@example.com">

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <button type="submit" class="checkout-btn">Log in</button>
      </form>
      <p class="muted">No account? <a href="signup.php">Create one</a></p>
    </div>
  </main>
</body>
</html>
