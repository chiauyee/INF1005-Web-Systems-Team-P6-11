<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

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

\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

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
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}