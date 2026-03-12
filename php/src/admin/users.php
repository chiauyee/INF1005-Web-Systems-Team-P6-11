<?php
require '../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* HANDLE ACTIONS */
if (isset($_GET['ban'])) {
    $stmt = $pdo->prepare("
        UPDATE users
        SET status='banned'
        WHERE id=?
    ");
    $stmt->execute([$_GET['ban']]);
}

if (isset($_GET['unban'])) {
    $stmt = $pdo->prepare("
        UPDATE users
        SET status='active'
        WHERE id=?
    ");
    $stmt->execute([$_GET['unban']]);
}

if (isset($_GET['delete'])) {
    if ($_GET['delete'] != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("
            DELETE FROM users
            WHERE id=?
        ");
        $stmt->execute([$_GET['delete']]);
    }
}

/* FETCH USERS */
$stmt = $pdo->query("
SELECT
    id,
    username,
    email,
    role,
    status,
    created_at
FROM users

ORDER BY role DESC, created_at DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/navigation.css"> 
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/inc/admin_nav.php'; ?>
    <main class="container mt-4 flex-grow-1">

        <h1 class="mb-1">Users</h1>
        <p class="text-muted mb-4">
            <?= count($users) ?> registered users.
        </p>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="badge bg-dark">Admin</span>

                                <?php else: ?>
                                    <span class="badge bg-secondary">User</span>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['status'] === 'banned'): ?>
                                    <span class="badge bg-danger">Banned</span>

                                <?php else: ?>
                                    <span class="badge bg-success">Active</span>

                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                            <td class="text-end">
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <div class="btn-group">
                                        <?php if ($user['status'] === 'active'): ?>

                                            <a href="?ban=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-outline-warning">
                                                Ban
                                            </a>

                                        <?php else: ?>
                                            <a href="?unban=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-outline-success">
                                                Unban
                                            </a>

                                        <?php endif; ?>
                                        <a href="?delete=<?= $user['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this user?')">
                                            Delete
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">Current user</span>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>