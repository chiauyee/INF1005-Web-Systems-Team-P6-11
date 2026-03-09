<?php
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

<form method="POST" action="process_reset.php">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <label>New Password</label>
    <input type="password" name="password" required>

    <button type="submit">Reset Password</button>
</form>