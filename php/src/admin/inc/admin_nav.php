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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav mx-auto justify-content-center">
                <li class="nav-item"><a class="nav-link active" href="Dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="listings.php">Listings</a></li>
                <li class="nav-item"><a class="nav-link active" href="users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link active" href="comments.php">Comments</a></li>
                <li class="nav-item"><a class="nav-link active" href="images.php">Images</a></li>
            </ul>

            <!-- Right side -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <span class="navbar-text">
                        Admin: <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                </li>

                <li class="nav-item">
                    <a class="btn btn-dark" href="/logout.php">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>