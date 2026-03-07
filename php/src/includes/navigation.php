<?php
// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">MusicMarket</a>
        
        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mx-auto justify-content-center">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Shop Music</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Sell Music</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">About Us</a></li>
            </ul>
            
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-cart fs-5"></i>
                        <?php if (!empty($_SESSION['cart_count'])): ?>
                            <?= $_SESSION['cart_count'] ?>
                        <?php endif; ?>
                    </a>
                </li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="ms-2"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
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