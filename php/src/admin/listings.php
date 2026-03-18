<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* ========= HANDLE ACTIONS ========= */
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];

    $stmt = $pdo->prepare("
        UPDATE listings
        SET status = 'available'
        WHERE listing_id = ?
    ");
    $stmt->execute([$id]);

    header("Location: listings.php");
    exit;
}

if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];

    $stmt = $pdo->prepare("
        UPDATE listings
        SET status = 'rejected'
        WHERE listing_id = ?
    ");
    $stmt->execute([$id]);

    header("Location: listings.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("
        DELETE FROM listings
        WHERE listing_id = ?
    ");
    $stmt->execute([$id]);

    header("Location: listings.php");
    exit;
}

/* ========= FETCH PENDING LISTINGS ========= */
$stmt = $pdo->query("
    SELECT
        listings.listing_id,
        listings.price,
        listings.created_at,
        users.username AS seller,
        albums.album_name,
        artists.artist_name
    FROM listings
    JOIN users
        ON listings.seller_id = users.id
    JOIN albums
        ON listings.album_mbid = albums.album_mbid
    JOIN artists
        ON albums.artist_mbid = artists.artist_mbid
    WHERE listings.status = 'pending'
    ORDER BY listings.created_at ASC
");

$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPending = count($listings);
$oldestPending = $totalPending > 0
    ? date('d M Y H:i', strtotime($listings[0]['created_at']))
    : 'No pending listings';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Listing Moderation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include __DIR__ . '/inc/admin_nav.php'; ?>

    <main class="container mt-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">Pending Listings</h1>
                <p class="text-muted mb-0">Review listings awaiting approval. Oldest submissions appear first.</p>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pending Listings</h6>
                        <h4 class="mb-0"><?= $totalPending ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Oldest Pending Submission</h6>
                        <h4 class="mb-0 fs-5"><?= htmlspecialchars($oldestPending) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi bi-card-checklist me-2"></i>Moderation Queue</strong>
                </div>
                <span class="badge text-bg-warning"><?= $totalPending ?></span>
            </div>

            <div class="card-body">
                <?php if (empty($listings)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                        <p class="text-muted mt-3 mb-0">No listings awaiting moderation.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Queue</th>
                                    <th>ID</th>
                                    <th>Artist</th>
                                    <th>Album</th>
                                    <th>Seller</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listings as $index => $listing): ?>
                                    <tr>
                                        <td>
                                            <span class="badge text-bg-secondary">
                                                <?= $index + 1 ?>
                                            </span>
                                        </td>
                                        <td><?= $listing['listing_id'] ?></td>
                                        <td><?= htmlspecialchars($listing['artist_name']) ?></td>
                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($listing['album_name']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($listing['seller']) ?></td>
                                        <td class="fw-semibold">$<?= number_format($listing['price'], 2) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($listing['created_at'])) ?></td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a
                                                    href="?approve=<?= $listing['listing_id'] ?>"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </a>

                                                <a
                                                    href="?reject=<?= $listing['listing_id'] ?>"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-x-lg"></i> Reject
                                                </a>

                                                <a
                                                    href="?delete=<?= $listing['listing_id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this listing?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>

</html>