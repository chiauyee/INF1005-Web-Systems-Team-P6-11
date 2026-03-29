<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled — MusicMarket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/payment_cancel.css">
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <div class="container py-5 text-center" style="max-width: 560px;">
        <div class="status-icon-wrap cancelled">
            <i class="bi bi-x-lg"></i>
        </div>
        <h1 class="page-heading">Payment Cancelled</h1>
        <p class="page-sub mb-4">
            No worries — your cart has been saved.<br>
            You can complete your purchase whenever you're ready.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="checkout.php" class="btn-primary-dark">
                <i class="bi bi-arrow-left me-2"></i>Try Again
            </a>
            <a href="listings.php" class="btn-outline-dark-custom">Keep Browsing</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>