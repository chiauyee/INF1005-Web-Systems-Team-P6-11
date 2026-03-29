<?php

require __DIR__ . '/../db.php';

if (session_status() == PHP_SESSION_NONE) session_start();

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
$listing_id = isset($body['listing_id']) ? (int) $body['listing_id'] : null;
$buyer_id = (int) $_SESSION['user_id'];

if (!$listing_id) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing required fields']);
  exit;
}

$seller = $pdo->prepare("SELECT seller_id FROM listings WHERE listing_id = :lid AND status = 'available'");
$seller->execute([":lid" => $listing_id]);
$seller_id = $seller->fetchColumn();

if (!$seller_id) {
  http_response_code(404);
  echo json_encode(['error' => 'No such available listing.']);
  exit;
}

if ($buyer_id === $seller_id) {
  http_response_code(400);
  echo json_encode(['error' => 'Cannot open chat with yourself']);
  exit;
}

try {
  $pdo->beginTransaction();

  // don't create a new chat if one already exists
  $existing = $pdo->prepare("SELECT id FROM chats where listing_id = :lid AND buyer_id = :bid");
  $existing->execute([':lid' => $listing_id, ':bid' => $buyer_id]);
  $row = $existing->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    error_log("exiting out here");
    echo json_encode(['status' => 'ok', 'data' => [
      'chat_id' => (int) $row['id']
    ]]);
    exit;
  }
  error_log("test");

  // update listings
  $update = $pdo->prepare("UPDATE listings SET buyer_id = :bid, status = 'pending' WHERE listing_id = :lid");
  $update->execute([
    ':lid' => $listing_id,
    ':bid' => $buyer_id
  ]);

  // create new chat
  $insert = $pdo->prepare("INSERT INTO chats (listing_id, buyer_id, seller_id) VALUES (:lid, :bid, :sid)");
  $insert->execute([
    ':lid' => $listing_id,
    ':bid' => $buyer_id,
    ':sid' => $seller_id
  ]);


  $chat_id = (int) $pdo->lastInsertId();
  $pdo->commit();
  error_log('exiting smoothly');
  echo json_encode(['status' => 'ok', 'data' => [
    'chat_id' => $chat_id
  ]]);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

