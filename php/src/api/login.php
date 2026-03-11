<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$username      = trim($_POST['username'] ?? '');
$password      = $_POST['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill in both fields.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

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
    http_response_code(401);
    echo json_encode(['error' => 'Invalid username or password.']);
}
