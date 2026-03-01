<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head><title>Home</title></head>
<body>
  <h1>hello vro</h1>
  <?php if (isset($_SESSION['user_id'])): ?>
    <p>Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
    <a href="profile.php">View Profile</a> |
    <a href="logout.php">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a> |
    <a href="register.php">Register</a>
  <?php endif; ?>
</body>
</html>
