<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user data for the header
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Wishlist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/css/navigation.css">
    <link rel="stylesheet" href="/css/profile.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main class="profile-page">
        <div class="profile-header">
            <div class="avatar">
                <?= strtoupper(substr(htmlspecialchars($user['username']), 0, 1)) ?>
            </div>
            <div class="profile-header-info">
                <p class="profile-eyebrow">Member Profile</p>
                <h1 class="profile-name"><?= htmlspecialchars($user['username']) ?>'s Wishlist</h1>
            </div>
        </div>

        <div class="section-card" id="wishlist-section">
            <h2 class="section-title">My Wishlist</h2>
            <div id="wishlist-list">
                <div class="empty-state">
                    <i class="bi bi-hourglass-split"></i>Loading...
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="profile.php" class="btn-dark-custom">
                <i class="bi bi-person"></i> Back to Profile
            </a>
            <a href="index.php" class="btn-outline-custom">
                <i class="bi bi-house"></i> Home
            </a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        // wishlist 
        function escHtmlProfile(str) {
            var div = document.createElement('div');
            div.textContent = String(str);
            return div.innerHTML;
        }

        function formatWishlistDate(dateStr) {
            return new Date(dateStr).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function buildWishlistRow(item) {
            return '<div class="order-row-profile" id="wishlist-item-' + escHtmlProfile(item.id) + '">' +
                '<div class="order-info">' +
                '<div class="order-album-name">' +
                '<a href="album.php?mbid=' + encodeURIComponent(item.album_mbid) + '" style="color:inherit;text-decoration:underline;text-underline-offset:3px;">' +
                escHtmlProfile(item.album_name) +
                '</a>' +
                '</div>' +
                '<div class="order-meta-text">' + escHtmlProfile(item.artist_name) + '</div>' +
                '<div class="order-date">' + formatWishlistDate(item.created_at) + '</div>' +
                '</div>' +
                '<div class="order-right" style="flex-direction: row; align-items: center; gap: 0.5rem;">' +
                '<a href="album.php?mbid=' + encodeURIComponent(item.album_mbid) + '" class="btn btn-sm btn-dark" style="font-family: \'DM Sans\', sans-serif; font-size: 0.8rem; font-weight: 500; padding: 0.3rem 0.75rem; border-radius: 6px;"><i class="bi bi-cart me-1"></i> Add to cart</a>' +
                '<button class="btn btn-sm wishlist-remove-btn" onclick="removeWishlistItem(this, \'' + escHtmlProfile(item.album_mbid) + '\', ' + escHtmlProfile(item.id) + ')">' +
                'Remove' +
                '</button>' +
                '</div>' +
                '</div>';
        }

        function loadWishlist() {
            fetch('/api/wishlist.php?action=get')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var container = document.getElementById('wishlist-list');
                    if (!data.items || !data.items.length) {
                        container.innerHTML = '<div class="empty-state"><i class="bi bi-heart"></i>No albums in your wishlist yet. <a href="listings.php">Browse albums</a></div>';
                        return;
                    }
                    container.innerHTML = data.items.map(buildWishlistRow).join('');
                })
                .catch(function () {
                    document.getElementById('wishlist-list').innerHTML = '<div class="empty-state"><i class="bi bi-exclamation-circle"></i>Could not load wishlist.</div>';
                });
        }

        function removeWishlistItem(btn, albumMbid, itemId) {
            btn.disabled = true;
            fetch('/api/wishlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'toggle', album_mbid: albumMbid })
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.error) { alert(data.error); btn.disabled = false; return; }
                    var row = document.getElementById('wishlist-item-' + itemId);
                    if (row) {
                        row.style.transition = 'opacity 0.25s';
                        row.style.opacity = '0';
                        setTimeout(function () { row.remove(); loadWishlist(); }, 300);
                    }
                })
                .catch(function () {
                    alert('Network error. Please try again.');
                    btn.disabled = false;
                });
        }

        loadWishlist();
    </script>
</body>

</html>