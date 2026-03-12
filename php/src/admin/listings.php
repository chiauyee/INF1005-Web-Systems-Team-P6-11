<?php
require '../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* HANDLE ACTIONS */
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare("
        UPDATE listings
        SET status='available'
        WHERE listing_id=?
    ");

    $stmt->execute([$_GET['approve']]);
}

if (isset($_GET['reject'])) {
    $stmt = $pdo->prepare("
        UPDATE listings
        SET status='rejected'
        WHERE listing_id=?
    ");

    $stmt->execute([$_GET['reject']]);
}

if (isset($_GET['delete'])) {

    $stmt = $pdo->prepare("
        DELETE FROM listings
        WHERE listing_id=?
    ");
    $stmt->execute([$_GET['delete']]);
}

/* FETCH PENDING LISTINGS */
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

WHERE listings.status='pending'

ORDER BY listings.created_at ASC
");

$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Listing Moderation</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css"> 
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/inc/admin_nav.php'; ?>

    <main class="container mt-4 flex-grow-1">
        <h1 class="mb-4">Pending Listings</h1>

        <!-- Moderation summary -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Awaiting Review</h6>
                    <h4 class="mb-0"><?= count($listings) ?> Listings</h4>
                </div>
            </div>
        </div>

        <?php if (empty($listings)): ?>
            <div class="alert alert-success">
                No listings awaiting moderation.
            </div>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
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

                        <?php foreach ($listings as $listing): ?>
                            <tr>
                                <td><?= $listing['listing_id'] ?></td>
                                <td><?= htmlspecialchars($listing['artist_name']) ?></td>
                                <td><?= htmlspecialchars($listing['album_name']) ?></td>
                                <td><?= htmlspecialchars($listing['seller']) ?></td>
                                <td class="fw-semibold">$<?= number_format($listing['price'], 2) ?></td>
                                <td><?= date('d M Y H:i', strtotime($listing['created_at'])) ?></td>

                                <td class="text-end">
                                    <div class="btn-group">

                                        <a href="?approve=<?= $listing['listing_id'] ?>"
                                            class="btn btn-sm btn-success">
                                            Approve
                                        </a>

                                        <a href="?reject=<?= $listing['listing_id'] ?>"
                                            class="btn btn-sm btn-outline-warning">
                                            Reject
                                        </a>

                                        <a href="?delete=<?= $listing['listing_id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this listing?')">
                                            Delete
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>