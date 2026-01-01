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
      <h1 class="logo"><a href="project.php">GameDeaL</a></h1>
      <div class="nav-actions">
        <a class="home" href="project.php">Home</a>
        <a class="home" href="auth/login.php" style="margin-left:12px">Login</a>
      </div>
    </div>
  </header>

  <main class="auth-page">
    <div class="auth-card">
      <h2>Create account</h2>
      <form id="signupForm">
        <label for="name">Full name</label>
        <input id="name" type="text" required placeholder="Your name">

        <label for="email">Email</label>
        <input id="email" type="email" required placeholder="you@example.com">

        <label for="password">Password</label>
        <input id="password" type="password" required minlength="6" placeholder="At least 6 characters">

        <label for="confirmPassword">Confirm password</label>
        <input id="confirmPassword" type="password" required minlength="6">

        <button type="submit" class="checkout-btn">Sign up</button>
      </form>
      <p class="muted">Already have an account? <a href="auth/login.php">Log in</a></p>
      <div id="message" class="auth-message" style="display:none"></div>
    </div>
  </main>

  <script src="../assets/js/auth.js"></script>
</body>
</html>
