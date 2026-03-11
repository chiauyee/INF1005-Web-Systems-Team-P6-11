<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Not admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: /index.php");
    exit();
}
?>