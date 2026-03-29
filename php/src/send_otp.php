<?php
session_start();
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Must have passed credentials check first
if (!isset($_SESSION['otp_user_id'])) 
{
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check rate limiting for OTP requests (max 5 per hour per user)
$userId = $_SESSION['otp_user_id'];
$rateLimit = Security::checkRateLimit("user_{$userId}", 'otp_request', 5, 3600);

if (!$rateLimit['allowed']) {
    $remainingMinutes = ceil($rateLimit['remaining_seconds'] / 60);
    http_response_code(429);
    echo json_encode(['error' => "Too many OTP requests. Please try again in {$remainingMinutes} minutes."]);
    exit;
}

// Generate 6-digit OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$_SESSION['otp_code']    = $otp;
$_SESSION['otp_expires'] = time() + 300; // 5 minutes

// Get user email
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['otp_user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) 
{
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit;
}

$mail = new PHPMailer(true);

try 
{
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'MusicMarket2026@gmail.com';
    $mail->Password   = 'csmq cqml fwuv zdax';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('MusicMarket2026@gmail.com', 'MusicMarket');
    $mail->addAddress($user['email']);
    $mail->Subject = 'Your Login Verification Code';
    $mail->Body = <<<EOT
        Dear {$user['username']},

        Your verification code is: {$otp}

        This code will expire in 5 minutes.

        If you did not attempt to log in, please ignore this email.

        Best regards,
        MusicMarket Support Team
        EOT;

    $mail->send();
    echo json_encode(['status' => 'ok']);

} 
catch (Exception $e) 
{
    error_log("OTP Email Error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send OTP email: ' . $mail->ErrorInfo]);
}
?>