<?php
session_start();
require 'db.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Always show success to prevent email enumeration
        // TODO: If $user exists, generate a token, store it, and send reset email via PHPMailer
        $success = 'If that email is registered, a reset link has been sent.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/forgot_password.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main>
        <div class="forgot-wrapper">
            <div class="forgot-left">
                <h2 class="panel-heading">Happens to<br>the <em>best of us.</em></h2>
                <p class="panel-desc">Enter your email and we'll send you a link to get back into your account.</p>
            </div>

            <div class="forgot-right">
                <div class="form-area">

                    <?php if ($success): ?>
                        <div class="success-state">
                            <div class="success-icon">
                                <i class="bi bi-envelope-check"></i>
                            </div>
                            <h1 class="success-title">Check your inbox</h1>
                            <p class="success-desc">
                                If <strong><?= htmlspecialchars($_POST['email'] ?? 'that email') ?></strong> is registered with us,
                                you'll receive a password reset link shortly.<br><br>
                                Don't see it? Check your spam folder.
                            </p>
                            <a href="login.php" class="btn-back">
                                <i class="bi bi-arrow-left"></i> Back to Sign In
                            </a>
                        </div>

                    <?php else: ?>
                        <p class="form-eyebrow">Account recovery</p>
                        <h1 class="form-heading">Forgot password?</h1>
                        <p class="form-sub">We'll send a reset link to your registered email address.</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address:</label>
                                <div class="input-wrap">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email" name="email" id="email" class="form-control"
                                           placeholder="you@example.com"
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                           required autofocus>
                                </div>
                            </div>

                            <button type="submit" class="btn-forgot">
                                Send Reset Link <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>

                        <p class="forgot-prompt">
                            Remember your password? <a href="login.php">Sign in</a>
                        </p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>