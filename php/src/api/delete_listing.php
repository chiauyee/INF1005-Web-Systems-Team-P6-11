<?php
session_start();
require '../db.php';

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

$csrf = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit;
}

$listing_id = (int)($_POST['listing_id'] ?? 0);
if ($listing_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid listing ID']);
    exit;
}

try {
    // Only allow deleting own rejected listings
    $stmt = $pdo->prepare(
        "DELETE FROM listings WHERE listing_id = ? AND seller_id = ? AND status = 'rejected'"
    );
    $stmt->execute([$listing_id, $_SESSION['user_id']]);

    if ($stmt->rowCount() === 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Listing not found or cannot be deleted']);
        exit;
    }

    header('Location: /profile.php');
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}
