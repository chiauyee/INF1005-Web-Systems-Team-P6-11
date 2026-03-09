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
$captcha_input = trim($_POST['captcha'] ?? '');
$captcha_answer = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];

// Regenerate captcha regardless of outcome
$_SESSION['captcha_num1'] = rand(1, 10);
$_SESSION['captcha_num2'] = rand(1, 10);

if ((int)$captcha_input !== (int)$captcha_answer) {
    http_response_code(400);
    echo json_encode(['error' => 'Wrong answer to the math question. Please try again.']);
    exit;
}

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill in both fields.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid username or password.']);
}
