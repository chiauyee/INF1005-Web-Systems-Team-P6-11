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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="ms-2"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                    <li class="nav-item me-2">
                        <a class="btn <?= $current_page == 'login.php' ? 'btn-outline-dark' : 'btn-dark' ?>" href="login.php">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn <?= $current_page == 'register.php' ? 'btn-outline-dark' : 'btn-dark' ?>" href="register.php">
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>