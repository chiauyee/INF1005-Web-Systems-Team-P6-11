<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../db.php';

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

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    http_response_code(400);
    echo json_encode(['error' => 'Your cart is empty']);
    exit;
}

// Block checkout if delivery address is not set
$addr_stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
$addr_stmt->execute([(int)$_SESSION['user_id']]);
$addr_row = $addr_stmt->fetch(PDO::FETCH_ASSOC);
if (empty($addr_row['address'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Please add a delivery address to your profile before placing an order.', 'missing_address' => true]);
    exit;
}

\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

// Re-checks all cart items are still available before charging the user
$listing_ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($listing_ids), '?'));
$stmt = $pdo->prepare(
    "SELECT listing_id FROM listings WHERE listing_id IN ($placeholders) AND status = 'available'"
);
$stmt->execute($listing_ids);
$available_ids = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'listing_id');

$unavailable = array_diff($listing_ids, $available_ids);
if (!empty($unavailable)) {

    // Removes sold item from cart to avoid any mistransactions
    foreach ($unavailable as $sold_id) {
        unset($_SESSION['cart'][$sold_id]);
    }
    http_response_code(409);
    echo json_encode([
        'error' => 'One or more items in your cart have just been sold. They have been removed from your cart. Please review your cart and try again.'
    ]);
    exit;
}

$line_items = [];
foreach ($cart as $listing_id => $item) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name'        => $item['album_name'] . ' — ' . $item['artist_name'],
                'description' => 'Seller: ' . $item['seller'],
            ],
            'unit_amount' => (int) round((float) $item['price'] * 100),
        ],
        'quantity' => 1,
    ];
}

$proto   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseUrl = $proto . '://' . $_SERVER['HTTP_HOST'];

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items'           => $line_items,
        'mode'                 => 'payment',
        'success_url'          => $baseUrl . '/payment_success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'           => $baseUrl . '/payment_cancel.php',
        'metadata'             => [
            'buyer_id'    => (string) $_SESSION['user_id'],
            'listing_ids' => implode(',', array_keys($cart)),
        ],
    ]);

    echo json_encode(['url' => $session->url]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    error_log('Stripe API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Payment could not be initiated. Please try again.']);
}
