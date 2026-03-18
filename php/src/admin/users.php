<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/inc/admin_auth.php';

/* ========= HANDLE ACTIONS ========= */
if (isset($_GET['ban'])) {
    $id = (int)$_GET['ban'];

    if ($id !== (int)$_SESSION['user_id']) {
        $stmt = $pdo->prepare("
            UPDATE users
            SET status = 'banned'
            WHERE id = ?
        ");
        $stmt->execute([$id]);
    }

    header("Location: users.php");
    exit;
}

if (isset($_GET['unban'])) {
    $id = (int)$_GET['unban'];

    $stmt = $pdo->prepare("
        UPDATE users
        SET status = 'active'
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    header("Location: users.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    if ($id !== (int)$_SESSION['user_id']) {
        $stmt = $pdo->prepare("
            DELETE FROM users
            WHERE id = ?
        ");
        $stmt->execute([$id]);
    }

    header("Location: users.php");
    exit;
}

/* ========= FETCH USERS ========= */
$stmt = $pdo->query("
    SELECT
        id,
        username,
        email,
        role,
        status,
        created_at
    FROM users
    ORDER BY
        CASE WHEN role = 'admin' THEN 0 ELSE 1 END,
        created_at DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ========= STATS ========= */
$totalUsers = count($users);
$totalAdmins = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
$totalBanned = count(array_filter($users, fn($u) => $u['status'] === 'banned'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Management</title>

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
                <h1 class="mb-1">Users</h1>
                <p class="text-muted mb-0">Manage user accounts and account status.</p>
            </div>
        </div>

        <!-- STATS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h4 class="mb-0"><?= $totalUsers ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Admin Accounts</h6>
                        <h4 class="mb-0"><?= $totalAdmins ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Banned Users</h6>
                        <h4 class="mb-0"><?= $totalBanned ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-people me-2"></i>User Accounts</strong>
                <span class="badge text-bg-dark"><?= $totalUsers ?></span>
            </div>

            <div class="card-body">
                <?php if (count($users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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

                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($user['username']) ?></span>

                                            <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                                <span class="badge text-bg-light border ms-2">Current</span>
                                            <?php endif; ?>
                                        </td>

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
                                                            class="btn btn-sm btn-outline-warning"
                                                            onclick="return confirm('Ban this user?')">
                                                            Ban
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?unban=<?= $user['id'] ?>"
                                                            class="btn btn-sm btn-outline-success"
                                                            onclick="return confirm('Unban this user?')">
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
                                                <span class="text-muted small">No actions</span>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-people fs-3 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">No users found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>