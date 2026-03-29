<?php
session_start();

// Sanitize and validate the MBID parameter to prevent XSS and SQL injection
$mbid = trim($_GET['mbid'] ?? '');

// MusicBrainz IDs are 36-character UUIDs (hexadecimal + hyphens). Validate format:
if (!$mbid || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $mbid)) {
    header('Location: listings.php');
    exit;
}

// Sanitize the validated string just to be defensively safe against any XSS vectors
$mbid = htmlspecialchars($mbid, ENT_QUOTES, 'UTF-8');

$logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Album | MusicMarket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/navigation.css"> 
    <link rel="stylesheet" href="/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>
    <link rel="stylesheet" href="/css/album.css">
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <div id="error-banner" class="alert alert-danger m-3 border-0 shadow-sm" style="display:none;"></div>

    <!-- Album Hero Section -->
    <section class="hero-album text-start">
        <div class="container position-relative z-1">
            <div id="album-info-content" style="display: none;">
                <h1 id="hero-title" class="hero-album-title"></h1>
                <p class="fs-5 text-white-50 mb-4 hero-album-artist">
                    By <span id="hero-artist"></span>
                </p>
                <div>
                    <a id="hero-mbid-link" href="#" target="_blank" class="mbid-link">
                        <i class="bi bi-box-arrow-up-right"></i> View on MusicBrainz
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-5">
            <!-- Left Column: Listings & Comments -->
            <div class="col-lg-8">
                <!-- Listings Section -->
                <section id="listings-section" class="mb-5">
                        <h2 class="section-header mb-4">Available Listings</h2>
                    <div id="listings-list"></div>
                </section>

                <!-- Comments Section -->
                <section id="comments-section">
                    <h2 class="section-header mb-4">Reviews & Comments</h2>
                    
                    <?php if ($logged_in): ?>
                    <div class="card mb-4 border-0" style="border: 1.5px solid var(--border) !important; border-radius: 8px;">
                        <div class="card-body p-4">
                          <h5 class="card-title fw-semibold mb-3 fs-6">Leave a comment</h5>
                            <label for="comment-input" class="visually-hidden">Discuss the artist</label>
                            <textarea id="comment-input" class="form-control mb-3 bg-light border-0" rows="3" maxlength="2000" placeholder="Share your thoughts on this album..." style="resize: none;"></textarea>
                            <div class="d-flex justify-content-between align-items-center">
                                <span id="comment-status" class="text-muted small fw-medium"></span>
                                <button type="button" id="btn-comment" class="btn btn-dark px-4 rounded-pill fw-medium shadow-sm">Post Comment</button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert bg-white" style="border: 1.5px dashed var(--border); border-radius: 8px;">
                        <i class="bi bi-info-circle me-2 text-muted"></i> <a href="login.php" class="text-dark fw-bold text-decoration-underline text-underline-offset-4">Log in</a> to leave a comment.
                    </div>
                    <?php endif; ?>

                    <div id="comments-list" class="mt-4"></div>
                </section>
            </div>
            
            <!-- Right Column: Photos -->
            <div class="col-lg-4">
                <section id="images-section">
                    <h2 class="section-header mb-4">Community Photos</h2>
                    
                    <?php if ($logged_in): ?>
                    <div class="card mb-4 border-0" style="border: 1.5px solid var(--border) !important; border-radius: 8px; background: #fafafa;">
                        <div class="card-body p-3">
                            <p class="mb-2 fw-semibold fs-6">Upload a photo</p>
                            <div class="input-group input-group-sm mb-2 shadow-sm">
                                <label for="image-input" class="visually-hidden">Upload</label>
                                <input type="file" class="form-control border-0" id="image-input" accept="image/*">
                                <button class="btn btn-dark" type="button" id="btn-upload">Upload</button>
                            </div>
                            <span id="upload-status" class="text-muted small fw-medium"></span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert bg-white small mb-4" style="border: 1.5px dashed var(--border); border-radius: 8px;">
                        <a href="login.php" class="text-dark fw-bold text-decoration-underline text-underline-offset-4">Log in</a> to upload photos.
                    </div>
                    <?php endif; ?>

                    <div id="images-list" class="row g-3"></div>
                </section>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
const MBID = <?= json_encode($mbid) ?>;
const LOGGED_IN = <?= json_encode($logged_in) ?>;
const IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;


function escHtml(str) {
    return DOMPurify.sanitize(String(str));
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function renderImages(images) {
    const el = document.getElementById('images-list');
    if (!images.length) { el.innerHTML = '<div class="col-12"><p class="text-muted p-4 text-center" style="border: 1px dashed var(--border); border-radius: 8px;">No photos yet.</p></div>'; return; }
    el.innerHTML = images.map(img => `
        <div class="col-6 col-md-4 col-lg-6">
            <div class="position-relative">
                <a href="/uploads/albums/${escHtml(img.filename)}" target="_blank">
                    <img src="/uploads/albums/${escHtml(img.filename)}" class="album-photo" alt="Album photo">
                </a>
                <div class="mt-2 small text-muted text-truncate fw-medium" title="Uploaded by ${escHtml(img.username)}">
                    <i class="bi bi-person me-1"></i>${escHtml(img.username)}
                </div>
            </div>
        </div>
    `).join('');
}

function renderListings(listings) {
    const el = document.getElementById('listings-list');
    if (!listings.length) { 
        el.innerHTML = `
            <div class="text-center p-5" style="background: #fafafa; border: 1.5px dashed var(--border); border-radius: 8px;">
                <i class="bi bi-vinyl fs-1 text-muted mb-2 opacity-50"></i>
                <p class="text-muted mb-0 fw-medium">No listings for this album yet.</p>
                <a href="make_listing.php" class="btn btn-link text-dark mt-2 fw-semibold text-decoration-underline">Be the first to list it</a>
            </div>
        `; 
        return; 
    }
    let html = `
        <div class="table-responsive table-custom">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Seller</th>
                        <th>Listed Date</th>
                        <th class="text-end">Price</th>
                        <th class="text-center pe-4" style="width: 160px;">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
    `;
    listings.forEach(l => {
        html += `<tr>
            <td class="ps-4 fw-medium text-dark">
                <i class="bi bi-shop me-2 text-muted"></i>${escHtml(l.seller)}
            </td>
            <td class="text-muted small">${formatDate(l.created_at)}</td>
            <td class="text-end fw-bold text-dark fs-6">$${parseFloat(l.price).toFixed(2)}</td>
            <td class="text-center pe-4">
                <div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-sm btn-add-cart flex-grow-1" onclick="addToCart(${l.listing_id})">
                        <i class="bi bi-cart-plus me-1"></i> Add
                    </button>
                    <button class="btn btn-sm btn-wishlist" id="wishlist-btn-${escHtml(l.listing_id)}" onclick="toggleWishlist('${escHtml(l.album_mbid || MBID)}', this)" title="Add to wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    });
    html += '</tbody></table></div>';
    el.innerHTML = html;
}

function formatComment(c) {
    return `
        <div class="comment-article shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="fs-6 text-dark"><i class="bi bi-person-circle me-2 text-secondary"></i>${escHtml(c.username)}</strong>
                <span class="comment-time">${formatDate(c.created_at)}</span>
            </div>
            <p class="mb-0 text-dark" style="line-height: 1.6;">${escHtml(c.comment).replace(/\n/g, '<br>')}</p>
        </div>
    `;
}

function renderComments(comments) {
    const el = document.getElementById('comments-list');
    if (!comments.length) { 
        el.innerHTML = '<p class="text-muted p-4 text-center" style="border: 1px dashed var(--border); border-radius: 8px;">No comments yet. Be the first to share your thoughts!</p>'; 
        return; 
    }
    el.innerHTML = comments.map(formatComment).join('');
}

function prependComment(comment) {
    const el = document.getElementById('comments-list');
    if (el.querySelector('p.text-center')) el.innerHTML = ''; // remove 'No comments yet'
    const div = document.createElement('div');
    div.innerHTML = formatComment(comment);
    el.prepend(div.firstElementChild);
}

// Load album data
fetch(`/api/get_album.php?mbid=${encodeURIComponent(MBID)}`)
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            const errBanner = document.getElementById('error-banner');
            errBanner.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> ${escHtml(data.error)}`;
            errBanner.style.display = 'block';
            return;
        }

        const al = data.album;
        document.title = escHtml(al.album_name) + ' | MusicMarket';
        
        const contentDiv = document.getElementById('album-info-content');
        document.getElementById('hero-title').textContent = al.album_name;
        document.getElementById('hero-artist').innerHTML = `<a href="artist.php?mbid=${encodeURIComponent(al.artist_mbid)}">${escHtml(al.artist_name)}</a>`;
        
        const mbidLink = document.getElementById('hero-mbid-link');
        mbidLink.href = `https://musicbrainz.org/release/${encodeURIComponent(al.album_mbid)}`;
        
        contentDiv.style.display = 'block';

        renderImages(data.images);
        renderListings(data.listings);
        renderComments(data.comments);

        // check wishlist state for this album after listings are rendered
        if (LOGGED_IN) {
            checkWishlistState(MBID);
        }
    })
    .catch(() => {
        const errBanner = document.getElementById('error-banner');
        errBanner.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> Failed to load album.`;
        errBanner.style.display = 'block';
    });

// wishlist toast
const _wishlistToastEl = (() => {
    const t = document.createElement('div');
    t.id = 'wishlist-toast';
    t.style.cssText = 'position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%) translateY(1rem);' +
        'background:#212529;color:#fff;padding:0.6rem 1.4rem;border-radius:999px;font-size:0.875rem;' +
        'font-family:\'DM Sans\',sans-serif;opacity:0;pointer-events:none;transition:opacity 0.25s,transform 0.25s;z-index:9998;white-space:nowrap;';
    document.body.appendChild(t);
    return t;
})();
let _wishlistToastTimer = null;
function showWishlistToast(msg) {
    _wishlistToastEl.textContent = msg;
    _wishlistToastEl.style.opacity = '1';
    _wishlistToastEl.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(_wishlistToastTimer);
    _wishlistToastTimer = setTimeout(() => {
        _wishlistToastEl.style.opacity = '0';
        _wishlistToastEl.style.transform = 'translateX(-50%) translateY(1rem)';
    }, 2500);
}

function setWishlistBtnState(btn, wishlisted) {
    if (!btn) return;
    const icon = btn.querySelector('i');
    if (wishlisted) {
        icon.className = 'bi bi-heart-fill';
        btn.classList.add('active');
        btn.title = 'Remove from wishlist';
    } else {
        icon.className = 'bi bi-heart';
        btn.classList.remove('active');
        btn.title = 'Add to wishlist';
    }
}

function checkWishlistState(albumMbid) {
    fetch(`/api/wishlist.php?action=check&album_mbid=${encodeURIComponent(albumMbid)}`)
        .then(r => r.json())
        .then(data => {
            // Apply state to all wishlist buttons on the page (one per listing row)
            document.querySelectorAll('[id^="wishlist-btn-"]').forEach(btn => {
                setWishlistBtnState(btn, data.wishlisted);
            });
        })
        .catch(() => {});
}

function toggleWishlist(albumMbid, btn) {
    if (!LOGGED_IN) {
        showWishlistToast('Please log in to save to wishlist'); // If not logged in
        return;
    }

    const currentlyWishlisted = btn.classList.contains('active'); // visual feedback
    setWishlistBtnState(btn, !currentlyWishlisted);

    fetch('/api/wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'toggle', album_mbid: albumMbid })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            setWishlistBtnState(btn, currentlyWishlisted); // if fail revert to unhearted
            showWishlistToast(data.error);
            return;
        }
        // Sync all buttons for this album
        document.querySelectorAll('[id^="wishlist-btn-"]').forEach(b => setWishlistBtnState(b, data.wishlisted));
        showWishlistToast(data.wishlisted ? ' Added to wishlist' : 'Removed from wishlist');
    })
    .catch(() => {
        // Revert on network error
        setWishlistBtnState(btn, currentlyWishlisted);
        showWishlistToast('Network error. Please try again.');
    });
}

// Upload photo
if (LOGGED_IN) {
    document.getElementById('btn-upload').addEventListener('click', function () {
        const file   = document.getElementById('image-input').files[0];
        const status = document.getElementById('upload-status');
        if (!file) { 
            status.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Please select a file.</span>'; 
            return; 
        }

        const body = new FormData();
        body.append('type', 'album');
        body.append('mbid', MBID);
        body.append('image', file);

        status.innerHTML = '<span class="text-secondary"><i class="spinner-border spinner-border-sm me-1" role="status"></i>Uploading...</span>';
        fetch('/api/upload_image.php', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok') {
                    status.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Uploaded successfully!</span>';
                    document.getElementById('image-input').value = '';
                    const list = document.getElementById('images-list');
                    if (list.querySelector('p.text-center')) list.innerHTML = '';
                    const fig = document.createElement('div');
                    // Create mock image inject
                    const mockImg = {
                        filename: data.filename,
                        username: 'You'
                    };
                    fig.innerHTML = `
                        <div class="col-6 col-md-4 col-lg-6">
                            <div class="position-relative">
                                <a href="/uploads/albums/${escHtml(mockImg.filename)}" target="_blank">
                                    <img src="/uploads/albums/${escHtml(mockImg.filename)}" class="album-photo" alt="Album photo">
                                </a>
                                <div class="mt-2 small text-muted text-truncate fw-medium">
                                    <i class="bi bi-person me-1"></i>${escHtml(mockImg.username)}
                                </div>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('afterbegin', fig.innerHTML);
                    
                    setTimeout(() => { status.innerHTML = ''; }, 3000);
                } else {
                    status.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>${escHtml(data.error || 'Upload failed.')}</span>`;
                }
            })
            .catch(() => { 
                status.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Upload failed. Network error.</span>'; 
            });
    });

    // post comment
    document.getElementById('btn-comment').addEventListener('click', function () {
        const comment = document.getElementById('comment-input').value.trim();
        const status  = document.getElementById('comment-status');
        if (!comment) { 
            status.innerHTML = '<span class="text-danger">Comment cannot be empty.</span>'; 
            return; 
        }

        status.innerHTML = '<span class="text-secondary"><i class="spinner-border spinner-border-sm me-1" role="status"></i>Posting...</span>';
        
        fetch('/api/add_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'album', mbid: MBID, comment })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok') {
                status.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Posted!</span>';
                document.getElementById('comment-input').value = '';
                prependComment(data.comment);
                setTimeout(() => { status.innerHTML = ''; }, 3000);
            } else {
                status.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>${escHtml(data.error || 'Failed to post comment.')}</span>`;
            }
        })
        .catch(() => { 
            status.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Failed to post comment. Network error.</span>'; 
        });
    });
}
</script>
</body>
</html>
