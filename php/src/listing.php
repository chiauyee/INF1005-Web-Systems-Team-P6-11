<?php
session_start();
require 'db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$listing_id = (int)($_GET['id'] ?? 0);
if (!$listing_id) {
    header('Location: profile.php');
    exit;
}

$user_id  = (int)$_SESSION['user_id'];
$is_admin = ($_SESSION['role'] ?? '') === 'admin';

// Fetch listing with album, artist, seller, buyer, and latest album image
$stmt = $pdo->prepare("
    SELECT l.listing_id, l.album_mbid, l.price, l.status, l.rejection_reason,
           l.created_at, l.purchased_at, l.seller_id, l.buyer_id, l.stripe_session_id,
           al.album_name, ar.artist_name, ar.artist_mbid,
           s.username AS seller_username,
           b.username AS buyer_username,
           ai.filename AS album_image
    FROM listings l
    JOIN albums al  ON l.album_mbid  = al.album_mbid
    JOIN artists ar ON al.artist_mbid = ar.artist_mbid
    JOIN users s    ON l.seller_id   = s.id
    LEFT JOIN users b           ON l.buyer_id  = b.id
    LEFT JOIN album_images ai   ON al.album_mbid = ai.album_mbid
    WHERE l.listing_id = ?
    ORDER BY ai.created_at DESC
    LIMIT 1
");
$stmt->execute([$listing_id]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    header('Location: profile.php');
    exit;
}

$is_seller = ($user_id === (int)$listing['seller_id']);
$is_buyer  = ($listing['buyer_id'] !== null && $user_id === (int)$listing['buyer_id']);

if (!$is_seller && !$is_buyer && !$is_admin) {
    header('Location: profile.php');
    exit;
}

$badgeClass = match($listing['status']) {
    'available' => 'status-available',
    'complete'  => 'status-complete',
    'rejected'  => 'status-rejected',
    'pending'   => 'status-pending',
    default     => 'status-pending',
};
$badgeLabel = match($listing['status']) {
    'available' => 'Approved',
    'complete'  => 'Sold',
    'rejected'  => 'Rejected',
    'pending'   => 'Pending Review',
    default     => 'Pending',
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing #<?= $listing['listing_id'] ?> — MusicMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/profile.css">
    <style>
        .listing-detail-page {
            max-width: 680px;
            margin: 3rem auto;
            padding: 0 1.5rem 3rem;
        }
        .detail-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .detail-header a {
            color: #666;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .detail-header a:hover { color: #1a1a1a; }
        .detail-header .sep { color: #ccc; font-size: 0.85rem; }
        .detail-header .current { color: #1a1a1a; font-size: 0.85rem; }
        .album-hero {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        .album-art {
            width: 110px;
            height: 110px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1.5px solid #e2e2de;
        }
        .album-art-placeholder {
            width: 110px;
            height: 110px;
            border-radius: 6px;
            background: #f0f0ee;
            border: 1.5px solid #e2e2de;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ccc;
            font-size: 2.5rem;
        }
        .album-hero-info { flex: 1; min-width: 0; }
        .album-hero-eyebrow {
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 0.25rem;
        }
        .album-hero-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.2rem;
            line-height: 1.2;
        }
        .album-hero-artist {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.75rem;
        }
        .price-display {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1;
            margin-bottom: 0.4rem;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .detail-item {}
        .detail-item-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 0.25rem;
        }
        .detail-item-value {
            font-size: 0.9rem;
            color: #1a1a1a;
            font-weight: 500;
        }
        .detail-item-value.muted { color: #999; font-weight: 400; }
        .ref-id {
            font-size: 0.78rem;
            color: #999;
            font-family: monospace;
        }
        @media (max-width: 520px) {
            .album-hero { flex-direction: column; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main class="listing-detail-page">

    <div class="detail-header">
        <a href="profile.php"><i class="bi bi-arrow-left"></i> My Profile</a>
        <span class="sep">/</span>
        <span class="current">Listing #<?= $listing['listing_id'] ?></span>
    </div>

    <!-- Album hero -->
    <div class="section-card mb-3">
        <div class="album-hero">
            <?php if (!empty($listing['album_image'])): ?>
                <img src="/uploads/albums/<?= htmlspecialchars($listing['album_image']) ?>"
                     alt="<?= htmlspecialchars($listing['album_name']) ?>"
                     class="album-art">
            <?php else: ?>
                <div class="album-art-placeholder"><i class="bi bi-vinyl"></i></div>
            <?php endif; ?>
            <div class="album-hero-info">
                <div class="album-hero-eyebrow">Album</div>
                <div class="album-hero-name">
                    <a href="album.php?mbid=<?= urlencode($listing['album_mbid']) ?>"
                       style="color:inherit;text-decoration:none;">
                        <?= htmlspecialchars($listing['album_name']) ?>
                    </a>
                </div>
                <div class="album-hero-artist"><?= htmlspecialchars($listing['artist_name']) ?></div>
                <div class="price-display">$<?= number_format((float)$listing['price'], 2) ?></div>
                <span class="status-badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
            </div>
        </div>
    </div>

    <!-- Transaction details -->
    <div class="section-card mb-3">
        <h2 class="section-title">Transaction Details</h2>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-item-label">Listed On</div>
                <div class="detail-item-value"><?= date('d M Y', strtotime($listing['created_at'])) ?></div>
            </div>
            <?php if ($listing['status'] === 'complete' && $listing['purchased_at']): ?>
            <div class="detail-item">
                <div class="detail-item-label">Purchased On</div>
                <div class="detail-item-value"><?= date('d M Y, g:i A', strtotime($listing['purchased_at'])) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($is_buyer || $is_admin): ?>
            <div class="detail-item">
                <div class="detail-item-label">Seller</div>
                <div class="detail-item-value"><?= htmlspecialchars($listing['seller_username']) ?></div>
            </div>
            <?php endif; ?>
            <?php if (($is_seller || $is_admin) && $listing['buyer_username']): ?>
            <div class="detail-item">
                <div class="detail-item-label">Buyer</div>
                <div class="detail-item-value"><?= htmlspecialchars($listing['buyer_username']) ?></div>
            </div>
            <?php endif; ?>
            <div class="detail-item">
                <div class="detail-item-label">Listing Reference</div>
                <div class="detail-item-value ref-id">#<?= $listing['listing_id'] ?></div>
            </div>
            <?php if (($is_buyer || $is_admin) && !empty($listing['stripe_session_id'])): ?>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <div class="detail-item-label">Payment Reference</div>
                <div class="detail-item-value ref-id"><?= htmlspecialchars($listing['stripe_session_id']) ?></div>
            </div>
            <?php endif; ?>
            <?php if ($listing['status'] === 'rejected' && !empty($listing['rejection_reason'])): ?>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <div class="detail-item-label" style="color:#b91c1c;">Rejection Reason</div>
                <div class="detail-item-value" style="color:#b91c1c;"><?= htmlspecialchars($listing['rejection_reason']) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="profile-actions">
        <a href="profile.php" class="btn-dark-custom">
            <i class="bi bi-arrow-left"></i> Back to Profile
        </a>
        <a href="album.php?mbid=<?= urlencode($listing['album_mbid']) ?>" class="btn-outline-custom">
            <i class="bi bi-vinyl"></i> View Album
        </a>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
