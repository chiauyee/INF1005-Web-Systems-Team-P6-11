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

$artist      = trim($_POST['artist'] ?? '');
$album       = trim($_POST['album'] ?? '');
$price       = $_POST['price'] ?? null;
$artist_mbid = trim($_POST['artist_mbid'] ?? '');
$album_mbid  = trim($_POST['album_mbid'] ?? '');
$cached      = isset($_POST['cached']) && $_POST['cached'] === 'on';

if (!$artist || !$album || !$artist_mbid || !$album_mbid || $price === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$price = filter_var($price, FILTER_VALIDATE_FLOAT);
if ($price === false || $price < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid price']);
    exit;
}

try {
    $pdo->beginTransaction();

    if (!$cached) {
        $stmt = $pdo->prepare("
            INSERT INTO artists (artist_mbid, artist_name)
            VALUES (:artist_mbid, :artist_name)
            ON DUPLICATE KEY UPDATE artist_name = VALUES(artist_name)
        ");
        $stmt->execute([
            ':artist_mbid' => $artist_mbid,
            ':artist_name' => $artist,
        ]);

        $stmt = $pdo->prepare("
            INSERT INTO albums (album_mbid, artist_mbid, album_name)
            VALUES (:album_mbid, :artist_mbid, :album_name)
            ON DUPLICATE KEY UPDATE album_name = VALUES(album_name)
        ");
        $stmt->execute([
            ':album_mbid'  => $album_mbid,
            ':artist_mbid' => $artist_mbid,
            ':album_name'  => $album,
        ]);
    }

    $stmt = $pdo->prepare("
        INSERT INTO listings (album_mbid, seller_id, price)
        VALUES (:album_mbid, :seller_id, :price)
    ");

    $stmt->execute([
        ':album_mbid' => $album_mbid,
        ':seller_id'  => $_SESSION['user_id'],
        ':price'      => $price
    ]);

    $pdo->commit();

    header('Location: /listings.php');
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}
