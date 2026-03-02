<?php
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/main.css">
  </head>

  <body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

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
        <div class="row g-4">
          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 1</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 2</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 3</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 4</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container mt-5">
        <div class="row g-4">
          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 5</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 6</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 7</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card shadow-sm">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80" class="featured_album_img" alt="Album 1">
              <div class="card-body">
                <h5 class="card-title">Album Name 8</h5>
                <p class="card-text">Artist Name</p>
                <a class="btn btn-outline-dark" href="#">Click for more info</a>
                <a class="btn btn-outline-dark" href="#" >
                  <i class="bi bi-cart"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>

