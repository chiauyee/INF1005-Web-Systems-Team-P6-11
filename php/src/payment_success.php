<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/vendor/autoload.php';

\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

$session_id = $_GET['session_id'] ?? '';
$paid       = false;
$error      = null;

if ($session_id) {
    try {
        $stripeSession = \Stripe\Checkout\Session::retrieve($session_id);

        if ($stripeSession->payment_status === 'paid') {
            $metadata    = $stripeSession->metadata->toArray();
            $listing_ids = array_filter(explode(',', $metadata['listing_ids'] ?? ''));
            $buyer_id    = (int) ($metadata['buyer_id'] ?? 0);

            foreach ($listing_ids as $lid) {
                $lid  = (int) $lid;
                $stmt = $pdo->prepare(
                    "UPDATE listings SET status = 'complete', buyer_id = ? WHERE listing_id = ? AND status = 'available'"
                );
                $stmt->execute([$buyer_id, $lid]);
            }

            $_SESSION['cart'] = [];
            $paid = true;
        } else {
            $error = 'Payment is not complete yet.';
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        $error = 'Could not verify payment: ' . $e->getMessage();
    }
} else {
    $error = 'No session ID provided.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $paid ? 'Payment Successful' : 'Payment Error' ?> — MusicMarket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/payment_success.css">
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <div class="container py-5" style="max-width: 560px;">

        <?php if ($paid): ?>

            <div class="text-center">
                <div class="status-icon-wrap success">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1 class="page-heading">Payment Successful</h1>
                <p class="page-sub mb-4">
                    Your order has been confirmed and your items are on their way.<br>
                    Thank you for shopping on MusicMarket.
                </p>
            </div>

            <div class="detail-card mb-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge" style="background:#d1fae5; color:#065f46; font-weight:500; font-size:0.8rem;">Confirmed</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Payment</div>
                        <div class="detail-value">
                            <span class="badge" style="background:#d1fae5; color:#065f46; font-weight:500; font-size:0.8rem;">Paid</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Reference</div>
                        <div class="detail-value" style="font-size:0.8rem; color:#999;"><?= htmlspecialchars($session_id) ?></div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="listings.php" class="btn-primary-dark">
                    <i class="bi bi-music-note-list me-2"></i>Browse More Music
                </a>
                <a href="profile.php" class="btn-outline-dark-custom">
                    View Profile
                </a>
            </div>

        <?php else: ?>

            <div class="text-center">
                <div class="status-icon-wrap error">
                    <i class="bi bi-x-lg"></i>
                </div>
                <h1 class="page-heading">Something Went Wrong</h1>
                <p class="page-sub mb-4"><?= htmlspecialchars($error) ?></p>
            </div>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="checkout.php" class="btn-primary-dark">Try Again</a>
                <a href="listings.php" class="btn-outline-dark-custom">Back to Listings</a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>