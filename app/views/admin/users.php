<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Manage Users</h3>

    <div class="mb-3">
        <a href="?role=" class="btn btn-sm btn-outline-secondary">All Roles</a>
        <a href="?role=customer" class="btn btn-sm btn-outline-primary">Customers</a>
        <a href="?role=seller" class="btn btn-sm btn-outline-info">Sellers</a>
        <a href="?role=admin" class="btn btn-sm btn-outline-dark">Admins</a>
        |
        <a href="?status=active" class="btn btn-sm btn-outline-success">Active</a>
        <a href="?status=suspended" class="btn btn-sm btn-outline-danger">Suspended</a>
    </div>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">No users found.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role_name']) ?></span></td>
                        <td>
                            <span class="badge <?= $user['status'] === 'active' ? 'bg-success' : 'bg-danger' ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars(date('d M Y', strtotime($user['created_at']))) ?></td>
                        <td>
                            <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']): ?>
                                <?php if ($user['status'] === 'active'): ?>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/users/update-status" style="display:inline;" onsubmit="return confirm('Suspend this user?');">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="status" value="suspended">
                                        <button type="submit" class="btn btn-sm btn-danger">Suspend</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/users/update-status" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-sm btn-success">Reactivate</button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>