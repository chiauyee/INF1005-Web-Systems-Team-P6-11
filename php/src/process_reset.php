<?php
require 'db.php';

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (!$token || !$password) {
    die("Invalid request");
}

$token_hash = hash('sha256', $token);

$stmt = $pdo->prepare("
SELECT * FROM password_resets
WHERE token_hash = ?
AND used = 0
AND expires_at > NOW()
");

$stmt->execute([$token_hash]);
$reset = $stmt->fetch();

if (!$reset) {
    die("Invalid or expired token");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$pdo->prepare("UPDATE users SET password = ? WHERE id = ?")
    ->execute([$password_hash, $reset['user_id']]);

$pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = ?")
    ->execute([$reset['id']]);

echo "Password successfully reset. <a href='login.php'>Login</a>";