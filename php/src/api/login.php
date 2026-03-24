<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Check if user is locked out
if (isset($_SESSION['locked'])) {
    $difference = time() - $_SESSION['locked'];
    if ($difference > 30) {
        // Unlock after 30 seconds
        unset($_SESSION['locked']);
        unset($_SESSION['login_attempts']);
    } else {
        // Still locked
        $remaining = 30 - $difference;
        http_response_code(429); // Too Many Requests
        echo json_encode([
            'error' => "Too many failed attempts. Please wait {$remaining} seconds before trying again.",
            'remaining_seconds' => $remaining,
            'locked' => true,
            'attempts_remaining' => 0
        ]);
        exit;
    }
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill in both fields.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Successful login - reset attempts
    $_SESSION['login_attempts'] = 0;
    unset($_SESSION['locked']);

    if ($user['status'] === 'banned') {
        http_response_code(403);
        echo json_encode(['error' => 'Your account has been banned.']);
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
    echo json_encode(['status' => 'ok', 'role' => $user['role']]);
} else {
    // Failed login - increment attempts
    $_SESSION['login_attempts']++;
    
    $remaining_attempts = 3 - $_SESSION['login_attempts'];
    
    // Check if we've reached the limit
    if ($_SESSION['login_attempts'] >= 3) {
        $_SESSION['locked'] = time();
        http_response_code(429);
        echo json_encode([
            'error' => 'Too many failed attempts. Please wait 30 seconds before trying again.',
            'locked' => true,
            'remaining_seconds' => 30,
            'attempts_remaining' => 0
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'error' => 'Invalid username or password.',
            'attempts_remaining' => $remaining_attempts,
            'login_attempts' => $_SESSION['login_attempts'],
            'locked' => false
        ]);
    }
}
?>