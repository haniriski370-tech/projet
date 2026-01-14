<?php
session_start();
include '../includes/db-connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['confirmPassword'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $message = "All fields are required.";
    } else {
        $check = $connect->query("SELECT * FROM users WHERE email='$email'");

        if ($check && $check->num_rows > 0) {
            $message = "Email already registered.";
        } elseif ($password !== $password_confirm) {
            $message = "Passwords do not match.";
        } else {
            $sql = "INSERT INTO users (username, email, password)
                    VALUES ('$username', '$email', '$password')";

            if ($connect->query($sql)) {
                $message = "Account created successfully. You can now log in.";
            } else {
                $message = "Error creating account.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - GameDeal</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<header class="navbar">
  <div class="inner">
    <h1 class="logo"><a href="../project.php">GameDeaL</a></h1>
    <div class="nav-actions">
      <a class="home" href="../project.php">Home</a>
      <a class="home" href="login.php" style="margin-left:12px">Login</a>
    </div>
  </div>
</header>

<main class="auth-page">
  <div class="auth-card">
    <h2>Create account</h2>

    <?php if (!empty($message)): ?>
      <div class="auth-message">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <label for="name">Full name</label>
      <input id="name" type="text" name="name" required>

      <label for="email">Email</label>
      <input id="email" type="email" name="email" required>

      <label for="password">Password</label>
      <input id="password" type="password" name="password" required minlength="6">

      <label for="confirmPassword">Confirm password</label>
      <input id="confirmPassword" type="password" name="confirmPassword" required minlength="6">

      <button type="submit" class="checkout-btn">Sign up</button>
    </form>

    <p class="muted">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</main>
</body>
</html>
