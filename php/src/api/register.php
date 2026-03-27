<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$country = trim($_POST['country'] ?? 'SG');
$password = $_POST['password'] ?? '';

$latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? floatval($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? floatval($_POST['longitude']) : null;

error_log("Registration - Latitude: " . ($latitude ?? 'null') . ", Longitude: " . ($longitude ?? 'null'));

if (!$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address.']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 8 characters.']);
    exit;
}

if (!preg_match('/[A-Z]/', $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must contain at least one uppercase letter.']);
    exit;
}

if (!preg_match('/[a-z]/', $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must contain at least one lowercase letter.']);
    exit;
}

if (!preg_match('/[0-9]/', $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must contain at least one number.']);
    exit;
}

if (!preg_match('/[@$!%*?&]/', $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must contain at least one special character (@$!%*?&).']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $checkColumns = $pdo->query("SHOW COLUMNS FROM users LIKE 'latitude'");
    if ($checkColumns->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN latitude DECIMAL(10,8) NULL");
        $pdo->exec("ALTER TABLE users ADD COLUMN longitude DECIMAL(11,8) NULL");
    }
    
    if ($latitude !== null && $longitude !== null) {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, phone, address, country, latitude, longitude) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $email, $hash, $phone, $address, $country, $latitude, $longitude]);
        $userId = $pdo->lastInsertId();
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, phone, address, country) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $email, $hash, $phone, $address, $country]);
        $userId = $pdo->lastInsertId();
    }
    
    // Add password to history
    Security::addPasswordToHistory($userId, $hash);
    
    echo json_encode(['status' => 'ok', 'message' => 'Registration successful']);
} 

catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        http_response_code(409);
        echo json_encode(['error' => 'Username or email already taken.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>