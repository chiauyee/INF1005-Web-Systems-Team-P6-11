<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: listings.php');
    exit;
}

$total = array_sum(array_column($cart, 'price'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — MusicMarket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/checkout.css">
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <div class="container py-5" style="max-width: 600px;">

        <p class="page-eyebrow">Checkout</p>
        <h1 class="page-heading">Order Summary</h1>
        <p class="page-sub mb-4">Review your items before paying.</p>

        <div class="order-card mb-4">
            <?php foreach ($cart as $item): ?>
            <div class="order-row">
                <div>
                    <div class="order-album"><?= htmlspecialchars($item['album_name']) ?></div>
                    <div class="order-meta"><?= htmlspecialchars($item['artist_name']) ?></div>
                    <div class="order-meta">Seller: <?= htmlspecialchars($item['seller']) ?></div>
                </div>
                <div class="order-price">$<?= number_format((float)$item['price'], 2) ?></div>
            </div>
            <?php endforeach; ?>

            <div class="order-total-row">
                <span class="order-total-label">Total</span>
                <span class="order-total-value">$<?= number_format($total, 2) ?> USD</span>
            </div>
        </div>

        <div id="error-msg" class="alert alert-danger d-none mb-3"></div>

        <div class="d-flex flex-column gap-2">
            <button id="pay-btn" class="btn-primary-dark">
                <i class="bi bi-lock-fill"></i>Pay with Stripe
            </button>
            <a href="listings.php" class="btn-outline-dark-custom">Bring me back</a>
        </div>

        <p class="secure-note mt-3">
            <i class="bi bi-shield-lock me-1"></i>Payments are securely processed by Stripe. Your card details never touch our server.
        </p>

    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
document.getElementById('pay-btn').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>Redirecting to Stripe…';

    fetch('/api/stripe_checkout.php', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if (data.url) {
                window.location.href = data.url;
            } else {
                document.getElementById('error-msg').textContent = data.error || 'Something went wrong.';
                document.getElementById('error-msg').classList.remove('d-none');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-lock-fill"></i>Pay with Stripe';
            }
        })
        .catch(() => {
            document.getElementById('error-msg').textContent = 'Network error. Please try again.';
            document.getElementById('error-msg').classList.remove('d-none');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-lock-fill"></i>Pay with Stripe';
        });
});
</script>
</body>
</html>