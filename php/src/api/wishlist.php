<?php
session_start();
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

// auto-create wishlist table if it doesn't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        album_mbid VARCHAR(200) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_album (user_id, album_mbid),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (album_mbid) REFERENCES albums(album_mbid) ON DELETE CASCADE
    )
");

// all actions require login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$body = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
}

$action = $body['action'] ?? $_GET['action'] ?? '';

// toggle
if ($action === 'toggle') {
    $album_mbid = trim($body['album_mbid'] ?? '');

    if (!$album_mbid || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $album_mbid)) {
        echo json_encode(['error' => 'Invalid album MBID']);
        exit;
    }

    // check if already wishlisted
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND album_mbid = ?");
    $stmt->execute([$user_id, $album_mbid]);
    $existing = $stmt->fetch();

    if ($existing) {
        // remove
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND album_mbid = ?");
        $stmt->execute([$user_id, $album_mbid]);
        $wishlisted = false;
    } else {
        // add
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, album_mbid) VALUES (?, ?)");
        $stmt->execute([$user_id, $album_mbid]);
        $wishlisted = true;
    }

    // return total wishlist count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $count = (int) $stmt->fetchColumn();

    echo json_encode(['wishlisted' => $wishlisted, 'count' => $count]);
    exit;
}

// check
if ($action === 'check') {
    $album_mbid = trim($body['album_mbid'] ?? $_GET['album_mbid'] ?? '');

    if (!$album_mbid || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $album_mbid)) {
        echo json_encode(['error' => 'Invalid album MBID']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND album_mbid = ?");
    $stmt->execute([$user_id, $album_mbid]);
    $wishlisted = (bool) $stmt->fetch();

    echo json_encode(['wishlisted' => $wishlisted]);
    exit;
}

// get
if ($action === 'get') {
    $stmt = $pdo->prepare("
        SELECT w.id, w.album_mbid, w.created_at,
               al.album_name, ar.artist_name, al.artist_mbid
        FROM wishlist w
        JOIN albums al  ON w.album_mbid   = al.album_mbid
        JOIN artists ar ON al.artist_mbid = ar.artist_mbid
        WHERE w.user_id = ?
        ORDER BY w.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['items' => $items, 'count' => count($items)]);
    exit;
}

echo json_encode(['error' => 'Unknown action']);
