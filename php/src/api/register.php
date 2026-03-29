<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

$username = strip_tags(trim($_POST['username'] ?? ''));
$email    = trim($_POST['email'] ?? '');
$phone    = strip_tags(trim($_POST['phone'] ?? ''));$address  = strip_tags(trim($_POST['address'] ?? ''));
$country = trim($_POST['country'] ?? 'SG');
$password = $_POST['password'] ?? '';

$latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? floatval($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? floatval($_POST['longitude']) : null;

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

// Validate country
$allowedCountries = ['AD','AE','AF','AG','AI','AL','AM','AO','AR','AS','AT','AU','AW','AZ',
    'BA','BB','BD','BE','BF','BG','BH','BI','BJ','BM','BN','BO','BR','BS','BT','BW','BY','BZ',
    'CA','CD','CF','CG','CH','CI','CK','CL','CM','CN','CO','CR','CU','CV','CY','CZ','DE','DJ',
    'DK','DM','DO','DZ','EC','EE','EG','ER','ES','ET','FI','FJ','FK','FM','FO','FR','GA','GB',
    'GD','GE','GH','GI','GL','GM','GN','GQ','GR','GT','GU','GW','GY','HK','HN','HR','HT','HU',
    'ID','IE','IL','IN','IQ','IR','IS','IT','JM','JO','JP','KE','KG','KH','KI','KM','KN','KP',
    'KR','KW','KY','KZ','LA','LB','LC','LI','LK','LR','LS','LT','LU','LV','LY','MA','MC','MD',
    'ME','MG','MH','MK','ML','MM','MN','MO','MP','MR','MS','MT','MU','MV','MW','MX','MY','MZ',
    'NA','NC','NE','NG','NI','NL','NO','NP','NR','NU','NZ','OM','PA','PE','PF','PG','PH','PK',
    'PL','PT','PW','PY','QA','RO','RS','RU','RW','SA','SB','SC','SD','SE','SG','SH','SI','SK',
    'SL','SM','SN','SO','SR','ST','SV','SY','SZ','TC','TD','TG','TH','TJ','TK','TL','TM','TN',
    'TO','TR','TT','TV','TW','TZ','UA','UG','US','UY','UZ','VA','VC','VE','VG','VI','VN','VU',
    'WF','WS','XK','YE','YT','ZA','ZM','ZW'];

if (!in_array($country, $allowedCountries)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid country selected.']);
    exit;
}

// Validate username
if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username must be 3-50 characters and can only contain letters, numbers and underscores.']);
    exit;
}

// Validate phone number
if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['error' => 'Please enter a valid phone number.']);
    exit;
}

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

// Check for duplicate username
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->rowCount() > 0) {
    http_response_code(409);
    echo json_encode(['error' => 'Username is already taken.']);
    exit;
}

// Check for duplicate email
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    http_response_code(409);
    echo json_encode(['error' => 'Email is already registered.']);
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