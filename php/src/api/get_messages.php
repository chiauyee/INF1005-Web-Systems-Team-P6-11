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
$chat_id = (int) ($_GET['chat_id'] ?? 0);

if (!$chat_id) {
    http_response_code(400);
    echo json_encode(['error' => 'chat_id required']);
    exit;
}

$check = $pdo->prepare("
    SELECT id FROM chats
    WHERE id = :chat_id AND (buyer_id = :uid OR seller_id = :uid)
");
$check->execute([':chat_id' => $chat_id, ':uid' => $user_id]);
if (!$check->fetch()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        m.id,
        m.chat_id,
        m.sender_id,
        u.username AS sender_username,
        m.content,
        m.created_at
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE m.chat_id = :chat_id
    ORDER BY m.created_at ASC
");
$stmt->execute([':chat_id' => $chat_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'ok', 'data' => $messages]);
