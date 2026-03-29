<?php
session_start();
require 'db.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Invalid reset link.");
}

$token_hash = hash('sha256', $token);

$stmt = $pdo->prepare("
    SELECT password_resets.*, users.email
    FROM password_resets
    JOIN users ON users.id = password_resets.user_id
    WHERE token_hash = ?
    AND used = 0
    AND expires_at > NOW()
");

$stmt->execute([$token_hash]);
$reset = $stmt->fetch();

if (!$reset) {
    die("Reset link invalid or expired.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/reset_password.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;600i&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main>
        <div class="reset-wrapper">
            <div class="reset-left">
                <h2 class="panel-heading">Almost<br><em>there.</em></h2>
                <p class="panel-desc">Choose a strong new password to secure your account.</p>
            </div>

            <div class="reset-right">
                <div class="form-area">
                    <p class="form-eyebrow">Account recovery</p>
                    <h1 class="form-heading">Reset password</h1>
                    <p class="form-sub">Resetting password for: <?= htmlspecialchars($reset['email']) ?></p>

                    <?php
                    $reset_error = $_SESSION['reset_error'] ?? '';
                    unset($_SESSION['reset_error']);
                    ?>
                    <?php if ($reset_error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($reset_error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="process_reset.php" id="resetForm">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <div class="input-wrap">
                                <i class="bi bi-lock"></i>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <ul class="password-checklist mt-2">
                                <li id="length">At least 8 characters</li>
                                <li id="upper">One uppercase letter</li>
                                <li id="lower">One lowercase letter</li>
                                <li id="number">One number</li>
                                <li id="special">One special character</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password:</label>
                            <div class="input-wrap">
                                <i class="bi bi-lock-fill"></i>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-reset" id="submitBtn">Reset Password 
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <p class="login-prompt">Remember your password? 
                        <a href="login.php">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        const password = document.getElementById("password");
        const checklist = document.querySelector(".password-checklist");

        password.addEventListener("focus", function(){
            checklist.style.display = "block";
        });

        password.addEventListener("blur", function(){
            if(password.value === ""){
                checklist.style.display = "none";
            }
        });

        password.addEventListener("keyup", function(){

            const value = password.value;

            document.getElementById("length").style.color =
                value.length >= 8 ? "green" : "red";

            document.getElementById("upper").style.color =
                /[A-Z]/.test(value) ? "green" : "red";

            document.getElementById("lower").style.color =
                /[a-z]/.test(value) ? "green" : "red";

            document.getElementById("number").style.color =
                /[0-9]/.test(value) ? "green" : "red";

            document.getElementById("special").style.color =
                /[@$!%*?&]/.test(value) ? "green" : "red";
        });

        document.getElementById('resetForm').addEventListener('submit', function(e) {
        const value = document.getElementById('password').value;
        const confirmValue = document.getElementById('confirm_password').value;
        const errors = [];

        if (value.length < 8) errors.push("At least 8 characters");
        if (!/[A-Z]/.test(value)) errors.push("One uppercase letter");
        if (!/[a-z]/.test(value)) errors.push("One lowercase letter");
        if (!/[0-9]/.test(value)) errors.push("One number");
        if (!/[@$!%*?&]/.test(value)) errors.push("One special character");

        if (value !== confirmValue) {
            errors.push("Passwords do not match");
        }

        if (errors.length > 0) {
            e.preventDefault(); // Block form submission
            
            // Show error
            let errorDiv = document.getElementById('validation-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'validation-error';
                errorDiv.className = 'alert alert-danger mt-3';
                document.getElementById('resetForm').prepend(errorDiv);
            }
            errorDiv.textContent = 'Password requirements not met: ' + errors.join(', ');
            errorDiv.style.display = 'block';
        }
    });
    </script>
</body>
</html>