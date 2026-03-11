<?php
require __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT
        c.id            AS chat_id,
        c.listing_id,
        c.buyer_id,
        c.seller_id,
        buyer.username  AS buyer_username,
        seller.username AS seller_username,
        l.album_mbid,
        (
            SELECT m2.content
            FROM messages m2
            WHERE m2.chat_id = c.id
            ORDER BY m2.created_at DESC
            LIMIT 1
        ) AS last_message,
        (
            SELECT m3.created_at
            FROM messages m3
            WHERE m3.chat_id = c.id
            ORDER BY m3.created_at DESC
            LIMIT 1
        ) AS last_message_at
    FROM chats c
    JOIN users buyer  ON buyer.id  = c.buyer_id
    JOIN users seller ON seller.id = c.seller_id
    LEFT JOIN listings l ON l.listing_id = c.listing_id
    WHERE c.buyer_id = :uid OR c.seller_id = :uid
    ORDER BY last_message_at DESC
");
$stmt->execute([':uid' => $user_id]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'ok', 'data' => $chats]);
