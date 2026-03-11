<?php
session_start();

// i want to get rid of this because it is worse than useless
// we can probably integrate an actual captcha, it's not hard
// will be implementing email otp verification

// Initialise captcha numbers if not set
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) {
    $_SESSION['captcha_num1'] = rand(1, 10);
    $_SESSION['captcha_num2'] = rand(1, 10);
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <main>
    <div class="login-wrapper">
        <div class="login-left">
            <h2 class="panel-heading">Good to have<br>you <em>back.</em></h2>
            <p class="panel-desc">Sign in to browse your collection, track orders and discover new records waiting for you.</p>
            <div class="panel-stats">
                <div class="stat-item">
                    <div class="stat-number">12k+</div>
                    <div class="stat-label">Listings</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4k+</div>
                    <div class="stat-label">Sellers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">80+</div>
                    <div class="stat-label">Genres</div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="form-area">
                <p class="form-eyebrow">Welcome back</p>
                <h1 class="form-heading">Sign in</h1>
                <p class="form-sub">Enter your credentials to access your account.</p>

                <div id="error-msg" class="alert alert-danger" style="display:none;"></div>

                <div id="login-form">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <div class="input-wrap">
                            <i class="bi bi-person"></i>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 text-end">
                        <a href="forgot_password.php" class="register-prompt">Forgot password?</a>
                    </div>

                    <div class="mb-3">
                        <label for="captcha" class="form-label">
                            Security Check: <?= $_SESSION['captcha_num1'] ?> + <?= $_SESSION['captcha_num2'] ?> = ?
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-lock"></i>
                            <input type="text" name="captcha" id="captcha" class="form-control" required autocomplete="off">
                        </div>
                    </div>

                    <button type="button" id="btn-login" class="btn-login">Sign In 
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                <p class="register-prompt">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script>
    document.getElementById('btn-login').addEventListener('click', function () {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const captcha  = document.getElementById('captcha').value.trim();
        const errorDiv = document.getElementById('error-msg');

        errorDiv.style.display = 'none';

        const body = new FormData();
        body.append('username', username);
        body.append('password', password);
        body.append('captcha', captcha);

        fetch('/api/login.php', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok') {
                    if (data.role === 'admin') {
                        window.location.href = '/admin/dashboard.php';
                    } else {
                        window.location.href = 'index.php';
                    }
                } else {
                    errorDiv.textContent = data.error || 'Login failed.';
                    errorDiv.style.display = 'block';
                    // Reload so PHP regenerates captcha numbers
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(() => {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            });
    });
  </script>
</body>
</html>