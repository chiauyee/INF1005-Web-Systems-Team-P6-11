<?php
session_start();

if (!isset($_SESSION['cart_count']))
{
  $_SESSION['cart_count'] = 0;
}

if (isset($_POST['add_to_cart']))
{
  $_SESSION['cart_count'] += 1;
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/navigation.css"> 
    <link rel="stylesheet" href="/css/main.css">
  </head>

  <body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main>
      <!-- Background Section -->
      <section class="background">
        <div class="container">
          <h1>Discover Music Collections</h1>
          <p>Search, buy and sell vinyl records, CDs and more</p>
          <form class="d-flex justify-content-center mt-4" role="search">
            <input class="form-control w-50 me-2" type="search" placeholder="Search albums title, artists..." aria-label="Search"></input>
            <button class="btn btn-dark" type="submit">Search</button>
          </form>
        </div>
      </section>

      <!-- Recently Listed Albums -->
      <section class="container mt-3 mb-3">
        <div class="container">
          <h2 class="mb-4 text-center">Recently Listed</h2>
          <div id="featured-albums" class="row g-3">
            <p class="text-center text-muted">Loading...</p>
          </div>
        </div>
      </section>
    </main>


    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
      const ALBUM_COVER = 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80';

      function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
      }

      function renderFeaturedAlbums(listings) {
        const container = document.getElementById('featured-albums');

        if (!listings.length) {
          container.innerHTML = '<p class="text-center text-muted">No listings available yet.</p>';
          return;
        }

        container.innerHTML = listings.map(listing => `
          <div class="col-md-3">
            <div class="card shadow-sm h-100">
              <a href="album.php?mbid=${encodeURIComponent(listing.album_mbid)}" class="text-decoration-none text-dark">
                <img src="${ALBUM_COVER}" class="card-img-top" alt="${escHtml(listing.album_name)}">
                <div class="card-body">
                  <h3 class="card-title fs-5">${escHtml(listing.album_name)}</h3>
                  <p class="card-text mb-1">${escHtml(listing.artist_name)}</p>
                  <p class="card-text mb-1 text-muted small">Listed by: <b>${escHtml(listing.seller)}</b></p>
                  <p class="card-text mb-1 fw-bold">$${parseFloat(listing.price).toFixed(2)}</p>
                  <p class="card-text text-muted small">${formatDate(listing.created_at)}</p>
                </div>
              </a>

              <div class="card-body d-flex gap-2">
                <form method="POST" class="m-0">
                  <input type="hidden" name="add_to_cart" value="1">
                  <button type="submit" class="btn btn-outline-dark">Add to cart
                    <i class="bi bi-cart"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        `).join('');
      }

      function escHtml(str) {
        const div = document.createElement('div');
        div.textContent = String(str);
        return div.innerHTML;
      }

      fetch('/api/get_listings.php')
        .then(r => r.json())
        .then(json => {
          if (json.error) {
            document.getElementById('featured-albums').innerHTML =
              '<p class="text-center text-danger">Failed to load listings.</p>';
            return;
          }
          renderFeaturedAlbums(json.data.slice(0, 8));
        })
        .catch(() => {
          document.getElementById('featured-albums').innerHTML =
            '<p class="text-center text-danger">Failed to load listings.</p>';
        });
    </script>
</body>
</html>
