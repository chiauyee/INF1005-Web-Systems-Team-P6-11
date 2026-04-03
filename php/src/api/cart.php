<?php
session_start();
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$body = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
}

$action = $body['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $listing_id = (int)($body['listing_id'] ?? 0);

    if (!$listing_id) {
        echo json_encode(['error' => 'Invalid listing ID']);
        exit;
    }

    if (isset($_SESSION['cart'][$listing_id])) {
        echo json_encode(['count' => count($_SESSION['cart']), 'already_in_cart' => true]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT l.listing_id, l.price, al.album_name, al.album_mbid, ar.artist_name, u.username AS seller
        FROM listings l
        JOIN albums al  ON l.album_mbid  = al.album_mbid
        JOIN artists ar ON al.artist_mbid = ar.artist_mbid
        JOIN users u    ON l.seller_id    = u.id
        WHERE l.listing_id = ? AND l.status = 'available'
    ");
    $stmt->execute([$listing_id]);
    $listing = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log($listing_id);
  
    if (!$listing) {
        echo json_encode(['error' => 'This listing is no longer available']);
        exit;
    }

    $_SESSION['cart'][$listing_id] = $listing;
    echo json_encode(['count' => count($_SESSION['cart'])]);
    exit;
}

if ($action === 'remove') {
    $listing_id = (int)($body['listing_id'] ?? 0);
    unset($_SESSION['cart'][$listing_id]);
    echo json_encode(['count' => count($_SESSION['cart'])]);
    exit;
}

if ($action === 'get') {
    echo json_encode([
        'items' => array_values($_SESSION['cart']),
        'count' => count($_SESSION['cart'])
    ]);
    exit;
}

echo json_encode(['error' => 'Unknown action']);
