<?php
if (session_status() == PHP_SESSION_NONE) {
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

// CSRF protection for admin POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!Security::validateCSRFToken($csrfToken)) {
        http_response_code(403);
        exit('Invalid CSRF token.');
    }
}
?>