<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            $error = 'Username or email already taken.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <main>
    <div class="register-wrapper">
        <div class="register-left">
            <h2 class="panel-heading">This is<br><em>for the record.</em></h2>
            <p class="panel-desc">Join thousands of collectors buying, selling and discovering vinyls, CDs and more.</p>

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

        <div class="register-right">
            <div class="form-area">
                <p class="form-eyebrow">Get started</p>
                <h1 class="form-heading">Create your account</h1>
                <p class="form-sub">It's free and takes less than a minute.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <div class="input-wrap">
                            <i class="bi bi-person"></i>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>
                            
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-register">Create Account
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <p class="login-prompt">Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </div>
  </main>
  
    <!-- Bootstrap JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
