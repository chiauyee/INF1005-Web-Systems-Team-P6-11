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
    <title>Artist</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/navigation.css"> 
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/artist.css">
    
</head>
<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main class="artist-page">
        <div id="error-banner" class="alert-danger-banner" style="display:none;"></div>
            
        <div id="artist-hero" class="artist-hero">
            <div class="spinner"></div>Loading...
        </div>

        <div class="artist-layout">
            <div class="artist-main">
                <div class="section-card">
                    <div class="section-card-hearder">
                        <h2 class="section-title"><i class="bi bi-images me-2"></i>Photos</h2>
                        <br>
                        <?php if ($logged_in): ?>
                            <label for="image-input" class="btn-upload-label">
                                <i class="bi bi-upload"></i> Upload Photo
                            </label>
                            <input type="file" id="image-input" accept="image/*" style="display:none;">
                        <?php endif; ?>
                    </div> 

                    <div id="images-list" class="images-grid">
                        <?php if ($logged_in): ?>
                            <div id="upload-status" class="status-msg"></div>
                        <?php else: ?>
                            <p class="login-prompt"><a href="login.php">Log in</a> to upload photos.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="artist-sidebar">
                    <h2 class="section-title">
                        <i class="bi bi-vinyl me-2"></i>Albums
                    </h2>
                    <div id="albums-list"></div>
                </div>
            </div>

            <div class="artist-sidebar">
                <div class="section-card">
                    <h2 class="section-title">
                        <i class="bi bi-chat me-2"></i>Comments
                    </h2>
                    <br>
                    <?php if ($logged_in): ?>
                        <div class="comment-form">
                            <textarea id="comment-input" class="comment-textarea" ="3" rows="4" cols="50" maxlength="2000" placeholder="Write a comment..."></textarea>
                            <button type="button" id="btn-comment" class="btn-post-comment">
                                <i class="bi bi-send"></i>Post comment
                            </button>
                            <div id="comment-status" class="status-msg"></div>
                        </div>
                    <?php else: ?>
                        <p class="login-prompt"><a href="login.php">Log in</a> to leave a comment.</p>
                    <?php endif; ?>

                    <div id="comments-list"></div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>

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