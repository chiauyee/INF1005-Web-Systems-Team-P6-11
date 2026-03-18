<?php
session_start();
require '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$search       = trim($_GET['search'] ?? '');
$artist_mbid  = trim($_GET['artist_mbid'] ?? '');
$album_mbid   = trim($_GET['album_mbid'] ?? '');

try {
    $params = [];
    $where  = ["l.status = 'available'"];

    if ($artist_mbid) {
        $where[]  = 'ar.artist_mbid = ?';
        $params[] = $artist_mbid;
    }

    if ($album_mbid) {
        $where[]  = 'al.album_mbid = ?';
        $params[] = $album_mbid;
    }

    if ($search) {
        $where[]  = '(al.album_name LIKE ? OR ar.artist_name LIKE ? OR u.username LIKE ?)';
        $like     = '%' . $search . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("
        SELECT
            l.listing_id,
            l.created_at,
            l.price,
            al.album_name,
            al.album_mbid,
            ar.artist_name,
            ar.artist_mbid,
            u.username AS seller
        FROM listings l
        JOIN albums al  ON l.album_mbid  = al.album_mbid
        JOIN artists ar ON al.artist_mbid = ar.artist_mbid
        JOIN users u    ON l.seller_id   = u.id
        $where_sql
        ORDER BY l.created_at DESC
    ");
    error_log($stmt->queryString);
    $stmt->execute($params);
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'ok', 'data' => $listings]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
