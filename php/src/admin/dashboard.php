<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* USERS */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

/* LISTINGS */
$totalListings = $pdo->query("SELECT COUNT(*) FROM listings")->fetchColumn();
$pendingListings = $pdo->query("
    SELECT COUNT(*)
    FROM listings
    WHERE status = 'pending'
")->fetchColumn();

/* IMAGES */
$artistImages = $pdo->query("SELECT COUNT(*) FROM artist_images")->fetchColumn();
$albumImages  = $pdo->query("SELECT COUNT(*) FROM album_images")->fetchColumn();
$totalImages = $artistImages + $albumImages;

/* COMMENTS */
$artistComments = $pdo->query("SELECT COUNT(*) FROM artist_comments")->fetchColumn();
$albumComments  = $pdo->query("SELECT COUNT(*) FROM album_comments")->fetchColumn();
$totalComments = $artistComments + $albumComments;

/* TOTAL MODERATION CONTENT */
$totalContent = $totalImages + $totalComments;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/inc/admin_nav.php'; ?>

    <main class="container mt-4 flex-grow-1">
        <div class="mb-4">
            <h1 class="mb-1">Admin Dashboard</h1>
            <p class="text-muted mb-0">Overview of site activity and moderation tools.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Total Users</h6>
                                <h2 class="display-6 fw-bold mb-0"><?= $totalUsers ?></h2>
                            </div>
                            <i class="bi bi-people fs-3 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Total Listings</h6>
                                <h2 class="display-6 fw-bold mb-0"><?= $totalListings ?></h2>
                            </div>
                            <i class="bi bi-music-note-list fs-3 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm border-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-warning mb-1">Pending Listings</h6>
                                <h2 class="display-6 fw-bold mb-2"><?= $pendingListings ?></h2>
                                <a href="listings.php" class="btn btn-sm btn-warning">
                                    Review Listings
                                </a>
                            </div>
                            <i class="bi bi-clock-history fs-3 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Content Items</h6>
                                <h2 class="display-6 fw-bold mb-0"><?= $totalContent ?></h2>
                            </div>
                            <i class="bi bi-collection fs-3 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-image me-2"></i>Image Breakdown</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span>Artist Images</span>
                            <span class="fw-semibold"><?= $artistImages ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Album Images</span>
                            <span class="fw-semibold"><?= $albumImages ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="images.php" class="btn btn-outline-secondary btn-sm">Manage Images</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-chat-left-text me-2"></i>Comment Breakdown</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span>Artist Comments</span>
                            <span class="fw-semibold"><?= $artistComments ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Album Comments</span>
                            <span class="fw-semibold"><?= $albumComments ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="comments.php" class="btn btn-outline-secondary btn-sm">Manage Comments</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>