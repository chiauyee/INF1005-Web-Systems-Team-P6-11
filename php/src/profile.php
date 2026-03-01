<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Profile</title></head>
<body>
  <h1>Your Profile</h1>
  <p><strong>ID:</strong> <?= htmlspecialchars($user['id']) ?></p>
  <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
  <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
  <br>
  <a href="index.php">Home</a> |
  <a href="logout.php">Logout</a>
</body>
</html>
