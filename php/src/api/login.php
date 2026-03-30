<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
{
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Validate CSRF token
$csrfToken = $_POST['csrf_token'] ?? '';
if (!Security::validateCSRFToken($csrfToken)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) 
{
    $_SESSION['login_attempts'] = 0;
}

// Check if user is locked out
if (isset($_SESSION['locked'])) 
{
    $difference = time() - $_SESSION['locked'];
    if ($difference > 30) 
    {
        // Unlock after 30 seconds
        unset($_SESSION['locked']);
        unset($_SESSION['login_attempts']);
    } 
    
    else 
    {
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

// Handles login validation
$email = strip_tags(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (!$email || !$password) 
{
    http_response_code(400);
    echo json_encode(['error' => 'Please fill in both fields.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) 
{
    $_SESSION['login_attempts'] = 0;
    unset($_SESSION['locked']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        http_response_code(400);
        echo json_encode(['error' => 'Please enter a valid email address.']);
        exit;
    }

    if ($user['status'] === 'banned')
    {
        http_response_code(403);
        echo json_encode(['error' => 'Your account has been banned.']);
        exit;
    }

    // Test accounts bypass OTP
    $otp_bypass_emails = [
        'admin@musicmarket.com',
        'testuser@gmail.com',
        'testuser1@gmail.com'
    ];

    if (in_array($user['email'], $otp_bypass_emails)) 
    {
        session_regenerate_id(true);
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['role']     = $user['role'];
        echo json_encode(['status' => 'ok', 'role' => $user['role']]);
        exit;
    }

    // Normal users go through OTP
    $_SESSION['otp_user_id'] = $user['id'];
    echo json_encode(['status' => 'otp_required']);
    exit; 
}

else 
{
    // Failed login - increment attempts
    if (!isset($_SESSION['login_attempts'])) 
    {
        $_SESSION['login_attempts'] = 0;
    }
    
    $_SESSION['login_attempts']++;
    
    $remaining_attempts = 3 - $_SESSION['login_attempts'];
    
    // Check if we've reached the limit
    if ($_SESSION['login_attempts'] >= 3) 
    {
        $_SESSION['locked'] = time();
        http_response_code(429);
        echo json_encode([
            'error' => 'Too many failed attempts. Please wait 30 seconds before trying again.',
            'locked' => true,
            'remaining_seconds' => 30,
            'attempts_remaining' => 0
        ]);
    } 
    
    else 
    {
        http_response_code(401);
        echo json_encode([
            'error' => 'Invalid email or password.',
            'attempts_remaining' => $remaining_attempts,
            'login_attempts' => $_SESSION['login_attempts'],
            'locked' => false
        ]);
    }
}
?>