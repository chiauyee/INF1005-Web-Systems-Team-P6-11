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
    // Artist info
    $stmt = $pdo->prepare("SELECT * FROM artists WHERE artist_mbid = ?");
    $stmt->execute([$mbid]);
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$artist) {
        http_response_code(404);
        echo json_encode(['error' => 'Artist not found']);
        exit;
    }

    // Albums by artist
    $stmt = $pdo->prepare("SELECT * FROM albums WHERE artist_mbid = ?");
    $stmt->execute([$mbid]);
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Images
    $stmt = $pdo->prepare("
        SELECT ai.filename, ai.created_at, u.username
        FROM artist_images ai
        JOIN users u ON ai.uploaded_by = u.id
        WHERE ai.artist_mbid = ?
        ORDER BY ai.created_at DESC
    ");
    $stmt->execute([$mbid]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comments
    $stmt = $pdo->prepare("
        SELECT ac.id, ac.comment, ac.created_at, u.username
        FROM artist_comments ac
        JOIN users u ON ac.user_id = u.id
        WHERE ac.artist_mbid = ?
        ORDER BY ac.created_at DESC
    ");
    $stmt->execute([$mbid]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status'   => 'ok',
        'artist'   => $artist,
        'albums'   => $albums,
        'images'   => $images,
        'comments' => $comments,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
