<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Listings</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/navigation.css"> 
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/listings.css">
</head>

<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main class="listings-page">
        <div class="listings-header">
            <div>
                <p class="page-eyebrow">Marketplace</p>
                <h1 class="page-heading">Browse Listings</h1>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="make_listing.php" class="btn-new-listing">
                    <i class="bi bi-plus"></i>New Listing
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['created'])): ?>
            <div class="alert-created">
                <i class="bi bi-check-circle me-2"></i>Listing created successfully!
            </div>
        <?php endif; ?>

        <div class="search-card">
            <h2 class="search-card-title">
                <i class="bi bi-search me-2"></i>Search
            </h2>

            <div class="mb-3">
                <!-- Free-text search -->
                <label for="search-label">Search by artist or album name:</label>
                <div class="search-input-row">
                    <div class="field-input-wrap" style="flex:1;">
              <i class="bi bi-music-note-beamed"></i>
                              <label for="search-text" class="visually-hidden">Search text</label>

                        <input type="text" id="search-text" class="form-control" placeholder="e.g. Pink Floyd, Dark Side...">
                    </div>

                    <button type="button" id="btn-text-search" class="btn-search">
                        <i class="bi bi-search"></i>Search
                    </button>   
            </div>
        
            <div class="search-divider">
                <span>or search with verified metadata</span>
            </div>

            <div>
                <!-- Metadata-verified search (uses search_metadata.php to resolve MBIDs first) -->
                <label for="search-label">Verified metadata search:</label>
                <div class="search-input-row">
                    <div class="field-input-wrap" style="flex:1;">
                <i class="bi bi-person"></i>
                                    <label for="search-artist" class="visually-hidden">Search artist</label>

                        <input type="text" id="search-artist" class="form-control" placeholder="Artist name">
                    </div>

                    <div class="field-input-wrap" style="flex:1;">
                <i class="bi bi-vinyl"></i>
                        <label for="search-album" class="visually-hidden">Search album</label>

                        <input type="text" id="search-album" class="form-control" placeholder="Album title">
                    </div>

                    <button type="button" id="btn-meta-search" class="btn-search">
                        <i class="bi bi-check2-circle"></i>Search with metadata</button>
                </div>
                <div class="meta-status" id="meta-status"></div>
            </div>
        </div>
            
        <div id="listings-container">
            <div class="loading-state">
                <div class="spinner"></div>
                <span>Loading listings...</span>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        const CURRENT_USER = <?php echo json_encode($_SESSION['username'] ?? null); ?>;
        const IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;


        function escHtml(str) {
            return DOMPurify.sanitize(String(str));
        }

        function renderListings(listings) {
            const container = document.getElementById('listings-container');

            if (!listings.length) {
                container.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-vinyl"></i>
                    No listings found. Try a different search.
                </div>`;
                return;
            }

            let html = `
            <div class="listings-table-wrap">
                <div class="listings-count">${escHtml(listings.length)} listing${listings.length !== 1 ? 's' : ''} found</div>
                    <table class="listings-table">
                        <thead>
                        <tr>
                            <th>Artist</th>
                            <th>Album</th>
                            <th>Seller</th>
                            <th>Listed</th>
                            <th>Price</th>
                            <th>Cart</th>
                        </tr>
                    </thead>
                    <tbody>
                    `;

            listings.forEach(row => {
                const date = new Date(row.created_at).toLocaleDateString('en-SG', {
                    day: 'numeric', month: 'short', year: 'numeric'
                });
                
                html += `
                <tr>
                    <td>
                        <a href="artist.php?mbid=${encodeURIComponent(row.artist_mbid)}" class="listing-link">
                            ${escHtml(row.artist_name)}
                        </a>
                    </td>
                    <td>
                        <a href="album.php?mbid=${encodeURIComponent(row.album_mbid)}" class="listing-link">
                            ${escHtml(row.album_name)}
                        </a>
                    </td>
                    <td class="text-muted-cell">${escHtml(row.seller)}</td>
                    <td class="text-muted-cell">${escHtml(date)}</td>
                    <td class="price-cell">SGD ${escHtml(row.price)}</td>
                    <td>
                        ${CURRENT_USER && CURRENT_USER === row.seller
                            ? '<span class="text-muted small">Your listing</span>'
                            : `<button class="btn-buy" onclick="addToCart(${row.listing_id})"><i class="bi bi-cart-plus"></i>Cart</button>`
                        }
                    </td>
                </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            container.innerHTML = html;
        }

        function fetchListings(params) {
            const qs = new URLSearchParams(params).toString();
            document.getElementById('listings-container').innerHTML = '<p>Loading...</p>';

            fetch('/api/get_listings.php?' + qs)
                .then(r => r.json())
                .then(json => {
                    if (json.error) {
                        document.getElementById('listings-container').innerHTML = '<p>Error: ' + escHtml(json.error) + '</p>';
                        return;
                    }
                    renderListings(json.data);
                })
                .catch(() => {
                    document.getElementById('listings-container').innerHTML = '<p>Failed to load listings.</p>';
                });
        }

        // Initial load — all listings
        fetchListings({});

        // Free-text search
        document.getElementById('btn-text-search').addEventListener('click', function () {
            const q = document.getElementById('search-text').value.trim();
            fetchListings(q ? { search: q } : {});
        });

        document.getElementById('search-text').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') document.getElementById('btn-text-search').click();
        });

        // Metadata-verified search: resolve to MBIDs via search_metadata, then filter listings
        document.getElementById('btn-meta-search').addEventListener('click', function () {
            const artist = document.getElementById('search-artist').value.trim();
            const album  = document.getElementById('search-album').value.trim();
            const status = document.getElementById('meta-status');

            if (!artist && !album) {
                fetchListings({});
                return;
            }

            status.textContent = 'Resolving metadata...';

            fetch('/api/search_metadata.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ artist, album })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'not_found') {
                    status.textContent = 'No metadata match found. Falling back to text search.';
                    fetchListings({ search: [artist, album].filter(Boolean).join(' ') });
                    return;
                }

                let artist_mbid = '';
                let album_mbid  = '';

                if (data.status === 'found_db') {
                    artist_mbid = data.data.artist_mbid;
                    album_mbid  = data.data.album_mbid;
                    status.textContent = 'Found in database.';
                } else if (data.status === 'found_musicbrainz') {
                    const release = data.data;
                    album_mbid  = release.id || '';
                    artist_mbid = (release['artist-credit'] && release['artist-credit'][0])
                        ? release['artist-credit'][0].artist.id
                        : '';
                    status.textContent = 'Found via MusicBrainz.';
                }

                const params = {};
                if (artist_mbid) params.artist_mbid = artist_mbid;
                if (album_mbid)  params.album_mbid  = album_mbid;
                fetchListings(params);
            })
            .catch(() => {
                status.textContent = 'Metadata lookup failed. Falling back to text search.';
                fetchListings({ search: [artist, album].filter(Boolean).join(' ') });
            });
        });
    </script>

</body>
</html>
