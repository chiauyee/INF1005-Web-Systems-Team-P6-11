<?php session_start(); ?>

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
    
    <style>
      /* Intro Overlay */
      body.intro-active { overflow: hidden; }
      #vinyldisk-intro {
        position: fixed; inset: 0; z-index: 9999;
        display: flex; align-items: center; justify-content: center;
      }
      #intro-bg {
        position: absolute; inset: 0; background: #0a0a0a; z-index: 0;
      }
      #vinyldisk-intro.hiding { opacity: 0; pointer-events: none; }
      #vinyldisk-canvas-wrap { width: 100%; height: 100%; position: relative; cursor: grab; z-index: 1; }
      #vinyldisk-canvas-wrap:active { cursor: grabbing; }

      #vinyldisk-ui {
        position: absolute; bottom: 3rem; left: 50%;
        transform: translateX(-50%);
        text-align: center; pointer-events: none; z-index: 10;
        pointer-events: none;
      }
      #vinyldisk-ui h1 {
        font-family: 'Playfair Display', serif; font-size: 2.2rem;
        color: #fff; margin-bottom: 0.5rem; letter-spacing: 0.05em;
      }
      #vinyldisk-ui p {
        font-family: 'DM Sans', sans-serif; font-size: 0.85rem;
        text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.6);
        margin: 0;
      }
      
      #vinyldisk-hint {
        position: absolute; top: 3rem; left: 50%; transform: translateX(-50%);
        font-family: 'DM Sans', sans-serif; font-size: 0.8rem; letter-spacing: 0.15em;
        text-transform: uppercase; color: rgba(255,255,255,0.4);
        pointer-events: none; transition: opacity 1s; white-space: nowrap; z-index: 10;
      }
      
      #enter-prompt {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, calc(-50% + 230px));
        text-align: center; pointer-events: none; z-index: 10;
        animation: prompt-pulse 2.2s ease-in-out infinite;
      }
      #enter-prompt span {
        font-family: 'DM Sans', sans-serif; font-size: 0.85rem; letter-spacing: 0.2em;
        text-transform: uppercase; color: rgba(255,255,255,0.7);
        display: flex; align-items: center; gap: 12px; white-space: nowrap;
      }
      #enter-prompt span::before, #enter-prompt span::after {
        content: ''; width: 30px; height: 1px; background: rgba(255,255,255,0.3);
      }
      @keyframes prompt-pulse{
        0%,100%{opacity:.5;transform:translate(-50%,calc(-50% + 230px));}
        50%{opacity:1;transform:translate(-50%,calc(-50% + 220px));}
      }

      /* Main Site Wrap */
      #main-site {
        visibility: hidden;
        height: 100vh;
        overflow: hidden;
      }
      #main-site.visible {
        visibility: visible;
        height: auto;
        overflow: visible;
      }
    </style>
  </head>

  <body class="intro-active">
    <!-- Intro Screen -->
    <div id="vinyldisk-intro">
      <div id="intro-bg"></div>
      <div id="vinyldisk-canvas-wrap">
        <div id="vinyldisk-hint">Drag to spin &nbsp;&middot;&nbsp; Click to enter</div>
        <div id="enter-prompt"><span>Click the vinyl to enter</span></div>
        <div id="vinyldisk-ui">
          <h1>MusicMarket</h1>
          <p>Discover &nbsp;&middot;&nbsp; Collect &nbsp;&middot;&nbsp; Listen</p>
        </div>
      </div>
    </div>

    <!-- Main Content wrapper -->
    <div id="main-site">

    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main>
      <!-- Background Section -->
      <section class="background">
        <div class="container">
            <p class="hero-eyebrow">Music Marketplace</p>
            <h1 class="hero-heading">Discover Music<br><em>Collections</em></h1>
            <p class="hero-sub">Search, buy and sell vinyl records, CDs and more</p>

            <!-- Search bar row -->
            <div class="hero-search-wrap">
                <div class="hero-search-bar">
                    <div class="hero-input-group">
                        <i class="bi bi-search"></i>
                        <input type="search" id="searchInput"
                              class="hero-input" placeholder="Search albums, artists...">
                    </div>
                    <div class="hero-divider"></div>
                    <div class="hero-input-group">
                        <i class="bi bi-geo-alt"></i>
                        <input type="text" id="locationInput"
                              class="hero-input" placeholder="Country or city...">
                    </div>
                    <button class="hero-search-btn" id="filterLocationBtn">
                        <i class="bi bi-search"></i>
                        <span>Search</span>
                    </button>
                </div>
            </div>

            <!-- Secondary actions -->
            <div class="hero-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="hero-action-btn" id="sortDistanceBtn">
                        <i class="bi bi-geo-alt-fill"></i> Sort by Distance
                    </button>
                    <button class="hero-action-ghost" id="clearFiltersBtn">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </button>
                <?php else: ?>
                    <button class="hero-action-ghost" id="clearFiltersBtn">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </button>
                    <a href="login.php" class="hero-login-prompt">
                        <i class="bi bi-lock"></i>
                        Log in to sort by distance
                    </a>
                <?php endif; ?>
            </div>
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

    </div> <!-- /#main-site -->

    <!-- Three.js Intro Overlay Script (Vinyl) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
      (function() {
        const wrap = document.getElementById('vinyldisk-canvas-wrap');
        const intro = document.getElementById('vinyldisk-intro');
        const mainSite = document.getElementById('main-site');
        
        if (!wrap) return;
        
        if (sessionStorage.getItem('vinylIntroPlayed') === 'true') {
          document.body.classList.remove('intro-active');
          if (intro) intro.style.display = 'none';
          if (mainSite) mainSite.classList.add('visible');
          return;
        }

        const scene = new THREE.Scene();
        // Transparent background so we can wipe the HTML background behind it
        const camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 0, 26);

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        wrap.prepend(renderer.domElement);

        const vinylGroup = new THREE.Group();
        scene.add(vinylGroup);

        const lineMat = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.25 });
        const faintLineMat = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.1 });
        const meshMat = new THREE.MeshBasicMaterial({ 
          color: 0x111111, 
          polygonOffset: true, 
          polygonOffsetFactor: 1, 
          polygonOffsetUnits: 1 
        });

        function createSolid(geometry, edgeMat, parent, x = 0, y = 0, z = 0) {
          const group = new THREE.Group();
          group.position.set(x, y, z);
            
          const mesh = new THREE.Mesh(geometry, meshMat);
          group.add(mesh);

          const edges = new THREE.EdgesGeometry(geometry);
          const lines = new THREE.LineSegments(edges, edgeMat);
          group.add(lines);

          parent.add(group);
          return group;
        }

        // Record Base
        const recordRadius = 8.5;
        const recordGeo = new THREE.CylinderGeometry(recordRadius, recordRadius, 0.2, 64);
        recordGeo.rotateX(Math.PI / 2);
        createSolid(recordGeo, lineMat, vinylGroup);

        // Grooves (multiple rings)
        for (let r = 3.2; r <= 8.2; r += 0.2) {
          const ringGeo = new THREE.EdgesGeometry(new THREE.CylinderGeometry(r, r, 0.22, 64));
          ringGeo.rotateX(Math.PI / 2);
          vinylGroup.add(new THREE.LineSegments(ringGeo, faintLineMat));
        }

        // Center Label
        const labelGeo = new THREE.CylinderGeometry(2.8, 2.8, 0.24, 64);
        labelGeo.rotateX(Math.PI / 2);
        createSolid(labelGeo, lineMat, vinylGroup);

        // Hole
        const holeGeo = new THREE.CylinderGeometry(0.35, 0.35, 0.26, 32);
        holeGeo.rotateX(Math.PI / 2);
        const holeMesh = new THREE.Mesh(holeGeo, new THREE.MeshBasicMaterial({ color: 0x0a0a0a }));
        vinylGroup.add(holeMesh);
        const holeEdges = new THREE.EdgesGeometry(holeGeo);
        vinylGroup.add(new THREE.LineSegments(holeEdges, lineMat));

        // Initial orientation
        vinylGroup.rotation.x = Math.PI / 1;

        let isDragging = false;
        let enterState = 0; // 0: idle, 1: shifting left, 2: rolling right, 3: done
        let dragDistance = 0;

        wrap.addEventListener('mousedown', (e) => {
          isDragging = true;
          dragDistance = 0;
        });

        wrap.addEventListener('mousemove', (e) => {
          if (isDragging && enterState === 0) {
            const dx = e.movementX;
            const dy = e.movementY;
            dragDistance += Math.abs(dx) + Math.abs(dy);
            vinylGroup.rotation.y += dx * 0.01;
            vinylGroup.rotation.x += dy * 0.01;
          }
        });

        // Touch support
        let lastTouch = null;
        wrap.addEventListener('touchstart', (e) => {
          isDragging = true;
          dragDistance = 0;
          if (e.touches.length > 0) {
            lastTouch = { x: e.touches[0].clientX, y: e.touches[0].clientY };
          }
        }, { passive: true });

        wrap.addEventListener('touchmove', (e) => {
          if (isDragging && enterState === 0 && e.touches.length > 0) {
            const touch = e.touches[0];
            const dx = touch.clientX - lastTouch.x;
            const dy = touch.clientY - lastTouch.y;
            dragDistance += Math.abs(dx) + Math.abs(dy);
            vinylGroup.rotation.y += dx * 0.01;
            vinylGroup.rotation.x += dy * 0.01;
            lastTouch = { x: touch.clientX, y: touch.clientY };
          }
        }, { passive: true });

        function handleUp() {
          isDragging = false;
          // If they didn't really drag around, treat it as a click to enter
          if (dragDistance < 10 && enterState === 0) {
            enterSite();
          }
        }

        wrap.addEventListener('mouseup', handleUp);
        wrap.addEventListener('touchend', handleUp);

        function enterSite() {
          if (enterState !== 0) return;
          enterState = 1;
            
          sessionStorage.setItem('vinylIntroPlayed', 'true');

          // Make main site visible immediately so it's behind the intro wipe
          mainSite.classList.add('visible');
          document.body.classList.remove('intro-active');

          // Hide UI elements immediately
          const uiVars = ['vinyldisk-ui', 'vinyldisk-hint', 'enter-prompt'];
          uiVars.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
              el.style.transition = 'opacity 0.3s';
              el.style.opacity = '0';
            }
          });
        }

        const introBg = document.getElementById('intro-bg');

        function animate() {
          if (enterState < 3) requestAnimationFrame(animate); 

          if (enterState === 0) {
            if (!isDragging) {
              vinylGroup.rotation.y -= 0.004; // Ambient spin
              vinylGroup.position.y = Math.sin(Date.now() * 0.002) * 0.3; // Ambient bob
            }
          }

          else if (enterState === 1) {
            // Phase 1: Shift vinyl to the far left, lay flat
            vinylGroup.position.x = THREE.MathUtils.lerp(vinylGroup.position.x, -35, 0.06);
            vinylGroup.rotation.x = THREE.MathUtils.lerp(vinylGroup.rotation.x, Math.PI / 1, 0.08); 
            vinylGroup.rotation.y = THREE.MathUtils.lerp(vinylGroup.rotation.y, 0, 0.08);
              
            // Wait until it's off-screen or far left
            if (vinylGroup.position.x < -20) {
              enterState = 2;
            }
          } 

          else if (enterState === 2) {
            // Phase 2: Roll back to the right across the screen
            vinylGroup.position.x += 1.2;
            vinylGroup.rotation.z -= 0.15; // Roll like a wheel

            // Sync the wipe clip-path with the vinyl's position
            const vector = new THREE.Vector3();
            vector.setFromMatrixPosition(vinylGroup.matrixWorld);
            // Move projection point slightly left of center to hide the straight line behind the curved vinyl body
            vector.sub(new THREE.Vector3(1, 0, 0));
            vector.project(camera);
              
            const xPct = Math.max(0, Math.min(100, ((vector.x + 1) / 2) * 100));
              
            if (introBg) {
              introBg.style.clipPath = `polygon(${xPct}% 0%, 100% 0%, 100% 100%, ${xPct}% 100%)`;
            }

            if (vinylGroup.position.x > 35) {
              enterState = 3;
              intro.style.display = 'none';
              renderer.dispose();
            }
          }

          renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
          if (enterState !== 0) return;
          const width = window.innerWidth;
          const height = window.innerHeight;
          renderer.setSize(width, height);
          camera.aspect = width / height;
          camera.updateProjectionMatrix();
        });
      })();
    </script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
      const ALBUM_COVER = 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&w=1950&q=80';
      const CURRENT_USER = <?php echo json_encode($_SESSION['username'] ?? null); ?>;
      const IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

      let currentSearch = '';
      let currentLocation = '';
      let currentUserLat = null;
      let currentUserLon = null;
      let sortByDistance = false;

      function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
      }

      function escHtml(str) {
        const div = document.createElement('div');
        div.textContent = String(str);
        return div.innerHTML;
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
                  <p class="card-text mb-1 text-muted small">
                    Listed by: <b>${escHtml(listing.seller)}</b>
                    ${listing.distance_km ? `<br><i class="bi bi-geo-alt"></i> ${listing.distance_km} km away` : ''}
                  </p>
                  <p class="card-text mb-1 fw-bold">$${parseFloat(listing.price).toFixed(2)}</p>
                  <p class="card-text text-muted small">${formatDate(listing.created_at)}</p>
                </div>
              </a>
              <div class="card-body d-flex gap-2">
                ${CURRENT_USER && CURRENT_USER === listing.seller
                ? '<span class="btn btn-outline-dark disabled" style="pointer-events:none; opacity:1; border:none;">Your listing</span>'
                : `<button class="btn btn-outline-dark" onclick="addToCart(${listing.listing_id})">Add to cart <i class="bi bi-cart"></i></button>`
                }
                </div>
            </div>
          </div>
        `).join('');
      }

      function loadListings() {
        let url = '/api/get_listings.php?';
        const params = [];
          
        if (currentSearch) {
          params.push(`search=${encodeURIComponent(currentSearch)}`);
        }
          
        if (currentLocation) {
          params.push(`location=${encodeURIComponent(currentLocation)}`);
        }
          
        // Only add coordinates if user is logged in AND sort by distance is enabled
        if (IS_LOGGED_IN && sortByDistance && currentUserLat && currentUserLon) {
          params.push(`lat=${currentUserLat}`);
          params.push(`lon=${currentUserLon}`);
          console.log('Sorting by distance with coordinates:', currentUserLat, currentUserLon);
        }
          
        url += params.join('&');
          
        const container = document.getElementById('featured-albums');
        container.innerHTML = '<p class="text-center text-muted">Loading...</p>';
          
        fetch(url)
          .then(r => r.json())
          .then(json => {
            if (json.error) {
              container.innerHTML = '<p class="text-center text-danger">Failed to load listings.</p>';
              return;
            }
                  
            if (json.data.length === 0) {
              container.innerHTML = '<p class="text-center text-muted">No listings found for your filters.</p>';
              return;
            }
                  
            renderFeaturedAlbums(json.data.slice(0, 8));
                  
            // Update button state only if button exists (user is logged in)
            const distanceBtn = document.getElementById('sortDistanceBtn');
            if (distanceBtn) {
              if (sortByDistance) {
                distanceBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Sorting by Distance';
                distanceBtn.classList.add('active');
              } 
              
              else {
                distanceBtn.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Sort by Distance';
                distanceBtn.classList.remove('active');
              }
            }
        })
          
        .catch((error) => {
          console.error('Fetch error:', error);
          container.innerHTML = '<p class="text-center text-danger">Failed to load listings.</p>';
        });
      }

      // Get user's location - only called when logged in
      function getUserLocation() {
        const locationBtn = document.getElementById('sortDistanceBtn');
          
        // If button doesn't exist (user not logged in), just return
        if (!locationBtn) {
          return;
        }
          
        if ("geolocation" in navigator) {
          locationBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Getting location...';
          locationBtn.disabled = true;
              
          navigator.geolocation.getCurrentPosition(
            function(position) {
              currentUserLat = position.coords.latitude;
              currentUserLon = position.coords.longitude;
              console.log(`Location obtained: ${currentUserLat}, ${currentUserLon}`);
                      
              locationBtn.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Sort by Distance';
              locationBtn.disabled = false;
                      
              // Show success message
              const successDiv = document.createElement('div');
              successDiv.className = 'alert alert-success mt-2';
              successDiv.innerHTML = '<i class="bi bi-check-circle"></i> Location detected! Click "Sort by Distance" to see nearest listings.';
              successDiv.style.cssText = `
              font-size: 0.875rem;
              padding: 0.5rem 1rem;
              border-radius: 0.5rem;
              max-width: 500px;
              margin: 0 auto;
              text-align: center;
              `;
                      
              const container = document.querySelector('.hero-actions');
              if (container) container.parentNode.insertBefore(successDiv, container.nextSibling);
              setTimeout(() => successDiv.remove(), 3000);
            },
            
            function(error) {
              console.log("Location permission denied or error:", error);
              locationBtn.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Sort by Distance';
              locationBtn.disabled = false;
                      
              // Show error message
              const errorDiv = document.createElement('div');
              errorDiv.className = 'alert alert-warning mt-2';
              errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Please enable location to sort by distance.';
              errorDiv.style.fontSize = '0.875rem';
              errorDiv.style.padding = '0.5rem 1rem';
              errorDiv.style.borderRadius = '0.5rem';
                      
              const container = document.querySelector('.hero-actions');
              if (container) container.parentNode.insertBefore(errorDiv, container.nextSibling);
              setTimeout(() => errorDiv.remove(), 3000);
            }
          );
        } 
        
        else {
          console.log("Geolocation not supported");
          const errorDiv = document.createElement('div');
          errorDiv.className = 'alert alert-warning mt-2';
          errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Your browser doesn\'t support location services.';
          errorDiv.style.fontSize = '0.875rem';
          errorDiv.style.padding = '0.5rem 1rem';
          errorDiv.style.borderRadius = '0.5rem';
              
          const container = document.querySelector('.hero-actions');
          if (container) container.parentNode.insertBefore(errorDiv, container.nextSibling);
          setTimeout(() => errorDiv.remove(), 3000);
        }
      }

      // Search function that handles both search and location
      function performSearch() {
        currentSearch = document.getElementById("searchInput").value.trim();
        currentLocation = document.getElementById("locationInput").value.trim();
        sortByDistance = false; // Reset distance sorting when doing a new search
        loadListings();
      }

      // Search button click
      const searchBtn = document.getElementById("filterLocationBtn");
      if (searchBtn) {
        searchBtn.addEventListener("click", function(e) {
          e.preventDefault();
          performSearch();
        });
      }

      // Search with Enter key on either input
      const searchInput = document.getElementById("searchInput");
      const locationInput = document.getElementById("locationInput");

      if (searchInput) {
        searchInput.addEventListener("keypress", function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
          }
        });
      }

      if (locationInput) {
        locationInput.addEventListener("keypress", function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
          }
        });
      }

      // Sort by Distance button - only add if it exists (user is logged in)
      const sortDistanceBtn = document.getElementById("sortDistanceBtn");
      if (sortDistanceBtn) {
        sortDistanceBtn.addEventListener("click", function() {
          if (!currentUserLat || !currentUserLon) {
            getUserLocation();
            setTimeout(() => {
              if (currentUserLat && currentUserLon) {
                sortByDistance = !sortByDistance;
                loadListings();
              }
            }, 1000);
          } 
          
          else {
            sortByDistance = !sortByDistance;
            loadListings();
          }
        });
      }

      // Clear all filters
      const clearBtn = document.getElementById("clearFiltersBtn");
      if (clearBtn) {
        clearBtn.addEventListener("click", function() {
          currentSearch = '';
          currentLocation = '';
          sortByDistance = false;
          if (searchInput) searchInput.value = '';
          if (locationInput) locationInput.value = '';
          loadListings();
        });
      }

      // Initial load - always load listings
      loadListings();

      // Only try to get location if logged in
      if (IS_LOGGED_IN) {
        getUserLocation();
      }
    </script>
  </body>
</html>
