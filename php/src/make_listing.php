<?php
session_start();
require 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Make Listing</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/css/navigation.css"> 
  <link rel="stylesheet" href="/css/main.css"> 
  <link rel="stylesheet" href="/css/make_listing.css">
</head>

<body>
  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <div class="listing-page">
    <p class="page-eyebrow">Marketplace</p>
    <h1 class="page-heading">Create a listing</h1>
    <p class="page-sub">Search for your album to verify its metadata, then set a price and publish.</p>

    <!-- Login prompt banner -->
    <?php if (!$isLoggedIn): ?>
    <div class="alert alert-info d-flex align-items-center mb-4" style="border-left: 4px solid #0dcaf0;">
      <i class="bi bi-info-circle-fill me-3 fs-4"></i>
      <div>
        <strong>You're browsing as a guest.</strong> 
        <a href="login.php?redirect=/make_listing.php" class="alert-link">Log in</a> or 
        <a href="register.php?redirect=/make_listing.php" class="alert-link">register</a> to publish listings.
      </div>
    </div>
    <?php endif; ?>

    <form id="form" method="POST" action="/api/create_listing.php" onsubmit="return checkLoginBeforeSubmit(event)">
      <div class="listing-card">
        <h2 class="listing-card-title">
          <i class="bi bi-vinyl me-2"></i>Album Details
        </h2>

        <div class="mb-3">
          <label class="form-label" for="searched-artist">Artist Name</label>
          <div class="field-input-wrap">
            <i class="bi bi-person"></i>
            <input type="text" id="searched-artist" class="form-control" placeholder="Artist name..." required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="searched-album">Album Title</label>
          <div class="field-input-wrap">
            <i class="bi bi-music-note"></i>
            <input type="text" id="searched-album" class="form-control" placeholder="Album name..." required>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label" for="price">Price (SGD)</label>
            <div class="field-input-wrap">
              <i class="bi bi-currency-dollar"></i>
              <input type="number" id="price" name="price" class="form-control" placeholder="0.00" step="0.01" min="0" inputmode="decimal" required>
            </div>           
        </div>

        <button type="button" class="btn-verify" id="btn-verify" onclick=search_for_metadata()>
          <i class="bi bi-search"></i> Verify Metadata
        </button>
      </div>
      
      <div class="hidden-inputs">
        <input name='artist' type='hidden' id='metadata-artist'>
        <input name='album' type='hidden' id='metadata-album'>
        <input name='artist_mbid' type='hidden' id='metadata-artist-mbid'>
        <input name='album_mbid' type='hidden' id='metadata-album-mbid'>
        <input name='cached' type='hidden' id='metadata-cached'>
      </div>

      <div class="metadata-card" id="metadata-card">
        <h2 class="metadata-card-title">
          <i class="bi bi-check2-circle"></i>Metadata Result
          <span class="match-badge" id="match-badge"></span>
        </h2>

        <div id="metadata-found">
          <div class="metadata-grid">
            <div class="metadata-field">
              <label>Artist</label>
              <input type="text" class="form-control" id="display-artist" readonly>
            </div>
                              
            <div class="metadata-field">
              <label>Album</label>
              <input type="text" class="form-control" id="display-album" readonly>
            </div>
                              
            <div class="metadata-field">
              <label>Artist MBID</label>
              <input type="text" class="form-control" id="display-artist-mbid" readonly>
            </div>
                              
            <div class="metadata-field">
              <label>Album MBID</label>
              <input type="text" class="form-control" id="display-album-mbid" readonly>
            </div>
          </div>
          
          <button type='submit' class="btn-submit-listing" id='metadata-submit' disabled >
            <i class="bi bi-plus-circle"></i> Publish Listing
          </button>
        </div>

        <div id="metadata-not-found" style="display:none;">
          <div class="not-found-state">
            <i class="bi bi-question-circle"></i> No match found. Try adjusting the artist or album name.
          </div>
        </div>
      </div>
    </form>

    <div class="mt-2">
      <a href="index.php" style="font-size:0.82rem; color:black; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem;">
        <i class="bi bi-arrow-left"></i> Back to listings
      </a>
    </div>
  </div>

  <?php include __DIR__ . '/includes/footer.php'; ?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script>
    const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    
    function checkLoginBeforeSubmit(event) {
      if (!isLoggedIn) {
        event.preventDefault();
        
        const formData = {
          artist: $("#searched-artist").val(),
          album: $("#searched-album").val(),
          price: $("#price").val(),
          metadataArtist: $("#metadata-artist").val(),
          metadataAlbum: $("#metadata-album").val(),
          metadataArtistMbid: $("#metadata-artist-mbid").val(),
          metadataAlbumMbid: $("#metadata-album-mbid").val(),
          metadataCached: $("#metadata-cached").val() === 'on',
          displayArtist: $("#display-artist").val(),
          displayAlbum: $("#display-album").val(),
          displayArtistMbid: $("#display-artist-mbid").val(),
          displayAlbumMbid: $("#display-album-mbid").val()
        };
        
        sessionStorage.setItem('pendingListing', JSON.stringify(formData));
        
        window.location.href = 'login.php?redirect=/make_listing.php';
        return false;
      }
      
      alert('Your listing has been published and sent to an admin for review.\nIt will appear publicly once approved.');
      return true;
    }
    
    $(document).ready(function() {
      const savedData = sessionStorage.getItem('pendingListing');
      if (savedData) {
        const formData = JSON.parse(savedData);
        
        $("#searched-artist").val(formData.artist);
        $("#searched-album").val(formData.album);
        $("#price").val(formData.price);
        
        if (formData.metadataArtist) {
          $("#metadata-artist").val(formData.metadataArtist);
          $("#metadata-album").val(formData.metadataAlbum);
          $("#metadata-artist-mbid").val(formData.metadataArtistMbid);
          $("#metadata-album-mbid").val(formData.metadataAlbumMbid);
          $("#metadata-cached").val(formData.metadataCached ? 'on' : '');
          
          $("#display-artist").val(formData.displayArtist);
          $("#display-album").val(formData.displayAlbum);
          $("#display-artist-mbid").val(formData.displayArtistMbid);
          $("#display-album-mbid").val(formData.displayAlbumMbid);
          
          $('#metadata-card').addClass('visible');
          $('#metadata-found').show();
          $('#metadata-submit').prop('disabled', false);
          
          if (formData.metadataCached) {
            $('#match-badge').attr('class', 'match-badge cached')
              .html('<i class="bi bi-database"></i> From cache');
          } else {
            $('#match-badge').attr('class', 'match-badge found')
              .html('<i class="bi bi-check-circle"></i> Verified');
          }
        }
        
        sessionStorage.removeItem('pendingListing');
        
        $('<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' +
          '<i class="bi bi-check-circle-fill me-2"></i>' +
          'Welcome back! Your listing details have been restored.' +
          '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
          '</div>').insertAfter('.page-sub');
      }
    });

    function handle_data(data) {
      var status = data.status;
      var d = data.data;
      
      switch (status) {
        case "not_found":
          return false;
          
        case "found_musicbrainz":
          return {
            artist_name: d["artist-credit"][0].name.toUpperCase(),
            artist_mbid: d["artist-credit"][0].artist.id,
            album_name:  d.title.toUpperCase(),
            album_mbid:  d.id,
            cached:      false
          };

        case "found_db":
          return Object.assign({}, d, { cached: true });
      }
    }

    function search_for_metadata() {
      const btn = document.getElementById('btn-verify');
      btn.classList.add('loading');
      btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Searching...';
      
      fetch("api/search_metadata.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          artist: $("#searched-artist").val(),
          album:  $("#searched-album").val()
        })
      })
      
      .then(r => r.json())
      .then(data => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="bi bi-search"></i> Verify Metadata';
        
        const card  = document.getElementById('metadata-card');
        const badge = document.getElementById('match-badge');
        const found = document.getElementById('metadata-found');
        const notFound = document.getElementById('metadata-not-found');
        
        card.classList.add('visible');
        const album = handle_data(data);

        if (!album) {
          badge.className = 'match-badge not-found';
          badge.innerHTML = '<i class="bi bi-x-circle"></i> No match';
          found.style.display    = 'none';
          notFound.style.display = 'block';
          return;
        }

        $("#metadata-artist").val(album.artist_name);
        $("#metadata-album").val(album.album_name);
        $("#metadata-artist-mbid").val(album.artist_mbid);
        $("#metadata-album-mbid").val(album.album_mbid);
        $("#metadata-cached").val(album.cached ? 'on' : '');

        $("#display-artist").val(album.artist_name);
        $("#display-album").val(album.album_name);
        $("#display-artist-mbid").val(album.artist_mbid);
        $("#display-album-mbid").val(album.album_mbid);

        if (album.cached) {
          badge.className = 'match-badge cached';
          badge.innerHTML = '<i class="bi bi-database"></i> From cache';
        } else {
          badge.className = 'match-badge found';
          badge.innerHTML = '<i class="bi bi-check-circle"></i> Verified';
        }

        found.style.display    = 'block';
        notFound.style.display = 'none';
        $("#metadata-submit").prop("disabled", false);
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      })
      .catch(() => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="bi bi-search"></i> Verify Metadata';
      });
    }
  </script>

</body>
</html>

