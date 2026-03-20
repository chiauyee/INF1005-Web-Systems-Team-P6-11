<?php
session_start();
$mbid = trim($_GET['mbid'] ?? '');
if (!$mbid) {
    header('Location: listings.php');
    exit;
}
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/navigation.css"> 
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/artist.css">
    
    <style>
        .section-header {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text);
        }
        .artist-photo {
            object-fit: cover;
            aspect-ratio: 1;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s;
            background: #e8e8e8;
        }
        .artist-photo:hover {
            transform: scale(1.02);
        }
        .comment-article {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .comment-time {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        /* Custom Hero Styling */
        .hero-artist {
            background: var(--dark-panel);
            position: relative;
            overflow: hidden;
            padding: 80px 0 60px;
            color: #fff;
        }
        .hero-artist::before {
            content: '';
            position: absolute;
            width: 480px; height: 480px;
            border-radius: 50%;
            right: -100px; top: -150px;
            border: 40px solid rgba(255,255,255,0.03);
            pointer-events: none;
        }
        .hero-artist-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .mbid-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.1);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .mbid-link:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        .album-card {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            text-decoration: none;
            color: var(--text);
            transition: transform 0.2s, border-color 0.2s;
            margin-bottom: 0.75rem;
        }
        .album-card:hover {
            transform: translateY(-2px);
            border-color: #c8c8c4;
            color: var(--text);
        }
        .album-card i {
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-right: 1rem;
        }
    </style>
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

    function formatDate(str) {
        return new Date(str).toLocaleDateString('en-SG', {
            day: 'numeric', month: 'short', year: 'numeric'
        });
    }

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

    function renderImages(images) {
        const el = document.getElementById('images-list');
        if (!images.length) { 
            el.innerHTML = '<p>No photos yet.</p>'; 
            return; 
        }
        el.innerHTML = images.map(img => `
            <figure >
                <img src="/uploads/artists/${escHtml(img.filename)}" alt="Artist photo">
                <figcaption>
                    <i class="bi bi-person-circle"></i> ${escHtml(img.username)}
                    <span class="dot">·</span>
                    ${escHtml(formatDate(img.created_at))}
                </figcaption>
            </figure>
        `).join('');
    }

    function renderImages(images) {
        const el = document.getElementById('images-list');
        if (!images.length) {
            el.innerHTML = '<p class="empty-text">No photos yet.</p>';
            return;
        }
        
    }

    function renderAlbums(albums) {
        const el = document.getElementById('albums-list');
        if (!albums.length) {
            el.innerHTML = '<p class="empty-text">No albums listed.</p>';
            return;
        }
        el.innerHTML = albums.map(a => `
            <a href="album.php?mbid=${encodeURIComponent(a.album_mbid)}" class="album-row">
                <i class="bi bi-vinyl"></i>
                <span>${escHtml(a.album_name)}</span>
                <i class="bi bi-chevron-right ms-auto"></i>
            </a>
        `).join('');
    }

    function renderComments(comments) {
        const el = document.getElementById('comments-list');
        if (!comments.length) {
            el.innerHTML = '<p class="empty-text">No comments yet. Be the first!</p>';
            return;
        }
        el.innerHTML = comments.map(c => commentHTML(c)).join('');
    }

    function commentHTML(c) {
        return `
            <div class="comment-item">
                <div class="comment-meta">
                    <span class="comment-author">${escHtml(c.username)}</span>
                    <span class="comment-date">${escHtml(formatDate(c.created_at))}</span>
                </div>
                <p class="comment-body">${escHtml(c.comment)}</p>
            </div>
        `;
    }

    function prependComment(comment) {
        const el = document.getElementById('comments-list');
        const emptyMsg = el.querySelector('.empty-text');
        if (emptyMsg) emptyMsg.remove();
        const div = document.createElement('div');
        div.innerHTML = commentHTML(comment);
        el.prepend(div.firstElementChild);
    }

    // Load artist data
    fetch(`/api/get_artist.php?mbid=${encodeURIComponent(MBID)}`)
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                banner.textContent = data.error;
                const banner = document.getElementById('error-banner');
                banner.style.display = 'block';
                document.getElementById('artist-hero').innerHTML = '';
                return;
            }

            const a = data.artist;
            document.title = escHtml(a.artist_name) + ' — Artist';

            document.getElementById('artist-hero').innerHTML = `
                <div class="artist-hero-inner">
                    <div class="artist-avatar">${escHtml(a.artist_name.charAt(0).toUpperCase())}</div>
                    <div class="artist-hero-info">
                        <p class="artist-eyebrow">Artist</p>
                        <h1 class="artist-name">${escHtml(a.artist_name)}</h1>
                        <a class="mbid-link"
                        href="https://musicbrainz.org/artist/${escHtml(a.artist_mbid)}"
                        target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right"></i>
                            View on MusicBrainz
                        </a>
                    </div>
                </div>
            `;

            renderImages(data.images);
            renderAlbums(data.albums);
            renderComments(data.comments);
        })
        .catch(() => {
            const banner = document.getElementById('error-banner');
            banner.textContent = 'Failed to load artist.';
            banner.style.display = 'block';
            document.getElementById('artist-hero').innerHTML = '';
        });

    // Upload photo
    if (LOGGED_IN) {
        document.getElementById('image-input').addEventListener('change', function () {
            const file   = this.files[0];
            const status = document.getElementById('upload-status');
            if (!file) return;

            const label = document.querySelector('.btn-upload-label');
            label.innerHTML = '<i class="bi bi-arrow-repeat"></i> Uploading...';
            label.classList.add('loading');

            const body = new FormData();
            body.append('type', 'artist');
            body.append('mbid', MBID);
            body.append('image', file);

            fetch('/api/upload_image.php', { method: 'POST', body })
                .then(r => r.json())
                .then(data => {
                    label.innerHTML = '<i class="bi bi-upload"></i> Upload Photo';
                    label.classList.remove('loading');
                    if (data.status === 'ok') {
                        status.textContent = 'Photo uploaded!';
                        status.className = 'status-msg ok';
                        const list = document.getElementById('images-list');
                        const emptyMsg = list.querySelector('.empty-text');
                        if (emptyMsg) emptyMsg.remove();
                        const fig = document.createElement('figure');
                        fig.className = 'photo-figure';
                        fig.innerHTML = `
                            <img src="/uploads/artists/${escHtml(data.filename)}" alt="Artist photo">
                            <figcaption><i class="bi bi-person-circle"></i> You <span class="dot">·</span> Just now</figcaption>
                        `;
                        list.prepend(fig);
                        setTimeout(() => { status.textContent = ''; status.className = 'status-msg'; }, 3000);
                    } else {
                        status.textContent = data.error || 'Upload failed.';
                        status.className = 'status-msg error';
                    }
                })
                .catch(() => {
                    label.innerHTML = '<i class="bi bi-upload"></i> Upload Photo';
                    label.classList.remove('loading');
                    status.textContent = 'Upload failed.';
                    status.className = 'status-msg error';
                });
        });

        // Post comment
        document.getElementById('btn-comment').addEventListener('click', function () {
            const comment = document.getElementById('comment-input').value.trim();
            const status  = document.getElementById('comment-status');
            if (!comment) {
                status.textContent = 'Comment cannot be empty.';
                status.className = 'status-msg error';
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-arrow-repeat"></i> Posting...';

            fetch('/api/add_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ type: 'artist', mbid: MBID, comment })
            })
            .then(r => r.json())
            .then(data => {
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-send"></i> Post';
                if (data.status === 'ok') {
                    status.textContent = '';
                    document.getElementById('comment-input').value = '';
                    prependComment(data.comment);
                } else {
                    status.textContent = data.error || 'Failed to post comment.';
                    status.className = 'status-msg error';
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-send"></i> Post';
                status.textContent = 'Failed to post comment.';
                status.className = 'status-msg error';
            });
        });
    }
    </script>
</body>
</html>