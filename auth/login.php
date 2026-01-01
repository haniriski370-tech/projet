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
      <h1 class="logo"><a href="project.php">GameDeaL</a></h1>
      <div class="nav-actions">
        <a class="home" href="project.php">Home</a>
        <a class="home" href="auth/signup.php" style="margin-left:12px">Sign up</a>
      </div>
    </div>
  </header>

  <main class="auth-page">
    <div class="auth-card">
      <h2>Log in</h2>
      <form id="loginForm">
        <label for="email">Email</label>
        <input id="email" type="email" required placeholder="you@example.com">

        <label for="password">Password</label>
        <input id="password" type="password" required>

        <button type="submit" class="checkout-btn">Log in</button>
      </form>
      <p class="muted">No account? <a href="auth/signup.php">Create one</a></p>
      <div id="message" class="auth-message" style="display:none"></div>
    </div>
  </main>

  <script src="../assets/js/auth.js"></script>
</body>
</html>
