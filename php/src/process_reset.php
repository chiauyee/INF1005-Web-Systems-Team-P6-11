<?php
require 'db.php';

session_start(); // Use session to store messages

$token = $_POST['token'] ?? '';
$newPassword = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (!$token) {
    $_SESSION['reset_error'] = "Invalid request.";
    header("Location: reset_password.php?token=$token");
    exit;
}

// Hash the token
$token_hash = hash('sha256', $token);

// Fetch reset info and user password hash
$stmt = $pdo->prepare("
    SELECT password_resets.*, users.id AS user_id, users.password
    FROM password_resets
    JOIN users ON users.id = password_resets.user_id
    WHERE token_hash = ? 
      AND used = 0 
      AND expires_at > NOW()
");
$stmt->execute([$token_hash]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    $_SESSION['reset_error'] = "Reset link invalid or expired.";
    header("Location: reset_password.php");
    exit;
}

// Check password confirmation
if ($newPassword !== $confirmPassword) {
    $_SESSION['reset_error'] = "Passwords do not match.";
    header("Location: reset_password.php?token=$token");
    exit;
}

// Check if new password is the same as the old password
if (password_verify($newPassword, $reset['password'])) {
    $_SESSION['reset_error'] = "You cannot reuse your previous password. Please choose a different one.";
    header("Location: reset_password.php?token=$token");
    exit;
}

// Hash the new password
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the user's password
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$newHash, $reset['user_id']]);

// Mark token as used
$stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
$stmt->execute([$reset['id']]);

// Success message
$_SESSION['reset_success'] = "Password reset successfully! Please login.";
header("Location: login.php");
exit;