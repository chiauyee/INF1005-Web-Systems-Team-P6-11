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

    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
      }
      .navbar-brand {
        font-weight: bold;
        color: #343a40 !important;
      }
      .background {
        background: url('https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80') no-repeat center center;
        background-size: cover;
        color: white;
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
      footer {
        background-color: #343a40;
        color: white;
        padding: 20px 0;
      }
      footer a {
        color: #adb5bd;
        text-decoration: none;
      }
    </style>
  </head>

  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="#">BRANDNAME</a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        

        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav mx-auto justify-content-center">
            <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link active" href="#">Shop Music</a></li>
            <li class="nav-item"><a class="nav-link active" href="#">Sell Music</a></li>
            <li class="nav-item"><a class="nav-link active" href="#">About Us</a></li>
          </ul>

          <ul class="navbar-nav">  
            <?php if (isset($_SESSION['user_id'])): ?>
              <li class="nav-item">
                <span class="nav-link disabled">Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="profile.php">View Profile</a>
              </li>
              <li class="nav-item">
                <a class="btn btn-outline-dark ms-2" href="logout.php">Logout</a>
              </li>
            <?php else: ?>
              <li class="nav-item mb-2">
                <a class="btn btn-outline-dark ms-2" href="login.php">Login</a>
              </li>
              <li class="nav-item mb-2">
                <a class="btn btn-dark ms-2" href="register.php">Register</a>
              </li>
            <?php endif; ?>
          </ul> 
        </div>
      </div>
    </nav>

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

    <footer>
      <div class="container text-center">
        <p><em>Copyright &copy; 2026 BRANDNAME. All Rights Reserved.</em></p>
        <p>Cookie Policy | Terms of Service | Privacy Policy</p>
      </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>

