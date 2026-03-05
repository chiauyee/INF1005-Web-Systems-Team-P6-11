<?php
session_start();
require 'db.php';

$error = '';

// generate simple math CAPTCHA numbers for security checks
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) 
{
  $_SESSION['captcha_num1'] = rand(1, 10);
  $_SESSION['captcha_num2'] = rand(1, 10);
}
$captcha_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $captcha_input = trim($_POST['captcha'] ?? '');

  // check math CAPTCHA
  if ($captcha_input != $captcha_answer) 
  { 
    $error = "Wrong answer to the math question. Try again.";
  } 
  else 
  {
    if ($username && $password)
    {
      $_SESSION['user_id'] = 1;
      $_SESSION['username'] = $username;
      header("Location: index.php");
      exit;
    } 
    else 
    {
      $error = "PLease fill in both fields.";
    }
  }

  // regenerate numbers for next load
  $_SESSION['captcha_num1'] = rand(1, 10);
  $_SESSION['captcha_num2'] = rand(1, 10);
  $captcha_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];

  // wait for the database
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) 
  {
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    header('Location: profile.php');
    exit;
  } 
  else 
  {
    $error = 'Invalid username or password.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/main.css">
</head>

<body>
  <?php include __DIR__ . '/includes/navigation.php'; ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title mb-4 text-center">Login to BRANDNAME</h2>
            
            <?php if (isset($_GET['registered'])): ?>
              <div class="alert alert-success">Registration successful! Please log in.</div>
            <?php endif; ?>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
              </div>
              
              <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
              </div>
              
              <div class="mb-3">
                <label for="captcha" class="form-label">
                  What is <?= $_SESSION['captcha_num1'] ?> + <?= $_SESSION['captcha_num2'] ?>?
                </label>
                <input type="text" name="captcha" id="captcha" class="form-control" required>
              </div>

              <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>

            <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
  