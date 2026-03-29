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
            <button class="btn btn-dark w-100" onclick="handleCheckout()">Checkout</button>
        </div>
    </div>
</div>

<!-- Already in cart toast -->
<div id="cart-toast" role="status" style="
  position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%) translateY(1rem);
  background: #212529; color: #fff; padding: 0.6rem 1.4rem;
  display: none; border-radius: 999px; font-size: 1.5rem; font-family: 'DM Sans', sans-serif;
  opacity: 0; pointer-events: none; transition: opacity 0.25s, transform 0.25s;
  z-index: 9999; white-space: nowrap;">
  Already added to cart
</div>

<!-- Login required toast -->
<div id="login-toast" role="status" style="
  position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%) translateY(1rem);
  background: #212529; color: #fff; padding: 0.6rem 1.4rem;
  display: none; border-radius: 999px; font-size: 1.5rem; font-family: 'DM Sans', sans-serif;
  opacity: 0; pointer-events: none; transition: opacity 0.25s, transform 0.25s;
  z-index: 9999; white-space: nowrap;">
  Please log in to proceed to checkout
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

let _cartToastTimer = null;
function showCartToast() {
    const toast = document.getElementById('cart-toast');
    toast.style.display = 'block';
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(_cartToastTimer);
    _cartToastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(1rem)';
        toast.style.display = 'none'
    }, 2500);
}

let _loginToastTimer = null;
function showLoginToast() {
    const toast = document.getElementById('login-toast');
    toast.style.opacity = '1';
    toast.style.display = 'block';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(_loginToastTimer);
    _loginToastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(1rem)';
        toast.style.display = 'none';
    }, 2500);
}

function handleCheckout() {
    <?php if (isset($_SESSION['user_id'])): ?>
    window.location.href = '/checkout.php';
    <?php else: ?>
    showLoginToast();
    <?php endif; ?>
}

function addToCart(listingId) {

    if (!IS_LOGGED_IN) {
        window.location.href = 'login.php';
        return;
    }

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
        if (data.already_in_cart) {
            showCartToast();
        } else {
            const drawer = new bootstrap.Offcanvas(document.getElementById('cartDrawer'));
            drawer.show();
        }
    });
}

document.getElementById('cartDrawer').addEventListener('show.bs.offcanvas', loadCartItems);
</script>
