<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);

require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['otp_user_id'], $_SESSION['otp_code'], $_SESSION['otp_expires'])) 
{
    http_response_code(403);
    echo json_encode(['error' => 'No OTP session found.']);
    exit;
}

if (time() > $_SESSION['otp_expires']) 
{
    unset($_SESSION['otp_code'], $_SESSION['otp_expires'], $_SESSION['otp_user_id']);
    http_response_code(400);
    echo json_encode(['error' => 'OTP has expired. Please log in again.', 'expired' => true]);
    exit;
}

$entered_otp = trim($_POST['otp'] ?? '');

if ($entered_otp !== $_SESSION['otp_code']) 
{
    http_response_code(401);
    echo json_encode(['error' => 'Invalid OTP. Please try again.']);
    exit;
}

// OTP correct - complete the login
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['otp_user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) 
{
    http_response_code(404);
    echo json_encode(['error' => 'User not found.']);
    exit;
}

// Clear OTP session data first
unset($_SESSION['otp_code'], $_SESSION['otp_expires'], $_SESSION['otp_user_id']);

// Then regenerate session id
session_regenerate_id(true);

// Set login session
$_SESSION['user_id']  = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email']    = $user['email'];
$_SESSION['role']     = $user['role'];

echo json_encode(['status' => 'ok', 'role' => $user['role']]);
?>