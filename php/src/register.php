<?php
session_start();
require 'db.php';

$error = '';
$password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
$passwordErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {

        if (strlen($password) < 8) {
            $passwordErrors[] = "At least 8 characters";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $passwordErrors[] = "One uppercase letter";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $passwordErrors[] = "One lowercase letter";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $passwordErrors[] = "One number";
        }

        if (!preg_match('/[@$!%*?&]/', $password)) {
            $passwordErrors[] = "One special character";
        }

        if (!empty($passwordErrors)) {
            $error = "Password does not meet the requirements.";
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
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

                <div id="error-msg" class="alert alert-danger" style="display:none;"></div>
                <div id="success-msg" class="alert alert-success" style="display:none;">Account created! Redirecting to login...</div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" id="username" class="form-control" required>
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

                        <ul class="password-checklist mt-2">
                            <li id="length">At least 8 characters</li>
                            <li id="upper">One uppercase letter</li>
                            <li id="lower">One lowercase letter</li>
                            <li id="number">One number</li>
                            <li id="special">One special character</li>
                        </ul>

                <button type="button" id="btn-register" class="btn-register">
                    Create Account <i class="bi bi-arrow-right"></i>
                </button>

                <p class="login-prompt">Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </div>
  </main>
  
    <!-- Bootstrap JavaScript -->
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
    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script>
    document.getElementById('btn-register').addEventListener('click', function () {
        const username = document.getElementById('username').value.trim();
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const errorDiv   = document.getElementById('error-msg');
        const successDiv = document.getElementById('success-msg');

        errorDiv.style.display   = 'none';
        successDiv.style.display = 'none';

        const body = new FormData();
        body.append('username', username);
        body.append('email', email);
        body.append('password', password);

        fetch('/api/register.php', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok') {
                    successDiv.style.display = 'block';
                    setTimeout(() => { window.location.href = 'login.php?registered=1'; }, 1200);
                } else {
                    errorDiv.textContent = data.error || 'Registration failed.';
                    errorDiv.style.display = 'block';
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
