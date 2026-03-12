<?php
require '../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* USERS */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

/* LISTINGS */
$totalListings = $pdo->query("SELECT COUNT(*) FROM listings")->fetchColumn();

$pendingListings = $pdo->query("
    SELECT COUNT(*)
    FROM listings
    WHERE status='pending'
")->fetchColumn();

/* IMAGES */
$artistImages = $pdo->query("SELECT COUNT(*) FROM artist_images")->fetchColumn();
$albumImages  = $pdo->query("SELECT COUNT(*) FROM album_images")->fetchColumn();

/* COMMENTS */
$artistComments = $pdo->query("SELECT COUNT(*) FROM artist_comments")->fetchColumn();
$albumComments  = $pdo->query("SELECT COUNT(*) FROM album_comments")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css"> 
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/inc/admin_nav.php'; ?>

    <main class="container mt-4 mb-5 flex-grow-1">
        <h1 class="mb-4">Admin Dashboard</h1>
        <div class="row g-4">

            <!-- Users -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Users</h6>
                        <h2 class="display-6 fw-bold"><?= $totalUsers ?></h2>
                    </div>
                </div>
            </div>

            <!-- Listings -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Listings</h6>
                        <h2 class="display-6 fw-bold"><?= $totalListings ?></h2>
                    </div>
                </div>
            </div>

            <!-- Pending Listings -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm border-warning h-100">
                    <div class="card-body">
                        <h6 class="text-warning">Pending Listings</h6>
                        <h2 class="display-6 fw-bold"><?= $pendingListings ?></h2>

                        <a href="listings.php" class="btn btn-sm btn-warning mt-2">
                            Review Listings
                        </a>

                    </div>
                </div>
            </div>

            <!-- Artist Images -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Artist Images</h6>
                        <h2 class="display-6 fw-bold"><?= $artistImages ?></h2>
                    </div>
                </div>
            </div>

            <!-- Album Images -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Album Images</h6>
                        <h2 class="display-6 fw-bold"><?= $albumImages ?></h2>
                    </div>
                </div>
            </div>

            <!-- Artist Comments -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Artist Comments</h6>
                        <h2 class="display-6 fw-bold"><?= $artistComments ?></h2>
                    </div>
                </div>
            </div>

            <!-- Album Comments -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Album Comments</h6>
                        <h2 class="display-6 fw-bold"><?= $albumComments ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/admin/dashboard.php">MusicMarket Admin</a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav mx-auto justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?= strtolower($current_page) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strtolower($current_page) == 'listings.php' ? 'active' : '' ?>" href="listings.php">Listings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strtolower($current_page) == 'users.php' ? 'active' : '' ?>" href="users.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strtolower($current_page) == 'comments.php' ? 'active' : '' ?>" href="comments.php">Comments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strtolower($current_page) == 'images.php' ? 'active' : '' ?>" href="images.php">Images</a>
                </li>
            </ul>

            <!-- Right side -->
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="inc/admin_profile.php">View Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a class="btn <?= strtolower($current_page) == 'login.php' ? 'btn-outline-dark' : 'btn-dark' ?>" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn <?= strtolower($current_page) == 'register.php' ? 'btn-outline-dark' : 'btn-dark' ?>" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

</body>
</html>