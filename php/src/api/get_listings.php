<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT
            l.listing_id,
            l.created_at,
            l.price,
            al.album_name,
            ar.artist_name,
            u.username AS seller
        FROM listings l
        JOIN albums al ON l.album_mbid = al.album_mbid
        JOIN artists ar ON al.artist_mbid = ar.artist_mbid
        JOIN users u ON l.seller_id = u.id
        ORDER BY l.created_at DESC
    ");
    $stmt->execute();
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'data' => $listings]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
