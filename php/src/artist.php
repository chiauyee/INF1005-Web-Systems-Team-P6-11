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
    <title>Artist | MusicMarket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/navigation.css"> 
    <link rel="stylesheet" href="/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>
    <link rel="stylesheet" href="/css/artist.css">
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <div id="error-banner" class="alert alert-danger m-3 border-0 shadow-sm" style="display:none;"></div>

    <!-- Artist Hero Section -->
    <section class="hero-artist text-start">
        <div class="container position-relative z-1">
            <div id="artist-info-content" style="display: none;">
                <h1 id="hero-title" class="hero-artist-title"></h1>
                <p class="fs-5 text-white-50 mb-4 hero-artist-subtitle">
                    Artist Profile
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
            <!-- Left Column: Albums & Comments -->
            <div class="col-lg-8">
                <!-- Albums Section -->
                <section id="albums-section" class="mb-5">
                    <h2 class="section-header mb-4">Released Albums</h2>
                    <div id="albums-list"></div>
                </section>

                <!-- Comments Section -->
                <section id="comments-section">
                    <h2 class="section-header mb-4">Discussion & Comments</h2>
                    
                    <?php if ($logged_in): ?>
                    <div class="card mb-4 border-0" style="border: 1.5px solid var(--border) !important; border-radius: 8px;">
                        <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-3 fs-6">Leave a comment</h5>
                            <label for="comment-input" class="visually-hidden">Discuss the artist</label>

                            <textarea id="comment-input" class="form-control mb-3 bg-light border-0" rows="3" maxlength="2000" placeholder="Discuss the artist..." style="resize: none;"></textarea>
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
                <a href="/uploads/artists/${escHtml(img.filename)}" target="_blank">
                    <img src="/uploads/artists/${escHtml(img.filename)}" class="artist-photo" alt="Artist photo">
                </a>
                <div class="mt-2 small text-muted text-truncate fw-medium" title="Uploaded by ${escHtml(img.username)}">
                    <i class="bi bi-person me-1"></i>${escHtml(img.username)}
                </div>
            </div>
        </div>
    `).join('');
}

function renderAlbums(albums) {
    const el = document.getElementById('albums-list');
    if (!albums.length) { 
        el.innerHTML = `
            <div class="text-center p-5" style="background: #fafafa; border: 1.5px dashed var(--border); border-radius: 8px;">
                <i class="bi bi-vinyl fs-1 text-muted mb-2 opacity-50"></i>
                <p class="text-muted mb-0 fw-medium">No albums recorded yet.</p>
            </div>
        `; 
        return; 
    }
    el.innerHTML = albums.map(a => `
        <a href="album.php?mbid=${encodeURIComponent(a.album_mbid)}" class="album-card shadow-sm">
            <i class="bi bi-disc"></i>
            <span class="fs-6 fw-medium text-dark flex-grow-1">${escHtml(a.album_name)}</span>
            <i class="bi bi-chevron-right text-muted opacity-50 pe-1"></i>
        </a>
    `).join('');
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
        el.innerHTML = '<p class="text-muted p-4 text-center" style="border: 1px dashed var(--border); border-radius: 8px;">No comments yet. Start the discussion!</p>'; 
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

// load artist data
fetch(`/api/get_artist.php?mbid=${encodeURIComponent(MBID)}`)
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            const errBanner = document.getElementById('error-banner');
            errBanner.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> ${escHtml(data.error)}`;
            errBanner.style.display = 'block';
            return;
        }

        const a = data.artist;
        document.title = escHtml(a.artist_name) + ' | MusicMarket';
        
        const contentDiv = document.getElementById('artist-info-content');
        document.getElementById('hero-title').textContent = a.artist_name;
        
        const mbidLink = document.getElementById('hero-mbid-link');
        mbidLink.href = `https://musicbrainz.org/artist/${encodeURIComponent(a.artist_mbid)}`;
        
        contentDiv.style.display = 'block';

        renderImages(data.images);
        renderAlbums(data.albums);
        renderComments(data.comments);
    })
    .catch(() => {
        const errBanner = document.getElementById('error-banner');
        errBanner.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> Failed to load artist.`;
        errBanner.style.display = 'block';
    });

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
        body.append('type', 'artist');
        body.append('mbid', MBID);
        body.append('image', file);

        status.innerHTML = '<span class="text-secondary"><i class="spinner-border spinner-border-sm me-1" role="status"></i>Uploading...</span>'; // Validation
        fetch('/api/upload_image.php', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok') {
                    status.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Uploaded successfully!</span>';
                    document.getElementById('image-input').value = '';
                    const list = document.getElementById('images-list');
                    if (list.querySelector('p.text-center')) list.innerHTML = '';
                    const mockImg = {
                        filename: data.filename,
                        username: 'You'
                    };
                    const fig = document.createElement('div');
                    fig.innerHTML = `
                        <div class="col-6 col-md-4 col-lg-6">
                            <div class="position-relative">
                                <a href="/uploads/artists/${escHtml(mockImg.filename)}" target="_blank">
                                    <img src="/uploads/artists/${escHtml(mockImg.filename)}" class="artist-photo" alt="Artist photo">
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
            body: JSON.stringify({ type: 'artist', mbid: MBID, comment })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok') {
                status.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Posted!</span>';
                document.getElementById('comment-input').value = '';
                prependComment(data.comment);
                setTimeout(() => { status.innerHTML = ''; }, 3000); // validation
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
