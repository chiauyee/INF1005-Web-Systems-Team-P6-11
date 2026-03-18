<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* ========= DELETE ========= */
if (isset($_GET['delete_artist'])) {
    $id = (int)$_GET['delete_artist'];
    $stmt = $pdo->prepare("DELETE FROM artist_images WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: images.php");
    exit;
}

if (isset($_GET['delete_album'])) {
    $id = (int)$_GET['delete_album'];
    $stmt = $pdo->prepare("DELETE FROM album_images WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: images.php");
    exit;
}

/* ========= FETCH ========= */
$artistImages = $pdo->query("
    SELECT
        ai.id,
        ai.filename,
        ai.created_at,
        a.artist_name AS artist,
        u.username
    FROM artist_images ai
    JOIN artists a ON ai.artist_mbid = a.artist_mbid
    JOIN users u ON ai.uploaded_by = u.id
    ORDER BY ai.created_at DESC
")->fetchAll();

$albumImages = $pdo->query("
    SELECT
        ai.id,
        ai.filename,
        ai.created_at,
        ar.artist_name AS artist,
        al.album_name AS album,
        u.username
    FROM album_images ai
    JOIN albums al ON ai.album_mbid = al.album_mbid
    JOIN artists ar ON al.artist_mbid = ar.artist_mbid
    JOIN users u ON ai.uploaded_by = u.id
    ORDER BY ai.created_at DESC
")->fetchAll();

$totalImages = count($artistImages) + count($albumImages);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Image Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/main.css">

    <style>
        .admin-thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<?php include __DIR__ . '/inc/admin_nav.php'; ?>

<main class="container mt-4 flex-grow-1">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Images</h1>
            <p class="text-muted mb-0">Review and remove artist or album image uploads.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Images</h6>
                    <h4 class="mb-0"><?= $totalImages ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Artist Images</h6>
                    <h4 class="mb-0"><?= count($artistImages) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Album Images</h6>
                    <h4 class="mb-0"><?= count($albumImages) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <strong><i class="bi bi-person-badge me-2"></i>Artist Images</strong>
            </div>
            <span class="badge text-bg-primary"><?= count($artistImages) ?></span>
        </div>

        <div class="card-body">
            <?php if (count($artistImages) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Preview</th>
                                <th>Artist</th>
                                <th>Filename</th>
                                <th>User</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($artistImages as $img): ?>
                                <tr>
                                    <td><?= $img['id'] ?></td>
                                    <td>
                                        <img
                                            src="../uploads/artists/<?= htmlspecialchars($img['filename']) ?>"
                                            alt="Artist image"
                                            class="admin-thumb">
                                    </td>
                                    <td>
                                        <span class="fw-medium"><?= htmlspecialchars($img['artist']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($img['filename']) ?></td>
                                    <td><?= htmlspecialchars($img['username']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($img['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a
                                            href="?delete_artist=<?= $img['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this artist image?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-image fs-3 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No artist images found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <strong><i class="bi bi-disc me-2"></i>Album Images</strong>
            </div>
            <span class="badge text-bg-success"><?= count($albumImages) ?></span>
        </div>

        <div class="card-body">
            <?php if (count($albumImages) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Preview</th>
                                <th>Artist</th>
                                <th>Album</th>
                                <th>Filename</th>
                                <th>User</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($albumImages as $img): ?>
                                <tr>
                                    <td><?= $img['id'] ?></td>
                                    <td>
                                        <img
                                            src="../uploads/albums/<?= htmlspecialchars($img['filename']) ?>"
                                            alt="Album image"
                                            class="admin-thumb">
                                    </td>
                                    <td><?= htmlspecialchars($img['artist']) ?></td>
                                    <td>
                                        <span class="fw-medium"><?= htmlspecialchars($img['album']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($img['filename']) ?></td>
                                    <td><?= htmlspecialchars($img['username']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($img['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a
                                            href="?delete_album=<?= $img['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this album image?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-images fs-3 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No album images found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>