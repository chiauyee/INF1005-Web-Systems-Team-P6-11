<?php
require __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$chat_id = (int) ($body['chat_id'] ?? 0);
$content = trim($body['content'] ?? '');
$user_id = (int) $_SESSION['user_id'];

if (!$chat_id || $content === '') {
    http_response_code(400);
    echo json_encode(['error' => 'chat_id and content are required']);
    exit;
}

if (mb_strlen($content) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Message too long (max 2000 characters)']);
    exit;
}

// Verify the user is a participant
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

$insert = $pdo->prepare("
    INSERT INTO messages (chat_id, sender_id, content)
    VALUES (:chat_id, :sender_id, :content)
");
$insert->execute([
    ':chat_id'   => $chat_id,
    ':sender_id' => $user_id,
    ':content'   => $content,
]);

$msg_id = (int) $pdo->lastInsertId();

// Return the full message row (with username) so the client can render it immediately
$stmt = $pdo->prepare("
    SELECT m.id, m.chat_id, m.sender_id, u.username AS sender_username, m.content, m.created_at
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE m.id = :id
");
$stmt->execute([':id' => $msg_id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'ok', 'data' => $message]);
