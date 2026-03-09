<?php
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$mbid = trim($_GET['mbid'] ?? '');
if (!$mbid) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing mbid']);
    exit;
}

try {
    // Album + artist info
    $stmt = $pdo->prepare("
        SELECT al.*, ar.artist_name, ar.artist_mbid
        FROM albums al
        JOIN artists ar ON al.artist_mbid = ar.artist_mbid
        WHERE al.album_mbid = ?
    ");
    $stmt->execute([$mbid]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$album) {
        http_response_code(404);
        echo json_encode(['error' => 'Album not found']);
        exit;
    }

    // Active listings for this album
    $stmt = $pdo->prepare("
        SELECT l.listing_id, l.price, l.created_at, u.username AS seller
        FROM listings l
        JOIN users u ON l.seller_id = u.id
        WHERE l.album_mbid = ?
        ORDER BY l.price ASC
    ");
    $stmt->execute([$mbid]);
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Images
    $stmt = $pdo->prepare("
        SELECT ai.filename, ai.created_at, u.username
        FROM album_images ai
        JOIN users u ON ai.uploaded_by = u.id
        WHERE ai.album_mbid = ?
        ORDER BY ai.created_at DESC
    ");
    $stmt->execute([$mbid]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comments
    $stmt = $pdo->prepare("
        SELECT ac.id, ac.comment, ac.created_at, u.username
        FROM album_comments ac
        JOIN users u ON ac.user_id = u.id
        WHERE ac.album_mbid = ?
        ORDER BY ac.created_at DESC
    ");
    $stmt->execute([$mbid]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status'   => 'ok',
        'album'    => $album,
        'listings' => $listings,
        'images'   => $images,
        'comments' => $comments,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
