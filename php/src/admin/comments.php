<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/inc/admin_auth.php';

if (isset($_GET['delete_artist'])) {
    $id = (int)$_GET['delete_artist'];
    $stmt = $pdo->prepare("DELETE FROM artist_comments WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: comments.php");
    exit;
}

if (isset($_GET['delete_album'])) {
    $id = (int)$_GET['delete_album'];
    $stmt = $pdo->prepare("DELETE FROM album_comments WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: comments.php");
    exit;
}

$artistComments = $pdo->query("
    SELECT
        c.id,
        c.comment,
        c.created_at,
        a.artist_name AS artist,
        u.username
    FROM artist_comments c
    JOIN artists a ON c.artist_mbid = a.artist_mbid
    JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
")->fetchAll();

$albumComments = $pdo->query("
    SELECT
        c.id,
        c.comment,
        c.created_at,
        ar.artist_name AS artist,
        al.album_name AS album,
        u.username
    FROM album_comments c
    JOIN albums al ON c.album_mbid = al.album_mbid
    JOIN artists ar ON al.artist_mbid = ar.artist_mbid
    JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
")->fetchAll();

$totalComments = count($artistComments) + count($albumComments);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Comment Moderation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include __DIR__ . '/inc/admin_nav.php'; ?>

    <main class="container mt-4 flex-grow-1">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">Comments</h1>
                <p class="text-muted mb-0">Review and remove artist or album comments.</p>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Comments</h6>
                        <h4 class="mb-0"><?= $totalComments ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Artist Comments</h6>
                        <h4 class="mb-0"><?= count($artistComments) ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Album Comments</h6>
                        <h4 class="mb-0"><?= count($albumComments) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi bi-person-badge me-2"></i>Artist Comments</strong>
                </div>
                <span class="badge text-bg-primary"><?= count($artistComments) ?></span>
            </div>

            <div class="card-body">
                <?php if (count($artistComments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Artist</th>
                                    <th>User</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($artistComments as $c): ?>
                                    <tr>
                                        <td><?= $c['id'] ?></td>
                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($c['artist']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($c['username']) ?></td>
                                        <td style="max-width: 420px;">
                                            <?= htmlspecialchars($c['comment']) ?>
                                        </td>
                                        <td><?= date('d M Y H:i', strtotime($c['created_at'])) ?></td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a
                                                    href="?delete_artist=<?= $c['id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this artist comment?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-chat-left-text fs-3 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">No artist comments found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi bi-disc me-2"></i>Album Comments</strong>
                </div>
                <span class="badge text-bg-success"><?= count($albumComments) ?></span>
            </div>

            <div class="card-body">
                <?php if (count($albumComments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Artist</th>
                                    <th>Album</th>
                                    <th>User</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($albumComments as $c): ?>
                                    <tr>
                                        <td><?= $c['id'] ?></td>
                                        <td><?= htmlspecialchars($c['artist']) ?></td>
                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($c['album']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($c['username']) ?></td>
                                        <td style="max-width: 420px;">
                                            <?= htmlspecialchars($c['comment']) ?>
                                        </td>
                                        <td><?= date('d M Y H:i', strtotime($c['created_at'])) ?></td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a
                                                    href="?delete_album=<?= $c['id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this album comment?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-chat-square-text fs-3 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">No album comments found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>