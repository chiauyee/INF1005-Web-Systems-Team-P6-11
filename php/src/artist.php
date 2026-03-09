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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>
</head>
<body>
<?php include __DIR__ . '/includes/navigation.php'; ?>

<main>
    <div id="error-banner" style="display:none;"></div>
    <div id="artist-info">
        <p>Loading...</p>
    </div>

    <hr>

    <section id="images-section">
        <h2>Photos</h2>
        <div id="images-list"></div>

        <?php if ($logged_in): ?>
        <h3>Upload a photo</h3>
        <input type="file" id="image-input" accept="image/*">
        <button type="button" id="btn-upload">Upload</button>
        <span id="upload-status"></span>
        <?php else: ?>
        <p><a href="login.php">Log in</a> to upload photos.</p>
        <?php endif; ?>
    </section>

    <hr>

    <section id="albums-section">
        <h2>Albums</h2>
        <div id="albums-list"></div>
    </section>

    <hr>

    <section id="comments-section">
        <h2>Comments</h2>
        <div id="comments-list"></div>

        <?php if ($logged_in): ?>
        <h3>Leave a comment</h3>
        <textarea id="comment-input" rows="4" cols="50" maxlength="2000" placeholder="Write a comment..."></textarea><br>
        <button type="button" id="btn-comment">Post comment</button>
        <span id="comment-status"></span>
        <?php else: ?>
        <p><a href="login.php">Log in</a> to leave a comment.</p>
        <?php endif; ?>
    </section>
</main>

<script>
const MBID = <?= json_encode($mbid) ?>;
const LOGGED_IN = <?= json_encode($logged_in) ?>;

function escHtml(str) {
    return DOMPurify.sanitize(String(str));
}

function renderImages(images) {
    const el = document.getElementById('images-list');
    if (!images.length) { el.innerHTML = '<p>No photos yet.</p>'; return; }
    el.innerHTML = images.map(img => `
        <figure>
            <img src="/uploads/artists/${escHtml(img.filename)}" alt="Artist photo" style="max-width:200px;max-height:200px;">
            <figcaption>Uploaded by ${escHtml(img.username)} on ${escHtml(img.created_at)}</figcaption>
        </figure>
    `).join('');
}

function renderAlbums(albums) {
    const el = document.getElementById('albums-list');
    if (!albums.length) { el.innerHTML = '<p>No albums listed.</p>'; return; }
    el.innerHTML = '<ul>' + albums.map(a => `
        <li><a href="album.php?mbid=${encodeURIComponent(a.album_mbid)}">${escHtml(a.album_name)}</a></li>
    `).join('') + '</ul>';
}

function renderComments(comments) {
    const el = document.getElementById('comments-list');
    if (!comments.length) { el.innerHTML = '<p>No comments yet.</p>'; return; }
    el.innerHTML = comments.map(c => `
        <article>
            <strong>${escHtml(c.username)}</strong>
            <time> &mdash; ${escHtml(c.created_at)}</time>
            <p>${escHtml(c.comment)}</p>
        </article>
    `).join('<hr>');
}

function prependComment(comment) {
    const el = document.getElementById('comments-list');
    if (el.querySelector('p')) el.innerHTML = '';
    const article = document.createElement('article');
    article.innerHTML = `
        <strong>${escHtml(comment.username)}</strong>
        <time> &mdash; ${escHtml(comment.created_at)}</time>
        <p>${escHtml(comment.comment)}</p>
        <hr>
    `;
    el.prepend(article);
}

// Load artist data
fetch(`/api/get_artist.php?mbid=${encodeURIComponent(MBID)}`)
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            document.getElementById('error-banner').textContent = data.error;
            document.getElementById('error-banner').style.display = 'block';
            document.getElementById('artist-info').innerHTML = '';
            return;
        }

        const a = data.artist;
        document.title = escHtml(a.artist_name) + ' — Artist';
        document.getElementById('artist-info').innerHTML = `
            <h1>${escHtml(a.artist_name)}</h1>
            <p>MusicBrainz ID: <a href="https://musicbrainz.org/artist/${escHtml(a.artist_mbid)}" target="_blank">${escHtml(a.artist_mbid)}</a></p>
        `;

        renderImages(data.images);
        renderAlbums(data.albums);
        renderComments(data.comments);
    })
    .catch(() => {
        document.getElementById('error-banner').textContent = 'Failed to load artist.';
        document.getElementById('error-banner').style.display = 'block';
    });

// Upload photo
if (LOGGED_IN) {
    document.getElementById('btn-upload').addEventListener('click', function () {
        const file   = document.getElementById('image-input').files[0];
        const status = document.getElementById('upload-status');
        if (!file) { status.textContent = 'Please select a file.'; return; }

        const body = new FormData();
        body.append('type', 'artist');
        body.append('mbid', MBID);
        body.append('image', file);

        status.textContent = 'Uploading...';
        fetch('/api/upload_image.php', { method: 'POST', body })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'ok') {
                    status.textContent = 'Uploaded!';
                    // Prepend new image
                    const list = document.getElementById('images-list');
                    if (list.querySelector('p')) list.innerHTML = '';
                    const fig = document.createElement('figure');
                    fig.innerHTML = `
                        <img src="/uploads/artists/${escHtml(data.filename)}" alt="Artist photo" style="max-width:200px;max-height:200px;">
                        <figcaption>Just uploaded by you</figcaption>
                    `;
                    list.prepend(fig);
                } else {
                    status.textContent = data.error || 'Upload failed.';
                }
            })
            .catch(() => { status.textContent = 'Upload failed.'; });
    });

    // Post comment
    document.getElementById('btn-comment').addEventListener('click', function () {
        const comment = document.getElementById('comment-input').value.trim();
        const status  = document.getElementById('comment-status');
        if (!comment) { status.textContent = 'Comment cannot be empty.'; return; }

        status.textContent = 'Posting...';
        fetch('/api/add_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'artist', mbid: MBID, comment })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok') {
                status.textContent = '';
                document.getElementById('comment-input').value = '';
                prependComment(data.comment);
            } else {
                status.textContent = data.error || 'Failed to post comment.';
            }
        })
        .catch(() => { status.textContent = 'Failed to post comment.'; });
    });
}
</script>
</body>
</html>
