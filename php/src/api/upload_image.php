<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$type = $_POST['type'] ?? ''; // 'artist' or 'album'
$mbid = trim($_POST['mbid'] ?? '');

if (!in_array($type, ['artist', 'album']) || !$mbid) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid parameters']);
    exit;
}

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file field named "image" was uploaded']);
    exit;
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    $error_msg = 'Image upload error code: ' . $_FILES['image']['error'];
    if ($_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE) {
        $error_msg = 'The uploaded file exceeds the maximum file size.';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_PARTIAL) {
        $error_msg = 'The uploaded file was only partially uploaded.';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $error_msg = 'No file was uploaded.';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_NO_TMP_DIR) {
        $error_msg = 'Missing a temporary folder.';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_CANT_WRITE) {
        $error_msg = 'Failed to write file to disk.';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_EXTENSION) {
        $error_msg = 'A PHP extension stopped the file upload.';
    }
    echo json_encode(['error' => $error_msg]);
    exit;
}

$allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES['image']['tmp_name']);

if (!in_array($mime, $allowed_types)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid image type. Allowed: JPEG, PNG, WebP, GIF']);
    exit;
}

if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'Image too large. Max 5MB.']);
    exit;
}

$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$filename = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
$dir = __DIR__ . "/../uploads/{$type}s/";
$dest = $dir . $filename;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save image']);
    exit;
}

try {
    if ($type === 'artist') {
        $stmt = $pdo->prepare("INSERT INTO artist_images (artist_mbid, uploaded_by, filename) VALUES (?, ?, ?)");
    } else {
        $stmt = $pdo->prepare("INSERT INTO album_images (album_mbid, uploaded_by, filename) VALUES (?, ?, ?)");
    }
    $stmt->execute([$mbid, $_SESSION['user_id'], $filename]);
    echo json_encode(['status' => 'ok', 'filename' => $filename]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
