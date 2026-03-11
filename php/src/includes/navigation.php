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
                <li class="nav-item"><a class="nav-link active" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link active" href="faq.php">FAQ</a></li>
            </ul>
            
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <?php $cartCount = count($_SESSION['cart'] ?? []); ?>
                    <button class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" aria-controls="cartDrawer">
                        <i class="bi bi-cart fs-5"></i>
                        <span id="cart-badge" class="badge bg-dark ms-1<?= $cartCount === 0 ? ' d-none' : '' ?>"><?= $cartCount ?></span>
                    </button>
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

<!-- Cart Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="cartDrawerLabel">
            <i class="bi bi-cart me-2"></i>Your Cart
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-0">
        <div id="cart-items" class="flex-grow-1 overflow-auto p-3">
            <p class="text-muted text-center mt-4">Loading...</p>
        </div>
        <div class="border-top p-3">
            <div class="d-flex justify-content-between mb-3">
                <strong>Total</strong>
                <strong id="cart-total">$0.00</strong>
            </div>
            <button class="btn btn-dark w-100">Checkout</button>
        </div>
    </div>
</div>

<script>
function escHtmlCart(str) {
    const div = document.createElement('div');
    div.textContent = String(str);
    return div.innerHTML;
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('d-none');
    }
}

function loadCartItems() {
    fetch('/api/cart.php?action=get')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('cart-items');
            const items = data.items;

            if (!items.length) {
                container.innerHTML = '<p class="text-muted text-center mt-4">Your cart is empty.</p>';
                document.getElementById('cart-total').textContent = '$0.00';
                return;
            }

            let total = 0;
            container.innerHTML = items.map(item => {
                total += parseFloat(item.price);
                return `
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-semibold">${escHtmlCart(item.album_name)}</div>
                            <div class="text-muted small">${escHtmlCart(item.artist_name)}</div>
                            <div class="text-muted small">Seller: ${escHtmlCart(item.seller)}</div>
                        </div>
                        <div class="text-end ms-3">
                            <div class="fw-bold">$${parseFloat(item.price).toFixed(2)}</div>
                            <button class="btn btn-sm btn-outline-danger mt-1" onclick="removeFromCart(${escHtmlCart(item.listing_id)})">Remove</button>
                        </div>
                    </div>
                `;
            }).join('');

            document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
        });
}

function removeFromCart(listingId) {
    fetch('/api/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'remove', listing_id: listingId })
    })
    .then(r => r.json())
    .then(data => {
        updateCartBadge(data.count);
        loadCartItems();
    });
}

function addToCart(listingId) {
    fetch('/api/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'add', listing_id: listingId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        updateCartBadge(data.count);
        const drawer = new bootstrap.Offcanvas(document.getElementById('cartDrawer'));
        drawer.show();
    });
}

document.getElementById('cartDrawer').addEventListener('show.bs.offcanvas', loadCartItems);
</script>