<?php
session_start();
require 'db.php';

$error = '';

// i want to get rid of this because it is worse than useless
// we can probably integrate an actual captcha, it's not hard

// Generate simple math CAPTCHA
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) {
    $_SESSION['captcha_num1'] = rand(1, 10);
    $_SESSION['captcha_num2'] = rand(1, 10);
}
$captcha_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username      = trim($_POST['username'] ?? '');
    $password      = $_POST['password'] ?? '';
    $captcha_input = trim($_POST['captcha'] ?? '');

    // Regenerate captcha for next attempt
    $_SESSION['captcha_num1'] = rand(1, 10);
    $_SESSION['captcha_num2'] = rand(1, 10);

    if ($captcha_input != $captcha_answer) {
        $error = "Wrong answer to the math question. Please try again.";
    } elseif (!$username || !$password) {
        $error = "Please fill in both fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }

    $captcha_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>

    <!-- Bootstrap CSS -->
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
            <p class="panel-desc">Sign in to browse your collection, track orders and discover new records.</p>
            
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

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope"></i>
                            <input type="username" name="username" id="username" class="form-control" required>
                        </div>
                    </div>
                            
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="captcha" class="form-label">Security Check: <?= $_SESSION['captcha_num1'] ?> + <?= $_SESSION['captcha_num2'] ?> = ?</label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-lock"></i>
                            <input type="text" name="captcha" id="captcha" class="form-control" required autocomplete="off">
                        </div>
                    </div>

                    <button type="submit" class="btn-login">Sign In 
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <p class="register-prompt">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
  </main>
  
    <!-- Bootstrap JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
