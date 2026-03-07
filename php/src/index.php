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
    
    <style>
      :root {
          --bg: #f8f8f6;
          --white: #ffffff;
          --border: #e2e2de;
          --text: #1a1a1a;
          --text-muted: #666;
          --input-focus: rgba(26, 26, 26, 0.08);
      }

      * { box-sizing: border-box; }

      body {
          font-family: 'DM Sans', sans-serif;
          font-weight: 300;
          background-color: var(--bg);
          color: var(--text);
          min-height: 100vh;
      }

      .navbar-brand {
          font-weight: 700 !important;
          color: var(--text) !important;
          font-family: 'DM Sans', sans-serif;
      }

      .navbar .nav-link, .navbar .btn {
          font-family: 'DM Sans', sans-serif;
          font-weight: 300;
          color: var(--text);
      }

      .background {
          background: url('https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80') no-repeat center center;
          background-size: cover;
          color: var(--white);
          padding: 120px 0;
          min-height: 400px;
          text-align: center;
      }

      .background h1 {
          font-size: 3rem;
          font-weight: bold;
      }

      .background p {
          font-size: 1.25rem;
      }

      .btn, .btn-primary, .navbar .btn {
          background-color: #1a1a1a; 
          color: #fff;
          font-family: 'DM Sans', sans-serif;
          font-weight: 500;
          border-radius: 6px;
          border: none;
          transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
          cursor: pointer;
      }

      .btn:hover,
      .btn-primary:hover {
          background-color: #333;
          transform: translateY(-1px);
          box-shadow: 0 6px 20px rgba(0,0,0,0.12);
      }

      .btn:active,
      .btn-primary:active {
          transform: translateY(0);
      }

      footer {
          background-color: #1a1a1a; /* match register-left panel */
          color: #fff;
          padding: 20px 0;
          text-align: center;
      }

      footer a {
          color: #fff;
          text-decoration: none;
      }

      .text-muted {
          color: var(--text-muted);
      }

      @media (max-width: 768px) {
          .background {
              padding: 80px 0;
              min-height: 300px;
          }
      }
    </style>
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

      <!-- Featured Albums -->
      <section class="container mt-3 mb-3">
        <div class="container">
          <h2 class="mb-4 text-center">Featured Albums</h2>
          <div class="row g-3">
            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 1</h5>
                    <p class="card-text">Artist Name</p>
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

            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 2</h5>
                    <p class="card-text">Artist Name</p>
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

            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 3</h5>
                    <p class="card-text">Artist Name</p>
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

            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 4</h5>
                    <p class="card-text">Artist Name</p>
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

            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 5</h5>
                    <p class="card-text">Artist Name</p>
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

            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 6</h5>
                    <p class="card-text">Artist Name</p>
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

            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 7</h5>
                    <p class="card-text">Artist Name</p>
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
            
            <div class="col-md-3">
              <div class="card shadow-sm">
                <a href="product.php?id=1" class="text-decoration-none text-dark">
                  <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="card-img-top" alt="Album 1">
                  <div class="card-body">
                    <h3 class="card-title fs-5">Album Name 8</h5>
                    <p class="card-text">Artist Name</p>
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
        </div>
      </section>
    </main>
    

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>

