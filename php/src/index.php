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
          <h1>Discover Music Collections</h1>
          <p>Search, buy and sell vinyl records, CDs and more</p>
          <form class="d-flex justify-content-center mt-4" role="search" id="searchForm">
            <input class="form-control w-50 me-2" type="search" id="searchInput" placeholder="Search albums title, artists..."></input>
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

    </div> <!-- /#main-site -->

    <!-- Three.js Intro Overlay Script (Vinyl) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
      (function() {
        const wrap = document.getElementById('vinyldisk-canvas-wrap');
        const intro = document.getElementById('vinyldisk-intro');
        const mainSite = document.getElementById('main-site');
        if (!wrap) return;

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
          } else if (enterState === 1) {
            // Phase 1: Shift vinyl to the far left, lay flat
            vinylGroup.position.x = THREE.MathUtils.lerp(vinylGroup.position.x, -35, 0.06);
            vinylGroup.rotation.x = THREE.MathUtils.lerp(vinylGroup.rotation.x, Math.PI / 1, 0.08); 
            vinylGroup.rotation.y = THREE.MathUtils.lerp(vinylGroup.rotation.y, 0, 0.08);
            
            // Wait until it's off-screen or far left
            if (vinylGroup.position.x < -20) {
              enterState = 2;
            }
          } else if (enterState === 2) {
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
                <button class="btn btn-outline-dark" onclick="addToCart(${listing.listing_id})">Add to cart
                  <i class="bi bi-cart"></i>
                </button>
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

    <!-- Search Function -->
    <script>
      document.getElementById("searchForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const query = document.getElementById("searchInput").value.trim();

        if (!query) 
        {
          fetch('/api/get_listings.php')
            .then(res => res.json())
            .then(json => {
              if (json.error) {
                document.getElementById('featured-albums').innerHTML =
                  '<p class="text-danger text-center">Failed to load listings.</p>';
                return;
              }

              renderFeaturedAlbums(json.data.slice(0, 8));
            });
          return;
        }

        fetch(`/api/get_listings.php?search=${encodeURIComponent(query)}`)
          .then(res => res.json())
          .then(json => {
            if (json.error) {
              document.getElementById('featured-albums').innerHTML =
                '<p class="text-danger text-center">Search failed.</p>';
              return;
            }

            if (!json.data || json.data.length === 0) {
              document.getElementById('featured-albums').innerHTML =
                `<p class="text-center text-muted">No results found for 
                "<b>${query}</b>"</p>`;
              return;
            }

            renderFeaturedAlbums(json.data);
          })
          .catch(() => {
            document.getElementById('featured-albums').innerHTML =
              '<p class="text-danger text-center">Search failed.</p>';
          });
      });
    </script>

</body>
</html>
