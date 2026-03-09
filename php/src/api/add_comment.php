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

$data    = json_decode(file_get_contents('php://input'), true);
$type    = $data['type'] ?? '';   // 'artist' or 'album'
$mbid    = trim($data['mbid'] ?? '');
$comment = trim($data['comment'] ?? '');

if (!in_array($type, ['artist', 'album']) || !$mbid || !$comment) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

if (strlen($comment) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Comment too long (max 2000 characters)']);
    exit;
}

try {
    if ($type === 'artist') {
        $stmt = $pdo->prepare("INSERT INTO artist_comments (artist_mbid, user_id, comment) VALUES (?, ?, ?)");
    } else {
        $stmt = $pdo->prepare("INSERT INTO album_comments (album_mbid, user_id, comment) VALUES (?, ?, ?)");
    }
    $stmt->execute([$mbid, $_SESSION['user_id'], $comment]);

    echo json_encode([
        'status'   => 'ok',
        'comment'  => [
            'id'         => $pdo->lastInsertId(),
            'username'   => $_SESSION['username'],
            'comment'    => $comment,
            'created_at' => date('Y-m-d H:i:s'),
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
