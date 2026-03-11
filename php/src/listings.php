<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Listings</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <?php if (isset($_GET['created'])): ?>
        <p><strong>Listing created successfully!</strong></p>
    <?php endif; ?>

    <h1>Listings</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="make_listing.php">+ New listing</a>
    <?php endif; ?>

    <section id="search-section">
        <h2>Search</h2>

        <!-- Free-text search -->
        <label for="search-text">Search by artist or album name:</label><br>
        <input type="text" id="search-text" placeholder="e.g. Pink Floyd, Dark Side...">
        <button type="button" id="btn-text-search">Search</button>

        <br><br>

        <!-- Metadata-verified search (uses search_metadata.php to resolve MBIDs first) -->
        <label for="search-artist">Verified metadata search:</label><br>
        <input type="text" id="search-artist" placeholder="Artist name">
        <input type="text" id="search-album"  placeholder="Album title">
        <button type="button" id="btn-meta-search">Search with metadata</button>
        <span id="meta-status"></span>
    </section>

    <div id="listings-container">
        <p>Loading...</p>
    </div>
</main>

<script>
function escHtml(str) {
    return DOMPurify.sanitize(String(str));
}

function renderListings(listings) {
    const container = document.getElementById('listings-container');

    if (!listings.length) {
        container.innerHTML = '<p>No listings found.</p>';
        return;
    }

    let html = `
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Seller</th>
                    <th>Listed at</th>
                    <th>Price</th>
                    <th>Buy</th>
                </tr>
            </thead>
            <tbody>
    `;

    listings.forEach(row => {
        html += `
            <tr>
                <td>${escHtml(row.listing_id)}</td>
                <td><a href="artist.php?mbid=${encodeURIComponent(row.artist_mbid)}">${escHtml(row.artist_name)}</a></td>
                <td><a href="album.php?mbid=${encodeURIComponent(row.album_mbid)}">${escHtml(row.album_name)}</a></td>
                <td>${escHtml(row.seller)}</td>
                <td>${escHtml(row.created_at)}</td>
                <td>${escHtml(row.price)}</td>
                <td onclick=buyListing(${row.listing_id})>Place order</td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

function buyListing(listing_id) {
  fetch('/api/buy_listing.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({listing_id: listing_id})
  })
    .then(r => r.json())
    .then(json => {
      console.log(json);
    })
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
